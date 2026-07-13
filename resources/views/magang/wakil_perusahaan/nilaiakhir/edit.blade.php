@extends('magang.layouts.main')

@section('content')
<style>
    .create-page {
        min-height: 100vh;
        background: linear-gradient(180deg, #eef2ff 0%, #ffffff 100%);
        padding: 3rem 0;
    }

    .create-card {
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(99, 102, 241, 0.16);
    }

    .create-card-header {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(139, 92, 246, 0.95));
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        padding: 2rem 2rem 1.5rem;
        color: white;
    }

    .create-card-header h2 {
        margin: 0;
        font-size: 1.95rem;
        font-weight: 700;
    }

    .create-card-header p {
        margin: 0.5rem 0 0;
        color: rgba(255,255,255,0.88);
        font-size: 0.96rem;
    }

    .form-section {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.55rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control-custom {
        width: 100%;
        border-radius: 18px;
        border: 1px solid #c7d2fe;
        background: #f8fafc;
        padding: 0.95rem 1.15rem;
        color: #0f172a;
        font-size: 1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
        background: #ffffff;
    }

    .form-note {
        font-size: 0.92rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    .submit-button {
        border-radius: 999px;
        font-weight: 700;
        padding: 0.92rem 2rem;
        box-shadow: 0 14px 30px rgba(59, 130, 246, 0.18);
        border: none;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 35px rgba(79, 70, 229, 0.24);
    }

    .info-panel {
        background: #eef2ff;
        border-radius: 20px;
        padding: 1rem 1.25rem;
        border: 1px solid #e0e7ff;
        color: #475569;
        margin-bottom: 1.5rem;
    }

    .info-panel strong {
        color: #1e293b;
    }
</style>

<div class="create-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="create-card">
                    <div class="create-card-header">
                        <h2>Edit Nilai Akhir PKL</h2>
                        <p>Perbarui nilai laporan untuk menghitung ulang nilai akhir siswa.</p>
                    </div>
                    <div class="form-section">
                        <div class="info-panel d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-600"></i>
                            <div>
                                <strong>Tip:</strong> Pastikan nilai laporan yang direvisi sudah benar sebelum menyimpan.
                            </div>
                        </div>

                        @php
                            $avgHardSkill = ($penilaian->hard_skill_1 + $penilaian->hard_skill_2 + $penilaian->hard_skill_3) / 3;
                            $kewirausahaan = $penilaian->kewirausahaan;
                            $avgSoftSkill = ($penilaian->soft_skill_1 + $penilaian->soft_skill_2 + $penilaian->soft_skill_3 + $penilaian->soft_skill_4 + $penilaian->soft_skill_5 + $penilaian->soft_skill_6) / 6;
                            $nilaiPKL = round(($avgHardSkill + $kewirausahaan + $avgSoftSkill) / 3, 2);
                            $laporanFile = optional($penilaian->siswa->magangssiswa)->laporan_akhir_file;
                            $laporanUrl = $laporanFile ? asset('storage/laporan_akhir_magang/' . $laporanFile) : '';
                        @endphp

                        <form action="{{ route('magang.wakil_perusahaan.nilaiakhir.update', $penilaian->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-user-graduate text-indigo-500"></i>Nama Siswa</label>
                                <input type="text" class="form-control-custom" value="{{ $penilaian->siswa->name }}" readonly />
                                
                                <div class="mt-2 p-2 rounded" style="background-color: #f1f5f9;">
                                    @if($laporanUrl)
                                        <a href="{{ $laporanUrl }}" target="_blank" class="btn btn-sm btn-primary rounded-pill shadow-sm">
                                            <i class="fas fa-eye me-1"></i> Lihat Laporan Akhir
                                        </a>
                                    @else
                                        <span class="text-danger small fw-semibold">
                                            <i class="fas fa-exclamation-circle me-1"></i> Siswa belum mengunggah laporan akhir.
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-calculator text-indigo-500"></i>Nilai PKL (Gabungan Mitra)</label>
                                <input type="text" class="form-control-custom" value="{{ $nilaiPKL }}" readonly />
                            </div>

                            <div class="form-group">
                                <label for="nilai_laporan" class="form-label"><i class="fas fa-file-alt text-indigo-500"></i>Nilai Laporan</label>
                                <input type="number" name="nilai_laporan" class="form-control-custom" min="0" max="100" step="0.1" value="{{ $penilaian->nilai_laporan }}" required placeholder="Contoh: 87.5" />
                                <div class="form-note">Masukkan nilai laporan antara 0 hingga 100.</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3 mt-4">
                                <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Kembali</a>
                                <button type="submit" class="submit-button">💾 Perbarui Nilai</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
