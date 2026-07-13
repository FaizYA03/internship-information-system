<!-- filepath: resources/views/magang/partials/sidebar.blade.php -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a class="navbar-brand" href="{{ route('magang.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK" height="32">
            <span>Sistem Magang</span>
        </a>
    </div>
    
    <div class="menu-items">
        <ul>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    <span class="menu-text">Beranda</span>
                </a>
            </li>
            <!-- DASHBOARD -->
            @if(!auth()->check() || auth()->user()->role !== 'wakil_perusahaan')
            <li class="{{ request()->routeIs('magang.dashboard') ? 'active' : '' }}">
                <a href="{{ route('magang.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            @endif
            
            @auth

                {{-- ================= WAKIL PERUSAHAAN ================= --}}
                @if(Auth::user()->role === 'wakil_perusahaan')
                
                    @php
                        $wakilPerusahaan = \App\Models\WakilPerusahaan::where('email', Auth::user()->email)->first();
                        $pendingInternsCount = 0;
                        $pendingReportsCount = 0;
                        if ($wakilPerusahaan) {
                            $pendingInternsCount = \App\Models\MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
                                ->where('status', 'Menunggu')
                                ->count();
                                
                            $magangSiswaIds = \App\Models\MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
                                ->where('status', 'Disetujui Admin')
                                ->pluck('id');
                                
                            $pendingReportsCount = \App\Models\MagangLaporan::whereIn('magang_siswa_id', $magangSiswaIds)
                                ->where('status', 'submitted')
                                ->count();
                        }
                    @endphp

                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.dashboard') }}">
                            <i class="bi bi-grid-1x2"></i>
                            <span class="menu-text">Dashboard Perusahaan</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.openings*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.openings.index') }}">
                            <i class="bi bi-briefcase"></i>
                            <span class="menu-text">Program Magang</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.interns*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.interns') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-people"></i>
                                <span class="menu-text">Siswa Magang</span>
                            </div>
                            @if($pendingInternsCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pendingInternsCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.supervisors*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.supervisors.index') }}">
                            <i class="bi bi-person-check-fill"></i>
                            <span class="menu-text">Supervisor Mitra</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.reports*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.reports') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-text"></i>
                                <span class="menu-text">Laporan Harian</span>
                            </div>
                            @if($pendingReportsCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pendingReportsCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.penilaian*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.penilaian.index') }}">
                            <i class="bi bi-clipboard-check"></i>
                            <span class="menu-text">Penilaian</span>
                        </a>
                    </li>

                {{-- ================= SUPER ADMIN ================= --}}
                @elseif(Auth::user()->role === 'super_admin')
                    
                    @php
                        // Hitung jumlah pendaftar mitra baru yang masih Pending
                        $mitraBaruCount = \App\Models\WakilPerusahaan::where('status', 'Pending')->count();
                    @endphp

                    <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                        <a href="{{ route('magang.magang.index') }}">
                            <i class="bi bi-list-check"></i>
                            <span class="menu-text">Kelola Magang</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.perusahaan.*') ? 'active' : '' }}">
                        <a href="{{ route('magang.perusahaan.index') }}">
                            <i class="bi bi-building"></i>
                            <span class="menu-text">Kelola Perusahaan</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.magang.wakil_perusahaan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.magang.wakil_perusahaan.index') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-badge"></i>
                                <span class="menu-text">Kelola Mitra</span>
                            </div>
                            @if($mitraBaruCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $mitraBaruCount }}</span>
                            @endif
                        </a>
                    </li>

                {{-- ================= ADMIN MAGANG ================= --}}
                @elseif(Auth::user()->role === 'admin_magang')

                    @php
                        // Hitung jumlah pendaftar mitra baru yang masih Pending
                        $mitraBaruCount = \App\Models\WakilPerusahaan::where('status', 'Pending')->count();
                        
                        // Hitung jumlah siswa yang Disetujui Admin tapi belum punya pembimbing
                        $pembimbingBaruCount = \App\Models\MagangSiswa::where('status', 'Disetujui Admin')->whereDoesntHave('pembimbing')->count();
                    @endphp

                    <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                        <a href="{{ route('magang.magang.index') }}">
                            <i class="bi bi-list-check"></i>
                            <span class="menu-text">Kelola Magang</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.pembimbing.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pembimbing.index') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-check"></i>
                                <span class="menu-text">Kelola Pembimbing</span>
                            </div>
                            @if($pembimbingBaruCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pembimbingBaruCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.perusahaan.*') ? 'active' : '' }}">
                        <a href="{{ route('magang.perusahaan.index') }}">
                            <i class="bi bi-building"></i>
                            <span class="menu-text">Kelola Perusahaan</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.magang.wakil_perusahaan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.magang.wakil_perusahaan.index') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-badge"></i>
                                <span class="menu-text">Kelola Mitra</span>
                            </div>
                            @if($mitraBaruCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $mitraBaruCount }}</span>
                            @endif
                        </a>
                    </li>

                    
                {{-- ================= GURU ================= --}}
                @elseif(Auth::user()->role === 'guru')

                    @php
                        $guru = \App\Models\Guru::where('user_id', Auth::id())->first();
                        $pendingPenilaianCount = 0;
                        $pendingPengajuanCount = 0;
                        if ($guru) {
                            $siswaIds = \App\Models\Pembimbing::where('guru_id', $guru->id)->pluck('siswa_id');
                            $userIds = \App\Models\Siswa::whereIn('id', $siswaIds)->pluck('user_id');
                            
                            $pendingPenilaianCount = \App\Models\Penilaian::whereIn('siswa_id', $userIds)
                                ->whereNotNull('hard_skill_1') // sudah dinilai mitra
                                ->whereNull('nilai_laporan') // belum dinilai guru
                                ->count();
                                
                            $pendingPengajuanCount = \App\Models\PengajuanJudul::whereIn('user_id', $userIds)
                                ->where('status', 'pending')
                                ->count();
                        }
                    @endphp

                    <li class="{{ request()->routeIs('guru.siswa*') ? 'active' : '' }}">
                        <a href="{{ route('guru.siswa.index') }}">
                            <i class="bi bi-people"></i>
                            <span class="menu-text">Siswa Bimbingan</span>
                        </a>
                    </li>


                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.nilaiakhir*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.index') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-clipboard-check"></i>
                                <span class="menu-text">Penilaian</span>
                            </div>
                            @if($pendingPenilaianCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pendingPenilaianCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.admin.pengajuan_judul*') ? 'active' : '' }}">
                        <a href="{{ route('magang.admin.pengajuan_judul.index') }}" class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-pencil-square"></i>
                                <span class="menu-text">Kelola Judul</span>
                            </div>
                            @if($pendingPengajuanCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pendingPengajuanCount }}</span>
                            @endif
                        </a>
                    </li>


                {{-- ================= SISWA ================= --}}
                @elseif(Auth::user()->role === 'siswa')

                    @php
                        $magangSiswa = \App\Models\MagangSiswa::with('pembimbing')->where('user_id', Auth::id())
                            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
                            ->first();
                            
                        $validatedReportsCount = 0;
                        $nilaiBaruCount = 0;
                        $pengajuanJudulBaruCount = 0;
                        if ($magangSiswa) {
                            $validatedReportsCount = \App\Models\MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
                                ->whereIn('status', ['approved', 'rejected'])
                                ->where('is_read_by_siswa', false)
                                ->count();
                                
                            $nilaiBaruCount = \App\Models\Penilaian::where('siswa_id', Auth::id())
                                ->whereNotNull('nilai_akhir')
                                ->where('is_read_by_siswa', false)
                                ->count();
                                
                            $pengajuanJudulBaruCount = \App\Models\PengajuanJudul::where('user_id', Auth::id())
                                ->whereIn('status', ['accepted', 'rejected'])
                                ->where('is_read_by_siswa', false)
                                ->count();
                        }
                    @endphp

                    <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                        <a href="{{ route('magang.magang.index') }}">
                            <i class="bi bi-briefcase"></i>
                            <span class="menu-text">Program Magang</span>
                        </a>
                    </li>

                    @if($magangSiswa)
                        <li class="{{ request()->routeIs('magang.siswa.laporan*') ? 'active' : '' }}">
                            <a href="{{ route('magang.siswa.laporan.index') }}" class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-journal-text"></i>
                                    <span class="menu-text">Laporan Harian</span>
                                </div>
                                @if($validatedReportsCount > 0)
                                    <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $validatedReportsCount }}</span>
                                @endif
                            </a>
                        </li>

                        @if($magangSiswa->pembimbing)
                            <li class="{{ request()->routeIs('magang.pengajuan_judul*') ? 'active' : '' }}">
                                <a href="{{ route('magang.pengajuan_judul.indexsiswa') }}" class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-pencil"></i>
                                        <span class="menu-text">Ajukan Judul</span>
                                    </div>
                                    @if($pengajuanJudulBaruCount > 0)
                                        <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pengajuanJudulBaruCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        <li class="{{ request()->routeIs('magang.siswa.nilaimagang.*') ? 'active' : '' }}">
                            <a href="{{ route('magang.siswa.nilai.index') }}" class="d-flex justify-content-between align-items-center" data-title="Nilai">
                                <div>
                                    <i class="bi bi-graph-up"></i>
                                    <span class="menu-text">Nilai Magang</span>
                                </div>
                                @if($nilaiBaruCount > 0)
                                    <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $nilaiBaruCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                @endif

            @else
                {{-- ================= GUEST ================= --}}
                <li class="{{ request()->routeIs('magang.magang.create') ? 'active' : '' }}">
                    <a href="{{ route('magang.magang.create') }}">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Daftar Magang</span>
                    </a>
                </li>
            @endauth
            
            <!-- LOGOUT -->
            @auth
            <li>
                <a href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
            @endauth
        </ul>
    </div>
</div>