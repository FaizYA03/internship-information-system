@extends('sistem_akademik.layouts.main', ['title' => 'Kelola Berita'])

@section('css')
<style>
    /* Tombol tambah */
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 6px;
    }

    /* Thumbnail berita */
    .berita-thumb {
        height: 80px;
        width: 110px;
        object-fit: cover;
        border-radius: 6px;
    }

    /* Isi ringkasan */
    .berita-isi {
        max-width: 380px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Tanggal posting */
    .tanggal {
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Responsive tweaks */
    @media (max-width: 991.98px) {

        /* hide isi on small tablets and phones to keep layout tidy */
        .col-isi {
            display: none;
        }
    }

    @media (max-width: 767.98px) {
        .berita-thumb {
            height: 60px;
            width: 90px;
        }

        .berita-isi {
            max-width: 180px;
            font-size: 0.9rem;
        }

        table#data-table thead th {
            font-size: 0.85rem;
        }

        .btn-add {
            padding: 6px 8px;
            font-size: 0.9rem;
        }

        /* show tanggal in a smaller layout but hide other long columns */
        .col-tanggal {
            font-size: 0.85rem;
        }
    }

    /* Force table to allow horizontal scroll when needed */
    .table-responsive {
        overflow-x: auto;
    }

    /* Small spacing for action buttons */
    .table .btn {
        margin-right: 6px;
    }

    /* Center message under table when empty */
    .empty-hint {
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-newspaper text-primary me-2"></i> Kelola Berita</h5>
        <a href="{{ route('sistem_akademik.berita.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Berita
        </a>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3 flex-wrap" style="gap:10px;">

            <!-- SEARCH + FILTER (only one form in this view) -->
            <form action="{{ route('sistem_akademik.berita.index') }}"
                method="GET"
                class="row g-2 align-items-end">

                <!-- Filter Kategori (besar & jelas) -->
                <div class="col-12 col-lg-4">
                    <select name="filter"
                        class="form-select"
                        onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="terbaru" {{ request('filter') === 'terbaru' ? 'selected' : '' }}>Info Terbaru</option>
                        <option value="terlama" {{ request('filter') === 'terlama' ? 'selected' : '' }}>Info Terlama</option>
                        <option value="informasi" {{ request('filter') === 'informasi' ? 'selected' : '' }}>Informasi</option>
                        <option value="prestasi" {{ request('filter') === 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="pemberitahuan" {{ request('filter') === 'pemberitahuan' ? 'selected' : '' }}>Pemberitahuan</option>
                    </select>
                </div>

                <!-- Filter Tanggal (digabung jadi satu) -->
                <div class="col-12 col-lg-8">
                    <div class="input-group">

                        <input type="date"
                            name="from"
                            class="form-control"
                            value="{{ request('from') }}"
                            onchange="this.form.submit()">
                        <span class="input-group-text">-</span>
                        <input type="date"
                            name="to"
                            class="form-control"
                            value="{{ request('to') }}"
                            onchange="this.form.submit()">
                    </div>
                </div>

            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="data-table">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:12%;">Foto</th>
                        <th>Judul</th>
                        <th class="col-isi">Isi</th>
                        <th class="col-tanggal" style="width:14%;">Tanggal Posting</th>
                        <th style="width:18%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($berita as $b)
                    <tr>
                        <td>
                            @if($berita->firstItem())
                            {{ $berita->firstItem() + $loop->index }}
                            @else
                            {{ $loop->iteration }}
                            @endif
                        </td>

                        <td>
                            @if($b->foto)
                            <img src="{{ asset('assets/berita/' . $b->foto) }}" alt="" class="berita-thumb">
                            @else
                            <div style="height:80px;width:110px;background:#f0f0f0;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#9aa3ad;">
                                No Image
                            </div>
                            @endif
                        </td>

                        <td style="vertical-align:middle;">{{ Str::limit($b->judul, 80) }}</td>

                        <td class="berita-isi col-isi" style="vertical-align:middle;">{{ \Illuminate\Support\Str::limit(strip_tags($b->isi), 120) }}</td>

                        <td class="tanggal col-tanggal" style="vertical-align:middle;">
                            {{ optional($b->created_at)->format('d M Y H:i') }}
                        </td>

                        <td style="vertical-align:middle;">
                            <a href="{{ route('sistem_akademik.berita.show', $b->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('sistem_akademik.berita.edit', $b->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('sistem_akademik.berita.destroy', $b->id) }}" method="post" id="deleteForm{{ $b->id }}" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" onclick="confirmDelete('{{ $b->id }}')" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                            @if(!empty($b->file))
                            <a href="{{ asset('file/' . $b->file) }}" class="btn btn-sm btn-secondary" title="Unduh" target="_blank" download>
                                <i class="bi bi-download"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    {{-- Pastikan tetap jumlah td sama dengan jumlah th (6 kolom) agar DataTables tidak error --}}
                    <tr class="no-data">
                        <td>-</td>
                        <td>
                            <div style="height:80px;width:110px;background:#f8f9fb;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#9aa3ad;">
                                -
                            </div>
                        </td>
                        <td class="text-center">Tidak ada data berita.</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($berita->isEmpty())
        <div class="alert alert-info mt-3 empty-hint">
            Belum ada berita untuk kategori ini. Klik <a href="{{ route('sistem_akademik.berita.create') }}">Tambah Berita</a> untuk menambahkan konten.
        </div>
        @endif

        <div class="d-flex justify-content-center mt-2">
            {!! $berita->appends(request()->query())->links() !!}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }

        const table = $('#data-table').DataTable({
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: false,

            columnDefs: [{
                orderable: false,
                targets: [1, 5]
            }],

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
            }
        });
    });
</script>

@endsection