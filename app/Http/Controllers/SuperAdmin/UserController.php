<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function users(Request $request)
    {
        $allUsers = User::with('roles')->latest()->get();

        // Pisahkan user pelaku umkm dan non-pelaku umkm
        $pelakuUmkm = $allUsers->filter(function ($user) {
            return $user->hasRole('pelaku_umkm');
        });

        $nonPelakuUmkm = $allUsers->reject(function ($user) {
            return $user->hasRole('pelaku_umkm');
        });

        return view('superadmin.users.index', [
            'users' => $allUsers, // Untuk keandalan mundur (backward-compatibility)
            'pelakuUmkm' => $pelakuUmkm,
            'nonPelakuUmkm' => $nonPelakuUmkm,
        ]);
    }

    public function userCreate()
    {
        $roles = Role::all();
        $kelurahans = Kelurahan::with('rws.rts')->get();

        return view('superadmin.users.create', [
            'roles' => $roles,
            'kelurahans' => $kelurahans,
        ]);
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        
        $pemilik = null;
        if ($user->nik_hash) {
            $pemilik = \App\Models\Pemilik::where('nik_hash', $user->nik_hash)->first();
        }
        
        if ($pemilik) {
            if (empty($user->no_hp)) {
                $user->no_hp = $pemilik->no_hp;
            }
            if (empty($user->alamat)) {
                $user->alamat = $pemilik->alamat;
            }
            if (empty($user->id_kelurahan) && $pemilik->kelurahan) {
                $matchedKel = Kelurahan::where('nama_kelurahan', $pemilik->kelurahan)->first();
                if ($matchedKel) {
                    $user->id_kelurahan = $matchedKel->id;
                }
            }
            if (empty($user->rw)) {
                $user->rw = $pemilik->rw;
            }
            if (empty($user->rt)) {
                $user->rt = $pemilik->rt;
            }
            $user->tempat_lahir = $pemilik->tempat_lahir;
            $user->tanggal_lahir = $pemilik->tanggal_lahir;
            $user->jenis_kelamin = $pemilik->jenis_kelamin;
        }

        $roles = Role::all();
        $kelurahans = Kelurahan::with('rws.rts')->get();

        return view('superadmin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'kelurahans' => $kelurahans,
        ]);
    }

    public function rolesIndex()
    {
        // 1. Ensure Spatie standard Roles exist
        $roles = ['super_admin', 'admin_kecamatan', 'operator_lapangan'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Ensure Spatie standard Permissions exist
        $permissions = [
            'view_dashboard' => 'Lihat Dashboard',
            'manage_umkm' => 'Kelola Data UMKM',
            'verify_umkm' => 'Verifikasi & Tolak UMKM',
            'manage_users' => 'Kelola User & Role',
            'view_reports' => 'Lihat Laporan',
            'manage_system' => 'Pengaturan Sistem',
        ];

        foreach ($permissions as $permName => $permLabel) {
            Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
            ]);
        }

        // Assign default permissions to roles if none assigned yet
        $superAdminRole = Role::findByName('super_admin');
        if ($superAdminRole->permissions()->count() === 0) {
            $superAdminRole->syncPermissions(array_keys($permissions));
        }

        $kecamatanRole = Role::findByName('admin_kecamatan');
        if ($kecamatanRole->permissions()->count() === 0) {
            $kecamatanRole->syncPermissions(['view_dashboard', 'manage_umkm', 'verify_umkm', 'view_reports']);
        }

        $operatorRole = Role::findByName('operator_lapangan');
        if ($operatorRole->permissions()->count() === 0) {
            $operatorRole->syncPermissions(['view_dashboard', 'manage_umkm']);
        }

        // Fetch roles and permissions
        $allRoles = Role::with('permissions')->get();
        $allPermissions = Permission::all();

        return view('superadmin.users.roles', [
            'roles' => $allRoles,
            'permissions' => $allPermissions,
            'permissionLabels' => $permissions,
        ]);
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'nik' => $request->role === 'pelaku_umkm' ? 'required|string|max:20' : 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => $request->role === 'pelaku_umkm' ? 'required|string|max:20' : 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'alamat' => $request->role === 'pelaku_umkm' ? 'required|string' : 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_kelurahan' => $request->role === 'pelaku_umkm' ? 'required|integer' : 'nullable|integer',
            'rw' => $request->role === 'pelaku_umkm' ? 'required|string|max:5' : 'nullable|string|max:5',
            'rt' => 'nullable|string|max:5',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        $kodeUser = $this->generateKodeUser($request->role, $request->id_kelurahan, $request->rw);

        $kelurahanName = null;
        if ($request->id_kelurahan) {
            $kel = Kelurahan::find($request->id_kelurahan);
            if ($kel) {
                $kelurahanName = $kel->nama_kelurahan;
            }
        }

        $user = User::create([
            'kode_user' => $kodeUser,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
            'rw' => $request->rw,
            'rt' => $request->filled('rt') ? $request->rt : $user->rt,
            'kelurahan' => $kelurahanName,
            'id_kelurahan' => $request->id_kelurahan,
        ]);

        $user->assignRole($request->role);

        if ($request->role === 'pelaku_umkm' && $request->nik) {
            // Use nik_hash for lookup since NIK is encrypted at rest
            $nikHash = hash('sha256', $request->nik);
            $existingPemilik = \App\Models\Pemilik::where('nik_hash', $nikHash)->first();

            if ($existingPemilik) {
                $existingPemilik->update([
                    'nama_lengkap' => $request->name,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'no_hp' => $request->no_hp ?? '-',
                    'email' => $request->email,
                    'alamat' => $request->alamat ?? '',
                    'rw' => $request->rw ?? '001',
                    'kelurahan' => $kelurahanName ?? '',
                    'status_verifikasi_ktp' => 'terverifikasi',
                ]);
            } else {
                \App\Models\Pemilik::create([
                    'nama_lengkap' => $request->name,
                    'nik' => $request->nik,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'no_hp' => $request->no_hp ?? '-',
                    'email' => $request->email,
                    'alamat' => $request->alamat ?? '',
                    'rt' => $request->rt ?? '001',
                    'rw' => $request->rw ?? '001',
                    'kelurahan' => $kelurahanName ?? '',
                    'kecamatan' => 'Mandalajati',
                    'kota_kab' => 'Bandung',
                    'provinsi' => 'Jawa Barat',
                    'kode_pos' => '40285',
                    'status_verifikasi_ktp' => 'terverifikasi',
                ]);
            }
        }

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'User Berhasil Ditambahkan!',
            'message' => 'User "'.$user->name.'" telah terdaftar dengan role '.$request->role.'.',
        ]);

        return redirect()->route('superadmin.users');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'nik' => $request->role === 'pelaku_umkm' ? 'required|string|max:20' : 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => $request->role === 'pelaku_umkm' ? 'required|string|max:20' : 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'alamat' => $request->role === 'pelaku_umkm' ? 'required|string' : 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_kelurahan' => $request->role === 'pelaku_umkm' ? 'required|integer' : 'nullable|integer',
            'rw' => $request->role === 'pelaku_umkm' ? 'required|string|max:5' : 'nullable|string|max:5',
            'rt' => 'nullable|string|max:5',
        ]);

        $kelurahanName = null;
        if ($request->id_kelurahan) {
            $kel = Kelurahan::find($request->id_kelurahan);
            if ($kel) {
                $kelurahanName = $kel->nama_kelurahan;
            }
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'rw' => $request->rw,
            'rt' => $request->filled('rt') ? $request->rt : $user->rt,
            'kelurahan' => $kelurahanName,
            'id_kelurahan' => $request->id_kelurahan,
        ];

        if ($request->role === 'operator_lapangan') {
            if ($user->id_kelurahan != $request->id_kelurahan ||
                $user->rw != $request->rw ||
                ! $user->kode_user ||
                preg_match('/^OPL-\d+$/', $user->kode_user)) {
                $userData['kode_user'] = $this->generateKodeUser($request->role, $request->id_kelurahan, $request->rw);
            }
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store('users', 'public');
        }

        $user->update($userData);
        $user->syncRoles([$request->role]);

        if ($request->role === 'pelaku_umkm' || $user->hasRole('pelaku_umkm')) {
            // Use nik_hash for lookup since NIK is encrypted at rest
            $nikHash = hash('sha256', $request->nik ?? $user->nik);
            $pemilik = \App\Models\Pemilik::where('nik_hash', $nikHash)->first();
            
            if ($pemilik) {
                $pemilikUpdate = [
                    'nama_lengkap' => $request->name,
                    'tempat_lahir' => $request->filled('tempat_lahir') ? $request->tempat_lahir : $pemilik->tempat_lahir,
                    'tanggal_lahir' => $request->filled('tanggal_lahir') ? $request->tanggal_lahir : $pemilik->tanggal_lahir,
                    'jenis_kelamin' => $request->filled('jenis_kelamin') ? $request->jenis_kelamin : $pemilik->jenis_kelamin,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'rw' => $request->rw,
                    'rt' => $request->filled('rt') ? $request->rt : $pemilik->rt,
                ];
                
                if ($kelurahanName) {
                    $pemilikUpdate['kelurahan'] = $kelurahanName;
                }
                
                $pemilik->update($pemilikUpdate);
            }
        }

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'User Berhasil Diperbarui!',
            'message' => 'Data user "'.$user->name.'" telah diperbarui.',
        ]);

        return redirect()->route('superadmin.users');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $defaultPassword = 'password';
        $user->update([
            'password' => Hash::make($defaultPassword),
            'password_reset_status' => 'approved',
            'password_reset_requested_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password user "'.$user->name.'" berhasil di-reset menjadi "'.$defaultPassword.'".',
        ]);
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!',
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User "'.$user->name.'" berhasil dihapus.',
        ]);
    }

    public function rolesUpdate(Request $request)
    {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $permissionsInput = $request->input('permissions', []);
        $allRoles = Role::all();

        foreach ($allRoles as $role) {
            // Get checked permission names for this specific role
            $rolePermissions = isset($permissionsInput[$role->name]) ? array_keys($permissionsInput[$role->name]) : [];
            $role->syncPermissions($rolePermissions);
        }

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Hak Akses Berhasil Disimpan!',
            'message' => 'Konfigurasi hak akses role telah diperbarui.',
        ]);

        return redirect()->route('superadmin.users.roles');
    }

    private function generateKodeUser($role, $id_kelurahan = null, $rw = null)
    {
        $prefix = '';
        if ($role === 'super_admin') {
            $prefix = 'SUP';
        } elseif ($role === 'admin_kecamatan') {
            $prefix = 'ADM';
        } elseif ($role === 'operator_lapangan') {
            $prefix = 'OPL';
        } elseif ($role === 'pelaku_umkm') {
            $prefix = 'UMK';
        }

        if (! $prefix) {
            return null;
        }

        if ($prefix === 'OPL') {
            $singkatan = 'XXX';
            if ($id_kelurahan) {
                $kelurahan = Kelurahan::find($id_kelurahan);
                if ($kelurahan) {
                    $nama = strtolower($kelurahan->nama_kelurahan);
                    if (str_contains($nama, 'jatihandap')) {
                        $singkatan = 'Jti';
                    } elseif (str_contains($nama, 'karang pamulang') || str_contains($nama, 'karangpamulang')) {
                        $singkatan = 'Kpm';
                    } elseif (str_contains($nama, 'pasir impun') || str_contains($nama, 'pasirimpun')) {
                        $singkatan = 'Pim';
                    } elseif (str_contains($nama, 'sindangjaya') || str_contains($nama, 'sindang jaya')) {
                        $singkatan = 'Sjy';
                    } else {
                        $singkatan = strtoupper(substr($kelurahan->nama_kelurahan, 0, 3));
                    }
                }
            }

            $rwStr = ! empty($rw) ? str_pad($rw, 3, '0', STR_PAD_LEFT) : 'ALL';
            $basePrefix = "{$prefix}-{$singkatan}-{$rwStr}";

            $lastUser = User::where('kode_user', 'like', "{$basePrefix}-%")->orderBy('id', 'desc')->first();
            $urut = 1;
            if ($lastUser && preg_match('/-(\d+)$/', $lastUser->kode_user, $matches)) {
                $urut = intval($matches[1]) + 1;
            }

            return $basePrefix.'-'.str_pad($urut, 3, '0', STR_PAD_LEFT);
        } else {
            $lastUser = User::where('kode_user', 'like', "{$prefix}-%")->orderBy('id', 'desc')->first();
            $urut = 1;
            if ($lastUser && preg_match('/-(\d+)$/', $lastUser->kode_user, $matches)) {
                $urut = intval($matches[1]) + 1;
            }

            return $prefix.'-'.str_pad($urut, 3, '0', STR_PAD_LEFT);
        }
    }
}
