<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::check()) {
            // Check if user's role is in the allowed roles list
            if (in_array(Auth::user()->role, $roles)) {
                return $next($request);
            }
            
            // Only redirect if attempting to access restricted areas
            // Don't redirect for public-facing service pages
            if ($this->isAdminRoute($request) && Auth::user()->role != 'wakil_perusahaan') {
                // Admin tried to access unauthorized area
                return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            
            // Special handling for wakil_perusahaan routes
            if ($this->isWakilPerusahaanRoute($request) && Auth::user()->role == 'wakil_perusahaan') {
                return $next($request);
            }
            
            // If user is logged in but trying to access unauthorized area, redirect to their dashboard
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        
        // User not authenticated, redirect to login
        return redirect('/login');
    }
    
    /**
     * Redirect user based on role
     */
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'super_admin':
                return redirect('/admin/manage');
            case 'admin_ppdb':
                return redirect('/ppdb/laporan');
            case 'admin_sa':
                return redirect('/sistem-akademik/dashboard');
            case 'admin_perpus':
                return redirect('/perpustakaan/buku');
            case 'admin_lab':
                return redirect('/lab/admin-new');
            case 'kepala_lab':
                return redirect('/lab/kepala-lab');
            case 'kepala_sekolah':
                return redirect('/lab/kepala-sekolah');
            case 'waka_akademik':
                return redirect('/lab/waka-akademik');
            case 'admin_magang':
                return redirect('/magang/dashboard');
            case 'wakil_perusahaan':
                return redirect('/magang/wakil_perusahaan/dashboard'); // Update this path
            case 'guru':
            case 'siswa':
                return redirect('/sistem-akademik/dashboard');
            default:
                return redirect('/');
        }
    }
    
    /**
     * Check if route is an admin route
     */
    private function isAdminRoute($request)
    {
        $adminPaths = [
            'admin/manage',
            'ppdb/laporan', 
            'ppdb/create', 
            'ppdb/edit',
            'ppdb/store',
            'ppdb/update',
            'ppdb/destroy',
            'sistem_akademik/berita/create',
            'sistem_akademik/berita/edit',
            'sistem_akademik/berita/store',
            'sistem_akademik/berita/update',
            'sistem_akademik/berita/destroy',
            // Add other admin paths here
        ];
        
        $path = $request->path();
        foreach ($adminPaths as $adminPath) {
            if (strpos($path, $adminPath) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if route is a public-facing service route
     */
    private function isPublicServiceRoute($request)
    {
        $publicPaths = [
        'ppdb',
        'perpustakaan/buku',
        'lab/dashboard',
        'magang/dashboard',
        'magang/perusahaan/dashboard',  
        'inv/index',
        'inv/laporan'
        ];
        
        $path = $request->path();
        foreach ($publicPaths as $publicPath) {
            if (strpos($path, $publicPath) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if route is a wakil_perusahaan route
     */
    private function isWakilPerusahaanRoute($request)
    {
        return strpos($request->path(), 'magang/perusahaan') === 0;
    }

    /**
     * Check if the user is allowed to access the route
     */
    protected function isAllowed($role, $request)
    {
        // Allow students to access laboratory routes while in admin area
        if ($role === 'siswa' && 
            ($request->is('admin/dashboard') || 
             $request->is('admin/labor*') || 
             $request->is('admin/jadwal*') || 
             $request->is('admin/inventaris*') ||
             $request->is('admin/kelola/laporan*'))) {
            return true;
        }
        
        // Other permission checks...
    }
}