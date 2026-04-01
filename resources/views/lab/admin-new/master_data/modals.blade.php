<!-- Add Kategori Modal -->
<div class="modal fade" id="addKategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lab.admin_new.master_data.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Kategori Modal -->
<div class="modal fade" id="editKategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editKategoriForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editKategoriId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editKategoriNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="editKategoriDeskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Jenis Lab Modal -->
<div class="modal fade" id="addJenisLabModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lab.admin_new.master_data.jenis_lab.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Laboratorium</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Jenis Lab Modal -->
<div class="modal fade" id="editJenisLabModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editJenisLabForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editJenisLabId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jenis Laboratorium</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editJenisLabNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="editJenisLabDeskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Kondisi Modal -->
<div class="modal fade" id="addKondisiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lab.admin_new.master_data.kondisi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Status Kondisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Status <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Badge <span class="text-danger">*</span></label>
                        <select name="warna" class="form-select" required>
                            <option value="success">Hijau (success)</option>
                            <option value="warning">Kuning (warning)</option>
                            <option value="danger">Merah (danger)</option>
                            <option value="info">Biru (info)</option>
                            <option value="secondary">Abu-abu (secondary)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Kondisi Modal -->
<div class="modal fade" id="editKondisiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editKondisiForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editKondisiId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Status Kondisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Status <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editKondisiNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="editKondisiDeskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Badge <span class="text-danger">*</span></label>
                        <select name="warna" id="editKondisiWarna" class="form-select" required>
                            <option value="success">Hijau (success)</option>
                            <option value="warning">Kuning (warning)</option>
                            <option value="danger">Merah (danger)</option>
                            <option value="info">Biru (info)</option>
                            <option value="secondary">Abu-abu (secondary)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Sumber Modal -->
<div class="modal fade" id="addSumberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lab.admin_new.master_data.sumber.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sumber Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Sumber <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Sumber Modal -->
<div class="modal fade" id="editSumberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSumberForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editSumberId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sumber Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Sumber <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editSumberNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="editSumberDeskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
