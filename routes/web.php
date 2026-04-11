<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Akademik\SiswaController;
use App\Http\Controllers\Akademik\BeritaController;
use App\Http\Controllers\Akademik\SistemAkademikController;
use App\Http\Controllers\Akademik\KelasController;
use App\Http\Controllers\Akademik\CourseController;
use App\Http\Controllers\Akademik\PeminatanController;
use App\Http\Controllers\Akademik\GuruController;
use App\Http\Controllers\Akademik\MataPelajaranController;
use App\Http\Controllers\Akademik\MapelController;
use App\Http\Controllers\Akademik\ProfileController;
use App\Http\Controllers\Akademik\JurusanController;

use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PpdbController;

use App\Http\Controllers\MagangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\LaboratoriumController;
// Unused lab controllers removed


use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\DaftarUlangController;
use App\Http\Controllers\Admin\AdminPpdbController;

use App\Http\Controllers\WakilPerusahaanController;
use App\Http\Controllers\Admin\AdminWakilPerusahaanController;
use App\Http\Controllers\WakilPerusahaanDashboardController;
use App\Http\Controllers\WakilPerusahaanOpeningsController;
// Unused lab controllers removed

use App\Http\Controllers\Siswa\LaborController;
use App\Http\Controllers\Siswa\JadwalController;
use App\Http\Controllers\Siswa\InventarisController as SiswaInventarisController;
use App\Http\Controllers\Siswa\LaporanController;
use App\Http\Controllers\Siswa\PeminjamanController as SiswaPeminjamanController;
use App\Http\Controllers\WakilPerusahaanInternsController;
use App\Http\Controllers\Siswa\MagangLaporanController;
use App\Http\Controllers\WakilPerusahaanReportsController;
use App\Http\Controllers\Mitra\PenilaianController;
use App\Http\Controllers\Admin\NilaiAkhirController;

use App\Http\Controllers\UserController;

// --- Lab System Controllers ---
use App\Http\Controllers\Lab\AdminLabController;
use App\Http\Controllers\Lab\KepalaLabController;
use App\Http\Controllers\Lab\KepalaSekolahController;
use App\Http\Controllers\Lab\WakaAkademikController;

use App\Http\Controllers\WakilController;
use App\Http\Controllers\PengajuanJudulController;
use App\Http\Controllers\PengajuanJudulSiswaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Auth routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']);

// Super Admin Routes
Route::group([
    'prefix' => 'admin/manage',
    'name' => 'admin.manage.',
    'as' => 'admin.manage.',
    'middleware' => ['auth', 'role:super_admin']
], function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');

    // Export users to CSV
    Route::get('users/export', [UserController::class, 'export'])
        ->name('users.export');

    // import CSV
    Route::get('users/import', [UserController::class, 'showImportForm'])
        ->name('users.import');
    Route::post('users/import', [UserController::class, 'import'])
        ->name('users.import.post');

    // User management routes
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/bulk-delete', [SuperAdminController::class, 'bulkDestroy'])->name('users.bulkDestroy');
    Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
});

// PPDB Routes - Make registration accessible to all users
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    // Public routes accessible by anyone (including guests)
    Route::get('/', [PpdbController::class, 'index'])->name('index');
    Route::get('/create', [PpdbController::class, 'create'])->name('create');
    Route::post('/store', [PpdbController::class, 'store'])->name('store');

    // Admin-only routes requiring admin_ppdb role
    Route::middleware(['auth', 'role:super_admin,admin_ppdb'])->group(function () {
        Route::get('/laporan', [PpdbController::class, 'laporan'])->name('laporan');
        Route::get('/{calonSiswa}/edit', [PpdbController::class, 'edit'])->name('edit');
        Route::put('/{calonSiswa}', [PpdbController::class, 'update'])->name('update');
        Route::delete('/{calonSiswa}', [PpdbController::class, 'destroy'])->name('destroy');
        Route::post('/kirim-email-kelulusan/{calonSiswa}', [PpdbController::class, 'sendEmailKelulusan'])->name('emailkelulusan');
    });
});

// Daftar Ulang Routes
Route::get('/daftar-ulang', [DaftarUlangController::class, 'create'])->name('daftar-ulang.create');
Route::post('/daftar-ulang', [DaftarUlangController::class, 'store'])->name('daftar-ulang.store');
Route::get('/daftar-ulang/success', [DaftarUlangController::class, 'success'])->name('daftar-ulang.success');

// Admin PPDB Routes
Route::prefix('admin/ppdb')->name('admin.ppdb.')->middleware(['auth', 'role:admin_ppdb'])->group(function () {
    Route::get('/daftar-ulang', [AdminPpdbController::class, 'daftarUlangIndex'])->name('daftar-ulang.index');
    Route::put('/daftar-ulang/{id}/approve', [AdminPpdbController::class, 'approveDaftarUlang'])->name('daftar-ulang.approve');
    Route::put('/daftar-ulang/{id}/reject', [AdminPpdbController::class, 'rejectDaftarUlang'])->name('daftar-ulang.reject');
});

// Sistem Akademik - Consolidate routes
Route::prefix('sistem-akademik')
    ->name('sistem_akademik.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/', [SistemAkademikController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\Akademik\SistemAkademikController::class, 'index'])->name('dashboard');

        /*
    |--------------------------------------------------------------------------
    | BERITA - AKSES UMUM (SEMUA USER LOGIN)
    |--------------------------------------------------------------------------
    */
        Route::get('berita', [BeritaController::class, 'index'])->name('berita.index');
        /*
    |--------------------------------------------------------------------------
    | BERITA - KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */
        Route::middleware(['role:super_admin,admin_sa'])->group(function () {
            Route::get('berita/create', [BeritaController::class, 'create'])->name('berita.create');
            Route::post('berita', [BeritaController::class, 'store'])->name('berita.store');
            Route::get('berita/{berita}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
            Route::put('berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
            Route::delete('berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');
        });
    
        Route::get('berita/{berita}', [BeritaController::class, 'show'])->name('berita.show');
        /*
    |--------------------------------------------------------------------------
    | ADMIN MODULE
    |--------------------------------------------------------------------------
    */
        Route::middleware(['role:super_admin,admin_sa'])->group(function () {
            Route::resource('kelas', KelasController::class);
            Route::resource('guru', GuruController::class);
            Route::resource('jurusan', JurusanController::class);
            Route::resource('siswa', SiswaController::class);

            Route::get('/get-students-by-jurusan', [CourseController::class, 'getStudentsByJurusan'])
                ->name('get-students-by-jurusan');
        });

    /*
    |--------------------------------------------------------------------------
    | PROFILE & LAINNYA
    |--------------------------------------------------------------------------
    */
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('updateProfile'); 
        Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('updatePhoto'); 
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');

        Route::resource('mapels', MapelController::class);
        Route::resource('mata_pelajaran', MataPelajaranController::class);
        Route::resource('peminatan', PeminatanController::class);
        Route::resource('ruangans', App\Http\Controllers\Akademik\RuanganController::class);

        // Course Routes with additional AJAX endpoints
        Route::get('course/get-recommendations', [CourseController::class, 'getRecommendations'])->name('sistem_akademik.get-recommendations');
        Route::get('course/get-students-by-jurusan', [CourseController::class, 'getStudentsByJurusan'])->name('sistem_akademik.get-students-by-jurusan');
        Route::post('/course/check-conflicts', [CourseController::class, 'ajaxCheckConflicts'])->name('course.check-conflicts');
        Route::resource('course', CourseController::class);
    });

// Perpustakaan Routes - Split into public and admin routes
Route::prefix('perpustakaan')->name('perpustakaan.')->group(function () {
    // Public routes for viewing books - accessible by all users
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{buku}/pdf', [BukuController::class, 'showPdf'])->name('buku.pdf');

    // Student and teacher specific routes
    Route::middleware(['auth', 'role:super_admin,admin_perpus,guru,siswa'])->group(function () {
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/history', [PeminjamanController::class, 'history'])->name('peminjaman.history');
    });

    // Admin-only routes
    Route::middleware(['auth', 'role:super_admin,admin_perpus'])->group(function () {
        Route::resource('buku', BukuController::class)->except(['index', 'show']);
        Route::resource('peminjaman', PeminjamanController::class)->except(['create', 'store']);
        Route::resource('kategori', KategoriController::class);
    });
});

// Old Laboratorium Admin routes removed as per refactor plan.
// Use /lab/admin-new instead.

// Magang Routes - Allow students to view and apply
Route::prefix('magang')->name('magang.')->group(function () {
    // Public routes for viewing
    Route::get('/dashboard', [MagangController::class, 'dashboard'])->name('dashboard');

    // Routes for students to apply
    Route::middleware(['auth', 'role:super_admin,admin_magang,siswa'])->group(function () {
        Route::get('/magang/create', [MagangController::class, 'create'])->name('magang.create');
        Route::post('/magang', [MagangController::class, 'store'])->name('magang.store');
        Route::post('/magang/apply', [MagangController::class, 'apply'])->name('apply');
    });

    // Admin-only routes
    Route::middleware(['auth', 'role:super_admin,admin_magang'])->group(function () {
        Route::get('/magang', [MagangController::class, 'index'])->name('magang.index');
        Route::get('/magang/{magang}/edit', [MagangController::class, 'edit'])->name('magang.edit');
        Route::put('/magang/{magang}', [MagangController::class, 'update'])->name('magang.update');
        Route::delete('/magang/{magang}', [MagangController::class, 'destroy'])->name('magang.destroy');
        Route::resource('perusahaan', PerusahaanController::class);
    });
});

// Routes untuk pendaftaran Mitra Magang
Route::get('/daftar-mitra-magang', [WakilPerusahaanController::class, 'showRegistrationForm'])->name('magang.wakil_perusahaan.register');
Route::post('/daftar-mitra-magang', [WakilPerusahaanController::class, 'register'])->name('magang.wakil_perusahaan.store');
Route::get('/daftar-mitra-magang/success', [WakilPerusahaanController::class, 'showSuccessPage'])->name('magang.wakil_perusahaan.success');
Route::get('/profile/edit', [WakilPerusahaanController::class, 'editProfile'])->name('magang.wakil_perusahaan.profile.edit');
Route::put('/profile', [WakilPerusahaanController::class, 'updateProfile'])->name('magang.wakil_perusahaan.profile.update');

// Routes untuk Admin mengelola pendaftaran Mitra Magang
Route::prefix('admin/magang')->name('admin.magang.')->middleware(['auth', 'role:super_admin,admin_magang'])->group(function () {
    Route::get('/wakil-perusahaan', [AdminWakilPerusahaanController::class, 'index'])->name('wakil_perusahaan.index');
    Route::put('/wakil-perusahaan/{id}/approve', [AdminWakilPerusahaanController::class, 'approve'])->name('wakil_perusahaan.approve');
    Route::put('/wakil-perusahaan/{id}/reject', [AdminWakilPerusahaanController::class, 'reject'])->name('wakil_perusahaan.reject');
});

// Routes untuk Wakil Perusahaan setelah login
Route::prefix('magang/wakil_perusahaan')->name('magang.wakil_perusahaan.')->middleware(['auth', 'role:wakil_perusahaan'])->group(function () {
    Route::get('/dashboard', [WakilPerusahaanDashboardController::class, 'index'])->name('dashboard');
    Route::get('penilaian/create', [App\Http\Controllers\Mitra\PenilaianController::class, 'create'])->name('magang.wakil_perusahaan.penilaian.create');
    Route::get('/openings', [WakilPerusahaanOpeningsController::class, 'index'])->name('openings.index');
    Route::get('/openings/create', [WakilPerusahaanOpeningsController::class, 'create'])->name('openings.create');
    Route::post('/openings', [WakilPerusahaanOpeningsController::class, 'store'])->name('openings.store');
    Route::get('/openings/{id}/edit', [WakilPerusahaanOpeningsController::class, 'edit'])->name('openings.edit');
    Route::put('/openings/{id}', [WakilPerusahaanOpeningsController::class, 'update'])->name('openings.update');
    Route::delete('/openings/{id}', [WakilPerusahaanOpeningsController::class, 'destroy'])->name('openings.destroy');
    Route::get('/openings/{id}/applicants', [WakilPerusahaanOpeningsController::class, 'showApplicants'])->name('openings.applicants');

    // Other routes for Wakil Perusahaan
    Route::get('/interns', [WakilPerusahaanInternsController::class, 'index'])->name('interns');
    Route::get('/interns/{id}', [WakilPerusahaanInternsController::class, 'show'])->name('interns.show');
    Route::put('/interns/{id}/approve', [WakilPerusahaanInternsController::class, 'approve'])->name('interns.approve');
    Route::put('/interns/{id}/reject', [WakilPerusahaanInternsController::class, 'reject'])->name('interns.reject');
    Route::get('/profile', [WakilPerusahaanController::class, 'profile'])->name('profile');
});

// Group untuk routes yang bisa diakses wakil_perusahaan & admin_magang
Route::middleware(['auth', 'role:wakil_perusahaan,admin_magang'])
    ->prefix('magang/wakil_perusahaan')
    ->name('magang.wakil_perusahaan.')
    ->group(function () {
        Route::get('/reports', [WakilPerusahaanReportsController::class, 'index'])->name('reports');
        Route::get('/reports/{id}', [WakilPerusahaanReportsController::class, 'show'])->name('reports.show');
        Route::put('/reports/{id}/review', [WakilPerusahaanReportsController::class, 'review'])->name('reports.review');
    });


// For the home page laboratory link
Route::get('/laboratorium', [LaboratoriumController::class, 'dashboard'])->name('laboratorium.link');

// Redirects for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/lab/dashboard', function () {
        $user = Auth::user();
        if ($user->role == 'guru') {
            return redirect()->route('guru.labor.index');
        }
        if ($user->role == 'siswa') {
            return redirect()->route('siswa.labor.index');
        }
        if ($user->role == 'kepala_lab') {
            return redirect()->route('lab.kepala_lab.dashboard');
        }
        if ($user->role == 'kepala_sekolah') {
            return redirect()->route('lab.kepala_sekolah.dashboard');
        }
        if ($user->role == 'waka_akademik') {
            return redirect()->route('lab.waka_akademik.dashboard');
        }
        return redirect()->route('lab.admin_new.dashboard');
    })->name('lab.dashboard');

    Route::get('/lab/jadwal', function () {
        return redirect()->route('lab.admin_new.laboratorium.index');
    })->name('lab.jadwal');

    Route::get('/lab/index', function () {
        return redirect()->route('lab.admin_new.laboratorium.index');
    })->name('lab.index');

    Route::get('/inv/index', function () {
        return redirect()->route('lab.admin_new.inventaris.index');
    })->name('inv.index');

    Route::get('/inv/laporan', function () {
        return redirect()->route('lab.admin_new.kerusakan.index');
    })->name('inv.laporan');
});

// Guru routes for laboratory management
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    // Laboratory routes
    Route::get('/labor', [LaborController::class, 'index'])->name('labor.index');
    Route::get('/labor/{id}', [LaborController::class, 'show'])->name('labor.show');

    // Laboratory schedule routes
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    // Inventory routes
    Route::get('/inventaris', [SiswaInventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/{id}', [SiswaInventarisController::class, 'show'])->name('inventaris.show');

    // Damage report routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/perbaikan-selesai', [LaporanController::class, 'perbaikanSelesai'])->name('laporan.selesai');
    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
    Route::get('/laporan/get-inventaris-by-lab', [LaporanController::class, 'getInventarisByLab'])->name('laporan.getInventarisByLab');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');

    // Peminjaman routes
    Route::get('/peminjaman', [SiswaPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [SiswaPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [SiswaPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::delete('/peminjaman/{id}/cancel', [SiswaPeminjamanController::class, 'cancel'])->name('peminjaman.cancel');
    Route::get('/peminjaman-ruangan/create', [SiswaPeminjamanController::class, 'createRuangan'])->name('peminjaman.ruangan.create');
    Route::post('/peminjaman-ruangan', [SiswaPeminjamanController::class, 'storeRuangan'])->name('peminjaman.ruangan.store');
    Route::delete('/peminjaman-ruangan/{id}/cancel', [SiswaPeminjamanController::class, 'cancelRuangan'])->name('peminjaman.ruangan.cancel');
});

// Siswa routes for laboratory management
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    // Laboratory routes
    Route::get('/labor', [LaborController::class, 'index'])->name('labor.index');
    Route::get('/labor/{id}', [LaborController::class, 'show'])->name('labor.show');

    // Laboratory schedule routes
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    // Inventory routes
    Route::get('/inventaris', [SiswaInventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/{id}', [SiswaInventarisController::class, 'show'])->name('inventaris.show');

    // Damage report routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/perbaikan-selesai', [LaporanController::class, 'perbaikanSelesai'])->name('laporan.selesai');
    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
    Route::get('/laporan/get-inventaris-by-lab', [LaporanController::class, 'getInventarisByLab'])->name('laporan.getInventarisByLab');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');

    // Peminjaman routes
    Route::get('/peminjaman', [SiswaPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [SiswaPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [SiswaPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::delete('/peminjaman/{id}/cancel', [SiswaPeminjamanController::class, 'cancel'])->name('peminjaman.cancel');
    Route::get('/peminjaman-ruangan/create', [SiswaPeminjamanController::class, 'createRuangan'])->name('peminjaman.ruangan.create');
    Route::post('/peminjaman-ruangan', [SiswaPeminjamanController::class, 'storeRuangan'])->name('peminjaman.ruangan.store');
    Route::delete('/peminjaman-ruangan/{id}/cancel', [SiswaPeminjamanController::class, 'cancelRuangan'])->name('peminjaman.ruangan.cancel');
});

// Student Report Routes
Route::prefix('magang/siswa')->name('magang.siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::resource('laporan', \App\Http\Controllers\Siswa\MagangLaporanController::class);
});

// Student Dashboard Route
Route::get('/siswa/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])
    ->name('siswa.dashboard')
    ->middleware(['auth', 'role:siswa']);

Route::resource('kategori', KategoriController::class)->middleware(['auth']);
Route::prefix('perpustakaan')->name('perpustakaan.')->group(function () {
    Route::resource('kategori', KategoriController::class);
});

Route::middleware('role:mitra')->group(function () {
    Route::resource('penilaian', PenilaianController::class);
});

// NOTE: Guru\LaporanController tidak ada — routes dinonaktifkan sementara
// Route::middleware('role:guru')->group(function () {
//     Route::get('laporan/create', [Guru\LaporanController::class, 'create']);
//     Route::post('laporan/store', [Guru\LaporanController::class, 'store']);
// });

// NOTE: Siswa\NilaiController tidak ada — routes dinonaktifkan sementara
// Route::middleware('role:siswa')->group(function () {
//     Route::get('nilai', [Siswa\NilaiController::class, 'index']);
//     Route::get('nilai/download', [Siswa\NilaiController::class, 'download']);
// });


Route::prefix('magang/wakil_perusahaan')->middleware('auth', 'role:wakil_perusahaan')->group(function () {
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('magang.wakil_perusahaan.penilaian.index');
    Route::get('/penilaian/create', [PenilaianController::class, 'create'])->name('magang.wakil_perusahaan.penilaian.create');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/penilaian/{id}/edit', [PenilaianController::class, 'edit'])->name('magang.wakil_perusahaan.penilaian.edit');
    Route::put('/penilaian/{id}', [PenilaianController::class, 'update'])->name('magang.wakil_perusahaan.penilaian.update');
    Route::get('/penilaian/{id}', [PenilaianController::class, 'show'])->name('magang.wakil_perusahaan.penilaian.show');
});

Route::get('/magang', [MagangController::class, 'index'])->name('magang.magang.index');
// NOTE: App\Http\Controllers\Magang\ProfileController tidak ada — dinonaktifkan sementara
// Route::put('/profile/foto', [\App\Http\Controllers\Magang\ProfileController::class, 'updateFoto'])->name('magang.profile.updateFoto');


Route::prefix('magang/wakil_perusahaan')->middleware(['auth', 'role:admin_magang'])->group(function () {
    Route::get('/nilaiakhir', [NilaiAkhirController::class, 'index'])->name('magang.wakil_perusahaan.nilaiakhir.index');
    Route::get('/nilaiakhir/create', [NilaiAkhirController::class, 'create'])->name('magang.wakil_perusahaan.nilaiakhir.create');
    Route::post('/nilaiakhir', [NilaiAkhirController::class, 'store'])->name('nilai_akhir.store');
});


Route::get('/admin/magang/wakil_perusahaan/nilai-akhir', [NilaiAkhirController::class, 'index'])->name('magang.admin.wakil_perusahaan.nilai-akhir.index');

Route::get('/profil-kepsek', function () {
    return view('home.sections.profilkepsek');
});

Route::middleware(['auth', 'role:super_admin,admin_magang'])->prefix('magang/perusahaan')->name('magang.perusahaan.')->group(function () {
    Route::get('/', [WakilPerusahaanController::class, 'index'])->name('index');
    Route::get('/create', [WakilPerusahaanController::class, 'create'])->name('create');
    Route::post('/', [WakilPerusahaanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [WakilPerusahaanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [WakilPerusahaanController::class, 'update'])->name('update');
    Route::delete('/{id}', [WakilPerusahaanController::class, 'destroy'])->name('destroy');
});


Route::prefix('magang/perusahaan')->name('magang.perusahaan.')->group(function () {
    Route::get('/', [WakilController::class, 'index'])->name('index');
    Route::get('/create', [WakilController::class, 'create'])->name('create');
    Route::post('/', [WakilController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [WakilController::class, 'edit'])->name('edit');
    Route::put('/{id}', [WakilController::class, 'update'])->name('update');
    Route::delete('/{id}', [WakilController::class, 'destroy'])->name('destroy');
});

Route::resource('magang/perusahaan', WakilController::class)->names('magang.perusahaan');

Route::put('admin/magang/wakil_perusahaan/{id}/approve', [WakilPerusahaanController::class, 'approve'])->name('admin.magang.wakil_perusahaan.approve');



Route::prefix('magang/admin')->middleware(['auth', 'role:admin_magang'])->group(function () {
    Route::get('/pengajuan-judul', [PengajuanJudulController::class, 'index'])->name('magang.admin.pengajuan_judul.index');
    Route::post('/pengajuan-judul/{id}/review', [PengajuanJudulController::class, 'review'])->name('admin.pengajuan-judul.review');
    Route::get('/pengajuan-judul/export-pdf', [PengajuanJudulController::class, 'exportPdf'])->name('admin.pengajuan-judul.export-pdf');
});

Route::get('magang/wakil-perusahaan/profile', [WakilPerusahaanController::class, 'profile'])
    ->name('magang.wakil_perusahaan.profile')
    ->middleware('auth');


Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('magang/pengajuan-judul', [PengajuanJudulSiswaController::class, 'index'])->name('magang.pengajuan_judul.indexsiswa');
    Route::get('magang/pengajuan-judul/create', [PengajuanJudulSiswaController::class, 'create'])->name('magang.pengajuan_judul.create');
    Route::post('magang/pengajuan-judul', [PengajuanJudulSiswaController::class, 'store'])->name('pengajuan-judul.store');
});

Route::get('/nilai-akhir/export/', [NilaiAkhirController::class, 'exportPdf'])->name('magang.wakil_perusahaan.nilaiakhir.export');

/*
|--------------------------------------------------------------------------
| LAB SYSTEM ROUTES (NEW)
|--------------------------------------------------------------------------
*/
Route::prefix('lab')->name('lab.')->middleware(['auth'])->group(function () {
    
    // Kepala Lab — Monitoring, Supervisi, Persetujuan, Rekomendasi ONLY
    Route::prefix('kepala-lab')->name('kepala_lab.')->middleware('role:kepala_lab,super_admin')->group(function () {
        // Dashboard
        Route::get('/', [KepalaLabController::class, 'index'])->name('dashboard');

        // === MONITORING (Read-only) ===
        Route::get('/monitoring/lab', [KepalaLabController::class, 'monitoringLab'])->name('monitoring.lab');
        Route::get('/monitoring/jadwal', [KepalaLabController::class, 'monitoringJadwal'])->name('monitoring.jadwal');
        Route::get('/monitoring/inventaris', [KepalaLabController::class, 'monitoringInventaris'])->name('monitoring.inventaris');
        Route::get('/monitoring/peminjaman', [KepalaLabController::class, 'monitoringPeminjaman'])->name('monitoring.peminjaman');

        // === SUPERVISI — Laporan Kerusakan (lihat + approve/reject eskalasi) ===
        Route::get('/supervisi/kerusakan', [KepalaLabController::class, 'supervisiKerusakan'])->name('supervisi.kerusakan');
        Route::get('/supervisi/perbaikan-selesai', [KepalaLabController::class, 'perbaikanSelesai'])->name('supervisi.perbaikan_selesai');
        Route::post('/supervisi/kerusakan/{id}/approve', [KepalaLabController::class, 'approveEscalation'])->name('supervisi.kerusakan.approve');
        Route::post('/supervisi/kerusakan/{id}/reject', [KepalaLabController::class, 'rejectEscalation'])->name('supervisi.kerusakan.reject');

        // === REKOMENDASI — Peminjaman Eksternal ===
        Route::get('/approval/eksternal', [KepalaLabController::class, 'approvalEksternalIndex'])->name('approval.eksternal');
        Route::post('/approval/eksternal/{id}', [KepalaLabController::class, 'recommendEksternal'])->name('approval.eksternal.recommend');
        Route::post('/approval/ruangan-eksternal/{id}', [KepalaLabController::class, 'recommendRuanganEksternal'])->name('approval.ruangan_eksternal.recommend');
    });


    // Kepala Sekolah
    Route::prefix('kepala-sekolah')->name('kepala_sekolah.')->middleware('role:kepala_sekolah,super_admin')->group(function () {
        Route::get('/', [KepalaSekolahController::class, 'index'])->name('dashboard');

        // Data Inventaris (Read-only)
        Route::get('/inventaris', [KepalaSekolahController::class, 'inventarisIndex'])->name('inventaris.index');

        Route::get('/approval/eksternal', [KepalaSekolahController::class, 'approvalEksternalIndex'])->name('approval.eksternal');
        Route::post('/approval/eksternal/{id}/approve', [KepalaSekolahController::class, 'approveEksternal'])->name('approval.eksternal.approve');
        Route::post('/approval/eksternal/{id}/reject', [KepalaSekolahController::class, 'rejectEksternal'])->name('approval.eksternal.reject');
        
        Route::post('/approval/ruangan-eksternal/{id}/approve', [KepalaSekolahController::class, 'approveRuanganEksternal'])->name('approval.ruangan_eksternal.approve');
        Route::post('/approval/ruangan-eksternal/{id}/reject', [KepalaSekolahController::class, 'rejectRuanganEksternal'])->name('approval.ruangan_eksternal.reject');

        Route::get('/approval/pengadaan', [KepalaSekolahController::class, 'approvalPengadaanIndex'])->name('approval.pengadaan.index');
        Route::post('/approval/pengadaan/{id}/approve', [KepalaSekolahController::class, 'approvePengadaan'])->name('approval.pengadaan.approve');
        Route::post('/approval/pengadaan/{id}/reject', [KepalaSekolahController::class, 'rejectPengadaan'])->name('approval.pengadaan.reject');

        // Log Aktivitas Sistem
        Route::get('/activity-log', [KepalaSekolahController::class, 'activityLog'])->name('activity_log');

        // Export Laporan (PDF, CSV, Akreditasi)
        Route::get('/export-laporan', [KepalaSekolahController::class, 'exportLaporan'])->name('export_laporan');
    });

    // Waka Akademik
    Route::prefix('waka-akademik')->name('waka_akademik.')->middleware('role:waka_akademik,super_admin')->group(function () {
        Route::get('/', [WakaAkademikController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [WakaAkademikController::class, 'monitoring'])->name('monitoring');
        Route::get('/validasi-jadwal', [WakaAkademikController::class, 'validasiJadwal'])->name('validasi_jadwal');
        Route::post('/validasi-jadwal/{id}/approve', [WakaAkademikController::class, 'approveJadwal'])->name('validasi_jadwal.approve');
        Route::post('/validasi-jadwal/{id}/reject', [WakaAkademikController::class, 'rejectJadwal'])->name('validasi_jadwal.reject');
        Route::get('/monitoring-lab', [WakaAkademikController::class, 'monitoringLab'])->name('monitoring_lab');
        Route::get('/alerts', [WakaAkademikController::class, 'alerts'])->name('alerts');
        Route::get('/export-laporan', [WakaAkademikController::class, 'exportLaporan'])->name('export_laporan');

        // Approval Eksternal
        Route::get('/approval/eksternal', [WakaAkademikController::class, 'approvalEksternalIndex'])->name('approval.eksternal');
        Route::post('/approval/eksternal/{id}/approve', [WakaAkademikController::class, 'approveEksternal'])->name('approval.eksternal.approve');
        Route::post('/approval/eksternal/{id}/reject', [WakaAkademikController::class, 'rejectEksternal'])->name('approval.eksternal.reject');
        
        Route::post('/approval/ruangan-eksternal/{id}/approve', [WakaAkademikController::class, 'approveRuanganEksternal'])->name('approval.ruangan_eksternal.approve');
        Route::post('/approval/ruangan-eksternal/{id}/reject', [WakaAkademikController::class, 'rejectRuanganEksternal'])->name('approval.ruangan_eksternal.reject');
    });

    // Admin Lab (New Routes)
    Route::prefix('admin-new')->name('admin_new.')->middleware('role:admin_lab,super_admin,kepala_lab,kepala_sekolah,waka_akademik')->group(function () {
        Route::get('/', [AdminLabController::class, 'index'])->name('dashboard');
        
        // Laboratory Management
        Route::get('/laboratorium', [AdminLabController::class, 'laboratoryIndex'])->name('laboratorium.index');
        Route::get('/laboratorium/create', [AdminLabController::class, 'laboratoryCreate'])->name('laboratorium.create');
        // AJAX: Generate kode lab otomatis (harus SEBELUM /{id} routes)
        Route::get('/laboratorium/generate-kode', [AdminLabController::class, 'ajaxGenerateKode'])->name('laboratorium.generate_kode');
        Route::post('/laboratorium', [AdminLabController::class, 'laboratoryStore'])->name('laboratorium.store');
        Route::get('/laboratorium/{id}', [AdminLabController::class, 'laboratoryShow'])->name('laboratorium.show');
        Route::get('/laboratorium/{id}/edit', [AdminLabController::class, 'laboratoryEdit'])->name('laboratorium.edit');
        Route::put('/laboratorium/{id}', [AdminLabController::class, 'laboratoryUpdate'])->name('laboratorium.update');
        Route::delete('/laboratorium/{id}', [AdminLabController::class, 'laboratoryDestroy'])->name('laboratorium.destroy');
        Route::get('/laboratorium/{id}/manual-usage', [AdminLabController::class, 'laboratoryManualUsage'])->name('laboratorium.manual_usage');
        Route::post('/laboratorium/{id}/manual-usage', [AdminLabController::class, 'laboratoryManualUsageStore'])->name('laboratorium.manual_usage.store');
        
        // Peminjaman Alat (Internal)
        Route::get('/peminjaman/internal', [AdminLabController::class, 'pinjamInternalIndex'])->name('peminjaman.internal.index');
        Route::post('/peminjaman/internal/{id}/approve', [AdminLabController::class, 'approveInternal'])->name('peminjaman.internal.approve');
        Route::post('/peminjaman/internal/{id}/reject', [AdminLabController::class, 'rejectInternal'])->name('peminjaman.internal.reject');
        Route::post('/peminjaman/internal/{id}/return', [AdminLabController::class, 'returnInternal'])->name('peminjaman.internal.return');
        Route::get('/peminjaman/alat/{id}/edit', [AdminLabController::class, 'pinjamAlatEdit'])->name('peminjaman.alat.edit');
        Route::put('/peminjaman/alat/{id}', [AdminLabController::class, 'pinjamAlatUpdate'])->name('peminjaman.alat.update');
        Route::delete('/peminjaman/alat/{id}', [AdminLabController::class, 'pinjamAlatDestroy'])->name('peminjaman.alat.destroy');
        
        // Peminjaman Ruangan
        Route::get('/peminjaman/ruangan', [AdminLabController::class, 'pinjamRuanganIndex'])->name('peminjaman.ruangan.index');
        Route::post('/peminjaman/ruangan/{id}/approve', [AdminLabController::class, 'approveRuangan'])->name('peminjaman.ruangan.approve');
        Route::post('/peminjaman/ruangan/{id}/reject', [AdminLabController::class, 'rejectRuangan'])->name('peminjaman.ruangan.reject');
        Route::get('/peminjaman/ruangan/{id}/edit', [AdminLabController::class, 'pinjamRuanganEdit'])->name('peminjaman.ruangan.edit');
        Route::put('/peminjaman/ruangan/{id}', [AdminLabController::class, 'pinjamRuanganUpdate'])->name('peminjaman.ruangan.update');
        Route::delete('/peminjaman/ruangan/{id}', [AdminLabController::class, 'pinjamRuanganDestroy'])->name('peminjaman.ruangan.destroy');
        
        // Manual Input
        Route::get('/manual-input/alat-siswa', [AdminLabController::class, 'manualInputAlatSiswa'])->name('manual_input.alat_siswa');
        Route::post('/manual-input/alat-siswa', [AdminLabController::class, 'manualInputAlatSiswaStore'])->name('manual_input.alat_siswa.store');
        Route::get('/manual-input/alat-guru', [AdminLabController::class, 'manualInputAlatGuru'])->name('manual_input.alat_guru');
        Route::post('/manual-input/alat-guru', [AdminLabController::class, 'manualInputAlatGuruStore'])->name('manual_input.alat_guru.store');
        Route::get('/manual-input/ruangan-guru', [AdminLabController::class, 'manualInputRuanganGuru'])->name('manual_input.ruangan_guru');
        Route::post('/manual-input/ruangan-guru', [AdminLabController::class, 'manualInputRuanganGuruStore'])->name('manual_input.ruangan_guru.store');
        Route::get('/manual-input/alat-eksternal', [AdminLabController::class, 'manualInputAlatEksternal'])->name('manual_input.alat_eksternal');
        Route::post('/manual-input/alat-eksternal', [AdminLabController::class, 'manualInputAlatEksternalStore'])->name('manual_input.alat_eksternal.store');
        Route::get('/manual-input/ruangan-eksternal', [AdminLabController::class, 'manualInputRuanganEksternal'])->name('manual_input.ruangan_eksternal');
        Route::post('/manual-input/ruangan-eksternal', [AdminLabController::class, 'manualInputRuanganEksternalStore'])->name('manual_input.ruangan_eksternal.store');
        
        // Damage Reports
        Route::get('/kerusakan', [AdminLabController::class, 'kerusakanIndex'])->name('kerusakan.index');
        Route::get('/perbaikan-selesai', [AdminLabController::class, 'perbaikanSelesai'])->name('kerusakan.selesai');
        Route::get('/kerusakan/create', [AdminLabController::class, 'kerusakanCreate'])->name('kerusakan.create');
        Route::post('/kerusakan', [AdminLabController::class, 'kerusakanStore'])->name('kerusakan.store');
        Route::patch('/kerusakan/{id}', [AdminLabController::class, 'kerusakanUpdate'])->name('kerusakan.update');
        Route::post('/kerusakan/{id}/eskalasi', [AdminLabController::class, 'kerusakanEskalasi'])->name('kerusakan.eskalasi');
        
        // Static Data Management
        Route::get('/master-data', [AdminLabController::class, 'masterDataIndex'])->name('master_data.index');
        Route::get('/kategori', [AdminLabController::class, 'kategoriIndex'])->name('inventaris.kategori.index');
        
        // Kategori Alat CRUD
        Route::post('/master-data/kategori', [AdminLabController::class, 'storeKategori'])->name('master_data.kategori.store');
        Route::put('/master-data/kategori/{id}', [AdminLabController::class, 'updateKategori'])->name('master_data.kategori.update');
        Route::delete('/master-data/kategori/{id}', [AdminLabController::class, 'destroyKategori'])->name('master_data.kategori.destroy');
        
        // Jenis Lab CRUD - Dedicated Management Page
        Route::get('/jenis-lab', [AdminLabController::class, 'jenisLabIndex'])->name('jenis_lab.index');
        Route::post('/master-data/jenis-lab', [AdminLabController::class, 'storeJenisLab'])->name('master_data.jenis_lab.store');
        Route::put('/master-data/jenis-lab/{id}', [AdminLabController::class, 'updateJenisLab'])->name('master_data.jenis_lab.update');
        Route::delete('/master-data/jenis-lab/{id}', [AdminLabController::class, 'destroyJenisLab'])->name('master_data.jenis_lab.destroy');
        
        // Status Kondisi CRUD
        Route::post('/master-data/kondisi', [AdminLabController::class, 'storeKondisi'])->name('master_data.kondisi.store');
        Route::put('/master-data/kondisi/{id}', [AdminLabController::class, 'updateKondisi'])->name('master_data.kondisi.update');
        Route::delete('/master-data/kondisi/{id}', [AdminLabController::class, 'destroyKondisi'])->name('master_data.kondisi.destroy');
        
        // Sumber Aset CRUD
        Route::post('/master-data/sumber', [AdminLabController::class, 'storeSumber'])->name('master_data.sumber.store');
        Route::put('/master-data/sumber/{id}', [AdminLabController::class, 'updateSumber'])->name('master_data.sumber.update');
        Route::delete('/master-data/sumber/{id}', [AdminLabController::class, 'destroySumber'])->name('master_data.sumber.destroy');

        // Inventory Management (Equipment)
        Route::get('/inventaris', [AdminLabController::class, 'inventarisIndex'])->name('inventaris.index');
        Route::get('/inventaris/create', [AdminLabController::class, 'inventarisCreate'])->name('inventaris.create');
        Route::post('/inventaris', [AdminLabController::class, 'inventarisStore'])->name('inventaris.store');
        Route::get('/inventaris/{id}', [AdminLabController::class, 'inventarisShow'])->name('inventaris.show');
        Route::get('/inventaris/{id}/edit', [AdminLabController::class, 'inventarisEdit'])->name('inventaris.edit');
        Route::put('/inventaris/{id}', [AdminLabController::class, 'inventarisUpdate'])->name('inventaris.update');
        Route::delete('/inventaris/{id}', [AdminLabController::class, 'inventarisDestroy'])->name('inventaris.destroy');
        Route::patch('/inventaris/{id}/kondisi', [AdminLabController::class, 'inventarisUpdateKondisi'])->name('inventaris.update_kondisi');
        Route::patch('/inventaris/{id}/transfer', [AdminLabController::class, 'inventarisTransfer'])->name('inventaris.transfer');

        // Materials Management
        Route::get('/bahan', [AdminLabController::class, 'bahanIndex'])->name('bahan.index');
        Route::patch('/bahan/{id}/stock', [AdminLabController::class, 'bahanUpdateStock'])->name('bahan.update_stock');

        // External Borrowing
        Route::get('/eksternal', [AdminLabController::class, 'pinjamEksternalIndex'])->name('eksternal.index');
        Route::get('/eksternal/create', [AdminLabController::class, 'pinjamEksternalCreate'])->name('eksternal.create');
        Route::post('/eksternal', [AdminLabController::class, 'pinjamEksternalStore'])->name('eksternal.store');

        // Activity Log / Audit Trail
        Route::get('/activity-log', [AdminLabController::class, 'activityLogIndex'])->name('activity_log.index');

        // Lab Schedule Management
        Route::get('/jadwal', [AdminLabController::class, 'jadwalIndex'])->name('jadwal.index');
        Route::post('/laboratorium/{labor_id}/jadwal', [AdminLabController::class, 'jadwalStore'])->name('jadwal.store');
        Route::put('/jadwal/{id}', [AdminLabController::class, 'jadwalUpdate'])->name('jadwal.update');
        Route::delete('/jadwal/{id}', [AdminLabController::class, 'jadwalDestroy'])->name('jadwal.destroy');
    });
});
