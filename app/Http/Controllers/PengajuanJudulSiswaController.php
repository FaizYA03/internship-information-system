<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanJudul;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\MagangLaporan;

class PengajuanJudulSiswaController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        if (Auth::user()->role === 'siswa') {
            $pengajuanJuduls = PengajuanJudul::with('wakilPerusahaan')
                ->where('user_id', Auth::id())
                ->get();
                
            // Tandai pengajuan sebagai telah dibaca jika statusnya accepted/rejected
            foreach($pengajuanJuduls as $pj) {
                if (in_array($pj->status, ['accepted', 'rejected']) && !$pj->is_read_by_siswa) {
                    $pj->update(['is_read_by_siswa' => true]);
                }
            }
        } else {
            $pengajuanJuduls = PengajuanJudul::with('user', 'wakilPerusahaan')
                ->latest()
                ->get();
        }

        return view('magang.pengajuan_judul.indexsiswa', compact('pengajuanJuduls'));
    }

    // ================= CREATE =================
    public function create()
    {
        $user = Auth::user();

        // 🔥 ambil data magang siswa yang SUDAH DISETUJUI
        $magangSiswa = $user->magangSiswa()
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();

        // 🔥 ambil perusahaan dari relasi
        $namaPerusahaan = $magangSiswa?->wakilPerusahaan?->nama_perusahaan;
        $wakilPerusahaanId = $magangSiswa?->wakilPerusahaan?->id;

        return view('magang.pengajuan_judul.create', compact(
            'namaPerusahaan',
            'wakilPerusahaanId'
        ));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'jurusan' => 'required',
            'judul_laporan' => 'required',
            'link_drive' => 'required|url',
            'wakil_perusahaan_id' => 'required',
        ]);

        // 🔥 CEGAH DUPLIKAT (opsional tapi penting)
        $cek = PengajuanJudul::where('user_id', Auth::id())->first();
        if ($cek) {
            return back()->with('error', 'Anda sudah pernah mengajukan judul!');
        }

        PengajuanJudul::create([
            'user_id' => Auth::id(),
            'jurusan' => $request->jurusan,
            'judul_laporan' => $request->judul_laporan,
            'link_drive' => $request->link_drive,
            'wakil_perusahaan_id' => $request->wakil_perusahaan_id,
            'status' => 'pending', // ✅ sesuai DB
        ]);

        return redirect()
            ->route('magang.pengajuan_judul.indexsiswa')
            ->with('success', 'Pengajuan berhasil dikirim!');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $pengajuan = PengajuanJudul::findOrFail($id);

        // 🔥 CEGAH AKSES - hanya pemilik pengajuan yang bisa edit
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengajuan ini.');
        }

        // 🔥 CEGAH EDIT - jika status bukan pending
        if ($pengajuan->status !== 'pending') {
            return redirect()
                ->route('magang.pengajuan_judul.indexsiswa')
                ->with('error', 'Pengajuan tidak dapat diedit setelah direview oleh pembimbing.');
        }

        $user = Auth::user();
        $magangSiswa = $user->magangSiswa()
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();

        $namaPerusahaan = $magangSiswa?->wakilPerusahaan?->nama_perusahaan;
        $wakilPerusahaanId = $magangSiswa?->wakilPerusahaan?->id;

        return view('magang.pengajuan_judul.edit', compact(
            'pengajuan',
            'namaPerusahaan',
            'wakilPerusahaanId'
        ));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanJudul::findOrFail($id);

        // 🔥 CEGAH AKSES - hanya pemilik pengajuan yang bisa update
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate pengajuan ini.');
        }

        // 🔥 CEGAH UPDATE - jika status bukan pending
        if ($pengajuan->status !== 'pending') {
            return redirect()
                ->route('magang.pengajuan_judul.indexsiswa')
                ->with('error', 'Pengajuan tidak dapat diedit setelah direview oleh pembimbing.');
        }

        $request->validate([
            'jurusan' => 'required',
            'judul_laporan' => 'required',
            'link_drive' => 'required|url',
            'wakil_perusahaan_id' => 'required',
        ]);

        $pengajuan->update([
            'jurusan' => $request->jurusan,
            'judul_laporan' => $request->judul_laporan,
            'link_drive' => $request->link_drive,
            'wakil_perusahaan_id' => $request->wakil_perusahaan_id,
        ]);

        return redirect()
            ->route('magang.pengajuan_judul.indexsiswa')
            ->with('success', 'Pengajuan berhasil diperbarui!');
    }

    // ================= GENERATE JUDUL (AI) =================
    public function generateJudul(Request $request)
    {
        $user = Auth::user();

        // 1. Ambil data magang siswa yang disetujui
        $magangSiswa = $user->magangSiswa()
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();

        if (!$magangSiswa) {
            return response()->json(['error' => 'Anda belum memiliki data magang yang disetujui.'], 400);
        }

        // 2. Ambil Jurnal Harian (Laporan) terakhir siswa
        $laporans = MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
            ->latest('tanggal_mulai')
            ->take(15)
            ->get();

        if ($laporans->isEmpty()) {
            return response()->json(['error' => 'Anda belum memiliki catatan jurnal harian. Isi jurnal terlebih dahulu agar AI dapat memberikan rekomendasi.'], 400);
        }

        // 3. Susun Prompt untuk AI
        $kegiatan = "";
        foreach ($laporans as $laporan) {
            $kegiatan .= "- " . $laporan->judul . ": " . strip_tags($laporan->deskripsi) . "\n";
        }

        $prompt = "Saya adalah siswa SMK jurusan " . ($request->jurusan ?? 'Sekolah Menengah Kejuruan') . " yang sedang magang di perusahaan " . ($magangSiswa->wakilPerusahaan->nama_perusahaan ?? 'Mitra') . ".\n\n";
        $prompt .= "Berikut adalah daftar kegiatan yang sering saya lakukan selama magang berdasarkan jurnal harian saya:\n";
        $prompt .= $kegiatan . "\n\n";
        $prompt .= "Tugas Anda: Berdasarkan aktivitas riil di atas, buatkan 3 rekomendasi judul 'Laporan Akhir Praktik Kerja Lapangan (PKL)' yang spesifik, akademis, profesional, dan relevan dengan kegiatan magang saya. Judul harus dalam bahasa Indonesia yang baik dan benar. JANGAN MENULIS TEKS LAIN SELAIN DAFTAR JUDUL TERSEBUT (cukup output berupa format JSON array dari string). Contoh output yang diinginkan: [\"Judul 1\", \"Judul 2\", \"Judul 3\"]. PASTIKAN output HANYA array JSON, tanpa format markdown, tanpa ```json.";

        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            return response()->json(['error' => 'Konfigurasi Gemini API Key belum diatur oleh administrator.'], 500);
        }

        // 4. Panggil Gemini API
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key=" . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Ekstrak teks dari balasan Gemini
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Bersihkan dari kemungkinan markdown
                    $aiText = trim(str_replace(['```json', '```'], '', $aiText));
                    
                    // Decode JSON array
                    $judulArray = json_decode($aiText, true);
                    
                    if (is_array($judulArray) && count($judulArray) > 0) {
                        return response()->json(['judul' => $judulArray]);
                    }
                    
                    // Fallback jika bukan JSON rapi
                    return response()->json(['judul' => array_filter(array_map('trim', explode("\n", $aiText)))]);
                }
            }

            return response()->json(['error' => 'Error API: ' . $response->body()], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error Sistem: ' . $e->getMessage()], 500);
        }
    }
}