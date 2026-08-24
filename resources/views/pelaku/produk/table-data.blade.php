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
