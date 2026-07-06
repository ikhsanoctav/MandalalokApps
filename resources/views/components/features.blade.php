<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-serif mb-4">Mengapa Menggunakan Sistem Ini?</h2>
            <p class="text-gray-500">Solusi digital untuk ekosistem UMKM yang lebih transparan</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @php
                $features = [
                    [
                        'icon' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                        'title' => 'Pendataan Akurat',
                        'desc' =>
                            'Database UMKM yang terverifikasi dan up-to-date untuk memudahkan pengambilan kebijakan.',
                    ],
                    [
                        'icon' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>',
                        'title' => 'Analisis Tren',
                        'desc' => 'Pantau pertumbuhan usaha di Mandalajati melalui visualisasi data yang interaktif.',
                    ],
                    [
                        'icon' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>',
                        'title' => 'Pemetaan GIS',
                        'desc' => 'Temukan lokasi UMKM melalui peta distribusi yang memudahkan pemasaran dan logistik.',
                    ],
                ];
            @endphp

            @foreach ($features as $feature)
                <div
                    class="p-10 rounded-3xl bg-[#FDFCFB] border border-gray-100 shadow-sm hover:shadow-xl transition-all hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 bg-[#F5F2ED] rounded-2xl flex items-center justify-center text-[#5A5A40] mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $feature['icon'] !!}
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
