@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="page-title">Buat Draft Pengadaan Buku</h1>
            <p class="text-muted mb-4">Mulai proses pengadaan buku baru dengan memasukkan detail estimasi pembelian.</p>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('perpustakaan.pengadaan.store') }}" method="POST" id="pengadaanForm">
                        @csrf
                        
                        @if(isset($rekomendasiWaka) && $rekomendasiWaka->count() > 0)
                        <div class="alert alert-info mb-4 border-0 shadow-sm">
                            <h5 class="fw-bold"><i class="bi bi-bookmark-star me-2"></i>Rekomendasi Waka Kurikulum</h5>
                            <p class="small mb-3">Terdapat usulan pengadaan buku dari Waka Kurikulum. Anda dapat menyertakannya dalam draft pengadaan ini.</p>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle bg-white table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Judul Buku</th>
                                            <th>Pengarang</th>
                                            <th>Prioritas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rekomendasiWaka as $rek)
                                        <tr>
                                            <td>{{ $rek->judul_buku }}</td>
                                            <td>{{ $rek->pengarang }}</td>
                                            <td>{{ $rek->prioritas }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="addFromRekomendasi({{ $rek->id }}, '{{ addslashes($rek->judul_buku) }}', '{{ addslashes($rek->pengarang) }}', '{{ addslashes($rek->penerbit) }}')">
                                                    Tambahkan
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="selectedRekomendasiContainer"></div>
                        </div>
                        @endif
                        
                        <h5 class="mb-3 text-primary border-bottom pb-2">Informasi Umum</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Pengadaan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Pengadaan Buku Produktif TKJ Semester Ganjil 2026">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi Tambahan</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Catatan atau alasan pengadaan..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Vendor (Opsional di awal)</label>
                            <select name="vendor_id" class="form-select">
                                <option value="">-- Pilih Nanti --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <h5 class="mb-3 text-primary border-bottom pb-2 d-flex justify-content-between align-items-center">
                            Daftar Buku (Estimasi)
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBookRow()">
                                <i class="bi bi-plus-circle"></i> Tambah Buku
                            </button>
                        </h5>
                        
                        <div id="booksContainer">
                            <!-- Template for book item -->
                            <div class="book-row border p-3 rounded mb-3 bg-light">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label small">Judul Buku <span class="text-danger">*</span></label>
                                        <input type="text" name="details[0][judul]" class="form-control form-control-sm judul-input" required onkeyup="checkDuplicate(this)">
                                        <div class="duplicate-warning text-warning small mt-1 d-none">
                                            <i class="bi bi-exclamation-triangle"></i> Buku ini mungkin sudah ada di katalog.
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Penulis</label>
                                        <input type="text" name="details[0][penulis]" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">Penerbit</label>
                                        <input type="text" name="details[0][penerbit]" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">ISBN</label>
                                        <input type="text" name="details[0][isbn]" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label small">Jumlah <span class="text-danger">*</span></label>
                                        <input type="number" name="details[0][jumlah]" class="form-control form-control-sm item-qty" required min="1" value="1" onchange="calculateTotal()">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Estimasi Harga Satuan <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="details[0][harga_per_unit]" class="form-control item-price" required min="0" value="0" onchange="calculateTotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeBookRow(this)">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold">Total Estimasi Keseluruhan:</span>
                            <span class="fs-5 fw-bold" id="grandTotal">Rp 0</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perpustakaan.pengadaan.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary" style="background-color: var(--primary); border:none;">
                                <i class="bi bi-save"></i> Simpan sebagai Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    // Simplified duplicate checking list from server
    const catalogBooks = [
        @foreach($buku as $b)
        "{{ strtolower(addslashes($b->judul)) }}",
        @endforeach
    ];

    let rowCount = 1;

    function addBookRow() {
        const container = document.getElementById('booksContainer');
        const newRow = document.createElement('div');
        newRow.className = 'book-row border p-3 rounded mb-3 bg-light';
        newRow.innerHTML = `
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="details[${rowCount}][judul]" class="form-control form-control-sm judul-input" required onkeyup="checkDuplicate(this)">
                    <div class="duplicate-warning text-warning small mt-1 d-none">
                        <i class="bi bi-exclamation-triangle"></i> Buku ini mungkin sudah ada di katalog.
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Penulis</label>
                    <input type="text" name="details[${rowCount}][penulis]" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Penerbit</label>
                    <input type="text" name="details[${rowCount}][penerbit]" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">ISBN</label>
                    <input type="text" name="details[${rowCount}][isbn]" class="form-control form-control-sm">
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label class="form-label small">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="details[${rowCount}][jumlah]" class="form-control form-control-sm item-qty" required min="1" value="1" onchange="calculateTotal()">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Estimasi Harga Satuan <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="details[${rowCount}][harga_per_unit]" class="form-control item-price" required min="0" value="0" onchange="calculateTotal()">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeBookRow(this)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        rowCount++;
    }

    function addFromRekomendasi(id, judul, pengarang, penerbit) {
        const container = document.getElementById('booksContainer');
        const newRow = document.createElement('div');
        newRow.className = 'book-row border p-3 rounded mb-3 bg-light border-primary';
        newRow.innerHTML = `
            <input type="hidden" name="rekomendasi_ids[]" value="${id}">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="details[${rowCount}][judul]" class="form-control form-control-sm judul-input" required value="${judul}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Penulis</label>
                    <input type="text" name="details[${rowCount}][penulis]" class="form-control form-control-sm" value="${pengarang}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Penerbit</label>
                    <input type="text" name="details[${rowCount}][penerbit]" class="form-control form-control-sm" value="${penerbit}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">ISBN</label>
                    <input type="text" name="details[${rowCount}][isbn]" class="form-control form-control-sm">
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label class="form-label small">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="details[${rowCount}][jumlah]" class="form-control form-control-sm item-qty" required min="1" value="1" onchange="calculateTotal()">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Estimasi Harga Satuan <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="details[${rowCount}][harga_per_unit]" class="form-control item-price" required min="0" value="0" onchange="calculateTotal()">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeBookRow(this, ${id})">
                        <i class="bi bi-trash"></i> Batal Tambah
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        rowCount++;
        
        // Hide button
        event.target.innerText = "Sudah Ditambahkan";
        event.target.classList.replace('btn-outline-primary', 'btn-success');
        event.target.disabled = true;
        event.target.id = "btn-rek-" + id;
    }

    function removeBookRow(btn, rekId = null) {
        const row = btn.closest('.book-row');
        row.remove();
        calculateTotal();
        if (rekId) {
            let btnRek = document.getElementById("btn-rek-" + rekId);
            if(btnRek) {
                btnRek.innerText = "Tambahkan";
                btnRek.classList.replace('btn-success', 'btn-outline-primary');
                btnRek.disabled = false;
            }
        }
    }

    function calculateTotal() {
        const prices = document.querySelectorAll('.item-price');
        const qtys = document.querySelectorAll('.item-qty');
        let grandTotal = 0;

        for (let i = 0; i < prices.length; i++) {
            const price = parseFloat(prices[i].value) || 0;
            const qty = parseFloat(qtys[i].value) || 0;
            grandTotal += (price * qty);
        }

        document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(grandTotal);
    }

    function checkDuplicate(input) {
        const val = input.value.toLowerCase().trim();
        const warning = input.nextElementSibling;
        
        if (val.length > 3) {
            const found = catalogBooks.some(title => title.includes(val) || val.includes(title));
            if (found) {
                warning.classList.remove('d-none');
            } else {
                warning.classList.add('d-none');
            }
        } else {
            warning.classList.add('d-none');
        }
    }
</script>
@endsection
