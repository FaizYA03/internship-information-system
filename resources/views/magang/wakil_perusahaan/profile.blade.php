@extends('magang.layouts.main')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .profile-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 2rem 0;
        position: relative;
        overflow-x: hidden;
        width: 100%;
    }

    .profile-page::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
        width: 100%;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 0 0.75rem;
        }
    }

    .profile-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 100%);
        border-radius: 24px;
        padding: 4rem 2rem;
        color: white;
        margin-bottom: 3rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(59, 130, 246, 0.1) 100%);
        border-radius: 50%;
        transform: translate(50%, -50%);
        filter: blur(40px);
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 250px;
        height: 250px;
        background: rgba(99, 102, 241, 0.05);
        border-radius: 50%;
        transform: translate(-30%, 30%);
        filter: blur(40px);
    }

    .profile-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin: 0 0 0.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        letter-spacing: -1px;
        position: relative;
        z-index: 1;
    }

    .profile-header p {
        margin: 0.5rem 0 0;
        color: rgba(255,255,255,0.7);
        font-size: 1.1rem;
        font-weight: 300;
        max-width: 600px;
        position: relative;
        z-index: 1;
    }

    .header-icon {
        width: 5rem;
        height: 5rem;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .profile-content {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }

    @media (min-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr 380px;
        }
    }

    .profile-main {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        border: 1px solid rgba(226, 232, 240, 0.5);
        backdrop-filter: blur(10px);
    }

    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .status-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(99, 102, 241, 0.15);
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(99, 102, 241, 0.2);
    }

    .status-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1 0%, #3b82f6 100%);
    }

    .status-card h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
        margin: 1rem 0 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 2rem;
        font-weight: 700;
        border-radius: 50px;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        animation: pulse-status 2s infinite;
    }

    @keyframes pulse-status {
        0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 0 8px rgba(99, 102, 241, 0); }
    }

    .status-badge.accepted {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .status-badge.rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    }

    .attachment-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(99, 102, 241, 0.15);
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .attachment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(99, 102, 241, 0.2);
    }

    .attachment-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
    }

    .attachment-card h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
        margin: 1rem 0 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .attachment-icon {
        width: 5rem;
        height: 5rem;
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }

    .attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }

    .attachment-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(99, 102, 241, 0.4);
    }

    .info-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(99, 102, 241, 0.15);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(139, 92, 246, 0.2);
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #8b5cf6 0%, #6366f1 100%);
    }

    .info-card h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
        margin: 1rem 0 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-card h3 i {
        color: #8b5cf6;
        font-size: 1.2rem;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-item:hover {
        padding-left: 0.5rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .stat-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #6366f1;
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .profile-section {
        margin-bottom: 3rem;
    }

    .profile-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #e2e8f0;
        letter-spacing: -0.5px;
    }

    .section-title i {
        color: #6366f1;
        font-size: 1.75rem;
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        width: 100%;
        box-sizing: border-box;
    }

    .profile-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 1.5rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-sizing: border-box;
    }

    .profile-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #6366f1 0%, #3b82f6 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .profile-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(99, 102, 241, 0.15);
        border-color: #6366f1;
        background: white;
    }

    .profile-item:hover::before {
        transform: scaleX(1);
    }

    .profile-item.full {
        grid-column: span 2;
    }

    .profile-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .profile-label i {
        font-size: 1.1rem;
        color: #6366f1;
        width: 20px;
    }

    .profile-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
        line-height: 1.6;
        margin-left: 2.75rem;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    /* Responsive profile grid */
    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }

        .profile-item {
            padding: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .profile-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .profile-item {
            padding: 1rem;
        }

        .profile-item.full {
            grid-column: span 1;
        }

        .profile-label {
            font-size: 0.75rem;
            gap: 0.5rem;
        }

        .profile-value {
            font-size: 0.95rem;
            margin-left: 2rem;
        }
    }

    @media (max-width: 640px) {
        .profile-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .profile-item {
            padding: 0.875rem;
        }

        .profile-label {
            font-size: 0.7rem;
            gap: 0.5rem;
        }

        .profile-label i {
            font-size: 0.9rem;
            width: 18px;
        }

        .profile-value {
            font-size: 0.85rem;
            margin-left: 1.5rem;
        }
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2.5rem;
        border-top: 2px solid #e2e8f0;
    }

    @media (min-width: 640px) {
        .action-buttons {
            flex-direction: row;
            gap: 1.5rem;
        }
    }

    .btn {
        padding: 1.1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        flex: 1;
        box-sizing: border-box;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(99, 102, 241, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #6366f1;
        border: 2px solid #6366f1;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.1);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2);
        border-color: #4f46e5;
    }

    .btn i {
        font-size: 1.2rem;
    }

    /* Responsive for tablet and larger screens with sidebar */
    @media (max-width: 1024px) {
        .profile-header {
            padding: 3rem 1.5rem;
        }

        .profile-header h1 {
            font-size: 2.5rem;
        }

        .profile-content {
            gap: 2rem;
        }

        .profile-main {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-title i {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .profile-page {
            padding: 1.5rem 0;
        }

        .profile-header {
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            font-size: 1.75rem;
        }

        .profile-header p {
            font-size: 0.95rem;
        }

        .profile-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .profile-main {
            padding: 1.5rem;
        }

        .profile-sidebar {
            order: 2;
        }

        .section-title {
            font-size: 1.1rem;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .section-title i {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 640px) {
        .profile-header {
            padding: 2rem 1rem;
        }

        .profile-header h1 {
            font-size: 1.5rem;
        }

        .profile-main {
            padding: 1rem;
        }

        .profile-item {
            padding: 1rem;
        }

        .section-title {
            font-size: 1rem;
            gap: 0.4rem;
            margin-bottom: 0.75rem;
        }

        .section-title i {
            font-size: 1.1rem;
        }

        .profile-item.full {
            grid-column: span 1;
        }

        .profile-header h1 {
            gap: 0.75rem;
        }

        .header-icon {
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.75rem;
        }
    }
</style>

<div class="profile-page">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <h1>
                <div class="header-icon">
                    <i class="fas fa-building"></i>
                </div>
                Profil Perusahaan
            </h1>
            <p>Kelola informasi perusahaan dan data wakil perusahaan Anda</p>
        </div>

        <div class="profile-content">
            <!-- Main Content -->
            <div class="profile-main">
                <!-- Company Information -->
                <div class="profile-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi Perusahaan
                    </h2>

                    <div class="profile-grid">
                        <div class="profile-item">
                            <label class="profile-label">
                                <i class="fas fa-user-tie"></i>
                                Nama Wakil
                            </label>
                            <div class="profile-value">{{ $wakil->nama }}</div>
                        </div>

                        <div class="profile-item">
                            <label class="profile-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <div class="profile-value">{{ $wakil->email }}</div>
                        </div>

                        <div class="profile-item">
                            <label class="profile-label">
                                <i class="fas fa-building"></i>
                                Nama Perusahaan
                            </label>
                            <div class="profile-value">{{ $wakil->nama_perusahaan }}</div>
                        </div>

                        <div class="profile-item">
                            <label class="profile-label">
                                <i class="fas fa-phone"></i>
                                No. Telepon Perusahaan
                            </label>
                            <div class="profile-value">{{ $wakil->no_perusahaan }}</div>
                        </div>

                        <div class="profile-item full">
                            <label class="profile-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Alamat Perusahaan
                            </label>
                            <div class="profile-value">{{ $wakil->alamat }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('magang.wakil_perusahaan.profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Profil
                    </a>
                    <a href="{{ route('magang.wakil_perusahaan.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="profile-sidebar">
                <!-- Status Card -->
                <div class="status-card">
                    <h3>Status Akun</h3>
                    <span class="status-badge {{ strtolower($wakil->status) }}">
                        <i class="fas fa-circle"></i>
                        {{ $wakil->status }}
                    </span>
                </div>

                <!-- Attachment Card -->
                <div class="attachment-card">
                    <h3>Bukti Lampiran</h3>
                    <div class="attachment-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <a href="{{ asset('storage/' . $wakil->bukti_lampiran) }}" target="_blank" class="attachment-link">
                        <i class="fas fa-download"></i>
                        Download Lampiran
                    </a>
                </div>

                <!-- Company Info Quick Stats -->
                <div class="info-card">
                    <h3><i class="fas fa-chart-line"></i> Informasi Cepat</h3>
                    <div class="stat-item">
                        <span class="stat-label">Status</span>
                        <span class="stat-value">{{ $wakil->status }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Kontak</span>
                        <span class="stat-value">{{ $wakil->no_perusahaan }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
