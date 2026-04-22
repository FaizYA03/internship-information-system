@extends('magang.layouts.main')

@section('css')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --danger-color: #f72585;
        --warning-color: #f8961e;
        --info-color: #4895ef;
        --light-bg: #f8f9fa;
        --card-bg: #ffffff;
        --text-color: #2b2d42;
        --text-muted: #8d99ae;
        --border-radius: 12px;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    body {
        background-color: #f0f2f5;
        font-family: 'Inter', sans-serif;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .content-section {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .skill-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .skill-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        border: 1px solid rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .skill-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .skill-title {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .skill-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .skill-label {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .progress-bar-custom {
        height: 6px;
        background-color: rgba(0,0,0,0.08);
        border-radius: 3px;
        margin-top: 1rem;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 3px;
        transition: width 0.4s ease;
    }

    .breakdown-table {
        width: 100%;
        border-collapse: collapse;
    }

    .breakdown-table thead {
        background-color: var(--light-bg);
        border: 1px solid rgba(0,0,0,0.08);
    }

    .breakdown-table th {
        padding: 1rem;
        text-align: left;
        color: var(--text-color);
        font-weight: 700;
        font-size: 0.9rem;
    }

    .breakdown-table td {
        padding: 0.85rem 1rem;
        border: 1px solid rgba(0,0,0,0.05);
        color: var(--text-color);
    }

    .breakdown-table tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.02);
    }

    .skill-score {
        font-weight: 700;
        color: var(--primary-color);
    }

    .empty-value {
        color: var(--text-muted);
        font-style: italic;
    }

    /* Average Score Highlight */
    .average-highlight {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        text-align: center;
        margin-bottom: 2rem;
    }

    .average-label {
        font-size: 0.85rem;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .average-value {
        font-size: 2.5rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .skill-grid {
            grid-template-columns: 1fr;
        }

        .skill-value {
            font-size: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .content-section {
            padding: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Breakdown Penilaian PKL</h1>
        <p style="color: var(--text-muted); margin-bottom: 0;">Detail komponen penilaian dari mitra/perusahaan</p>
    </div>

    @if (isset($nilaiData['penilaian']) && $nilaiData['penilaian'] && $nilaiData['nilaiPKL'])
        <!-- Average Score -->
        <div class="average-highlight">
            <div class="average-label">Rata-Rata Nilai PKL</div>
            <div class="average-value">{{ $nilaiData['nilaiPKL'] }}/100</div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <h5 class="mb-4" style="font-weight: 700; color: var(--text-color);">Detail Komponen Penilaian</h5>

            <!-- Skills Grid -->
            <div class="skill-grid">
                <!-- Hard Skills -->
                <div class="skill-card">
                    <div class="skill-title">💻 Hard Skill 1</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->hard_skill_1 ?? '-' }}</div>
                    <div class="skill-label">Kemampuan Teknis</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->hard_skill_1 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">💻 Hard Skill 2</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->hard_skill_2 ?? '-' }}</div>
                    <div class="skill-label">Kemampuan Teknis</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->hard_skill_2 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">💻 Hard Skill 3</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->hard_skill_3 ?? '-' }}</div>
                    <div class="skill-label">Kemampuan Teknis</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->hard_skill_3 ?? 0) }}%"></div>
                    </div>
                </div>

                <!-- Kewirausahaan -->
                <div class="skill-card">
                    <div class="skill-title">🚀 Kewirausahaan</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->kewirausahaan ?? '-' }}</div>
                    <div class="skill-label">Spirit Wirausaha</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->kewirausahaan ?? 0) }}%"></div>
                    </div>
                </div>

                <!-- Soft Skills -->
                <div class="skill-card">
                    <div class="skill-title">👥 Soft Skill 1</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->soft_skill_1 ?? '-' }}</div>
                    <div class="skill-label">Komunikasi</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->soft_skill_1 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">👥 Soft Skill 2</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->soft_skill_2 ?? '-' }}</div>
                    <div class="skill-label">Kolaborasi Tim</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->soft_skill_2 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">👥 Soft Skill 3</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->soft_skill_3 ?? '-' }}</div>
                    <div class="skill-label">Problem Solving</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->soft_skill_3 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">👥 Soft Skill 4</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->soft_skill_4 ?? '-' }}</div>
                    <div class="skill-label">Manajemen Waktu</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->soft_skill_4 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">👥 Soft Skill 5</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->soft_skill_5 ?? '-' }}</div>
                    <div class="skill-label">Inisiatif</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->soft_skill_5 ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-title">👥 Soft Skill 6</div>
                    <div class="skill-value">{{ $nilaiData['penilaian']->soft_skill_6 ?? '-' }}</div>
                    <div class="skill-label">Etika Kerja</div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" style="width: {{ ($nilaiData['penilaian']->soft_skill_6 ?? 0) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Summary Table -->
            <div style="margin-top: 2.5rem;">
                <h6 style="font-weight: 700; margin-bottom: 1rem; color: var(--text-color);">📊 Ringkasan Penilaian</h6>
                <table class="breakdown-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th style="text-align: center;">Nilai</th>
                            <th style="text-align: center;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Hard Skill Rata-rata</strong></td>
                            <td style="text-align: center;">
                                <span class="skill-score">
                                    {{ round((($nilaiData['penilaian']->hard_skill_1 ?? 0) + ($nilaiData['penilaian']->hard_skill_2 ?? 0) + ($nilaiData['penilaian']->hard_skill_3 ?? 0)) / 3, 2) }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                {{ round(((($nilaiData['penilaian']->hard_skill_1 ?? 0) + ($nilaiData['penilaian']->hard_skill_2 ?? 0) + ($nilaiData['penilaian']->hard_skill_3 ?? 0)) / 3) / 100 * 100, 1) }}%
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Soft Skill Rata-rata</strong></td>
                            <td style="text-align: center;">
                                <span class="skill-score">
                                    {{ round((($nilaiData['penilaian']->soft_skill_1 ?? 0) + ($nilaiData['penilaian']->soft_skill_2 ?? 0) + ($nilaiData['penilaian']->soft_skill_3 ?? 0) + ($nilaiData['penilaian']->soft_skill_4 ?? 0) + ($nilaiData['penilaian']->soft_skill_5 ?? 0) + ($nilaiData['penilaian']->soft_skill_6 ?? 0)) / 6, 2) }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                {{ round(((($nilaiData['penilaian']->soft_skill_1 ?? 0) + ($nilaiData['penilaian']->soft_skill_2 ?? 0) + ($nilaiData['penilaian']->soft_skill_3 ?? 0) + ($nilaiData['penilaian']->soft_skill_4 ?? 0) + ($nilaiData['penilaian']->soft_skill_5 ?? 0) + ($nilaiData['penilaian']->soft_skill_6 ?? 0)) / 6) / 100 * 100, 1) }}%
                            </td>
                        </tr>
                        <tr style="background-color: var(--light-bg); font-weight: 700;">
                            <td><strong>🎯 Nilai PKL (Rata-rata Keseluruhan)</strong></td>
                            <td style="text-align: center;">
                                <span class="skill-score">{{ $nilaiData['nilaiPKL'] }}</span>
                            </td>
                            <td style="text-align: center;">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes -->
            <div style="margin-top: 2rem; padding: 1rem; background-color: rgba(67, 97, 238, 0.05); border-left: 4px solid var(--primary-color); border-radius: 0.5rem;">
                <p style="margin: 0; font-size: 0.9rem; color: var(--text-color);">
                    <strong>💡 Catatan:</strong> Nilai PKL ini adalah penilaian kompetensi yang diberikan oleh mitra/perusahaan selama Anda menjalankan program magang. Setiap komponen menggambarkan kemampuan spesifik yang dinilai berdasarkan performa Anda di lapangan.
                </p>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="content-section">
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">📭</div>
                <p><strong>Belum Ada Penilaian PKL</strong></p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">Data penilaian detail akan muncul setelah mitra/perusahaan memberikan penilaian komponen skills Anda.</p>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('magang.siswa.nilai.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Kembali ke Nilai
        </a>
    </div>
</div>
@endsection
