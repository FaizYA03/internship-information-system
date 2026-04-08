@extends('sistem_akademik.layouts.main', ['title' => 'Data Peminatan'])

@section('content')
@php
$userRole = Auth::user()->role;
$canCreate = $userRole === 'admin_sa' || ($userRole === 'siswa' && ! ($hasOwnPeminatan ?? false));
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-lightbulb text-primary me-2"></i> Data Peminatan</h5>
        @if($canCreate)
        <a href="{{ route('sistem_akademik.peminatan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Peminatan
        </a>
        @endif
    </div>
    <div class="card-body">
        {{-- FILTERROW --}}
        <form action="{{ route('sistem_akademik.peminatan.index') }}" method="GET" class="row g-2 mb-3 align-items-center">
            <div class="col-md-3">
                <select name="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach ($kelasList as $kelas)
                    <option value="{{ $kelas->id }}"
                        {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                        {{-- gunakan label jika sudah dibuat di controller, fallback ke nama_kelas --}}
                        {{ $kelas->label ?? $kelas->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="minat" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Minat --</option>
                    <option value="kuliah" {{ request('minat') == 'kuliah' ? 'selected' : '' }}>Kuliah</option>
                    <option value="bekerja" {{ request('minat') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                    <option value="wirausaha" {{ request('minat') == 'wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                    <option value="lainnya" {{ request('minat') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="guru_bk" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Guru BK --</option>
                    @foreach ($guruBKList as $bk)
                    <option value="{{ $bk->id }}" {{ request('guru_bk') == $bk->id ? 'selected' : '' }}>
                        {{-- sesuaikan kolom nama user di DB: bisa 'nama' atau 'name' --}}
                        {{ $bk->nama ?? $bk->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="tahun_ajaran" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tahun Ajaran --</option>
                    @foreach ($tahunAjaranList as $ta)
                    <option value="{{ $ta }}" {{ request('tahun_ajaran') == $ta ? 'selected' : '' }}>
                        {{ $ta }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- TABLE --}}
        @if($peminatans->total() === 0)
        <div class="text-center py-4">
            <i class="bi bi-info-circle fs-3"></i>
            <p class="mt-2 mb-0">Belum ada data peminatan.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr class="text-center align-middle">
                        <th style="width:10px">No</th>
                        <th>NIS</th>
                        <th style="width:100px">Nama</th>
                        <th style="width:70px">Minat</th>
                        <th>Detail Tujuan</th>
                        <th>Alasan</th>
                        <th style="width:120px">Penghasilan Orangtua</th>
                        <th style="width:50px">Tanggungan</th>
                        <th style="width:50px">Link Raport</th>
                        <th style="width:50px">Link Angket</th>
                        @if(in_array($userRole, ['admin_sa','siswa']))
                        <th style="width:60px">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminatans as $index => $p)
                    <tr>
                        <td class="text-center">{{ $peminatans->firstItem() + $index }}</td>

                        <td class="text-center">
                            {{ optional($p->user)->nis_nip ?? '-' }}
                            @php
                            $kelasNama = optional(optional($p->user)->siswa)->kelas ? optional($p->user->siswa->kelas)->nama_kelas : null;
                            @endphp
                            @if($kelasNama)
                            <div class="text-muted small">{{ $kelasNama }}</div>
                            @endif
                        </td>

                        <td class="text-left">{{ optional($p->user)->nama ?? '-' }}</td>

                        <td class="text-center">{{ ucfirst($p->minat) }}</td>

                        {{-- Detail Tujuan (gabungan) --}}
                        <td class="text-left">
                            @php
                            $tujuan = '-';
                            $minat = strtolower($p->minat ?? '');
                            if ($minat === 'kuliah' && !empty($p->pemilihan_jurusan)) {
                            $tujuan = $p->pemilihan_jurusan;
                            } elseif ($minat === 'bekerja' && !empty($p->jenis_pekerjaan)) {
                            $tujuan = $p->jenis_pekerjaan;
                            } elseif ($minat === 'wirausaha' && !empty($p->ide_bisnis)) {
                            $tujuan = $p->ide_bisnis;
                            } else {
                            $parts = array_filter([
                            $p->pemilihan_jurusan ?? null,
                            $p->jenis_pekerjaan ?? null,
                            $p->ide_bisnis ?? null
                            ]);
                            if (!empty($parts)) {
                            $tujuan = implode(' | ', $parts);
                            }
                            }
                            @endphp
                            {!! e($tujuan) !!}
                        </td>

                        <td style="max-width:300px; white-space:normal;">{{ Str::limit($p->alasan, 220) }}</td>

                        <td class="text-center">
                            @if(is_numeric($p->penghasilan_ortu) && $p->penghasilan_ortu > 0)
                            Rp. {{ number_format($p->penghasilan_ortu, 0, ',', '.') }}
                            @else
                            -
                            @endif
                        </td>

                        <td class="text-center">{{ $p->tanggungan_keluarga ? $p->tanggungan_keluarga . ' Orang' : '-' }}</td>

                        <td class="text-center">
                            @if($p->file_raport)
                            <a href="{{ $p->file_raport }}" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> Cek</a>
                            @else
                            -
                            @endif
                        </td>

                        <td class="text-center">
                            @if($p->file_angket)
                            <a href="{{ $p->file_angket }}" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> Cek</a>
                            @else
                            -
                            @endif
                        </td>

                        @if($userRole === 'admin_sa')
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('sistem_akademik.peminatan.edit', $p->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <button class="btn btn-sm btn-outline-danger shadow-sm" onclick="confirmDelete('{{ $p->id }}')" title="Hapus"><i class="bi bi-trash"></i></button>

                                <form id="deleteForm{{ $p->id }}" action="{{ route('sistem_akademik.peminatan.destroy', $p->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                        @elseif($userRole === 'siswa')
                        <td>
                            @if($p->user_id === Auth::id())
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('sistem_akademik.peminatan.edit', $p->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                            </div>
                            @else
                            <div class="text-center">-</div>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- RINGKASAN DI ATAS CHART --}}
    <div class="card p-3 mb-4">
        <h6 class="mb-2">
            Total Siswa yang Mengisi Peminatan:
            <strong>{{ $totalRespondents }}</strong>
            dari
            <strong>{{ $totalStudents }}</strong> siswa
        </h6>

        <div class="row">
            <div class="col-md-6">
                <ul class="mb-0">
                    @foreach ($statsPerOption as $optKey => $count)
                    <li>{{ ucfirst($optKey) }}: <strong>{{ $count }}</strong> siswa</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    Filter aktif:
                    <strong>{{ request('minat') ? ucfirst(request('minat')) : 'Semua' }}</strong> |

                    Kelas:
                    @php
                    $kelasAktif = $kelasList->firstWhere('id', request('kelas'));
                    @endphp

                    <strong>
                        {{ $kelasAktif
                ? $kelasAktif->nama_kelas . ' · ' . $kelasAktif->jurusan
                : 'Semua' }}
                    </strong>
                </small>
            </div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Distribusi Minat per Tahun</div>
                <div class="card-body chart-area" style="height: 300px;">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Proporsi Minat</div>
                <div class="card-body chart-pie" style="height: 300px;">
                    <canvas id="myPieChart"></canvas>
                    <p class="mt-3">
                        <small>
                            <strong>Total:</strong> {{ $totalRespondents }} siswa &nbsp;| &nbsp;
                            @foreach ($statsPerOption as $optKey => $count)
                            <span>{{ ucfirst($optKey) }}: {{ $count }}</span>@if(!$loop->last),@endif&nbsp;
                            @endforeach
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- SUMMARY DINAMIS --}}
    <div class="card p-3 mb-4">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-bar-chart-line-fill fs-3 text-primary"></i>
            <div>
                <h6 class="mb-1">Ringkasan Hasil</h6>
                <p class="mb-0 text-muted small">
                    {!! $summaryText ?? 'Belum ada ringkasan karena tidak ada data.' !!}
                </p>
            </div>
        </div>
    </div>

    {{-- TREN & RINCIAN --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card p-3 h-100">
                <h6 class="mb-2">Tren Per Minat (tahun terakhir)</h6>
                @if(!empty($trendSummary))
                <ul class="mb-0 list-unstyled small">
                    @foreach($trendSummary as $key => $t)
                    <li class="mb-2">
                        <strong>{{ $t['label'] ?? ucfirst($key) }}:</strong>
                        <div class="text-muted">{!! $t['text'] ?? 'Tidak cukup data.' !!}</div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-muted small">Tidak ada data tren.</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3 h-100">
                <h6 class="mb-2">Rincian & Alasan Umum</h6>

                <p class="mb-1 small text-muted">Jumlah per pilihan (filter aktif):</p>
                <ul class="mb-2">
                    @foreach(['bekerja','wirausaha','kuliah','lainnya'] as $opt)
                    <li>
                        <strong>{{ ucfirst($opt) }}:</strong>
                        {{ $detailedCounts[$opt] ?? 0 }} siswa
                        @php
                        $pct = isset($detailedCounts[$opt]) && $totalRespondents>0 ? round(($detailedCounts[$opt]/max(1,$detailedCounts[$opt] + array_sum($detailedCounts) - $detailedCounts[$opt]))*100,1) : null;
                        @endphp
                    </li>
                    @endforeach
                </ul>

                <p class="mb-1 small text-muted">Alasan teratas (global):</p>
                @if(!empty($topReasonsGlobal))
                <ol class="mb-0">
                    @foreach($topReasonsGlobal as $reason => $count)
                    <li class="small">{{ Str::limit($reason, 120) }} <span class="text-muted">({{ $count }}x)</span></li>
                    @endforeach
                </ol>
                @else
                <div class="small text-muted">Alasan beragam / tidak cukup contoh untuk dirangkum.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data peminatan akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }

    // Chart data dari controller
    const years = JSON.parse("{!! addslashes(json_encode($years ?? [])) !!}");
    const perOption = JSON.parse("{!! addslashes(json_encode($perOptionPerYear ?? [])) !!}");
    const chartPieLabels = JSON.parse("{!! addslashes(json_encode($chartPie['labels'] ?? [])) !!}");
    const chartPieData = JSON.parse("{!! addslashes(json_encode($chartPie['totals'] ?? [])) !!}");

    // Area chart
    (function() {
        const datasets = [];
        const palette = [
            'rgba(75, 192, 192, 0.6)',
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)'
        ];
        let idx = 0;
        for (const [key, arr] of Object.entries(perOption)) {
            datasets.push({
                label: key.charAt(0).toUpperCase() + key.slice(1),
                data: arr,
                fill: false,
                tension: 0.4,
                borderColor: palette[idx % palette.length],
                backgroundColor: palette[idx % palette.length],
                borderWidth: 2
            });
            idx++;
        }

        if (document.getElementById('myAreaChart')) {
            const ctx = document.getElementById('myAreaChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: years,
                    datasets
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Jumlah Peminatan per Opsi Tiap Tahun'
                        }
                    }
                }
            });
        }
    })();

    // Pie chart
    (function() {
        if (document.getElementById('myPieChart')) {
            const ctx = document.getElementById('myPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartPieLabels,
                    datasets: [{
                        data: chartPieData
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true
                }
            });
        }
    })();
</script>
@endsection