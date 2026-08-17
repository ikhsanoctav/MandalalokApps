@if ($status == 'terverifikasi' || $status == 'disetujui')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 whitespace-nowrap">
        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Terverifikasi
    </span>
@elseif($status == 'ditolak')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 whitespace-nowrap">
        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
    </span>
@elseif($status == 'menunggu_verifikasi')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 whitespace-nowrap">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu
    </span>
@else
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 whitespace-nowrap">
        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Draft
    </span>
@endif
