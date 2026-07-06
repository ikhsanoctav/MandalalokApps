<div {{ $attributes->merge(['class' => 'bg-white/90 backdrop-blur-md rounded-3xl border border-white/60 shadow-sm overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300']) }}>
    @isset($title)
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-slate-800">{{ $title }}</h3>
            {{ $action ?? '' }}
        </div>
    @endisset
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
