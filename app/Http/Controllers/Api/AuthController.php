<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * API Authentication Controller
 * Menangani login/logout untuk MandalalokaApk
 */
class AuthController extends Controller
{
    /**
     * POST /api/login
     * Login user dan return Sanctum token
     */
    public function login(LoginRequest $request)
    {

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        $roles = $user->getRoleNames();

        // Cek role: hanya operator_lapangan dan admin_kecamatan yang bisa login
        $allowedRoles = ['operator_lapangan', 'admin_kecamatan', 'super_admin'];
        if ($roles->intersect($allowedRoles)->isEmpty()) {
            Auth::logout();

            return response()->json([
                'message' => 'Akun Anda tidak memiliki akses ke aplikasi mobile.',
            ], 403);
        }

        // Hapus token lama, buat token baru
        $user->tokens()->delete();
        $token = $user->createToken('MandalalokaApk')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'jabatan' => $user->jabatan,
                'foto' => $user->foto ? asset('storage/'.$user->foto) : null,
                'roles' => $roles,
            ],
        ]);
    }

    /**
     * POST /api/logout
     * Revoke current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * GET /api/me
     * Return data user yang sedang login
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'jabatan' => $user->jabatan,
                'alamat' => $user->alamat,
                'foto' => $user->foto ? asset('storage/'.$user->foto) : null,
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }
}
