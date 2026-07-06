@props(['title', 'value', 'icon', 'color' => 'blue'])
<div
    class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-white/60 flex items-center hover:shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300">
    <div class="p-4 rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 mr-4">
        <i class="ph {{ $icon }} text-3xl"></i>
    </div>
    <div>
        <p class="text-sm font-medium text-slate-500">{{ $title }}</p>
        <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
    </div>
</div>
