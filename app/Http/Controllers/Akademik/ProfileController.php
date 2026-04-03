<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AdminProfile;

/**
 * ProfileController handles show/update of profile for all roles.
 */
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile page for authenticated user.
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        // make sure relations exist and are loaded
        $user->load(['siswa.dataKelas.waliKelas', 'guru', 'adminProfile']);
        
        $kelases = \App\Models\Kelas::all();

        return view('sistem_akademik.profile', [
            'user' => $user,
            'title' => 'Profile',
            'kelases' => $kelases
        ]);
    }

    /**
     * Update basic profile fields.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // RBAC: If role is siswa, ONLY allow no_hp, alamat
        if ($user->role === 'siswa') {
            $rules = [
                'no_hp' => 'nullable|string|max:50',
                'alamat' => 'nullable|string|max:2000',
            ];
            $data = $request->validate($rules);
            
            // Note: Siswa can no longer update their 'nama', 'kelas', etc. 
            // The frontend is locked, and backend only accepts no_hp & alamat.
            
            // Update Siswa table
            if ($user->siswa) {
                $user->siswa->update([
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);
            }
            return back()->with('status', 'profile-updated')->with('success', 'Data kontak Anda berhasil diperbarui.');
        }

        // RBAC: If role is guru, ONLY allow email, no_hp, alamat
        if ($user->role === 'guru') {
            $rules = [
                'email' => 'required|email|unique:users,email,' . $user->id,
                'no_hp' => 'nullable|string|max:50',
                'alamat' => 'nullable|string|max:2000',
            ];
            $data = $request->validate($rules);
            
            $user->update(['email' => $data['email']]);
            
            if ($user->guru) {
                $user->guru->update([
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);
            }
            return back()->with('status', 'profile-updated')->with('success', 'Data kontak Anda berhasil diperbarui.');
        }

        // Standard logic for Admin
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:2000',
            'no_hp' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status_siswa' => 'nullable|in:Aktif,Lulus,Nonaktif',
            'tahun_masuk' => 'nullable|integer',
            'wali_kelas_id' => 'nullable|exists:guru,id',
        ];

        $data = $request->validate($rules);

        // Update user core fields
        $user->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
        ]);

        // update role-specific profile
        $profilePayload = [
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'no_hp' => $data['no_hp'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'jurusan_id' => $data['jurusan_id'] ?? null,
            'kelas_id' => $data['kelas_id'] ?? null,
            'status' => $data['status'] ?? null,
            'status_siswa' => $data['status_siswa'] ?? null,
            'tahun_masuk' => $data['tahun_masuk'] ?? null,
            'wali_kelas_id' => $data['wali_kelas_id'] ?? null,
        ];

        // Ensure string `kelas` matches `kelas_id` if updated
        if (!empty($profilePayload['kelas_id'])) {
            $matchedKelas = \App\Models\Kelas::find($profilePayload['kelas_id']);
            if ($matchedKelas) {
                $profilePayload['kelas'] = $matchedKelas->nama_kelas ?? null;
            }
        }

        // filter out nulls so we don't overwrite with null
        $filtered = array_filter($profilePayload, function ($v) {
            return $v !== null && $v !== '';
        });

        if ($user->siswa) {
            $user->siswa->update($filtered);
        } elseif ($user->guru) {
            $user->guru->update($filtered);
        } else {
            AdminProfile::updateOrCreate(['user_id' => $user->id], $filtered);
        }

        return back()->with('status', 'profile-updated');
    }

    /**
     * Update profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $user = $request->user();

        // choose relation model to store image (priority siswa -> guru -> adminProfile)
        $model = null;
        if ($user->relationLoaded('siswa') || $user->siswa) {
            $model = $user->siswa;
            $field = 'image';
        } elseif ($user->relationLoaded('guru') || $user->guru) {
            $model = $user->guru;
            $field = 'image';
        } elseif ($user->relationLoaded('adminProfile') || $user->adminProfile) {
            $model = $user->adminProfile;
            $field = 'image';
        } else {
            // fallback: store on user table if you prefer (not used here)
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Tidak ada profil terkait untuk menyimpan foto.'], 422)
                : redirect()->back()->with('status', 'error')->with('message', 'Tidak ada profil terkait.');
        }

        $file = $request->file('image');
        $name = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $dest = public_path('assets/profile');

        if (!File::exists($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        // move new file
        $file->move($dest, $name);

        // delete previous file if exists and not default
        if (!empty($model->{$field})) {
            $old = $dest . DIRECTORY_SEPARATOR . $model->{$field};
            if (File::exists($old)) {
                @unlink($old);
            }
        }

        // save new filename
        $model->{$field} = $name;
        $model->save();

        $url = asset('assets/profile/' . $name);

        // respond differently for AJAX or normal request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'file' => $name, 'url' => $url]);
        }

        return redirect()->back()->with('status', 'photo-updated')->with('message', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|min:5',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('status', 'password-updated');
    }
}
