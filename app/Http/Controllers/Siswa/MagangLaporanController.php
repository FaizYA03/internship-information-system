<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MagangLaporan;
use App\Models\MagangSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangLaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Magang';
        $header = 'Daftar Laporan Kegiatan Magang';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
                            ->where('status', 'Disetujui Admin')
                            ->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporans = MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
                        ->orderBy('minggu_ke', 'desc')
                        ->get();
                        
        // Tandai laporan yang sudah divalidasi sebagai "telah dibaca"
        MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->where('is_read_by_siswa', false)
            ->update(['is_read_by_siswa' => true]);
        
        return view('magang.siswa.laporan.index', compact(
            'title',
            'header',
            'laporans',
            'magangSiswa'
        ));
    }

    public function create()
    {
        $title = 'Buat Laporan Magang';
        $header = 'Buat Laporan Kegiatan Magang';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
                            ->where('status', 'Disetujui Admin')
                            ->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        // Determine next week number
        $latestReport = MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
                            ->orderBy('minggu_ke', 'desc')
                            ->first();
        
        $nextWeek = $latestReport ? $latestReport->minggu_ke + 1 : 1;
        
        return view('magang.siswa.laporan.create', compact(
            'title',
            'header',
            'magangSiswa',
            'nextWeek'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
                            ->where('status', 'Disetujui Admin')
                            ->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'status' => 'required|in:draft,submitted',
        ]);
        
        MagangLaporan::create([
            'magang_siswa_id' => $magangSiswa->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => $request->status,
        ]);
        
        return redirect()->route('magang.siswa.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kegiatan magang berhasil dibuat.');
    }

    public function show($id)
    {
        $title = 'Detail Laporan';
        $header = 'Detail Laporan Kegiatan';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->firstOrFail();
        
        return view('magang.siswa.laporan.show', compact(
            'title',
            'header',
            'laporan'
        ));
    }

    public function edit($id)
    {
        $title = 'Edit Laporan';
        $header = 'Edit Laporan Kegiatan';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->where('status', '!=', 'approved')
                      ->firstOrFail();
        
        return view('magang.siswa.laporan.edit', compact(
            'title',
            'header',
            'laporan'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->where('status', '!=', 'approved')
                      ->firstOrFail();
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'status' => 'required|in:draft,submitted',
        ]);
        
        $laporan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => $request->status,
        ]);
        
        return redirect()->route('magang.siswa.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kegiatan magang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->where('status', '!=', 'approved')
                      ->firstOrFail();
        
        $laporan->delete();
        
        return redirect()->route('magang.siswa.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kegiatan magang berhasil dihapus.');
    }

    public function improveWithAi(Request $request)
    {
        $request->validate([
            'prompt_text' => 'required|string',
        ]);

        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            return response()->json(['error' => 'Konfigurasi Gemini API Key belum diatur oleh administrator.'], 500);
        }

        try {
            $prompt = "Tugas Anda adalah membuat deskripsi laporan kegiatan magang harian yang profesional, terstruktur, sistematis, dan menggunakan tata bahasa Indonesia yang baku berdasarkan poin-poin/kegiatan berikut ini:\n\n" . $request->prompt_text . "\n\nKembangkan poin tersebut menjadi beberapa paragraf laporan yang baik. JANGAN gunakan kata ganti orang pertama secara berlebihan. ATURAN PENTING: Anda hanya boleh membalas dengan TEKS HASIL AKHIR saja tanpa tanda kutip. JANGAN gunakan format markdown (seperti tanda bintang ** atau bullet point *). JANGAN berikan penjelasan atau komentar apapun.";

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json'
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
                
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
                    return response()->json(['deskripsi' => trim($aiText)]);
                }
            }

            return response()->json(['error' => 'Error API: ' . $response->body()], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error Sistem: ' . $e->getMessage()], 500);
        }
    }
}