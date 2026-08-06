@extends('layouts.superadmin')
@section('title', 'Role Access Matrix')
@section('breadcrumb', 'User Manajemen / Role Access')

@section('content')
    <div class="space-y-6">
        
        <div
            class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Role Access Matrix</h1>
                <p class="text-xs text-slate-700 mt-0.5">Konfigurasikan kewenangan fitur (Spatie Permissions) untuk
                    masing-masing tipe peran</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('superadmin.users') }}"
                    class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 font-semibold text-xs text-slate-700 shadow-sm transition-all w-full sm:w-auto">
                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Daftar User
                </a>
                <a href="{{ route('superadmin.users.create') }}"
                    class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 font-semibold text-xs text-slate-700 shadow-sm transition-all w-full sm:w-auto">
                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <form action="{{ route('superadmin.users.roles.update') }}" method="POST">
                @csrf

                <div
                    class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Matriks Hak Akses Peran</h2>
                        <p class="text-xs text-slate-400 mt-1">Beri tanda centang pada perizinan fitur untuk menugaskan hak
                            akses ke masing-masing role</p>
                    </div>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        Simpan Konfigurasi
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-200/60 text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <th class="py-4 px-6 w-1/2">Modul Fitur & Perizinan</th>
                                @foreach ($roles as $role)
                                    <th class="py-4 px-6 text-center w-1/6 uppercase tracking-wider">
                                        {{ str_replace('_', ' ', $role->name) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @foreach ($permissionLabels as $permName => $permLabel)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800">{{ $permLabel }}</span>
                                            <span class="text-xs text-slate-400 font-mono">Permission Key:
                                                {{ $permName }}</span>
                                        </div>
                                    </td>
                                    @foreach ($roles as $role)
                                        @php
                                            $hasPermission = $role->hasPermissionTo($permName);
                                        @endphp
                                        <td class="py-4 px-6 text-center">
                                            <label class="inline-flex items-center justify-center cursor-pointer group">
                                                <input type="checkbox"
                                                    name="permissions[{{ $role->name }}][{{ $permName }}]"
                                                    value="1" {{ $hasPermission ? 'checked' : '' }}
                                                    class="rounded-xl border-slate-300 text-blue-600 focus:ring-blue-500/20 w-4 h-4 transition-all">
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
