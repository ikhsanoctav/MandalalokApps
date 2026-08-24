<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'konten',
        'gambar',
        'kategori',
        'penulis',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'gambar_url',
        'dummy_gambar',
    ];

    /**
     * Dapatkan URL gambar utama berita atau fallback dummy kontekstual.
     */
    public function getGambarUrlAttribute(): string
    {
        if (!empty($this->gambar) && $this->gambar !== 'news_default.jpg') {
            return \Illuminate\Support\Facades\Storage::url($this->gambar);
        }

        return $this->getDummyGambarAttribute();
    }

    /**
     * Dapatkan gambar dummy kontekstual sesuai judul dan kategori warta.
     */
    public function getDummyGambarAttribute(): string
    {
        $title = strtolower($this->judul ?? '');
        $kat = strtolower($this->kategori ?? $this->penulis ?? '');
        $seed = $this->id ?? crc32($title);

        // 1. Legalitas, NIB, Izin Usaha, Pajak, Sertifikat, Hibah, Pemerintah
        if (preg_match('/(nib|legal|izin|surat|hibah|dana|pajak|sertifik|dokumen|pemerintah|dinas|resmi|kantor)/i', $title)) {
            $images = [
                'https://images.unsplash.com/photo-1450133064473-71024230f91b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // 2. Kuliner, Festival, Bazar, Makanan, Jajanan, Pasar
        if (preg_match('/(kuliner|festival|bazar|makan|jajanan|pasar|umkm expo|expo|pameran)/i', $title)) {
            $images = [
                'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?w=800&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // 3. Halal, Sosialisasi, Workshop, Seminar, Rapat, Edukasi, Pertemuan
        if (preg_match('/(halal|sosialisasi|seminar|edukasi|pelaku usaha|pertemuan|rapat|bimbingan|diskusi)/i', $title)) {
            $images = [
                'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // 4. Pelatihan, Digital, Pemasaran, Kerajinan, Pengrajin, Workshop, Teknologi
        if (preg_match('/(pelatihan|digital|pemasaran|pengrajin|kerajinan|workshop|kreatif|teknologi|online|e-commerce)/i', $title)) {
            $images = [
                'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // 5. Kesehatan, Posyandu, Kebersihan, Lingkungan, Gotong Royong
        if (preg_match('/(sehat|posyandu|lingkungan|bersih|tani|hijau|gotong)/i', $title)) {
            $images = [
                'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&auto=format&fit=crop&q=80',
            ];
            return $images[$seed % count($images)];
        }

        // Fallback warta umum
        $defaultImages = [
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1577495508048-b635879837f1?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&auto=format&fit=crop&q=80',
        ];
        return $defaultImages[$seed % count($defaultImages)];
    }
}
