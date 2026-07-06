<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'nik' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email', 'nik'))
                ->withErrors(['email' => 'Email tidak ditemukan di sistem kami.']);
        }

        // Validasi NIK
        $nikHash = hash('sha256', $request->nik);
        if ($user->nik_hash !== $nikHash) {
            return back()->withInput($request->only('email', 'nik'))
                ->withErrors(['nik' => 'Data NIK tidak sesuai dengan yang terdaftar pada Email ini.']);
        }

        // Cek status reset
        if ($user->password_reset_status === 'pending') {
            return back()->with('status', 'Permintaan Anda masih diproses oleh Super Admin. Silakan cek kembali nanti.');
        } elseif ($user->password_reset_status === 'approved') {
            return back()->with('status', 'Permintaan disetujui. Password Anda telah di-reset menjadi "password". Silakan login dan ubah password Anda.');
        }

        // Buat permintaan baru
        $user->update([
            'password_reset_status' => 'pending',
            'password_reset_requested_at' => now(),
        ]);

        return back()->with('status', 'Permintaan reset password berhasil dikirim ke Super Admin. Silakan cek status Anda di halaman ini secara berkala.');
    }
}
