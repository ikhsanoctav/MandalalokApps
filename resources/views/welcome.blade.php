<!DOCTYPE html>
<html lang="id">
<head>
    @include('landing.partials.head')
    @include('landing.partials.styles')
</head>
<body>

    {{-- Navigasi Utama --}}
    @include('landing.partials.navbar')

    {{-- Hero Section --}}
    @include('landing.partials.hero')

    {{-- Statistik & Grafik Distribusi UMKM --}}
    @include('landing.partials.stats')

    {{-- Showcase Produk Unggulan --}}
    @include('landing.partials.products-showcase')

    {{-- Peta Interaktif Sebaran UMKM (OpenStreetMap) --}}
    @include('landing.partials.map')

    {{-- Warta, Berita & FAQ Interaktif --}}
    @include('landing.partials.warta')

    {{-- Footer --}}
    @include('landing.partials.footer')

    {{-- Script Utama Halaman Depan & Peta --}}
    @include('landing.partials.scripts-main')

    {{-- Widget AI Chatbot Floating --}}
    @include('landing.partials.chatbot-widget')
    @include('landing.partials.chatbot-script')

    {{-- Modal Flyer Program Bantuan --}}
    @include('partials.program-flyer-popup')

</body>
</html>
