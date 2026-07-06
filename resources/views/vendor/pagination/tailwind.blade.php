@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 bg-white rounded-2xl border border-slate-200/60 shadow-sm mt-6">
        
        {{-- Mobile View --}}
        <div class="flex gap-3 items-center justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200/60 cursor-not-allowed rounded-md w-1/2">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-md hover:bg-slate-50 active:scale-95 transition-all w-1/2 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-md hover:bg-slate-50 active:scale-95 transition-all w-1/2 shadow-sm">
                    Berikutnya
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200/60 cursor-not-allowed rounded-md w-1/2">
                    Berikutnya
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-xs font-medium text-slate-500">
                    Menampilkan 
                    @if ($paginator->firstItem())
                        <span class="font-extrabold text-slate-800">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-extrabold text-slate-800">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-extrabold text-slate-800">{{ $paginator->total() }}</span>
                    data UMKM
                </p>
            </div>

            <div>
                <div class="flex items-center gap-1.5">
                    
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Halaman Sebelumnya">
                            <span class="inline-flex items-center justify-center w-9 h-9 text-slate-300 bg-slate-50 border border-slate-200/60 cursor-not-allowed rounded-xl" aria-hidden="true">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 hover:-translate-y-0.5 active:scale-95 transition-all shadow-sm" aria-label="Halaman Sebelumnya">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-400 bg-white cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-extrabold bg-blue-600 text-white rounded-md shadow-md shadow-blue-500/25 border border-blue-600 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-500 bg-white border border-slate-200 rounded-md hover:bg-slate-50 hover:text-slate-800 hover:-translate-y-0.5 active:scale-95 transition-all shadow-sm" aria-label="Ke halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 hover:-translate-y-0.5 active:scale-95 transition-all shadow-sm" aria-label="Halaman Berikutnya">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Halaman Berikutnya">
                            <span class="inline-flex items-center justify-center w-9 h-9 text-slate-300 bg-slate-50 border border-slate-200/60 cursor-not-allowed rounded-xl" aria-hidden="true">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif
