@php
    $role = auth()->user()->roles->first()->name ?? 'pelaku_umkm';
    $layout = 'layouts.pelaku';
    if ($role === 'super_admin') {
        $layout = 'layouts.superadmin';
    } elseif ($role === 'admin_kecamatan') {
        $layout = 'layouts.admin';
    } elseif ($role === 'operator_lapangan') {
        $layout = 'layouts.petugas';
    }
@endphp

@extends($layout)
@section('title', 'Riwayat Notifikasi')
@section('breadcrumb', 'Notifikasi / Riwayat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Pemberitahuan & Notifikasi
            </h1>
            <p class="text-xs text-slate-500 mt-1">Lihat seluruh riwayat pemberitahuan aktivitas akun dan status aplikasi Anda</p>
        </div>

        @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-200/60 flex items-center gap-2 text-xs font-semibold">
        <a href="{{ route('notifications.index') }}" 
           class="px-4 py-2 rounded-xl transition-all {{ empty($filter) ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua ({{ $totalCount }})
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ $filter === 'unread' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Belum Dibaca ({{ $unreadCount }})
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ $filter === 'read' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Sudah Dibaca ({{ $readCount }})
        </a>
    </div>

    <!-- Notifications List Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 divide-y divide-slate-100 overflow-hidden">
        @forelse($notifications as $n)
            @php
                $type = $n->data['type'] ?? 'info';
                $title = $n->data['title'] ?? 'Pemberitahuan';
                $message = $n->data['message'] ?? '';
                $url = $n->data['url'] ?? '#';
                $isRead = $n->read_at !== null;

                $themeMap = [
                    'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200', 'icon' => 'M5 13l4 4L19 7'],
                    'danger' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200', 'icon' => 'M6 18L18 6M6 6l12 12'],
                    'warning' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                    'info' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                $theme = $themeMap[$type] ?? $themeMap['info'];
            @endphp

            <div class="p-4 md:p-5 flex items-start justify-between gap-4 transition-colors {{ $isRead ? 'bg-white opacity-75 hover:opacity-100' : 'bg-blue-50/30' }}">
                <div class="flex items-start gap-4 min-w-0 flex-1">
                    <div class="w-10 h-10 rounded-xl {{ $theme['bg'] }} {{ $theme['border'] }} border flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 {{ $theme['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $theme['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $title }}</h3>
                            @if(!$isRead)
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold">Baru</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $message }}</p>
                        <div class="flex items-center gap-4 mt-2 text-[11px] text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $n->created_at->diffForHumans() }}
                            </span>
                            @if($url && $url !== '#')
                                <a href="{{ $url }}" class="text-blue-600 hover:underline font-medium">Buka Tautan &rarr;</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(!$isRead)
                        <form action="{{ route('notifications.read', $n->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Tandai Dibaca">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $n->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Notifikasi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="font-medium text-slate-600">Tidak ada notifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Belum ada riwayat pemberitahuan yang tersedia.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60">
            {{ $notifications->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
