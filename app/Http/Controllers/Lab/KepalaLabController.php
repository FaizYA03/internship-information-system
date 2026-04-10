<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\LaporanKerusakan;
use App\Models\Lab\PinjamAlat;
use App\Models\Lab\PinjamEksternal;
use App\Models\Lab\JadwalLaboratorium;
use App\Models\Labor;          // Model laboratorium yang benar (tabel: labor)
use App\Models\Laboratorium;   // Model jadwal/peminjaman ruangan (tabel: laboratorium)
use App\Models\Inventaris;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * KepalaLabController
 *
 * Role: kepala_lab
 * Hak akses:
 *   ✅ Monitoring (lihat semua data, read-only)
 *   ✅ Supervisi (supervisi laporan kerusakan)
 *   ✅ Persetujuan eskalasi kerusakan
 *   ✅ Rekomendasi peminjaman eksternal
 *
 * Tidak boleh:
 *   ❌ Tambah data
 *   ❌ Edit data utama
 *   ❌ Hapus data
 *   ❌ Input peminjaman
 */
class KepalaLabController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:kepala_lab,super_admin']);
    }

    // ============================================================
    // DASHBOARD — Ringkasan statistik umum untuk kepala lab
    // ============================================================
    public function index()
    {
        $stats = [
            // Jumlah laboratorium (tabel labor)
            'total_lab'          => Labor::count(),
            'lab_aktif'          => Labor::count(), // Labor tidak punya kolom status

            // Inventaris
            'total_inventaris'   => Inventaris::count(),
            'inventaris_rusak'   => Inventaris::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Rusak Sedang'])->count(),
            'inventaris_baik'    => Inventaris::whereIn('kondisi', ['Baik', 'Sangat Baik'])->count(),

            // Peminjaman Alat (tabel pinjam_alat)
            'peminjaman_aktif'   => PinjamAlat::where('status', 'approved')->count(),
            'peminjaman_pending' => PinjamAlat::where('status', 'pending')->count(),

            // Peminjaman eksternal menunggu rekomendasi
            'eksternal_menunggu' => PinjamEksternal::where('status', 'pending')->count(),

            // Laporan kerusakan
            'kerusakan_aktif'    => LaporanKerusakan::whereNotIn('status_perbaikan', ['Selesai'])->count(),
            'kerusakan_eskalasi' => LaporanKerusakan::where('is_eskalasi', true)
                                        ->where('eskalasi_ke', 'kepala_lab')
                                        ->where('eskalasi_status', 'menunggu')
                                        ->count(),

            // Jadwal tetap hari ini (berdasarkan nama hari)
            'jadwal_hari_ini'    => JadwalLaboratorium::where('hari', now()->locale('id')->isoFormat('dddd'))->count(),
        ];

        // 5 laporan kerusakan terbaru (untuk preview)
        $kerusakan_terbaru = LaporanKerusakan::with(['inventaris', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Eskalasi pending untuk kepala_lab
        $eskalasi_pending = LaporanKerusakan::with(['inventaris', 'user'])
            ->where('is_eskalasi', true)
            ->where('eskalasi_ke', 'kepala_lab')
            ->where('eskalasi_status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        return view('lab.kepala_lab.dashboard', compact('stats', 'kerusakan_terbaru', 'eskalasi_pending'));
    }

    // ============================================================
    // MONITORING — LABORATORIUM (Read-only, menggunakan model Labor)
    // ============================================================
    public function monitoringLab(Request $request)
    {
        $query = Labor::withCount([
            'jadwalTetap as jadwal_hari_ini' => function ($q) {
                $q->where('hari', now()->locale('id')->isoFormat('dddd'));
            }
        ]);

        if ($request->filled('search')) {
            $query->where('nama_labor', 'like', '%' . $request->search . '%');
        }

        $labs = $query->latest()->paginate(12)->withQueryString();

        return view('lab.kepala_lab.monitoring.lab', compact('labs'));
    }

    // ============================================================
    // MONITORING — JADWAL (Read-only, JadwalLaboratorium = jadwal tetap mingguan)
    // ============================================================
    public function monitoringJadwal(Request $request)
    {
        $query = JadwalLaboratorium::with(['labor', 'guru']);

        if ($request->filled('lab_id')) {
            $query->where('labor_id', $request->lab_id);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20)->withQueryString();
        $labs   = Labor::orderBy('nama_labor')->get();

        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('lab.kepala_lab.monitoring.jadwal', compact('jadwal', 'labs', 'hariOptions'));
    }

    // ============================================================
    // MONITORING — INVENTARIS (Read-only)
    // ============================================================
    public function monitoringInventaris(Request $request)
    {
        $query = Inventaris::with(['labor']);

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('lab_id')) {
            $query->where('labor_id', $request->lab_id);
        }
        if ($request->filled('search')) {
            $query->where('nama_inventaris', 'like', '%' . $request->search . '%');
        }

        $inventaris = $query->latest()->paginate(20)->withQueryString();
        $labs       = Labor::orderBy('nama_labor')->get();

        // Statistik kondisi
        $stats_kondisi = Inventaris::select('kondisi', DB::raw('count(*) as total'))
            ->groupBy('kondisi')
            ->pluck('total', 'kondisi');

        return view('lab.kepala_lab.monitoring.inventaris', compact('inventaris', 'labs', 'stats_kondisi'));
    }

    // ============================================================
    // MONITORING — PEMINJAMAN (Read-only)
    // ============================================================
    public function monitoringPeminjaman(Request $request)
    {
        $status = $request->status;

        // Peminjaman Alat Internal (PinjamAlat)
        $pinjamAlat = PinjamAlat::with(['inventaris', 'user'])
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(15, ['*'], 'alat_page')
            ->withQueryString();

        // Peminjaman Eksternal (PinjamEksternal)
        $pinjamEksternal = PinjamEksternal::with(['inventaris'])
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(15, ['*'], 'ekst_page')
            ->withQueryString();

        return view('lab.kepala_lab.monitoring.peminjaman', compact('pinjamAlat', 'pinjamEksternal'));
    }

    // ============================================================
    // SUPERVISI — LAPORAN KERUSAKAN (Read + Approve/Reject Eskalasi)
    // ============================================================
    public function supervisiKerusakan(Request $request)
    {
        $query = LaporanKerusakan::with(['inventaris', 'user'])
            ->where('status_perbaikan', '!=', 'selesai');

        if ($request->filled('eskalasi')) {
            $query->where('is_eskalasi', true)->where('eskalasi_ke', 'kepala_lab');
        }

        $laporan = $query->latest()->paginate(20)->withQueryString();

        // Hanya eskalasi yang ditujukan ke kepala_lab dan belum diproses
        $eskalasi = LaporanKerusakan::with(['inventaris', 'user'])
            ->where('is_eskalasi', true)
            ->where('eskalasi_ke', 'kepala_lab')
            ->where('eskalasi_status', 'menunggu')
            ->latest()
            ->get();

        return view('lab.kepala_lab.supervisi.kerusakan', compact('laporan', 'eskalasi'));
    }

    /**
     * Specialized view for Finished Repairs (Kepala Lab)
     */
    public function perbaikanSelesai(Request $request)
    {
        $laporan = LaporanKerusakan::with(['inventaris', 'user'])
            ->where('status_perbaikan', 'selesai')
            ->where(function($q) {
                $q->where('is_eskalasi', false)
                  ->orWhere('eskalasi_status', 'disetujui');
            })
            ->latest()
            ->paginate(20);

        return view('lab.kepala_lab.supervisi.selesai', compact('laporan'));
    }

    /**
     * Setujui eskalasi laporan kerusakan
     * Bukan edit laporan utama — hanya keputusan atas eskalasi yang dikirim admin
     */
    public function approveEscalation(Request $request, $id)
    {
        try {
            $laporan = LaporanKerusakan::findOrFail($id);

            if ($laporan->eskalasi_ke !== 'kepala_lab') {
                return back()->with('error', 'Eskalasi ini bukan ditujukan untuk Kepala Lab.');
            }

            $updateData = [
                'eskalasi_status' => 'disetujui',
            ];

            // Optional fields jika ada di tabel
            if (in_array('eskalasi_diproses_oleh', $laporan->getFillable())) {
                $updateData['eskalasi_diproses_oleh'] = Auth::id();
            }
            if (in_array('eskalasi_diproses_at', $laporan->getFillable())) {
                $updateData['eskalasi_diproses_at'] = now();
            }

            $laporan->update($updateData);

            if (class_exists(\App\Services\Lab\ActivityLogService::class)) {
                \App\Services\Lab\ActivityLogService::log(
                    'approved_escalation',
                    "Kepala Lab menyetujui eskalasi laporan kerusakan #{$laporan->id}",
                    $laporan
                );
            }

            return back()->with('success', 'Eskalasi berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Tolak eskalasi laporan kerusakan
     */
    public function rejectEscalation(Request $request, $id)
    {
        $request->validate(['catatan' => 'nullable|string|max:500']);

        try {
            $laporan = LaporanKerusakan::findOrFail($id);

            if ($laporan->eskalasi_ke !== 'kepala_lab') {
                return back()->with('error', 'Eskalasi ini bukan ditujukan untuk Kepala Lab.');
            }

            $updateData = [
                'eskalasi_status' => 'ditolak',
            ];

            if (in_array('eskalasi_diproses_oleh', $laporan->getFillable())) {
                $updateData['eskalasi_diproses_oleh'] = Auth::id();
            }
            if (in_array('eskalasi_diproses_at', $laporan->getFillable())) {
                $updateData['eskalasi_diproses_at'] = now();
            }
            if ($request->catatan && in_array('eskalasi_catatan', $laporan->getFillable())) {
                $updateData['eskalasi_catatan'] = $request->catatan;
            }

            $laporan->update($updateData);

            return back()->with('success', 'Eskalasi berhasil ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ============================================================
    // REKOMENDASI — PEMINJAMAN EKSTERNAL
    // Bukan input/edit, hanya tindakan persetujuan/rekomendasi
    // ============================================================
    public function approvalEksternalIndex()
    {
        $pending = PinjamEksternal::with(['inventaris'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $riwayat = PinjamEksternal::with(['inventaris'])
            ->where('status', '!=', 'pending')
            ->latest()
            ->take(20)
            ->get();

        $pendingRuangan = \App\Models\PinjamLabor::with(['labor'])
            ->whereNull('user_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $riwayatRuangan = \App\Models\PinjamLabor::with(['labor'])
            ->whereNull('user_id')
            ->where('status', '!=', 'pending')
            ->latest()
            ->take(20)
            ->get();

        return view('lab.kepala_lab.approval.eksternal', compact('pending', 'riwayat', 'pendingRuangan', 'riwayatRuangan'));
    }

    /**
     * Berikan rekomendasi untuk peminjaman eksternal
     * Bukan tambah/edit data — hanya keputusan rekomendasi
     */
    public function recommendEksternal(Request $request, $id)
    {
        $request->validate([
            'action'  => 'required|in:recommend,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pinjam = PinjamEksternal::findOrFail($id);

        if ($pinjam->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $status = $request->action === 'recommend' ? 'recommended' : 'rejected';

        $updateData = ['status' => $status];

        // Field opsional — cek fillable agar tidak error
        $fillable = $pinjam->getFillable();
        if (in_array('rekomendasi_kalab_by', $fillable))      $updateData['rekomendasi_kalab_by']      = Auth::id();
        if (in_array('rekomendasi_kalab_at', $fillable))      $updateData['rekomendasi_kalab_at']      = now();
        if (in_array('rekomendasi_kalab_catatan', $fillable)) $updateData['rekomendasi_kalab_catatan'] = $request->catatan;

        $pinjam->update($updateData);

        $msg = $request->action === 'recommend'
            ? 'Rekomendasi diberikan. Menunggu persetujuan Kepala Sekolah.'
            : 'Peminjaman eksternal ditolak.';

        return back()->with('success', $msg);
    }

    /**
     * Berikan rekomendasi untuk peminjaman ruangan eksternal
     */
    public function recommendRuanganEksternal(Request $request, $id)
    {
        $request->validate([
            'action'  => 'required|in:recommend,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pinjam = \App\Models\PinjamLabor::findOrFail($id);

        if ($pinjam->status !== 'pending') {
            return back()->with('error', 'Permohonan ruangan eksternal ini sudah diproses sebelumnya.');
        }

        $status = $request->action === 'recommend' ? 'recommended' : 'rejected';

        $updateData = ['status' => $status];
        if ($request->action === 'reject') {
            $updateData['alasan_penolakan'] = $request->catatan;
        }

        $pinjam->update($updateData);

        $msg = $request->action === 'recommend'
            ? 'Rekomendasi ruangan eksternal diberikan. Menunggu persetujuan Kepala Sekolah.'
            : 'Peminjaman ruangan eksternal ditolak.';

        return back()->with('success', $msg);
    }
}
