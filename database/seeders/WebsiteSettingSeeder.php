<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use App\Models\AboutPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Website Setting
        WebsiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'Fasel Aquarium',
                'slogan' => 'Platform E-commerce Terpercaya Untuk Ikan Hias',
                'email' => 'info@faselaquarium.com',
                'phone' => '08123456789',
                'whatsapp' => '08123456789',
                'address' => 'Jl. Diponegoro No. 123, Indramayu, Jawa Barat 45213',
                'instagram' => '@faselaquarium',
                'facebook' => 'Fasel Aquarium',
                'latitude' => -6.3044,
                'longitude' => 108.3257,
                'timezone' => 'Asia/Jakarta',
                'date_format' => 'd/m/Y',
                'products_per_page' => 12,
                'maintenance_mode' => false,
                'copyright_text' => '© 2024 Fasel Aquarium. All Rights Reserved.',
            ]
        );

        // About Page
        AboutPage::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Tentang Kami',
                'about_content' => 'Cara Baru Belanja Ikan Hias di Indramayu: Pesan Online, Ambil Langsung di Galeri Kami

Selamat datang di Fasel Aquarium, platform e-commerce dan galeri ikan hias terpercaya yang hadir khusus untuk melayani para pencinta dunia acuatik di wilayah Indramayu dan sekitarnya. Kami menyediakan berbagai jenis ikan hias berkualitas, tanaman air, hingga perlengkapan akuarium terlengkap yang bisa Anda pantau dan pesan secara online, lalu diambil langsung di toko fisik kami.

Kami memahami bahwa bagi warga Indramayu, memilih ikan hias adalah sebuah kepuasan tersendiri. Melalui situs ini, kami ingin memberikan kemudahan bagi Anda untuk melihat stok terbaru dari rumah, mengamankan ikan incaran agar tidak kehabisan, lalu mengambilnya secara langsung (Pick-up) atau COD di toko kami tanpa perlu khawatir risiko ikan rusak atau mati di perjalanan kurir paket.',
                'why_choose_us' => 'Mengapa Berbelanja di Fasel Aquarium?

✓ Lihat & Pastikan Langsung Kondisi Ikan
✓ Transaksi Aman (Bayar di Toko / COD)
✓ Ikan Bebas Stres Perjalanan Jauh
✓ Edukasi & Sharing Sesama Penghobi di Indramayu',
                'how_to_shop' => 'Bagaimana Cara Berbelanja

1. Pilih ikan yang Anda inginkan
2. Checkout pesanan
3. Pilih metode COD atau Pick-up
4. Kami siapkan pesanan Anda
5. Ambil dan bayar di toko fisik kami',
                'facilities' => 'Fasilitas & Galeri Fasel Aquarium

Kami mengelola galeri fisik dan fasilitas karantina mandiri yang bersih dan terawat di Indramayu. Semua stok ikan hias yang ada di katalog web kami dirawat dengan standar filtrasi yang baik dan monitoring kesehatan ikan secara berkala.',
                'contact_address' => 'Jl. Diponegoro No. 123, Indramayu, Jawa Barat 45213',
                'contact_whatsapp' => '08123456789',
                'contact_instagram' => '@faselaquarium',
                'contact_phone' => '08123456789',
                'operation_hours' => '09.00 - 21.00 WIB (Setiap Hari)',
            ]
        );
    }
}
