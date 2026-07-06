

@props([
    'title' => 'Fitur Dalam Pengembangan',
    'description' => 'Halaman ini sedang dalam proses pengembangan. Kami akan segera merilis fitur ini.',
    'icon' => '🚧',
    'backRoute' => null,
    'backLabel' => 'Kembali',
    'features' => [],
])

<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
    <div class="text-center max-w-md w-full">

        <div class="relative inline-flex items-center justify-center w-28 h-28 mx-auto mb-6">
            
            <span class="absolute inset-0 rounded-full bg-amber-100 animate-ping opacity-30"></span>
            <span class="absolute inset-2 rounded-full bg-amber-50 border-2 border-amber-200"></span>
            <span class="relative text-5xl select-none">{{ $icon }}</span>
        </div>

        <div
            class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold px-3 py-1 rounded-full mb-4">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
            DALAM PENGEMBANGAN
        </div>

        <h2 class="text-2xl font-extrabold text-slate-800 mb-3">{{ $title }}</h2>

        <p class="text-slate-500 text-sm leading-relaxed mb-6">{{ $description }}</p>

        @if (!empty($features))
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 text-left">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Yang Akan Hadir:</p>
                <ul class="space-y-2">
                    @foreach ($features as $feature)
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6">
            <div class="flex justify-between text-xs text-slate-400 mb-1.5">
                <span>Progress Pengembangan</span>
                <span>Segera Hadir</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full relative overflow-hidden"
                    style="width: 65%">
                    <span class="absolute inset-0 bg-white/30 animate-[shimmer_1.5s_infinite]"
                        style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                                 animation: shimmer 1.5s infinite;"></span>
                </div>
            </div>
        </div>

        @if ($backRoute)
            <a href="{{ $backRoute }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ $backLabel }}
            </a>
        @endif

    </div>
</div>

<style>
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(200%);
        }
    }
</style>
