@extends('magang.layouts.main')

@section('css')
<style>
    .form-section {
        background-color: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-section-title {
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    .guidelines {
        background-color: #f8f9fa;
        border-left: 4px solid var(--primary);
        border-radius: var(--radius);
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Edit Laporan</h1>
        <a href="{{ route('magang.siswa.laporan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">

            {{-- 🔥 FORM EDIT --}}
            <form action="{{ route('magang.siswa.laporan.update', $laporan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h4 class="form-section-title">Informasi Dasar</h4>

                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text"
                               name="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $laporan->judul) }}" required>

                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Hari Ke</label>
                            <input type="number"
                                   name="minggu_ke"
                                   class="form-control"
                                   value="{{ old('minggu_ke', $laporan->minggu_ke) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kegiatan</label>
                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="{{ old('tanggal_mulai', $laporan->tanggal_mulai) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Isi Laporan</h4>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label for="deskripsi" class="form-label mb-0">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-sm btn-outline-info" id="btnImproveAi">
                            <i class="bi bi-magic me-1"></i> Buat dengan AI
                        </button>
                    </div>
                    <textarea id="deskripsi" name="deskripsi"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              rows="10" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Status Laporan</h4>

                    <div class="form-check">
                        <input type="radio" name="status" value="draft"
                            {{ old('status', $laporan->status) == 'draft' ? 'checked' : '' }}>
                        Draft
                    </div>

                    <div class="form-check mt-2">
                        <input type="radio" name="status" value="submitted"
                            {{ old('status', $laporan->status) == 'submitted' ? 'checked' : '' }}>
                        Submit
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('magang.siswa.laporan.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Laporan</button>
                </div>

            </form>
        </div>

        {{-- SIDEBAR TETAP --}}
        <div class="col-lg-4">
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="bi bi-info-circle me-1"></i> Panduan
                </h4>

                <div class="guidelines">
                    <p>Perbaiki laporan sesuai revisi dari pembimbing.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal AI Prompt -->
<div class="modal fade" id="aiPromptModal" tabindex="-1" aria-labelledby="aiPromptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 bg-light">
                <h5 class="modal-title fw-bold" style="color: var(--primary);" id="aiPromptModalLabel">
                    <i class="bi bi-magic me-2"></i>Buat Laporan dengan AI
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <label for="aiPromptInput" class="form-label fw-semibold text-secondary mb-2">
                    Ketik poin-poin / inti kegiatan Anda hari ini:
                </label>
                <textarea class="form-control" id="aiPromptInput" rows="3" placeholder="Contoh: Hari ini saya memperbaiki PC di lab 2, mengganti RAM pada 5 buah PC..."></textarea>
                <div class="form-text mt-2">
                    AI akan menyusun kalimat singkat ini menjadi paragraf laporan yang baku dan profesional.
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSubmitAiPrompt">
                    <i class="bi bi-send me-1"></i> Mulai Susun
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnImprove = document.getElementById('btnImproveAi');
    const deskripsiInput = document.getElementById('deskripsi');

    if(btnImprove && deskripsiInput) {
        let aiModal;
        if(typeof bootstrap !== 'undefined') {
            aiModal = new bootstrap.Modal(document.getElementById('aiPromptModal'));
        }
        const btnSubmitAi = document.getElementById('btnSubmitAiPrompt');
        const aiPromptInput = document.getElementById('aiPromptInput');

        btnImprove.addEventListener('click', function() {
            aiPromptInput.value = '';
            aiPromptInput.classList.remove('is-invalid');
            if(aiModal) aiModal.show();
            setTimeout(() => aiPromptInput.focus(), 500);
        });

        btnSubmitAi.addEventListener('click', async function() {
            const userPrompt = aiPromptInput.value.trim();
            if (!userPrompt) {
                aiPromptInput.classList.add('is-invalid');
                return;
            }
            aiPromptInput.classList.remove('is-invalid');
            if(aiModal) aiModal.hide();

            const originalText = btnImprove.innerHTML;
            btnImprove.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyusun...';
            btnImprove.disabled = true;

            try {
                const response = await fetch("{{ route('magang.siswa.laporan.improve_ai') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ prompt_text: userPrompt.trim() })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Terjadi kesalahan sistem');
                }

                if (data.deskripsi) {
                    deskripsiInput.value = data.deskripsi;
                    deskripsiInput.classList.add('is-valid');
                    setTimeout(() => deskripsiInput.classList.remove('is-valid'), 2000);
                } else {
                    alert('AI tidak memberikan respon. Coba lagi.');
                }
            } catch (error) {
                alert(error.message);
            } finally {
                btnImprove.innerHTML = originalText;
                btnImprove.disabled = false;
            }
        });
    }
});
</script>
@endsection