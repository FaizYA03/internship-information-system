<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CourseController extends Controller
{
    protected function slotOrder(): array   
    {
        return [
            '1',
            '2',
            '3',
            'istirahat',
            '4',
            '5',
            '6',
            'ISHOMA',
            '7',
            '8',
            '9',
            '10',
            'ISHO',
            '11',
            '12',
            '13'
        ];
    }

    protected function slotDetails(): array
    {
        return [
            '1' => ['label' => 'Jam 1',  'start' => '07:15', 'end' => '08:00',  'selectable' => true],
            '2' => ['label' => 'Jam 2',  'start' => '08:00', 'end' => '08:45',  'selectable' => true],
            '3' => ['label' => 'Jam 3',  'start' => '08:45', 'end' => '09:30',  'selectable' => true],
            'istirahat' => ['label' => 'Istirahat', 'start' => '09:30', 'end' => '10:00', 'selectable' => false],
            '4' => ['label' => 'Jam 4',  'start' => '10:00', 'end' => '10:45',  'selectable' => true],
            '5' => ['label' => 'Jam 5',  'start' => '10:45', 'end' => '11:30',  'selectable' => true],
            '6' => ['label' => 'Jam 6',  'start' => '11:30', 'end' => '12:15',  'selectable' => true],
            'ISHOMA' => ['label' => 'ISHOMA', 'start' => '12:15', 'end' => '13:15', 'selectable' => false],
            '7' => ['label' => 'Jam 7',  'start' => '13:15', 'end' => '13:45',  'selectable' => true],
            '8' => ['label' => 'Jam 8',  'start' => '13:45', 'end' => '14:45',  'selectable' => true],
            '9' => ['label' => 'Jam 9',  'start' => '14:15', 'end' => '14:45',  'selectable' => true],
            '10' => ['label' => 'Jam 10', 'start' => '14:45', 'end' => '15:15',  'selectable' => true],
            'ISHO' => ['label' => 'ISHO', 'start' => '15:15', 'end' => '15:45', 'selectable' => false],
            '11' => ['label' => 'Jam 11', 'start' => '15:45', 'end' => '16:15',  'selectable' => true],
            '12' => ['label' => 'Jam 12', 'start' => '16:15', 'end' => '16:45',  'selectable' => true],
            '13' => ['label' => 'Jam 13', 'start' => '16:45', 'end' => '17:00',  'selectable' => true],
        ];
    }

    protected function allowedDays(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    }

    protected function selectableSlots(): array
    {
        $details = $this->slotDetails();
        return array_filter($details, fn($s) => $s['selectable']);
    }

    /**
     * Convert slot id range -> jam mulai & jam selesai.
     * Range boleh melewati slot non-selectable (istirahat/ISHOMA/ISHO).
     */
    protected function slotRangeToTimes(string $startId, string $endId): array
    {
        $order = $this->slotOrder();
        $details = $this->slotDetails();

        $pos = array_flip($order);

        if (!isset($pos[$startId]) || !isset($pos[$endId])) {
            throw new \InvalidArgumentException('Slot tidak valid.');
        }

        $s = $pos[$startId];
        $e = $pos[$endId];

        if ($s > $e) {
            throw new \InvalidArgumentException('Slot akhir harus setelah slot awal.');
        }

        for ($i = $s; $i <= $e; $i++) {
            $id = $order[$i];
            if (!isset($details[$id])) throw new \InvalidArgumentException("Detail slot tidak ditemukan ($id).");
        }

        return [$details[$startId]['start'], $details[$endId]['end']];
    }

    protected function timesOverlap(string $aStart, string $aEnd, string $bStart, string $bEnd): bool
    {
        $aS = Carbon::createFromFormat('H:i', $aStart);
        $aE = Carbon::createFromFormat('H:i', $aEnd);
        $bS = Carbon::createFromFormat('H:i', $bStart);
        $bE = Carbon::createFromFormat('H:i', $bEnd);

        return $aS->lt($bE) && $bS->lt($aE);
    }

    /**
     * Periksa konflik untuk guru, ruangan, dan kelas.
     */
    protected function checkConflicts(string $hari, string $start, string $end, ?int $guruId = null, ?string $ruangan = null, ?int $kelasId = null, ?int $excludeCourseId = null): array
    {
        $conflicts = [
            'guru' => collect(),
            'ruangan' => collect(),
            'kelas' => collect(),
        ];

        $ruanganNorm = $ruangan !== null ? strtolower(trim($ruangan)) : null;

        $query = Course::with(['mataPelajaran.guru', 'kelas'])->where('hari', $hari);
        if ($excludeCourseId) $query->where('id', '!=', $excludeCourseId);
        $courses = $query->get();

        foreach ($courses as $c) {
            // safety: jika course sama dengan exclude (double-safety) skip
            if ($excludeCourseId && $c->id == $excludeCourseId) {
                continue;
            }

            if (!$c->jam_mulai || !$c->jam_selesai) {
                continue;
            }

            $cStart = substr($c->jam_mulai, 0, 5);
            $cEnd   = substr($c->jam_selesai, 0, 5);

            if ($this->timesOverlap($start, $end, $cStart, $cEnd)) {
                // guru conflict: cari guru id pada mataPelajaran relasi (jika ada)
                $mp = $c->mataPelajaran;
                $cGuruId = $mp->guru_id ?? null;
                if ($guruId && $cGuruId && $cGuruId == $guruId) {
                    $conflicts['guru']->push($c);
                }

                // ruangan conflict: normalisasi string sebelum bandingkan
                if ($ruanganNorm && $c->ruangan) {
                    $cRuanganNorm = strtolower(trim($c->ruangan));
                    if ($cRuanganNorm === $ruanganNorm) {
                        $conflicts['ruangan']->push($c);
                    }
                }

                // kelas conflict
                if ($kelasId && $c->kelas_id == $kelasId) {
                    $conflicts['kelas']->push($c);
                }
            }
        }
        // make unique by id to avoid duplicates
        $conflicts['guru'] = $conflicts['guru']->unique('id')->values();
        $conflicts['ruangan'] = $conflicts['ruangan']->unique('id')->values();
        $conflicts['kelas'] = $conflicts['kelas']->unique('id')->values();

        Log::debug('Course conflict check', [
            'exclude' => $excludeCourseId,
            'jam' => [$start, $end],
            'ruanganNorm' => $ruanganNorm,
            'conflicts' => [
                'guru' => $conflicts['guru']->pluck('id'),
                'ruangan' => $conflicts['ruangan']->pluck('id'),
                'kelas' => $conflicts['kelas']->pluck('id'),
            ]
        ]);

        return $conflicts;
    }

    protected function findAvailableSlots(?int $kelasId, ?int $guruId, ?string $ruangan, string $hari, ?int $excludeCourseId = null): array
    {
        $available = [];
        $order = $this->slotOrder();
        $details = $this->slotDetails();

        foreach ($order as $id) {
            if (!isset($details[$id])) continue;
            if ($details[$id]['selectable'] === false) continue;

            $start = $details[$id]['start'];
            $end = $details[$id]['end'];

            $conflicts = $this->checkConflicts($hari, $start, $end, $guruId, $ruangan, $kelasId, $excludeCourseId);
            if ($conflicts['guru']->isEmpty() && $conflicts['ruangan']->isEmpty() && $conflicts['kelas']->isEmpty()) {
                $available[] = [
                    'id' => $id,
                    'label' => $details[$id]['label'],
                    'start' => $start,
                    'end' => $end,
                ];
            }
        }

        return $available;
    }

    /* ===========================
     * PUBLIC ACTIONS (resource)
     * =========================== */

    public function index()
    {
        $title = 'Kelola Course & Mata Pelajaran';
        $header = 'Jadwal Mata Pelajaran';

        $query = Course::with(['mataPelajaran.guru', 'kelas', 'siswa'])
            ->orderBy('hari')
            ->orderBy('jam_mulai');

        // jika user adalah guru, batasi hanya ke course yang berkaitan dengan guru tersebut
        if (Auth::check() && Auth::user()->role === 'guru') {
            $user = Auth::user();
            $guruUserId = $user->id;
            $guruModelId = null;

            // jika ada relasi guru() pada User dan instance tersedia, ambil id model Guru (jika ada)
            if (method_exists($user, 'guru') && $user->guru) {
                $guruModelId = $user->guru->id ?? null;
                // juga jika model Guru menyimpan user_id, kita bisa gunakan user id-nya
                $possibleUserId = $user->guru->user_id ?? null;
                if ($possibleUserId) {
                    $guruUserId = $possibleUserId;
                }
            }

            $query->whereHas('mataPelajaran', function ($q) use ($guruUserId, $guruModelId) {
                $q->where('guru_id', $guruUserId);
                if ($guruModelId) {
                    $q->orWhere('guru_id', $guruModelId);
                }
            });
        }

        if (Auth::check() && Auth::user()->role === 'siswa' && Auth::user()->siswa) {
            $kelasId = Auth::user()->siswa->kelas_id;
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            } else {
                // kalau siswa record ada tapi belum ada kelas, hasilkan kosong
                $query->whereRaw('1 = 0');
            }
        }

        $courses = $query->get();

        return view('sistem_akademik.course.index', compact('courses', 'title', 'header'));
    }

    public function create()
    {
        $title = 'Tambah Jadwal';
        $header = 'Tambah Jadwal Mapel';

        $kelas = Kelas::all();
        $mapels = MataPelajaran::with('guru')->get();
        $slots = $this->selectableSlots();

        // KUNCI: kirim semua siswa agar view menampilkan nama siswa secara default (fallback)
        $siswa = Siswa::with('user')->orderBy('id')->get();
        $ruangans = \App\Models\Ruangan::orderBy('jenis_ruangan')->orderBy('nama_ruangan')->get();
        $labors = \App\Models\Labor::orderBy('nama_labor')->get();

        return view('sistem_akademik.course.createOrEdit', compact('kelas', 'mapels', 'slots', 'siswa', 'ruangans', 'labors', 'title', 'header'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'guru_id' => 'nullable|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_course' => 'nullable|string|max:255',
            'hari' => 'required|string',
            'slot_start' => 'nullable|string',
            'slot_end' => 'nullable|string',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'ruangan' => 'nullable|string|max:255',
            'labor_id' => 'nullable|exists:labor,id',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        if (!in_array($request->hari, $this->allowedDays())) {
            return back()->withErrors(['hari' => 'Hari harus antara Senin sampai Jumat.'])->withInput();
        }

        try {
            if ($request->filled('slot_start') && $request->filled('slot_end')) {
                [$jamMulai, $jamSelesai] = $this->slotRangeToTimes($request->slot_start, $request->slot_end);
            } elseif ($request->filled('jam_mulai') && $request->filled('jam_selesai')) {
                $jamMulai = $request->jam_mulai;
                $jamSelesai = $request->jam_selesai;
            } else {
                return back()->withErrors(['slot' => 'Pilih slot atau masukkan jam mulai & selesai.'])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['slot' => $e->getMessage()])->withInput();
        }

        $guruId = null;
        if ($request->filled('mata_pelajaran_id')) {
            $mp = MataPelajaran::find($request->mata_pelajaran_id);
            if (! $mp) {
                return back()->withErrors(['mata_pelajaran_id' => 'Mata pelajaran tidak ditemukan.'])->withInput();
            }
            $guruId = $mp->guru_id ?? null;
        } elseif ($request->filled('guru_id')) {
            $u = User::find($request->guru_id);
            if (! $u || ($u->role ?? '') !== 'guru') {
                return back()->withErrors(['guru_id' => 'Guru tidak valid.'])->withInput();
            }
            $guruId = $u->id;
        }

        $ruanganName = $request->ruangan;
        if (str_starts_with($ruanganName ?? '', 'LAB_')) {
            $parts = explode('_', $ruanganName, 3);
            if (count($parts) === 3) {
                $ruanganName = $parts[2]; // get the lab name
            }
        }

        // cek konflik (termasuk ruangan)
        $conflicts = $this->checkConflicts($request->hari, $jamMulai, $jamSelesai, $guruId, $ruanganName, $request->kelas_id);

        if (!$conflicts['guru']->isEmpty() || !$conflicts['ruangan']->isEmpty() || !$conflicts['kelas']->isEmpty()) {
            $recommendations = $this->findAvailableSlots($request->kelas_id, $guruId, $ruanganName, $request->hari);

            $conflictDetails = [
                'guru' => $conflicts['guru']->map(fn($c) => [
                    'course_id' => $c->id,
                    'kelas' => $c->kelas?->nama_kelas ?? null,
                    'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                    'jam_mulai' => $c->jam_mulai,
                    'jam_selesai' => $c->jam_selesai,
                    'ruangan' => $c->ruangan,
                ])->values(),
                'ruangan' => $conflicts['ruangan']->map(fn($c) => [
                    'course_id' => $c->id,
                    'kelas' => $c->kelas?->nama_kelas ?? null,
                    'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                    'jam_mulai' => $c->jam_mulai,
                    'jam_selesai' => $c->jam_selesai,
                    'ruangan' => $c->ruangan,
                ])->values(),
                'kelas' => $conflicts['kelas']->map(fn($c) => [
                    'course_id' => $c->id,
                    'kelas' => $c->kelas?->nama_kelas ?? null,
                    'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                    'jam_mulai' => $c->jam_mulai,
                    'jam_selesai' => $c->jam_selesai,
                    'ruangan' => $c->ruangan,
                ])->values(),
            ];

            return back()
                ->with('status', 'error')
                ->with('message', 'Terjadi bentrok jadwal (guru/ruangan/kelas). Lihat rekomendasi slot kosong.')
                ->with('conflicts', $conflicts)
                ->with('conflict_details', $conflictDetails)
                ->with('recommendations', $recommendations)
                ->withInput();
        }

        $ruanganName = $request->ruangan;
        if ($request->filled('labor_id') && str_starts_with($ruanganName, 'LAB_')) {
            $lab = \App\Models\Labor::find($request->labor_id);
            if ($lab) {
                $ruanganName = $lab->nama_labor;
            }
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // simpan course
            $course = Course::create([
                'kelas_id' => $request->kelas_id,
                'mata_pelajaran_id' => $request->mata_pelajaran_id ?? null,
                'hari' => $request->hari,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'ruangan' => $ruanganName,
                'labor_id' => $request->labor_id,
            ]);

            if ($request->filled('siswa_ids') && method_exists($course, 'siswa')) {
                $course->siswa()->attach($request->siswa_ids);
            }

            // Sync with jadwal_laboratorium if using a Lab
            if ($course->labor_id) {
                $kelasObj = \App\Models\Kelas::find($course->kelas_id);
                $namaKelasUrl = $kelasObj ? ($kelasObj->nama_kelas . ($kelasObj->jurusan ? ' ' . $kelasObj->jurusan : '')) : '-';
                
                $guruUserId = null;
                if ($course->mataPelajaran && $course->mataPelajaran->guru) {
                    $guruUserId = $course->mataPelajaran->guru->user_id;
                } elseif ($request->filled('guru_id')) {
                    $guruUserId = User::find($request->guru_id)?->id;
                }

                \App\Models\Lab\JadwalLaboratorium::create([
                    'course_id' => $course->id,
                    'labor_id' => $course->labor_id,
                    'mata_pelajaran' => $course->mataPelajaran ? $course->mataPelajaran->nama_mata_pelajaran : '',
                    'guru_id' => $guruUserId,
                    'kelas_id' => $course->kelas_id,
                    'kelas' => $namaKelasUrl,
                    'hari' => $course->hari,
                    'jam_mulai' => $course->jam_mulai,
                    'jam_selesai' => $course->jam_selesai,
                    'keterangan' => 'Jadwal Reguler Akademik'
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('sistem_akademik.course.index')
                ->with('status', 'success')
                ->with('message', 'Jadwal berhasil dibuat.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Gagal menyimpan jadwal: ' . $e->getMessage());
            return back()->with('status', 'error')->with('message', 'Gagal menyimpan jadwal: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Course $course)
    {
        $course->load(['mataPelajaran.guru', 'kelas', 'siswa.user']);
        return view('sistem_akademik.course.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $title = 'Edit Jadwal';
        $header = 'Edit Jadwal Mapel';

        $kelas = Kelas::all();
        $mapels = MataPelajaran::with('guru')->get();
        $slots = $this->selectableSlots();

        $selected = ['slot_start' => null, 'slot_end' => null];
        foreach ($this->slotDetails() as $id => $d) {
            if ($d['selectable']) {
                if (substr($course->jam_mulai, 0, 5) == $d['start']) $selected['slot_start'] = $id;
                if (substr($course->jam_selesai, 0, 5) == $d['end']) $selected['slot_end'] = $id;
            }
        }

        // include siswa list as fallback (harmless) so view can show options if needed
        $siswa = Siswa::with('user')->orderBy('id')->get();

        $selectedSiswaIds = method_exists($course, 'siswa') ? $course->siswa->pluck('id')->toArray() : [];
        $ruangans = \App\Models\Ruangan::orderBy('jenis_ruangan')->orderBy('nama_ruangan')->get();
        $labors = \App\Models\Labor::orderBy('nama_labor')->get();

        return view('sistem_akademik.course.createOrEdit', compact(
            'course',
            'kelas',
            'mapels',
            'slots',
            'siswa',
            'ruangans',
            'labors',
            'selected',
            'selectedSiswaIds',
            'title',
            'header'
        ));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'guru_id' => 'nullable|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_course' => 'nullable|string|max:255',
            'hari' => 'required|string',
            'slot_start' => 'nullable|string',
            'slot_end' => 'nullable|string',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'ruangan' => 'nullable|string|max:255',
            'labor_id' => 'nullable|exists:labor,id',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        if (!in_array($request->hari, $this->allowedDays())) {
            return back()->withErrors(['hari' => 'Hari harus antara Senin sampai Jumat.'])->withInput();
        }

        try {
            if ($request->filled('slot_start') && $request->filled('slot_end')) {
                [$jamMulai, $jamSelesai] = $this->slotRangeToTimes($request->slot_start, $request->slot_end);
            } elseif ($request->filled('jam_mulai') && $request->filled('jam_selesai')) {
                $jamMulai = $request->jam_mulai;
                $jamSelesai = $request->jam_selesai;
            } else {
                return back()->withErrors(['slot' => 'Pilih slot atau masukkan jam mulai & selesai.'])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['slot' => $e->getMessage()])->withInput();
        }

        // tentukan guru berdasarkan mata_pelajaran_id jika ada; jika tidak, gunakan guru_id (jika diberikan)
        $guruId = null;
        if ($request->filled('mata_pelajaran_id')) {
            $mp = MataPelajaran::find($request->mata_pelajaran_id);
            if (! $mp) {
                return back()->withErrors(['mata_pelajaran_id' => 'Mata pelajaran tidak ditemukan.'])->withInput();
            }
            $guruId = $mp->guru_id ?? null;
        } elseif ($request->filled('guru_id')) {
            $u = User::find($request->guru_id);
            if (! $u || ($u->role ?? '') !== 'guru') {
                return back()->withErrors(['guru_id' => 'Guru tidak valid.'])->withInput();
            }
            $guruId = $u->id;
        }

        // normalisasi nilai lama & baru untuk perbandingan
        $oldHari = $course->hari ?? '';
        $oldJamMulai = substr($course->jam_mulai ?? '', 0, 5);
        $oldJamSelesai = substr($course->jam_selesai ?? '', 0, 5);
        $oldRuangan = strtolower(trim($course->ruangan ?? ''));
        $oldKelasId = $course->kelas_id;
        $oldGuruId = $course->mataPelajaran?->guru_id ?? null;

        $newHari = $request->hari;
        $newJamMulai = substr($jamMulai, 0, 5);
        $newJamSelesai = substr($jamSelesai, 0, 5);
        $ruanganName = $request->ruangan;
        if (str_starts_with($ruanganName ?? '', 'LAB_')) {
            $parts = explode('_', $ruanganName, 3);
            if (count($parts) === 3) {
                $ruanganName = $parts[2]; // get the lab name
            }
        }
        $newRuangan = strtolower(trim($ruanganName ?? ''));
        $newKelasId = $request->kelas_id;
        $newGuruId = $guruId;

        $isCriticalChanged =
            ($oldHari !== $newHari) ||
            ($oldJamMulai !== $newJamMulai) ||
            ($oldJamSelesai !== $newJamSelesai) ||
            ($oldRuangan !== $newRuangan) ||
            ($oldKelasId != $newKelasId) ||
            ($oldGuruId != $newGuruId);

        // HANYA cek konflik jika ada perubahan kritikal
        if ($isCriticalChanged) {
            // gunakan ruangan tanpa perubahan case/space karena checkConflicts akan membandingkan trim,
            // namun kita juga kirim lowercased value supaya konsisten.
             $conflicts = $this->checkConflicts(
                $newHari,
                $newJamMulai,
                $newJamSelesai,
                $newGuruId,
                $ruanganName,
                $newKelasId,
                $course->id // exclude current course
            );

            // Safety: filter apapun yang masih refer ke current course (kadang exclude belum bekerja jika id falsy)
            $conflicts['guru'] = $conflicts['guru']->filter(fn($c) => $c->id !== $course->id)->values();
            $conflicts['ruangan'] = $conflicts['ruangan']->filter(fn($c) => $c->id !== $course->id)->values();
            $conflicts['kelas'] = $conflicts['kelas']->filter(fn($c) => $c->id !== $course->id)->values();

            if (!$conflicts['guru']->isEmpty() || !$conflicts['ruangan']->isEmpty() || !$conflicts['kelas']->isEmpty()) {
                $recommendations = $this->findAvailableSlots($request->kelas_id, $newGuruId, $ruanganName, $request->hari);

                $conflictDetails = [
                    'guru' => $conflicts['guru']->map(fn($c) => [
                        'course_id' => $c->id,
                        'kelas' => $c->kelas?->nama_kelas ?? null,
                        'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                        'jam_mulai' => $c->jam_mulai,
                        'jam_selesai' => $c->jam_selesai,
                        'ruangan' => $c->ruangan,
                    ])->values(),
                    'ruangan' => $conflicts['ruangan']->map(fn($c) => [
                        'course_id' => $c->id,
                        'kelas' => $c->kelas?->nama_kelas ?? null,
                        'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                        'jam_mulai' => $c->jam_mulai,
                        'jam_selesai' => $c->jam_selesai,
                        'ruangan' => $c->ruangan,
                    ])->values(),
                    'kelas' => $conflicts['kelas']->map(fn($c) => [
                        'course_id' => $c->id,
                        'kelas' => $c->kelas?->nama_kelas ?? null,
                        'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                        'jam_mulai' => $c->jam_mulai,
                        'jam_selesai' => $c->jam_selesai,
                        'ruangan' => $c->ruangan,
                    ])->values(),
                ];

                return back()
                    ->with('status', 'error')
                    ->with('message', 'Terjadi bentrok jadwal saat update. Lihat rekomendasi slot.')
                    ->with('conflicts', $conflicts)
                    ->with('conflict_details', $conflictDetails)
                    ->with('recommendations', $recommendations)
                    ->withInput();
            }
        }

        // Simpan perubahan (tidak terpengaruh pengecekan konflik bila tidak kritikal)
        $ruanganName = $request->ruangan;
        if ($request->filled('labor_id') && str_starts_with($ruanganName ?? '', 'LAB_')) {
            $lab = \App\Models\Labor::find($request->labor_id);
            if ($lab) {
                $ruanganName = $lab->nama_labor;
            }
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $course->update([
                'kelas_id' => $request->kelas_id,
                'mata_pelajaran_id' => $request->mata_pelajaran_id ?? null,
                'hari' => $request->hari,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'ruangan' => $ruanganName,
                'labor_id' => $request->labor_id,
            ]);

            if ($request->filled('siswa_ids') && method_exists($course, 'siswa')) {
                $course->siswa()->sync($request->siswa_ids);
            } elseif (method_exists($course, 'siswa')) {
                $course->siswa()->detach();
            }

            // Sync update to jadwal_laboratorium
            $jadwalLab = \App\Models\Lab\JadwalLaboratorium::where('course_id', $course->id)->first();
            if ($course->labor_id) {
                $kelasObj = \App\Models\Kelas::find($course->kelas_id);
                $namaKelasUrl = $kelasObj ? ($kelasObj->nama_kelas . ($kelasObj->jurusan ? ' ' . $kelasObj->jurusan : '')) : '-';
                
                $guruUserId = null;
                if ($course->mataPelajaran && $course->mataPelajaran->guru) {
                    $guruUserId = $course->mataPelajaran->guru->user_id;
                } elseif ($request->filled('guru_id')) {
                    $guruUserId = User::find($request->guru_id)?->id;
                }

                if ($jadwalLab) {
                    $jadwalLab->update([
                        'labor_id' => $course->labor_id,
                        'mata_pelajaran' => $course->mataPelajaran ? $course->mataPelajaran->nama_mata_pelajaran : '',
                        'guru_id' => $guruUserId,
                        'kelas_id' => $course->kelas_id,
                        'kelas' => $namaKelasUrl,
                        'hari' => $course->hari,
                        'jam_mulai' => $course->jam_mulai,
                        'jam_selesai' => $course->jam_selesai,
                    ]);
                } else {
                    \App\Models\Lab\JadwalLaboratorium::create([
                        'course_id' => $course->id,
                        'labor_id' => $course->labor_id,
                        'mata_pelajaran' => $course->mataPelajaran ? $course->mataPelajaran->nama_mata_pelajaran : '',
                        'guru_id' => $guruUserId,
                        'kelas_id' => $course->kelas_id,
                        'kelas' => $namaKelasUrl,
                        'hari' => $course->hari,
                        'jam_mulai' => $course->jam_mulai,
                        'jam_selesai' => $course->jam_selesai,
                        'keterangan' => 'Jadwal Reguler Akademik'
                    ]);
                }
            } elseif ($jadwalLab) {
                // Lab removed, delete the scheduled lab
                $jadwalLab->delete();
            }

            \Illuminate\Support\Facades\DB::commit();

            Log::info('Update debug', compact('oldHari', 'oldJamMulai', 'oldJamSelesai', 'oldRuangan', 'oldKelasId', 'oldGuruId', 'newHari', 'newJamMulai', 'newJamSelesai', 'newRuangan', 'newKelasId', 'newGuruId'));

            return redirect()->route('sistem_akademik.course.index')
                ->with('status', 'success')
                ->with('message', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Gagal update jadwal: ' . $e->getMessage());
            return back()->with('status', 'error')->with('message', 'Gagal update jadwal: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Course $course)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            if (method_exists($course, 'siswa')) {
                $course->siswa()->detach();
            }

            // Remove associated JadwalLaboratorium explicitly before course
            \App\Models\Lab\JadwalLaboratorium::where('course_id', $course->id)->delete();

            $course->delete(); // Delete course record

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('sistem_akademik.course.index')
                ->with('status', 'success')
                ->with('message', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Hapus jadwal error: ' . $e->getMessage());
            return redirect()->back()
                ->with('status', 'error')
                ->with('message', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    public function getRecommendations(Request $request)
    {
        $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'guru_id' => 'nullable|exists:users,id',
            'ruangan' => 'nullable|string',
            'hari' => 'required|string',
            'exclude_course_id' => 'nullable|integer',
        ]);

        if (!in_array($request->hari, $this->allowedDays())) {
            return response()->json(['success' => false, 'message' => 'Hari harus antara Senin sampai Jumat.'], 422);
        }

        $guruId = null;
        if ($request->filled('mata_pelajaran_id')) {
            $mp = MataPelajaran::find($request->mata_pelajaran_id);
            $guruId = $mp->guru_id ?? null;
        } elseif ($request->filled('guru_id')) {
            $u = User::find($request->guru_id);
            $guruId = ($u && ($u->role ?? '') === 'guru') ? $u->id : null;
        }

        $exclude = $request->input('exclude_course_id') ? (int) $request->input('exclude_course_id') : null;

        $ruanganName = $request->ruangan;
        if (str_starts_with($ruanganName ?? '', 'LAB_')) {
            $parts = explode('_', $ruanganName, 3);
            if (count($parts) === 3) {
                $ruanganName = $parts[2]; // get the lab name
            }
        }
        
        $available = $this->findAvailableSlots($request->kelas_id, $guruId, $ruanganName, $request->hari, $exclude);

        return response()->json([
            'success' => true,
            'available_slots' => $available
        ]);
    }

    public function getStudentsByJurusan(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $students = Siswa::with('user')
            ->where('kelas_id', $request->kelas_id)
            ->orderBy('id')
            ->get();

        // Jika ada siswa, beri petunjuk agar client otomatis memilih semua (kecuali client mengirim preselect)
        $selectAll = $students->isNotEmpty();

        return response()->json([
            'success' => true,
            'students' => $students,
            'select_all' => $selectAll
        ]);
    }


    public function ajaxCheckConflicts(Request $request)
    {
        $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'guru_id' => 'nullable|exists:users,id',
            'ruangan' => 'nullable|string',
            'slot_start' => 'nullable|string',
            'slot_end' => 'nullable|string',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'hari' => 'required|string',
            'exclude_course_id' => 'nullable|integer'
        ]);

        if (!in_array($request->hari, $this->allowedDays())) {
            return response()->json(['success' => false, 'message' => 'Hari tidak valid.'], 422);
        }

        // tentukan jam mulai & selesai dari slot atau gunakan jam langsung
        try {
            if ($request->filled('slot_start') && $request->filled('slot_end')) {
                [$jamMulai, $jamSelesai] = $this->slotRangeToTimes($request->slot_start, $request->slot_end);
            } elseif ($request->filled('jam_mulai') && $request->filled('jam_selesai')) {
                $jamMulai = $request->jam_mulai;
                $jamSelesai = $request->jam_selesai;
            } else {
                return response()->json(['success' => false, 'message' => 'Slot atau jam harus diisi.'], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        // tentukan guru id seperti di store/update (jika mata_pelajaran_id diberikan)
        $guruId = null;
        if ($request->filled('mata_pelajaran_id')) {
            $mp = MataPelajaran::find($request->mata_pelajaran_id);
            $guruId = $mp->guru_id ?? null;
        } elseif ($request->filled('guru_id')) {
            $u = User::find($request->guru_id);
            $guruId = ($u && ($u->role ?? '') === 'guru') ? $u->id : null;
        }

        $excludeId = $request->input('exclude_course_id') ? (int)$request->input('exclude_course_id') : null;

        $ruanganName = $request->ruangan;
        if (str_starts_with($ruanganName ?? '', 'LAB_')) {
            $parts = explode('_', $ruanganName, 3);
            if (count($parts) === 3) {
                $ruanganName = $parts[2]; // get the lab name
            }
        }

        $conflicts = $this->checkConflicts($request->hari, $jamMulai, $jamSelesai, $guruId, $ruanganName, $request->kelas_id, $excludeId);

        $conflictDetails = [
            'guru' => $conflicts['guru']->map(fn($c) => [
                'course_id' => $c->id,
                'kelas_id' => $c->kelas?->id ?? null,
                'kelas' => $c->kelas?->nama_kelas ?? null,
                'jurusan' => $c->kelas?->jurusan ?? null,
                'tahun_ajaran' => $c->kelas?->tahun_ajaran ?? null,
                'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                'jam_mulai' => $c->jam_mulai,
                'jam_selesai' => $c->jam_selesai,
                'ruangan' => $c->ruangan,
            ])->values(),
            'ruangan' => $conflicts['ruangan']->map(fn($c) => [
                'course_id' => $c->id,
                'kelas_id' => $c->kelas?->id ?? null,
                'kelas' => $c->kelas?->nama_kelas ?? null,
                'jurusan' => $c->kelas?->jurusan ?? null,
                'tahun_ajaran' => $c->kelas?->tahun_ajaran ?? null,
                'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                'jam_mulai' => $c->jam_mulai,
                'jam_selesai' => $c->jam_selesai,
                'ruangan' => $c->ruangan,
            ])->values(),
            'kelas' => $conflicts['kelas']->map(fn($c) => [
                'course_id' => $c->id,
                'kelas_id' => $c->kelas?->id ?? null,
                'kelas' => $c->kelas?->nama_kelas ?? null,
                'jurusan' => $c->kelas?->jurusan ?? null,
                'tahun_ajaran' => $c->kelas?->tahun_ajaran ?? null,
                'mata_pelajaran' => $c->mataPelajaran?->nama_mata_pelajaran ?? null,
                'jam_mulai' => $c->jam_mulai,
                'jam_selesai' => $c->jam_selesai,
                'ruangan' => $c->ruangan,
            ])->values(),
        ];

        return response()->json([
            'success' => true,
            'conflict_details' => $conflictDetails,
            'has_conflict' => (!$conflicts['guru']->isEmpty() || !$conflicts['ruangan']->isEmpty() || !$conflicts['kelas']->isEmpty())
        ]);
    }
}
