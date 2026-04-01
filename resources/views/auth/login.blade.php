@extends('dashboard.layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Hide navigation in login page */
    nav {
        display: none;
    }

    body {
        font-family: var(--font-family);
        background-color: var(--bg-gray);
        color: var(--text-dark);
        line-height: 1.6;
    }

    /* Modern Header */
    header {
        background-color: var(--primary);
        color: var(--text-light);
        text-align: center;
        padding: var(--spacing-lg);
        box-shadow: var(--shadow);
    }

    header .logo img {
        width: 80px;
        transition: var(--transition);
    }

    header .logo img:hover {
        transform: scale(1.05);
    }

    header h1 {
        font-size: var(--font-size-xl);
        margin-top: var(--spacing-sm);
        font-weight: 600;
    }

    /* Modern Login Form */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 250px);
        padding: var(--spacing-xl) var(--spacing);
    }

    .login-form {
        background-color: var(--bg-light);
        padding: var(--spacing-xl);
        width: 100%;
        max-width: 450px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border-top: 4px solid var(--secondary);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .login-form:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .login-form h2 {
        font-size: var(--font-size-2xl);
        text-align: center;
        color: var(--primary);
        margin-bottom: var(--spacing-lg);
        font-weight: 600;
        position: relative;
        padding-bottom: var(--spacing);
    }

    .login-form h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(to right, var(--secondary-dark), var(--secondary));
        border-radius: 2px;
    }

    .form-group {
        margin-bottom: var(--spacing-lg);
        position: relative;
    }

    .form-group label {
        display: block;
        font-size: var(--font-size-sm);
        margin-bottom: var(--spacing-xs);
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Fixed icon alignment with text */
    .form-group .input-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: var(--spacing);
        color: var(--text-muted);
        pointer-events: none;
        line-height: 1;
        margin-top: calc(var(--font-size) * 0.6);
    }

    .form-control {
        width: 100%;
        padding: var(--spacing) var(--spacing) var(--spacing) calc(var(--spacing) * 2.5);
        border: 1px solid rgba(26, 42, 58, 0.15);
        border-radius: var(--radius);
        font-size: var(--font-size);
        transition: var(--transition);
        color: var(--text-dark);
        background-color: rgba(26, 42, 58, 0.02);
        font-family: var(--font-family);
        line-height: 1.5;
        height: auto;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.15);
        background-color: var(--bg-light);
    }

    .form-control:focus+.input-icon,
    .form-control:not(:placeholder-shown)+.input-icon {
        color: var(--secondary);
    }

    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .btn-login {
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        color: var(--bg-light);
        padding: var(--spacing) var(--spacing-lg);
        font-size: var(--font-size);
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        transition: var(--transition);
        width: 100%;
        font-weight: 600;
        font-family: var(--font-family);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-sm);
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow);
        background: linear-gradient(135deg, var(--secondary-dark), var(--primary));
    }

    .login-footer {
        text-align: center;
        margin-top: var(--spacing-lg);
        color: var(--text-muted);
        font-size: var(--font-size-sm);
    }

    .login-footer a {
        color: var(--secondary);
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .login-footer a:hover {
        color: var(--secondary-dark);
        text-decoration: underline;
    }

    /* Footer */
    footer {
        background-color: var(--primary);
        color: var(--text-light);
        text-align: center;
        padding: var(--spacing-md) 0;
        margin-top: auto;
    }

    footer p {
        margin: 5px 0;
        font-size: var(--font-size-sm);
        opacity: 0.8;
    }

    /* Decorative background elements */
    .login-form::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        top: -75px;
        right: -75px;
        background-color: rgba(78, 205, 196, 0.03);
        border-radius: 50%;
        z-index: -1;
    }

    .login-form::after {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        bottom: -50px;
        left: -50px;
        background-color: rgba(78, 205, 196, 0.03);
        border-radius: 50%;
        z-index: -1;
    }

    /* Back button styling */
    .back-to-home {
        position: absolute;
        top: var(--spacing-lg);
        left: var(--spacing-lg);
        color: var(--text-light);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: var(--font-size-sm);
        font-weight: 500;
        transition: var(--transition);
        background-color: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        backdrop-filter: blur(4px);
    }

    .back-to-home:hover {
        background-color: rgba(255, 255, 255, 0.25);
        transform: translateX(-3px);
        color: var(--text-light);
    }

    /* Add margin-top to header to prevent overlap with back button on mobile */
    @media (max-width: 768px) {
        .login-form {
            max-width: 90%;
            padding: var(--spacing-lg);
        }

        .login-form h2 {
            font-size: var(--font-size-xl);
        }

        .back-to-home {
            top: var(--spacing);
            left: var(--spacing);
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>
@endsection

@section('content')

<div class="login-container">
    <div class="login-form">
        <h2>Login Pengguna</h2>
        
        <form action="{{ route('authenticate') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nis_nip">Email / NIS / NIP</label>
                <input type="text" id="nis_nip" name="nis_nip" class="form-control" required placeholder="Masukkan Email / NIS / NIP">
                <i class="bi bi-person-badge input-icon"></i>
            </div>
        
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan Password">
                <i class="bi bi-lock input-icon"></i>
            </div>
        

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>
        

        <div class="login-footer">
            <p>Sistem Informasi SMK 5 Padang</p>
            <p><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> Kembali ke Beranda</a></p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('loginError'))
        swal({
            title: 'Login Gagal',
            text: '{{ session('loginError') }}',
            icon: 'error',
            button: {
                text: "OK",
                className: "btn-primary"
            },
        });
        @endif
    });
</script>
@endsection