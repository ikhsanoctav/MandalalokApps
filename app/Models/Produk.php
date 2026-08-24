<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'id_umkm',
        'nama_produk',
        'deskripsi',
        'harga',
        'foto_produk',
    ];

    protected $casts = [
        'foto_produk' => 'array',
        'harga' => 'decimal:2',
    ];

    protected $appends = [
        'foto_utama',
        'dummy_foto',
    ];

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'id_umkm', 'id_umkm');
    }

    /**
     * Dapatkan foto utama produk atau fallback dummy relevan.
     */
    public function getFotoUtamaAttribute(): string
    {
        if ($this->foto_produk && is_array($this->foto_produk) && count($this->foto_produk) > 0 && !empty($this->foto_produk[0])) {
            return \Illuminate\Support\Facades\Storage::url($this->foto_produk[0]);
        }

        return $this->getDummyFotoAttribute();
    }

    /**
     * Dapatkan gambar dummy kontekstual sesuai nama produk dan sektor UMKM.
     */
    public function getDummyFotoAttribute(): string
    {
        $name = strtolower($this->nama_produk ?? '');
        $sektor = strtolower($this->umkm?->sektor?->nama_sektor ?? $this->umkm?->kategori?->nama_kategori ?? '');
        $seed = $this->id ?? crc32($name);

        // 1. Keyword-based matching
        // Bakso, Cuanki, Mie, Soto, Kuah
        if (preg_match('/(cuanki|bakso|baso|mie|soto|ramen|soup|kuah|noodle)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1552611052-33e04de081de?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1594998893017-36147cbcae05?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Minuman, Kopi, Es, Jus, Teh, Segar
        if (preg_match('/(minum|es |teh|kopi|jus|juice|boba|segar|drink|coffee|beverage|latte|cendol|boba)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Camilan, Snack, Keripik, Kue, Roti, Bolu, Pastry, Kering
        if (preg_match('/(camilan|snack|keripik|kripik|kue|roti|bolu|pastry|dessert|kering|gorengan|cookies|biskuit)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1599785209707-a456fc1337bb?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Sembako, Beras, Minyak, Telur, Toko, Kelontong, Harian, Paket Hemat
        if (preg_match('/(sembako|beras|minyak|gula|telur|paket|kebutuhan|harian|toko|kelontong|mart|belanja|hemat)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1583258292688-d0213dc5a3a8?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Makanan, Hidangan, Nasi, Ayam, Bebek, Sambal, Seafood, Kuliner
        if (preg_match('/(makan|hidangan|nasi|ayam|bebek|ikan|seafood|sambal|sate|kuliner|geprek|bakar|goreng|warung|resto)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Pakaian, Baju, Fashion, Gamis, Hijab, Tas, Sepatu
        if (preg_match('/(baju|kaos|celana|gamis|hijab|pakaian|fashion|tas|sepatu|busana|jaket|kerudung|dress)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Kerajinan, Kriya, Handmade, Dekorasi, Souvenir
        if (preg_match('/(kerajinan|craft|handmade|kayu|rajut|anyaman|souvenir|dekor|kriya|hiasan)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1607344645866-009c320c5ab8?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Jasa, Laundry, Service, Bengkel, Cuci
        if (preg_match('/(jasa|laundry|cuci|service|servis|bengkel|repair|salon|barber)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Pertanian, Buah, Sayur, Tanaman
        if (preg_match('/(sayur|buah|tani|tanaman|organik|pertanian|kebun|bunga|bibit)/i', $name)) {
            $images = [
                'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // 2. Sector-based fallback
        if (str_contains($sektor, 'kuliner') || str_contains($sektor, 'makanan') || str_contains($sektor, 'minuman')) {
            return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&auto=format&fit=crop&q=80';
        }
        if (str_contains($sektor, 'perdagangan') || str_contains($sektor, 'retail') || str_contains($sektor, 'toko')) {
            return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&auto=format&fit=crop&q=80';
        }
        if (str_contains($sektor, 'fashion') || str_contains($sektor, 'tekstil') || str_contains($sektor, 'konveksi')) {
            return 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=600&auto=format&fit=crop&q=80';
        }
        if (str_contains($sektor, 'kerajinan') || str_contains($sektor, 'kreatif')) {
            return 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80';
        }
        if (str_contains($sektor, 'jasa')) {
            return 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&auto=format&fit=crop&q=80';
        }
        if (str_contains($sektor, 'pertanian') || str_contains($sektor, 'perkebunan') || str_contains($sektor, 'peternakan')) {
            return 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600&auto=format&fit=crop&q=80';
        }

        // 3. General attractive UMKM product image
        return 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=600&auto=format&fit=crop&q=80';
    }
}
