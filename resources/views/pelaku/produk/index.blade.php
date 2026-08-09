@extends('layouts.pelaku')

@section('title', 'Katalog Produk UMKM')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Katalog Produk</h2>
        <p class="text-sm text-slate-500">Kelola daftar produk untuk UMKM Anda.</p>
    </div>
    <a href="{{ route('pelaku.produk.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
        <i class="mdi mdi-plus text-lg"></i>
        <span>Tambah Produk</span>
    </a>
</div>

@php $activeFiltersProduk = array_filter([request('search'), request('umkm_id')]); @endphp
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('pelaku.produk.index') }}" class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau deskripsi produk..."
                class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
        @if($umkms->count() > 1)
        <div class="relative">
            <select name="umkm_id" onchange="this.form.submit()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none transition-all cursor-pointer {{ request('umkm_id') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                <option value="">Semua Usaha</option>
                @foreach($umkms as $u)
                    <option value="{{ $u->id_umkm }}" {{ request('umkm_id') == $u->id_umkm ? 'selected' : '' }}>{{ $u->nama_usaha }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
        </div>
        @endif
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
            <select name="per_page" onchange="this.form.submit()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                @foreach([10, 20, 50] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Cari</button>
        @if(count($activeFiltersProduk) > 0)
            <a href="{{ route('pelaku.produk.index') }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-lg hover:bg-rose-100 transition-all">Reset</a>
        @endif
        <div class="ml-auto text-xs text-slate-500">
            Menampilkan <span class="font-black text-slate-700">{{ $produks->firstItem() ?? 0 }}–{{ $produks->lastItem() ?? 0 }}</span> dari <span class="font-black text-blue-600">{{ $produks->total() }}</span> produk
        </div>
    </form>
</div>

@if($produks->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" x-data="produkIndex()">
    @foreach($produks as $produk)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
        <div class="aspect-video bg-slate-100 relative overflow-hidden group">
            @if($produk->foto_produk && count($produk->foto_produk) > 0)
                <img src="{{ Storage::url($produk->foto_produk[0]) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e8f0fb/0f2e5c?text={{ urlencode($produk->nama_produk) }}';">
                @if(count($produk->foto_produk) > 1)
                <div class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-md backdrop-blur-sm">
                    +{{ count($produk->foto_produk) - 1 }} Foto
                </div>
                @endif
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                    <i class="mdi mdi-image-outline text-4xl mb-2"></i>
                    <span class="text-sm">Tidak ada foto</span>
                </div>
            @endif
        </div>
        <div class="p-5 flex-1 flex flex-col">
            <div class="text-xs text-blue-600 font-medium mb-1 truncate">{{ $produk->umkm->nama_usaha }}</div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1">{{ $produk->nama_produk }}</h3>
            <div class="text-amber-600 font-bold mb-3">
                {{ $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : 'Harga tidak dicantumkan' }}
            </div>
            <p class="text-slate-500 text-sm line-clamp-2 mb-4 flex-1">
                {{ $produk->deskripsi ?? 'Tidak ada deskripsi' }}
            </p>
            
            <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                <a href="{{ route('pelaku.produk.edit', $produk->id) }}" class="flex-1 text-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    Edit
                </a>
                <button type="button" @click="deleteData('{{ $produk->id }}', '{{ e($produk->nama_produk) }}', $event)" class="flex-1 text-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $produks->links() }}
</div>
@else
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="mdi mdi-package-variant-closed text-4xl"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Produk</h3>
    <p class="text-slate-500 mb-6 max-w-md mx-auto">Anda belum menambahkan produk apa pun ke katalog. Produk yang ditambahkan akan tampil di Katalog Publik.</p>
    <a href="{{ route('pelaku.produk.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors">
        <i class="mdi mdi-plus text-lg"></i>
        <span>Tambah Produk Pertama</span>
    </a>
</div>
@endif

@push('scripts')
<script>
    function produkIndex() {
        return {
            deleteData(id, name, event) {
                const card = event.target.closest('.bg-white.rounded-2xl');
                this.$dispatch('open-confirm-modal', {
                    title: 'Hapus Produk',
                    message: `Apakah Anda yakin ingin menghapus produk "${name}"?`,
                    confirmText: 'Ya, Hapus',
                    action: () => {
                        return fetch(`/pelaku/produk/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.showToast(data.message, 'success');
                                if (card) {
                                    card.style.transition = 'all 0.3s ease';
                                    card.style.opacity = '0';
                                    card.style.transform = 'scale(0.9)';
                                    setTimeout(() => card.remove(), 300);
                                }
                            } else {
                                window.showToast(data.message || 'Gagal menghapus data.', 'error');
                            }
                        })
                        .catch(() => {
                            window.showToast('Terjadi kesalahan jaringan.', 'error');
                        });
                    }
                });
            }
        }
    }
</script>
@endpush

@endsection
