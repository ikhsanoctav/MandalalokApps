<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $kelurahans = Kelurahan::with(['rws.rts'])->get();
        return view('auth.register', compact('kelurahans'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nik' => [
                'required', 
                'string', 
                'size:16',
                function ($attribute, $value, $fail) {
                    $hash = hash('sha256', $value);
                    if (\App\Models\User::where('nik_hash', $hash)->exists()) {
                        $fail('NIK ini sudah terdaftar. Silakan gunakan NIK lain atau masuk dengan akun Anda.');
                    }
                }
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_hp' => ['required', 'string', 'max:15'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'id_kelurahan' => ['nullable', 'exists:kelurahans,id'],
            'rw' => ['nullable', 'string', 'max:10'],
            'rt' => ['nullable', 'string', 'max:10'],
        ], [
            'no_hp.max' => 'Nomor HP terlalu panjang (maksimal 15 karakter).',
        ]);

        $kelurahan = $request->filled('id_kelurahan')
            ? Kelurahan::find($request->id_kelurahan)
            : null;
        $nikHash = hash('sha256', $request->nik);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'nik_hash' => $nikHash,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'id_kelurahan' => $kelurahan?->id,
            'kelurahan' => $kelurahan?->nama_kelurahan,
            'rw' => $request->rw,
            'rt' => $request->rt,
        ]);

        Pemilik::updateOrCreate(
            ['nik_hash' => $nikHash],
            [
                'nik' => $request->nik,
                'nama_lengkap' => $request->name,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'id_kelurahan' => $kelurahan?->id,
                'kelurahan' => $kelurahan?->nama_kelurahan,
                'rw' => $request->rw,
                'rt' => $request->rt,
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'status_verifikasi_ktp' => 'pending',
            ]
        );

        $user->assignRole('pelaku_umkm');
        event(new Registered($user));

        return redirect()->route('register.success')->with('success', 'Pendaftaran berhasil!');
    }

    /**
     * Check if NIK already exists for real-time validation
     */
    public function checkNik(Request $request)
    {
        $nik = $request->query('nik');
        if (!$nik || strlen($nik) !== 16) {
            return response()->json(['valid' => false, 'message' => 'Format tidak valid.']);
        }

        $hash = hash('sha256', $nik);
        $exists = \App\Models\User::where('nik_hash', $hash)->exists();

        if ($exists) {
            return response()->json(['valid' => false, 'message' => 'NIK sudah terdaftar!']);
        }

        return response()->json(['valid' => true, 'message' => 'NIK tersedia.']);
    }
}
