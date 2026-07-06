<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FlyerController extends Controller
{
    public function index()
    {
        $flyerActive = Setting::get('flyer_popup_active', false);
        $flyerImage = Setting::get('flyer_popup_image', '');
        $flyerLink = Setting::get('flyer_popup_link', '');
        $flyerTarget = Setting::get('flyer_popup_target', 'both');

        return view('superadmin.flyer.index', compact('flyerActive', 'flyerImage', 'flyerLink', 'flyerTarget'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'flyer_popup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'flyer_popup_link' => 'nullable|url|max:255',
            'flyer_popup_target' => 'required|in:both,welcome,dashboard',
        ], [
            'flyer_popup_image.image' => 'Berkas harus berupa gambar.',
            'flyer_popup_image.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg, gif, svg, webp.',
            'flyer_popup_image.max' => 'Ukuran gambar maksimal adalah 2MB.',
            'flyer_popup_link.url' => 'Format link tujuan harus berupa URL yang valid (misal: https://example.com).',
            'flyer_popup_target.in' => 'Target tampilan yang dipilih tidak valid.',
        ]);

        $activeVal = ($request->input('flyer_popup_active') === 'true') ? 'true' : 'false';
        Setting::set('flyer_popup_active', $activeVal);

        Setting::set('flyer_popup_link', $request->input('flyer_popup_link', ''));

        Setting::set('flyer_popup_target', $request->input('flyer_popup_target', 'both'));

        if ($request->hasFile('flyer_popup_image')) {
            $oldImage = Setting::get('flyer_popup_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('flyer_popup_image')->store('flyers', 'public');
            Setting::set('flyer_popup_image', $path);
        }

        AktivitasLogger::log(
            'Memperbarui konfigurasi flyer program bantuan.',
            'settings',
            null,
            $request
        );

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Pengaturan Flyer Disimpan!',
            'message' => 'Konfigurasi flyer program bantuan berhasil diperbarui.',
        ]);

        return redirect()->route('superadmin.flyer.index');
    }
}
