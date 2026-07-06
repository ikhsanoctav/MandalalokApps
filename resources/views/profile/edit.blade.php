@php
    $userRole = auth()->user()->roles->first()->name ?? 'super_admin';
    if ($userRole === 'super_admin') {
        $layout = 'layouts.superadmin';
    } elseif ($userRole === 'admin_kecamatan') {
        $layout = 'layouts.admin';
    } elseif ($userRole === 'operator_lapangan') {
        $layout = 'layouts.petugas';
    } else {
        $layout = 'layouts.pelaku';
    }
@endphp

@extends($layout)

@section('title', 'Profil Saya')
@section('breadcrumb', 'Pengaturan Profil')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div
                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-bold text-3xl shadow-lg shadow-blue-500/20">
                {{ substr(Auth::user()->name ?? 'SA', 0, 2) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ Auth::user()->name }}</h2>
                <p class="text-slate-500 mb-2">{{ Auth::user()->email }}</p>
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 border border-blue-100 text-blue-600 shadow-sm">
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->roles->first()->name ?? 'Pengguna')) }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-2xl border border-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-2xl border border-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white shadow-sm rounded-2xl border border-red-100">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
