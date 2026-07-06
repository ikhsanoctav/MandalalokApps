<?php

namespace Database\Seeders;

use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UmkmSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data referensi petugas
        $petugas = User::where('email', 'operator@mandalajati.com')->first();
        if (! $petugas) {
            $petugas = User::first();
        }

        // Ambil kategori
        $kategoriMikro = KategoriUMKM::where('nama_kategori', 'Mikro')->first();
        $kategoriKecil = KategoriUMKM::where('nama_kategori', 'Kecil')->first();
        $kategoriMenengah = KategoriUMKM::where('nama_kategori', 'Menengah')->first();

        // Ambil sektor
        $sektors = SektorUmkm::all()->keyBy('nama_sektor');

        // Nama-nama pemilik dummy yang realistis untuk Jatihandap, Karang Pamulang, dan Pasir Impun
        $ownerNames = [
            'Budi Santoso', 'Hendra Wijaya', 'Ahmad Fauzi', 'Siti Aminah', 'Dewi Lestari', 
            'Rian Hidayat', 'Taufik Hidayat', 'Yusuf Maulana', 'Agus Susanto', 'Sri Wahyuni', 
            'Wawan Setiawan', 'Dedi Supriadi', 'Cecep Hermawan', 'Eman Sulaeman', 'Lilis Marlina', 
            'Nenden Nurhayati', 'Euis Rohayati', 'Maman Abdurrahman', 'Heri Kiswanto', 'Diding Tajudin', 
            'Asep Sunandar', 'Ujang Bustomi', 'Dadang Supardan', 'Iman Budiman', 'Rudi Hermawan', 
            'Nana Suryana', 'Ade Kurnia', 'Endang Wijaya', 'Aria Pratama', 'Putra Ramadhan', 
            'Siska Rahayu', 'Wati Herawati', 'Dian Sastrowardoyo', 'Indra Lesmana', 'Yani Mulyani', 
            'Jajang Nurjaman', 'Toni Sucipto', 'Tantan', 'Deden Supriatna', 'Ade Sukmana', 
            'Rina Herlina', 'Titin Sumarni', 'Roni Wijaya', 'Kiki Zakaria', 'Ginanjar Kartasasmita', 
            'Asep Hendra', 'Taufiq Rahman', 'Irfan Hakim', 'Reza Rahadian', 'Raffi Ahmad', 
            'Sule Sutisna', 'Andre Taulany', 'Desta Mahendra', 'Vincent Rompies', 'Indra Jegel', 
            'Bintang Emon', 'Raditya Dika', 'Pandji Pragiwaksono', 'Ernest Prakasa', 'Abdur Arsyad', 
            'Arie Kriting', 'Soleh Solihun', 'Ge Pamungkas', 'Gilang Bhaskara', 'Joko Widodo',
            'Prabowo Subianto', 'Megawati Soekarnoputri', 'Susilo Bambang Yudhoyono', 'Abdurrahman Wahid'
        ];

        // 131 Data Riil UMKM dari gambar lampiran terbaru
        $rawUmkmData = [
            // ==========================================
            // JATIHAND (Kelurahan Jati Handap) - 8 data
            // ==========================================
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'INDOMARET JATIHANDAP',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Jatihandap No. 10, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Gerai ritel modern penyedia kebutuhan pokok rumah tangga, makanan ringan, dan pembayaran digital.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'ALFAMART JATIHANDAP 1',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Jatihandap No. 10, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Gerai ritel kebutuhan sehari-hari yang menawarkan promosi mingguan menarik dan layanan transaksi digital.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'ALFAMART JATIHANDAP 2',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. A.H. Nasution No. 12, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 12,
                'deskripsi' => 'Minimarket penyedia kebutuhan pokok masyarakat sekitar kelurahan Jatihandap.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'TB BINTEK 1',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Jatihandap No. 7, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 7,
                'deskripsi' => 'Toko bangunan penyedia pasir, semen, bata merah, besi beton, dan cat dinding.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'TB BINTEK 2',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Jatihandap No. 8, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 8,
                'deskripsi' => 'Kios cabang toko bangunan Bintek yang menyediakan paralon, keramik, dan alat pertukangan.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'TB PANUTAN',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Jatihandap No. 10, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Toko bahan bangunan terlengkap yang melayani pemesanan kayu konstruksi dan material atap.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'CV. SINAR LOGAM',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. A. Yani No. 15, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 15,
                'deskripsi' => 'Distributor besi siku, plat baja, kawat duri, dan berbagai perkakas logam industri.',
            ],
            [
                'kelurahan_name' => 'Jati Handap',
                'nama_usaha' => 'CV. BIDURI ASIH',
                'sektor_name' => 'Property',
                'alamat_usaha' => 'Jl. Jatihandap No. 9, Kel. Jati Handap',
                'jumlah_tenaga_kerja' => 9,
                'deskripsi' => 'Penyedia layanan kontraktor sipil, jasa konstruksi perumahan, dan renovasi bangunan.',
            ],

            // ==============================================
            // KARANG F (Kelurahan Karang Pamulang) - 33 data
            // ==============================================
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'DAHLIA',
                'sektor_name' => 'Kerajinan',
                'alamat_usaha' => 'Jl. Pasir II No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Pembuatan bunga hias plastik dari kantong plastik bekas daur ulang dan anyaman vas tali kur.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'RIZA KARSA MANDIRI',
                'sektor_name' => 'Kerajinan',
                'alamat_usaha' => 'Jl. Mande No. 3, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 3,
                'deskripsi' => 'Usaha kerajinan minyak atsiri berbahan dasar akar wangi asli untuk wewangian aromaterapi.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'ANEKA JASA PERKASA',
                'sektor_name' => 'Kerajinan',
                'alamat_usaha' => 'Komp. Giri No. 3, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 3,
                'deskripsi' => 'Pembuatan keset rajutan tangan dari kain perca kaos katun yang tebal dan menyerap air.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'NUR SARI RASA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Produksi kuliner khas pepes bandeng duri lunak bungkus daun pisang matang kukus bakar.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'JAMBAL TUBAN',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'RW 04 No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Pengolahan dan penggaraman ikan jambal roti asin Tuban kering tebal gurih tanpa bahan pengawet.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'AGAR PELANGI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Kertasari No. 3, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 3,
                'deskripsi' => 'Home industri agar-agar jelly warna-warni rasa buah dengan kemasan cup higienis disukai anak.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'CV 3 SAUDARA',
                'sektor_name' => 'Pertanian',
                'alamat_usaha' => 'Kertasari No. 5, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 5,
                'deskripsi' => 'Produksi pupuk kompos humus subur gembur kemasan karung 10kg untuk tanaman hias.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'BINTANG SNACK',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Cikadu No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Home industri kue kering mentega, kue semprit, nastar nenas, dan kastengel keju renyah.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'DODI JAYA COLECT',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Jl. Cikadu No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Desain dan produksi tas totebag rajut benang nilon kuat motif bunga modis.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'DEMITRI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Jasa katering makanan rumahan harian sehat rendah lemak khusus penderita kolesterol.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'KUE "RIRI"',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Toko kue rumahan penyedia bolu marmer panggang jadul empuk dan kue sus vla vanilla.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'D.E.G.',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. A.H. Nasution No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Jasa fotokopi lembaran, print tugas warna, jilid lakban, dan penjualan perlengkapan sekolah.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'LILIS',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Sekebimbing No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Cemilan stik keju gurih (cheesestick) goreng renyah dari bahan keju cheddar melimpah.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'CICI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Cikadu No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Produksi stik keju renyah rumahan aneka varian rasa bbq, sapi panggang, dan original.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'IMAS',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kedai nasi ayam bakar kecap dengan sambal terasi matang ulek mantap kuah lalap segar.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'SINTA KARNIA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Produksi keripik singkong asin pedas gila kering tanpa pengawet kemasan plastik zipper.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'ETI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Jajanan pasar tradisional bumbu kelapa, kue lupis ketan kuah kencana cair manis wangi.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'SONYA SUSWANTI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Komp. Giri No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Penyedia sushi rumahan isi kepiting/sosis goreng dipadukan rumput laut nori gurih.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'IMA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Cemilan nostalgia mie lidi tipis aneka taburan bumbu keju manis, asin pedas daun jeruk.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'DIAN',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Pembuatan pizza mini teflon topping sosis sapi keju mozarella meleleh gurih.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'BINTANG',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Rolade daging ayam cincang bumbu rempah dibungkus kulit telur tipis kukus beku.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'JULEHA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Cikadu No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Jualan sosis bakar ukuran jumbo, sate bakso ikan bumbu barbeque dan saus cabai.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'ROSI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kedai minuman milkshake buah alpukat durian blender dingin kental topping meses.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'LINDA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Jualan es teh susu Thailand (thai tea) seduh segar wangi teh asli dipadu susu kental manis.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'YENI KAMILA DEWI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Penjualan olahan susu sapi murni rasa stroberi, cokelat botol plastik higienis.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'YUNENGSIH',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Home industri stik keju gurih krispi renyah tanpa bahan pengembang buatan.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'MIYATI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Pengolahan kacang bawang gurih bumbu irisan daun jeruk wangi minyak kelapa asli.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'SRI MARLINA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kue basah tradisional menerima pesanan snack box arisan rukun warga.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'IDA LAELA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Girimande No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Donat kentang taburan gula halus lembut empuk rasa manis pas buatan ibu Ida.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'TITIN',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'RW 06 No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Jasa pembuatan tumpeng nasi kuning syukuran khitanan, ulang tahun lengkap ayam goreng.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'NONENG',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'RW 07 No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Produksi rengginang ketan asin gurih mentah jemur matahari kering alami siap goreng.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'YATI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'RW 07 No. 2, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kue basah risoles mayo, tahu isi, bakwan sayur garing untuk titip jual di warung kopi.',
            ],
            [
                'kelurahan_name' => 'Karang Pamulang',
                'nama_usaha' => 'DEDEH',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'RW 07 No. 1, Kel. Karang Pamulang',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Produksi kerupuk mentah seblak pedas bantet kemasan renceng warung kelontong.',
            ],

            // ==========================================
            // PASIR IMP (Kelurahan Pasir Impun) - 8 data
            // ==========================================
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'PT. GIS SAINS ENGINERING',
                'sektor_name' => 'Teknologi',
                'alamat_usaha' => 'Jl. Pasir Impun No. 28, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 28,
                'deskripsi' => 'Perusahaan konsultan teknik rekayasa geospasial, pemetaan wilayah, dan sistem informasi geografis.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'PT. NATA DESAIN DIVER',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. Pasir Impun No. 4, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 4,
                'deskripsi' => 'Konsultan desain interior, arsitektur lanskap, dan pemodelan gedung 3D.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'PT. ROMIAN JAYA ABADI',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Komp. BU No. 18, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 18,
                'deskripsi' => 'Penyedia jasa outsourcing tenaga kebersihan (cleaning service) dan sistem keamanan gedung perkantoran.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'PT. SYAFEE JANNAR',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. Pasir Impun No. 18, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 18,
                'deskripsi' => 'Layanan konsultan pajak, audit keuangan independen, dan bimbingan manajemen UMKM.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'CV. CIPTA PRIANGAN',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Komp. GI No. 4, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 4,
                'deskripsi' => 'Jasa penyusunan studi kelayakan usaha, survei pasar, dan manajemen pemasaran digital.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'CV. GIAN TOKO MEBEL',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Pasir Impun No. 10, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Pembuatan dan toko furniture kayu jati seperti lemari, tempat tidur, kursi makan, dan meja belajar.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'CV. MANDALA KARYA ENGINERING',
                'sektor_name' => 'Teknologi',
                'alamat_usaha' => 'Komp. BU No. 2, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Konsultan pengawas kelistrikan (MEP) gedung bertingkat dan audit sistem proteksi petir.',
            ],
            [
                'kelurahan_name' => 'Pasir Impun',
                'nama_usaha' => 'CV. MULTI KARYA ENGINERING',
                'sektor_name' => 'Teknologi',
                'alamat_usaha' => 'Komp. BU No. 2, Kel. Pasir Impun',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Jasa konsultan sipil untuk perencanaan konstruksi jalan raya dan jembatan skala kecamatan.',
            ],

            // ==========================================
            // SINDANG (Kelurahan Sindangjaya) - 82 data
            // ==========================================
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DEDE WARTIAH',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Warung kelontong penyedia minyak curah, gula pasir, terigu eceran, dan sabun mandi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RONI HARYONO',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Babakan, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kelontong grosir penyedia air galon, mie instan dus-dusan, dan isi ulang gas melon.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'LILIS HERLINA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Arcamanik, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Warung jajanan warung kopi pagi hari menerima pesanan gorengan tahu tempe bakwan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ANNE HERMIATI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Taman S, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios mainan anak, alat tulis kantor, dan pernak-pernik jepit rambut wanita.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ILEH',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 3,
                'deskripsi' => 'Spesialis bubur ketan hitam kuah santan wangi pandan dan es lilin aneka buah.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'WISNU WARDHANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios pulsa elektrik, kartu perdana internet lengkap, dan jasa transfer uang kilat.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'LIS WIDIANINGSIH',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 3,
                'deskripsi' => 'Layanan jasa rekayasa gambar arsitektur rumah minimalis 2 lantai berlisensi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'TALANG RAWAN SYARI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Arcamanik, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko busana muslimah syar\'i, kerudung pashmina instan, dan gamis bermotif.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'MUHAMMAD HANI AL-FATH',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Desain kaos distro sablon plastisol dengan katun combed 30s berkualitas tinggi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DIEN RUSNARDINI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios pakaian anak bermotif kartun, piyama katun adem disukai buah hati.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'EVY MULYANAWATI',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Perum BI, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Butik rumahan penyedia hijab instan segiempat voal premium polos dan bermotif.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ALI HERSYAD',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. A.H. Nasution, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Toko sembako keluarga menyediakan beras raskin minyak goreng curah gula pasir.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'INUN SUMIATI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri No. 1, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios bumbu instan giling basah khas Sunda rendang opor sayur lodeh.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ENTIN KARTINI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios ritel warung kelontong jajanan anak, snack kemasan, es sirup marjan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'LELA MARDIANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kosmetik rias wajah lipstik bedak lokal aman berkualitas bpom.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'HENDRA SETIADI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Warung penjual telur ayam ras segar, telur bebek asin, minyak kelapa botol.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ROISAN SIKHOERUDIN',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko pakan burung peliharaan ulat hongkong jangkrik kering sangkar bambu.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SITI NURJANAH',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Warung sayur mayur sup taoge sawi kangkung tahu cina bumbu bawang putih.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SHELLY MULYANI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios kelontong isi ulang gas melon detergen bubuk sabun mandi cair.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'NURWAHIDAH YATIEA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Butik pakaian gamis wanita bahan maxmara tebal bermotif elegan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'GEBI GANDAWAN MAMAN',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios penjualan air mineral galon isi ulang bersih steril saringan ultraviolet.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'TINA MULYATI',
                'sektor_name' => 'Pertanian',
                'alamat_usaha' => 'Jl. Arcamanik, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 3,
                'deskripsi' => 'Penyedia bibit bunga tanaman hias mawar anggrek media tanam sekam padi bakar.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SUTRISNO',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Arcamanik, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios penjual kelapa kupas tua parut untuk santan murni masakan padang.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DIAN YULIANTI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios pakaian daster emak-emak batik kencana wungu kancing depan busui.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ENDANG SURYANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Warung kelontong penyedia obat warung p3k balsem tolak angin mie sedap.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'UUM SUMIATI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Pasempak, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios penjualan perkakas plastik ember baskom gayung saringan teh jemuran baju.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RUMIYATIN',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Spesialis bubur kacang ijo ketan hitam hangat gurih kuah santan wangi daun pandan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DENI YULIANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kelontong beras Cianjur beras ketan putih eceran literan berkualitas.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'TASMAN IDRISAN',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios pulsa listrik kuota internet termurah lengkap all operator.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'KARNO',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko makanan beku frozen food sosis nugget kentang kemasan kiloan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DIAN RADIAN',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Layanan jasa perbaikan pompa air jetpump tersumbat mati total bergaransi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RIZA AMELIA AGUSTINA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. H. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios hijab kerudung anak muda pashmina plisket ceruti warna pastel cantik.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'NENH UANTI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Kp. Pasempak, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Warung jajanan gorengan pagi bakwan tahu cireng gehu hangat cocol sambal kacang.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'LILIS ROSTIANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Toko kosmetik bedak bayi lulur kecantikan sabun muka herbal aman bpom.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'IIS PURWANTI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios minyak goreng curah minyak goreng kemasan margarin mentega piringan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ELIS SUGIARTI',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Jl. H. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Butik busana pesta kebaya payet gaun pesta brukat modern pesanan kustom.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'LELIANA TRESNASARI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Komp. BU, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Toko kelontong kecil bumbu kering lada ketumbar kemiri instan penyedap rasa.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'AJANG YOSANA',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Jl. Sukaasih No. 10, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Konveksi pembuatan celana kulot, rok panjang plisket kancing pinggang karet tebal.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SUPRIONO',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko grosir air minum galon aqua vit isi ulang steril terpercaya.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SUDIN SUMARSA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Warung penjual kelapa parut santan kental murni parutan segar harian.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'WARNA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Warung rokok kopi seduh indomie rebus hangat warung kelontong kecil.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RIZKI DHARMAWAN',
                'sektor_name' => 'Kerajinan',
                'alamat_usaha' => 'Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kerajinan hiasan kaligrafi arab ukiran kayu jati kusen jendela dekorasi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'HANA RIANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Arcamanik, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios mainan anak-anak boneka barbie balon tiup mobil-mobilan edukatif plastik.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'YUKE PATRIANI RAMDHANIA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Penyedia es teler durian kuah nangka mutiara kelapa muda kental manis.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'EVA RAHAYU SETYA WARDANI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios jilbab anak sekolah segiempat polos voal warna warni murah adem.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'KIKI RIZKI SUCIATI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri No. 10, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Kios baju gamis motif kembang modis tali pinggang bahan monalisa.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RENDRA SANTANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri No. 10, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Toko kelontong grosir gula minyak goreng indomie gas melon karungan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SUNARDI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Kp. Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Warung kelontong p3k bumbu instan sayur minyak goreng curah.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'NANDANG SUPRIATNA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko beras Cianjur rojolele eceran timbang literan berkualitas tinggi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'JULIA LAHITANI PUJA',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Desain kaos sablon kustom berkualitas distro cotton combed adem.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RD AYU KUSUMAH',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Rumah makan nasi liwet khas Sunda komplit peda bakar lalap jengkol.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'MUHAMMAD BAGJA IRFAN SYARIF II',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. A.H. Nasution, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios perlengkapan sekolah tas sekolah sepatu hitam kaos kaki buku tulis.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'IRMA FUJI ASTUTI',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. Sindang No. 10, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Layanan jasa pengetikan tugas skripsi print dokumen jilid spiral.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SITI ENDANG SRI RAHAYU',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios kosmetik parfum refill aroma wangi awet tahan lama non alkohol.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'CARTO',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kelontong sembako garam beryodium terigu curah sabun batangan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'AI KOKOM KOMALASARI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Pasempak, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Warung kelontong beras timbang minyak goreng gelas mie instan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'TETTY ROSMAETI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. A.H. Nasution, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Toko pakaian daster daster batik emak adem kancing depan kencana wungu.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'UMI KALSUM',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. N. Suka, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios pakan ayam konsentrat dedak padi pakan burung peliharaan sangkar.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'AYU LESTARI',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Layanan salon kustom rias wajah wisuda sanggul rambut modern.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ASEP AJAT SUDRAJAT',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. N. Moch, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Layanan pangkas rambut pria cepat rapi wangi anak-anak bersahabat.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'NENENG SUMIATI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Warung beras eceran minyak goreng gelas mie instan bumbu dapur.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'NINDAR NURANI KURNELIS',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios daster emak adem daster batik kancing depan kencana wungu.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'SITI JULAEHA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sindang, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios kerudung segiempat polos voal warna warni adem murah.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'TUGIPANTO ABDUL ROZAK',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios pakan ayam konsentrat dedak padi pakan burung sangkar.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RAISSA TRI FADHILA',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Spesialis bubur kacang ijo ketan hitam kuah santan daun pandan wangi.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'INAT RAHMAT',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Warung makan nasi kuning pagi komplit dadar iris bihun sambal goreng.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'AGUS RAHMAT',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Pasempak, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kelontong sembako beras timbang minyak goreng gelas mie instan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'AAN SUPARTINI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kedai es campur segar alpukat kelapa muda nangka manis kuah susu.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'YAN MARDIANA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih No. 10, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 10,
                'deskripsi' => 'Toko kelontong sembako beras timbang minyak goreng indomie gas melon.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'FERDI IMAM HATTA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko grosir air minum galon aqua vit isi ulang steril bersih.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RIANI SEPTIANI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios daster emak adem daster batik kancing depan kencana wungu.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DEWI KANIA',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Kios kerudung segiempat polos voal warna warni murah adem.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'DINA MARTIANI',
                'sektor_name' => 'Fashion',
                'alamat_usaha' => 'Jl. H. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Butik busana pesta kebaya payet gaun pesta brukat modern kustom.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RISKA ARYANTI',
                'sektor_name' => 'Kuliner',
                'alamat_usaha' => 'Jl. N. Moch, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Spesialis bubur ketan hitam kuah santan wangi pandan es lilin.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'BAMBANG HARTONO',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. H. Moch. Syahri, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kelontong sembako beras timbang minyak goreng gelas mie instan.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ANITA HARTATI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Pasempak, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios penjualan perkakas plastik ember baskom gayung saringan teh.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'ROSAD SUPRIADI',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Layanan jasa perbaikan pompa air jetpump tersumbat mati total.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'KARLITA RAHMADINI SE',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Sukaasih, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Toko kosmetik bedak bayi lulur kecantikan sabun muka herbal.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'MUHAMAD RIZAL',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. A.H. Nasution, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios mainan anak boneka barbie balon tiup mobil-mobilan plastik.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'JAYET',
                'sektor_name' => 'Jasa',
                'alamat_usaha' => 'Jl. Sekepier, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 1,
                'deskripsi' => 'Layanan pangkas rambut pria cepat rapi wangi anak-anak bersahabat.',
            ],
            [
                'kelurahan_name' => 'Sindangjaya',
                'owner_name' => 'RESTI FAHMI ANJARI',
                'sektor_name' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Sersan, Kel. Sindangjaya',
                'jumlah_tenaga_kerja' => 2,
                'deskripsi' => 'Kios daster batik emak adem kancing depan kencana wungu premium.',
            ],
        ];

        $totalCreated = 0;
        $nikCounter = 3000; // Mulai dari 3000 untuk menjamin keunikan NIK

        $this->command->info('📝 Memulai seeding data riil 131 UMKM...');

        foreach ($rawUmkmData as $i => $data) {
            $nikCounter++;
            $nik = '3270010101' . str_pad($nikCounter, 6, '0', STR_PAD_LEFT);

            // Ambil objek kelurahan
            $kelurahanObj = Kelurahan::where('nama_kelurahan', $data['kelurahan_name'])->first();
            if (! $kelurahanObj) {
                $this->command->error("Kelurahan tidak ditemukan: {$data['kelurahan_name']}");
                continue;
            }

            // Tentukan nama pemilik (Gunakan nama riil bila ada, atau dummy nama Indonesia bila tidak ada)
            if (isset($data['owner_name'])) {
                $ownerName = $data['owner_name'];
                
                // Tentukan nama usaha secara realistis berdasarkan jenis/sektor untuk Sindangjaya
                $sektorName = $data['sektor_name'];
                if ($sektorName === 'Kuliner') {
                    $namaUsaha = 'Warung Makan ' . $ownerName;
                } elseif ($sektorName === 'Fashion') {
                    $namaUsaha = 'Butik ' . $ownerName;
                } elseif ($sektorName === 'Kerajinan') {
                    $namaUsaha = 'Kerajinan ' . $ownerName;
                } elseif ($sektorName === 'Jasa') {
                    $namaUsaha = 'Jasa Service ' . $ownerName;
                } elseif ($sektorName === 'Perdagangan') {
                    $namaUsaha = 'Toko Kelontong ' . $ownerName;
                } else {
                    $namaUsaha = 'Usaha Mandiri ' . $ownerName;
                }
            } else {
                $ownerName = $ownerNames[$i % count($ownerNames)];
                $namaUsaha = $data['nama_usaha'];
            }

            // Tebak jenis kelamin berdasarkan nama pemilik
            $perempuan = [
                'Siti Aminah', 'Dewi Lestari', 'Sri Wahyuni', 'Lilis Marlina', 'Nenden Nurhayati', 
                'Euis Rohayati', 'Siska Rahayu', 'Wati Herawati', 'Yani Mulyani', 'Rina Herlina', 
                'Titin Sumarni', 'Elah Tarmilah', 'Yoyoh Yohanah', 'Reni Rosmiati', 'Nyai Hayati', 
                'Nina Dinarwat', 'Nining', 'Nia', 'Rosilah', 'Susi', 'Kokom', 'Yanti',
                'DEDE WARTIAH', 'LILIS HERLINA', 'ANNE HERMIATI', 'ILEH', 'LIS WIDIANINGSIH',
                'EVY MULYANAWATI', 'INUN SUMIATI', 'ENTIN KARTINI', 'LELA MARDIANA',
                'SITI NURJANAH', 'SHELLY MULYANI', 'NURWAHIDAH YATIEA', 'TINA MULYATI',
                'DIAN YULIANTI', 'UUM SUMIATI', 'RUMIYATIN', 'DENI YULIANA',
                'RIZA AMELIA AGUSTINA', 'NENH UANTI', 'LILIS ROSTIANA', 'IIS PURWANTI',
                'ELIS SUGIARTI', 'LELIANA TRESNASARI', 'AJANG YOSANA', 'HANA RIANA',
                'YUKE PATRIANI RAMDHANIA', 'EVA RAHAYU SETYA WARDANI', 'KIKI RIZKI SUCIATI',
                'JULIA LAHITANI PUJA', 'RD AYU KUSUMAH', 'IRMA FUJI ASTUTI', 'SITI ENDANG SRI RAHAYU',
                'AI KOKOM KOMALASARI', 'TETTY ROSMAETI', 'UMI KALSUM', 'AYU LESTARI',
                'NENENG SUMIATI', 'NINDAR NURANI KURNELIS', 'SITI JULAEHA', 'RAISSA TRI FADHILA',
                'INAT RAHMAT', 'AAN SUPARTINI', 'RIANI SEPTIANI', 'DEWI KANIA', 'DINA MARTIANI',
                'RISKA ARYANTI', 'ANITA HARTATI', 'KARLITA RAHMADINI SE', 'RESTI FAHMI ANJARI'
            ];
            $jenisKelamin = in_array(strtoupper($ownerName), array_map('strtoupper', $perempuan)) ? 'P' : 'L';

            // Buat Pemilik baru
            $rw = str_pad(mt_rand(1, 9), 3, '0', STR_PAD_LEFT);
            $rt = str_pad(mt_rand(1, 15), 3, '0', STR_PAD_LEFT);

            $pemilik = Pemilik::create([
                'id_pemilik' => (string) Str::uuid(),
                'nama_lengkap' => $ownerName,
                'nik' => $nik,
                'no_hp' => '08' . mt_rand(111111111, 999999999),
                'email' => strtolower(str_replace([' ', '.'], '', $ownerName)) . mt_rand(10, 99) . '@example.com',
                'kelurahan' => $kelurahanObj->nama_kelurahan,
                'id_kelurahan' => $kelurahanObj->id,
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'rw' => $rw,
                'rt' => $rt,
                'alamat' => $data['alamat_usaha'],
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => date('Y-m-d', strtotime('-' . mt_rand(20, 60) . ' years')),
                'jenis_kelamin' => $jenisKelamin,
                'kode_pos' => '40285',
                'status_verifikasi_ktp' => 'terverifikasi',
            ]);

            // Seluruh UMKM otomatis terverifikasi.
            $statusVerifikasi = 'terverifikasi';
            $tanggalVerifikasi = now()->subDays(mt_rand(1, 30));

            // Tentukan Kategori Usaha dinamis berdasarkan jumlah tenaga kerja
            $jumlahPegawai = $data['jumlah_tenaga_kerja'];
            if ($jumlahPegawai < 5) {
                $kategoriId = $kategoriMikro->id ?? 1;
            } elseif ($jumlahPegawai < 20) {
                $kategoriId = $kategoriKecil->id ?? 2;
            } else {
                $kategoriId = $kategoriMenengah->id ?? 3;
            }

            // Tentukan Sektor Usaha
            $sektorObj = $sektors->get($data['sektor_name']) ?? SektorUmkm::where('nama_sektor', $data['sektor_name'])->first();
            $sektorId = $sektorObj->id ?? 1;

            // Pembagian tenaga kerja laki/perempuan secara acak logis
            $tenagaKerjaLaki = mt_rand(0, $jumlahPegawai);
            $tenagaKerjaPerempuan = $jumlahPegawai - $tenagaKerjaLaki;

            // Buat UMKM
            UMKM::create([
                'id_umkm' => (string) Str::uuid(),
                'nama_usaha' => $namaUsaha,
                'id_pemilik' => $pemilik->id_pemilik,
                'id_kategori' => $kategoriId,
                'id_sektor' => $sektorId,
                'status_usaha' => 'aktif',
                'tahun_berdiri' => mt_rand(2015, 2025),
                'no_izin_usaha' => 'NIB-' . mt_rand(1000000000, 9999999999),
                'jenis_izin' => 'NIB',
                'telp_usaha' => '022' . mt_rand(100000, 999999),
                'email_usaha' => strtolower(str_replace([' ', '.', '(', ')'], '', $namaUsaha)) . '@example.com',
                'alamat_usaha' => $data['alamat_usaha'],
                'deskripsi' => $data['deskripsi'],
                'jumlah_tenaga_kerja' => $jumlahPegawai,
                'tenaga_kerja_laki' => $tenagaKerjaLaki,
                'tenaga_kerja_perempuan' => $tenagaKerjaPerempuan,
                'id_petugas' => $petugas->id ?? 1,
                'tanggal_pendataan' => now()->subDays(mt_rand(30, 365)),
                'status_verifikasi' => $statusVerifikasi,
                'tanggal_verifikasi' => $tanggalVerifikasi,
                'catatan_penolakan' => null,
            ]);

            // Buat Akun User login untuk pelaku UMKM ini
            $user = User::firstOrCreate(
                ['email' => $pemilik->email],
                [
                    'name' => $pemilik->nama_lengkap,
                    'nik' => $nik,
                    'kelurahan' => $kelurahanObj->nama_kelurahan,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('pelaku_umkm')) {
                $user->assignRole('pelaku_umkm');
            }

            $totalCreated++;
        }

        $this->command->info("✅ Seeder berhasil memuat {$totalCreated} data riil UMKM!");
        $this->command->info("   - Status Terverifikasi: 100%");
    }
}
