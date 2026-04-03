<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Peminatan;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PeminatanController extends Controller
{
    /**
     * Tampilkan daftar peminatan (dengan filter).
     */
    public function index(Request $request)
    {
        $header = 'Data Peminatan';

        // Ambil daftar kelas (untuk dropdown). Tambahkan label gabungan: nama_kelas · jurusan
        $kelasList = \App\Models\Kelas::select('id', 'nama_kelas', 'jurusan', 'tahun_ajaran')
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($k) {
                $k->label = trim($k->nama_kelas . ' · ' . $k->jurusan);
                return $k;
            });

        // Ambil daftar guru BK (distinct) yang ada di tabel kelas
        $guruBkIds = \App\Models\Kelas::whereNotNull('guru_bk_id')->pluck('guru_bk_id')->unique()->toArray();
        $guruBKList = \App\Models\User::whereIn('id', $guruBkIds)->orderBy('nama')->get();

        // Ambil daftar jurusan dan tahun ajaran unik dari tabel kelas (untuk dropdown)
        $jurusanList = \App\Models\Kelas::select('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        $tahunAjaranList = \App\Models\Kelas::select('tahun_ajaran')->distinct()->orderBy('tahun_ajaran')->pluck('tahun_ajaran');

        // Base query: relasikan ke user -> siswa -> kelas
        $query = \App\Models\Peminatan::with(['user.siswa.dataKelas']);

        // FILTER: kelas (melalui siswa.kelas_id)
        if ($request->filled('kelas')) {
            $query->whereHas('user.siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas);
            });
        }

        // FILTER: guru_bk (melalui kelas.guru_bk_id -> siswa -> user_id)
        if ($request->filled('guru_bk')) {
            $query->whereHas('user.siswa.dataKelas', function ($q) use ($request) {
                $q->where('guru_bk_id', $request->guru_bk);
            });
        }

        // FILTER: minat (langsung pada tabel peminatan)
        if ($request->filled('minat')) {
            $query->where('minat', $request->minat);
        }

        // FILTER: jurusan (berdasarkan jurusan kelas siswa)
        if ($request->filled('jurusan')) {
            $jurusan = $request->jurusan;
            $query->whereHas('user.siswa.dataKelas', function ($kc) use ($jurusan) {
                $kc->where('jurusan', $jurusan);
            });
        }

        // FILTER: tahun_ajaran (via kelas -> siswa -> user_id)
        if ($request->filled('tahun_ajaran')) {
            $query->whereHas('user.siswa.dataKelas', function ($q) use ($request) {
                $q->where('tahun_ajaran', $request->tahun_ajaran);
            });
        }

        // Ambil hasil (tanpa pagination) untuk analitik / ringkasan -> gunakan clone sebelum paginate
        $filteredCollection = (clone $query)->get();

        // Pagination hasil yang ditampilkan
        $perPage = 15;
        $peminatans = $query->latest()->paginate($perPage)->appends($request->query());

        // apakah siswa login sudah punya peminatan?
        $hasOwnPeminatan = false;
        if (Auth::check() && Auth::user()->role === 'siswa') {
            $hasOwnPeminatan = \App\Models\Peminatan::where('user_id', Auth::id())->exists();
        }

        // total siswa (global)
        $totalStudents = \App\Models\User::where('role', 'siswa')->count();

        // opsi minat konsisten
        $options = [
            'bekerja'   => 'Bekerja',
            'wirausaha' => 'Wirausaha',
            'kuliah'    => 'Kuliah',
            'lainnya'   => 'Lainnya',
        ];

        // jika tidak ada data setelah filter, siapkan default kosong
        if ($filteredCollection->isEmpty()) {
            $totalRespondents = 0;
            $statsPerOption = array_fill_keys(array_keys($options), 0);
            $years = [];
            $perOptionPerYear = array_map(fn($k) => [], array_keys($options));
            $chartPie = ['labels' => array_values($options), 'totals' => array_values($statsPerOption)];
            $summaryText = "Belum ada data peminatan untuk kombinasi filter saat ini.";
            $trendSummary = [];
            $detailedCounts = [];
            $topReasonsGlobal = [];
            $topReasonsPerOption = [];
        } else {
            // total responden pada hasil filter
            $totalRespondents = $filteredCollection->count();

            // statistik per opsi (filtered)
            $statsPerOption = $filteredCollection->groupBy('minat')->map->count()->toArray();
            foreach (array_keys($options) as $k) {
                if (!isset($statsPerOption[$k])) $statsPerOption[$k] = 0;
            }

            // tahun yang tersedia pada filtered set (urut)
            $years = $filteredCollection
                ->map(fn($p) => \Carbon\Carbon::parse($p->created_at)->year)
                ->unique()
                ->sort()
                ->values()
                ->toArray();

            // fallback: jika kosong, ambil tahun global
            if (empty($years)) {
                $years = \App\Models\Peminatan::select(DB::raw('YEAR(created_at) as year'))
                    ->distinct()
                    ->orderBy('year')
                    ->pluck('year')
                    ->toArray();
            }

            // perOptionPerYear dari filteredCollection
            $perOptionPerYear = [];
            foreach ($options as $key => $label) {
                $arr = [];
                foreach ($years as $yr) {
                    $arr[] = $filteredCollection
                        ->where('minat', $key)
                        ->filter(fn($p) => \Carbon\Carbon::parse($p->created_at)->year == $yr)
                        ->count();
                }
                $perOptionPerYear[$key] = $arr;
            }

            // pie chart data
            $chartPie = [
                'labels' => array_values($options),
                'totals' => array_map(fn($k) => $statsPerOption[$k] ?? 0, array_keys($options)),
            ];

            // top reasons global & per option (frekuensi sederhana)
            $reasonCounts = $filteredCollection->pluck('alasan')
                ->filter()
                ->map(fn($r) => \Illuminate\Support\Str::of(trim($r))->lower()->substr(0, 200)->__toString())
                ->countBy()
                ->sortDesc();
            $topReasonsGlobal = $reasonCounts->take(3)->toArray();

            $topReasonsPerOption = [];
            foreach (array_keys($options) as $opt) {
                $entries = $filteredCollection->where('minat', $opt);
                $rc = $entries->pluck('alasan')
                    ->filter()
                    ->map(fn($r) => \Illuminate\Support\Str::of(trim($r))->lower()->substr(0, 200)->__toString())
                    ->countBy()
                    ->sortDesc()
                    ->take(3)
                    ->toArray();
                $topReasonsPerOption[$opt] = $rc;
            }

            // Trend summary (bandingkan dua tahun terakhir jika tersedia)
            $trendSummary = [];
            if (count($years) >= 2) {
                $lastIndex = count($years) - 1;
                $prevIndex = $lastIndex - 1;
                foreach ($options as $key => $label) {
                    $arr = $perOptionPerYear[$key] ?? [];
                    $prev = $arr[$prevIndex] ?? 0;
                    $curr = $arr[$lastIndex] ?? 0;
                    $diff = $curr - $prev;
                    if ($prev == 0) {
                        $pct = $curr > 0 ? 100.0 : 0.0;
                    } else {
                        $pct = round(($diff / max(1, $prev)) * 100, 1);
                    }

                    if ($diff > 0) {
                        $trendLabel = "Meningkat {$diff} siswa ({$pct}%) dibandingkan tahun {$years[$prevIndex]} → {$years[$lastIndex]}";
                    } elseif ($diff < 0) {
                        $trendLabel = "Menurun " . abs($diff) . " siswa (" . abs($pct) . "%) dibandingkan tahun {$years[$prevIndex]} → {$years[$lastIndex]}";
                    } else {
                        $trendLabel = "Stabil antara tahun {$years[$prevIndex]} dan {$years[$lastIndex]} (tidak ada perubahan).";
                    }

                    $trendSummary[$key] = [
                        'label' => $label,
                        'prev' => $prev,
                        'curr' => $curr,
                        'diff' => $diff,
                        'pct' => $pct,
                        'text' => $trendLabel,
                    ];
                }
            } else {
                foreach ($options as $key => $label) {
                    $trendSummary[$key] = [
                        'label' => $label,
                        'text' => 'Tidak cukup data tahun untuk menghitung tren (butuh minimal 2 tahun).'
                    ];
                }
            }

            // Ringkasan teks — gunakan jurusan yang dimiliki siswa (kelas->jurusan) penyumbang untuk topMinat
            $detailedCounts = $filteredCollection->groupBy('minat')->map->count()->toArray();
            arsort($detailedCounts);
            $topMinat = array_key_first($detailedCounts);
            $topCount = $detailedCounts[$topMinat] ?? 0;
            $topPct = $totalRespondents ? round(($topCount / $totalRespondents) * 100, 1) : 0;

            // tentukan jurusanText: prioritas -> request jurusan / request kelas / hitung dominan dari siswa
            if ($request->filled('jurusan') && $request->jurusan !== 'Semua Jurusan') {
                $jurusanText = $request->jurusan;
            } elseif ($request->filled('kelas')) {
                $jurusanText = optional($kelasList->firstWhere('id', (int)$request->kelas))->jurusan ?? 'tidak diketahui';
            } else {
                $entriesTop = $filteredCollection->where('minat', $topMinat);
                $jurusanCounts = [];
                foreach ($entriesTop as $e) {
                    $j = optional(optional($e->user)->siswa)->dataKelas->jurusan ?? null;
                    if ($j) $jurusanCounts[$j] = ($jurusanCounts[$j] ?? 0) + 1;
                }
                arsort($jurusanCounts);
                $topJurusan = array_keys(array_slice($jurusanCounts, 0, 2, true));
                $jurusanText = !empty($topJurusan) ? implode(' dan ', $topJurusan) : 'berbagai jurusan';
            }

            $lastUpdated = $filteredCollection->max('updated_at') ?? $filteredCollection->max('created_at');
            $lastUpdatedFormatted = $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->isoFormat('D MMMM Y') : null;

            $summaryText = "Berdasarkan data filter saat ini, minat terbanyak adalah <strong>"
                . ucfirst($topMinat) . "</strong> (sekitar <strong>{$topPct}%</strong> dari <strong>{$totalRespondents}</strong> responden). "
                . "Mayoritas pemilih minat ini berasal dari jurusan <strong>{$jurusanText}</strong>. "
                . ($lastUpdatedFormatted ? "Data terakhir diperbarui pada <strong>{$lastUpdatedFormatted}</strong>." : "");
        }

        // Aliases / kompatibilitas view
        $kelas = $kelasList;

        // Kirim semua variabel ke view
        return view('sistem_akademik.peminatan.index', compact(
            'peminatans',
            'header',
            'totalRespondents',
            'totalStudents',
            'statsPerOption',
            'years',
            'perOptionPerYear',
            'chartPie',
            'hasOwnPeminatan',
            'kelasList',
            'kelas',
            'jurusanList',
            'tahunAjaranList',
            'guruBKList',
            'summaryText',
            'trendSummary',
            'detailedCounts',
            'topReasonsGlobal',
            'topReasonsPerOption'
        ));
    }

    /**
     * Form tambah peminatan.
     */
    public function create(Request $request)
    {
        $header = 'Tambah Data Peminatan';

        // Hanya ambil user yang role = siswa **dan** BELUM memiliki peminatan
        $usersQuery = User::where('role', 'siswa')
            ->whereDoesntHave('peminatan');

        // optional: jika front-end menyediakan filter kelas pada form create, terima parameter 'kelas'
        if ($request->filled('kelas')) {
            $kelasFilter = $request->kelas;
            $usersQuery->whereHas('siswa', function ($q) use ($kelasFilter) {
                $q->where('kelas_id', $kelasFilter);
            });
        }

        $users = $usersQuery->orderBy('nama')->get();

        $kelasList = Kelas::select('id', 'nama_kelas')->orderBy('nama_kelas')->get();

        return view('sistem_akademik.peminatan.createOrEdit', compact('users', 'header', 'kelasList'));
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        // Validasi dengan aturan kondisional
        $rules = [
            'minat' => ['required', Rule::in(['bekerja', 'wirausaha', 'kuliah', 'lainnya'])],
            'alasan' => 'required|string',
            'jenis_pekerjaan'   => 'required_if:minat,bekerja|nullable|string|max:255',
            'ide_bisnis'        => 'required_if:minat,wirausaha|nullable|string|max:255',
            'pemilihan_jurusan' => 'required_if:minat,kuliah|nullable|string|max:255',
            'penghasilan_ortu'    => 'nullable|integer',
            'tanggungan_keluarga' => 'nullable|integer',
            'file_angket'         => 'nullable|url',
            'file_raport'         => 'nullable|url',
        ];

        // Jika admin (admin_sa) membuat untuk siswa, user_id wajib
        if (Auth::user()->role === 'admin_sa' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin') {
            $rules['user_id'] = ['required', 'integer', Rule::exists('users', 'id')->where(function ($q) {
                $q->where('role', 'siswa');
            })];
        }

        $validated = $request->validate($rules);

        // Tentukan user_id (jika siswa, pakai auth)
        if (Auth::user()->role === 'siswa') {
            $userId = Auth::id();
        } else {
            $userId = $validated['user_id'] ?? $request->input('user_id');
        }

        // Cek: apakah siswa ini sudah punya peminatan?
        if (Peminatan::where('user_id', $userId)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['user_id' => 'Siswa ini sudah memiliki data peminatan (1 siswa = 1 peminatan).']);
        }

        // Simpan
        $data = [
            'user_id' => $userId,
            'minat' => $validated['minat'],
            'alasan' => $validated['alasan'],
            'pemilihan_jurusan' => $validated['pemilihan_jurusan'] ?? null,
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'] ?? null,
            'ide_bisnis' => $validated['ide_bisnis'] ?? null,
            'penghasilan_ortu' => $validated['penghasilan_ortu'] ?? null,
            'tanggungan_keluarga' => $validated['tanggungan_keluarga'] ?? null,
            'file_angket' => $validated['file_angket'] ?? null,
            'file_raport' => $validated['file_raport'] ?? null,
        ];

        Peminatan::create($data);

        return redirect()
            ->route('sistem_akademik.peminatan.index')
            ->with('status', 'success')
            ->with('message', 'Data peminatan berhasil ditambah.');
    }

    /**
     * Form edit.
     */
    public function edit(Peminatan $peminatan)
    {
        $header = 'Edit Data Peminatan';

        $users = User::where('role', 'siswa')
            ->where(function ($q) use ($peminatan) {
                $q->whereDoesntHave('peminatan')
                    ->orWhere('id', $peminatan->user_id);
            })
            ->orderBy('nama')
            ->get();

        $kelasList = Kelas::select('id', 'nama_kelas')->orderBy('nama_kelas')->get();

        return view('sistem_akademik.peminatan.createOrEdit', compact('peminatan', 'users', 'header', 'kelasList'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, Peminatan $peminatan)
    {
        $rules = [
            'minat' => ['required', Rule::in(['bekerja', 'wirausaha', 'kuliah', 'lainnya'])],
            'alasan' => 'required|string',
            'jenis_pekerjaan'   => 'required_if:minat,bekerja|nullable|string|max:255',
            'ide_bisnis'        => 'required_if:minat,wirausaha|nullable|string|max:255',
            'pemilihan_jurusan' => 'required_if:minat,kuliah|nullable|string|max:255',
            'penghasilan_ortu'    => 'nullable|integer',
            'tanggungan_keluarga' => 'nullable|integer',
            'file_angket'         => 'nullable|url',
            'file_raport'         => 'nullable|url',
        ];

        if (Auth::user()->role === 'admin_sa' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin') {
            $rules['user_id'] = ['required', 'integer', Rule::exists('users', 'id')->where(function ($q) {
                $q->where('role', 'siswa');
            })];
        }

        $validated = $request->validate($rules);

        // Tentukan user_id
        if (Auth::user()->role === 'siswa') {
            // siswa hanya boleh update miliknya sendiri
            if ($peminatan->user_id !== Auth::id()) {
                abort(403);
            }
            $userId = Auth::id();
        } else {
            $userId = $validated['user_id'] ?? $request->input('user_id');
        }

        // Jika admin mengubah user_id, cek apakah user tujuan sudah punya peminatan (kecuali jika user tujuan sama dengan current)
        if ($userId != $peminatan->user_id && Peminatan::where('user_id', $userId)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['user_id' => 'Siswa tujuan sudah memiliki data peminatan.']);
        }

        $data = [
            'user_id' => $userId,
            'minat' => $validated['minat'],
            'alasan' => $validated['alasan'],
            'pemilihan_jurusan' => $validated['pemilihan_jurusan'] ?? null,
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'] ?? null,
            'ide_bisnis' => $validated['ide_bisnis'] ?? null,
            'penghasilan_ortu' => $validated['penghasilan_ortu'] ?? null,
            'tanggungan_keluarga' => $validated['tanggungan_keluarga'] ?? null,
            'file_angket' => $validated['file_angket'] ?? null,
            'file_raport' => $validated['file_raport'] ?? null,
        ];

        $peminatan->update($data);

        return redirect()
            ->route('sistem_akademik.peminatan.index')
            ->with('status', 'success')
            ->with('message', 'Data peminatan berhasil diupdate.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Peminatan $peminatan)
    {
        $peminatan->delete();

        return redirect()
            ->route('sistem_akademik.peminatan.index')
            ->with('status', 'success')
            ->with('message', 'Data peminatan berhasil dihapus.');
    }
}
