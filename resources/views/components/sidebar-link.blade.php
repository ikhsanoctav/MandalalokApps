@props(['route', 'icon', 'label'])
@php $isActive = request()->routeIs($route . '*'); @endphp
<a href="{{ route($route) }}"
    class="flex items-center px-4 py-3 mb-1 text-sm rounded-md transition-all duration-200 {{ $isActive ? 'bg-white text-primary font-bold shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
    <i class="ph {{ $icon }} text-xl mr-3 {{ $isActive ? 'text-primary' : 'text-blue-300' }}"></i>
    {{ $label }}
</a>
