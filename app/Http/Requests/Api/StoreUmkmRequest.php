<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreUmkmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Data Pemilik
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat_pemilik' => 'required|string',
            'kelurahan' => 'required|string',
            // Data UMKM
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'required|integer',
            'id_sektor' => 'required|integer',
            'status_usaha' => 'required|in:aktif,non_aktif,tutup,pindah',
            'tahun_berdiri' => 'nullable|integer|min:1900|max:' . date('Y'),
            'alamat_usaha' => 'required|string',
            'deskripsi' => 'nullable|string',
        ];
    }
}
