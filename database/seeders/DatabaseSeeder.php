<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\PublicDocument;
use App\Models\Service;
use App\Models\InformasiTab;
use App\Models\GalleryAlbum;
use App\Models\VideoItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        User::updateOrCreate(
            ['username' => 'aditya'],
            [
                'name' => 'aditya',
                'email' => 'aditya@dishub.probolinggokab.go.id',
                'whatsapp' => '082131001001',
                'referral_code' => 'ADITYA2026',
                'password' => Hash::make('aditya123'),
                'role' => 'super_admin',
                'is_hidden' => true,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@dishub.probolinggokab.go.id'],
            [
                'name' => 'Administrator DISHUB',
                'username' => 'admin',
                'whatsapp' => '081234567890',
                'referral_code' => 'ADMIN2026',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_hidden' => false,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'sukma'],
            [
                'name' => 'Sukma Anggota Staf',
                'email' => 'sukma@dishub.probolinggokab.go.id',
                'whatsapp' => '089876543210',
                'referral_code' => 'SUKMA2026',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'is_hidden' => false,
                'is_active' => true,
            ]
        );

        // 2. Navigation Menus DISHUB
        NavigationMenu::truncate();

        NavigationMenu::create(['title' => 'HOME', 'url' => '/', 'order' => 1]);
        
        $profil = NavigationMenu::create(['title' => 'PROFIL', 'url' => '#', 'order' => 2]);
        NavigationMenu::create(['title' => 'Struktur Organisasi', 'url' => '/halaman/struktur-organisasi', 'parent_id' => $profil->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Visi Misi', 'url' => '/halaman/visi-misi', 'parent_id' => $profil->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Tugas dan Fungsi', 'url' => '/halaman/tugas-dan-fungsi', 'parent_id' => $profil->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Survei Kepuasan Masyarakat', 'url' => '/halaman/survei-kepuasan-masyarakat', 'parent_id' => $profil->id, 'order' => 4]);

        $layanan = NavigationMenu::create(['title' => 'LAYANAN', 'url' => '#', 'order' => 3]);
        NavigationMenu::create(['title' => 'Semua Layanan Publik DISHUB', 'url' => '/layanan', 'parent_id' => $layanan->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Pendaftaran Uji Berkala KIR Online', 'url' => '/layanan/pendaftaran-uji-kir-online', 'parent_id' => $layanan->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Permohonan Izin Trayek Angkutan', 'url' => '/layanan/izin-trayek-angkutan-umum', 'parent_id' => $layanan->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Pengaduan Lalu Lintas & PJU', 'url' => '/layanan/pengaduan-lalu-lintas', 'parent_id' => $layanan->id, 'order' => 4]);

        $dokumen = NavigationMenu::create(['title' => 'DOKUMEN', 'url' => '#', 'order' => 4]);
        NavigationMenu::create(['title' => 'Perencanaan Kinerja', 'url' => '/dokumen/perencanaan-kinerja', 'parent_id' => $dokumen->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Pengukuran Kinerja', 'url' => '/dokumen/pengukuran-kinerja', 'parent_id' => $dokumen->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Pelaporan Kinerja', 'url' => '/dokumen/pelaporan-kinerja', 'parent_id' => $dokumen->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Evaluasi Kinerja', 'url' => '/dokumen/evaluasi-kinerja', 'parent_id' => $dokumen->id, 'order' => 4]);

        $informasi = NavigationMenu::create(['title' => 'INFORMASI', 'url' => '#', 'order' => 5]);
        NavigationMenu::create(['title' => 'Berita', 'url' => '/informasi', 'parent_id' => $informasi->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'PPID', 'url' => 'https://ppid.probolinggokab.go.id/', 'target' => '_blank', 'parent_id' => $informasi->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Video', 'url' => '/video', 'parent_id' => $informasi->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Galery', 'url' => '/galery', 'parent_id' => $informasi->id, 'order' => 4]);

        $hubungi = NavigationMenu::create(['title' => 'HUBUNGI', 'url' => '#', 'order' => 6]);
        NavigationMenu::create(['title' => 'Lapor SP4N', 'url' => 'https://www.lapor.go.id/', 'target' => '_blank', 'parent_id' => $hubungi->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Kontak', 'url' => '/kontak', 'parent_id' => $hubungi->id, 'order' => 2]);

        NavigationMenu::create(['title' => 'LOGIN', 'url' => '/login', 'order' => 7]);

        // 3. Public Documents (4 Jenis Dokumen)
        PublicDocument::truncate();
        $adminUser = User::where('role', 'admin')->first() ?: User::first();
        $adminId = $adminUser ? $adminUser->id : 1;
        
        // 1. Perencanaan Kinerja
        PublicDocument::create([
            'title' => 'Rencana Strategis (Renstra) Dinas Perhubungan Tahun 2024-2029',
            'type' => 'perencanaan-kinerja',
            'category' => 'Rencana Strategis',
            'tahun' => '2026',
            'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'download_count' => 185,
            'created_by' => $adminId,
        ]);
        PublicDocument::create([
            'title' => 'Pohon Kinerja Dinas Perhubungan Tahun 2026',
            'type' => 'perencanaan-kinerja',
            'category' => 'Pohon Kinerja',
            'tahun' => '2026',
            'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'download_count' => 98,
            'created_by' => $adminId,
        ]);

        // 2. Pengukuran Kinerja
        PublicDocument::create([
            'title' => 'Capaian Kinerja Dinas Perhubungan Triwulan IV Tahun 2025',
            'type' => 'pengukuran-kinerja',
            'category' => 'Capaian Kinerja',
            'tahun' => '2025',
            'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'download_count' => 120,
            'created_by' => $adminId,
        ]);

        // 3. Pelaporan Kinerja
        PublicDocument::create([
            'title' => 'Laporan Akuntabilitas Kinerja Instansi Pemerintah (LAKIP / LKjIP) Tahun 2025',
            'type' => 'pelaporan-kinerja',
            'category' => 'LAKIP / LKjIP',
            'tahun' => '2025',
            'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'download_count' => 340,
            'created_by' => $adminId,
        ]);

        // 4. Evaluasi Kinerja
        PublicDocument::create([
            'title' => 'LHE AKIP 2025 (Lembar Hasil Evaluasi AKIP)',
            'type' => 'evaluasi-kinerja',
            'category' => 'Lembar Hasil Evaluasi (LHE)',
            'tahun' => '2025',
            'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'download_count' => 275,
            'created_by' => $adminId,
        ]);
        PublicDocument::create([
            'title' => 'Laporan Hasil Evaluasi Akuntabilitas Kinerja Tahun 2024',
            'type' => 'evaluasi-kinerja',
            'category' => 'Lembar Hasil Evaluasi (LHE)',
            'tahun' => '2024',
            'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'download_count' => 150,
            'created_by' => $adminId,
        ]);

        // 4. Tab Informasi Default
        InformasiTab::truncate();
        InformasiTab::create(['name' => 'Semua Berita',  'slug' => 'semua-berita',  'icon' => 'fas fa-newspaper',     'order' => 1, 'is_active' => true, 'filter_type' => 'all']);
        InformasiTab::create(['name' => 'Pemerintahan',  'slug' => 'pemerintahan',  'icon' => 'fas fa-landmark',      'order' => 2, 'is_active' => true, 'filter_type' => 'category', 'filter_value' => 'Pemerintahan']);
        InformasiTab::create(['name' => 'Lalu Lintas',   'slug' => 'lalu-lintas',   'icon' => 'fas fa-traffic-light', 'order' => 3, 'is_active' => true, 'filter_type' => 'category', 'filter_value' => 'Lalu Lintas']);
        InformasiTab::create(['name' => 'Pelayanan',     'slug' => 'pelayanan',     'icon' => 'fas fa-car-side',      'order' => 4, 'is_active' => true, 'filter_type' => 'category', 'filter_value' => 'Pelayanan Publik']);

        // 5. Sample Layanan Publik
        Service::truncate();

        Service::create([
            'title'       => 'Pendaftaran Uji Berkala Kendaraan (KIR) Online',
            'slug'        => 'pendaftaran-uji-kir-online',
            'description' => 'Daftarkan kendaraan Anda untuk uji berkala (KIR) secara online tanpa harus antri di kantor.',
            'content'     => "Layanan Uji Berkala Kendaraan Bermotor (KIR) adalah kewajiban bagi setiap kendaraan angkutan umum dan kendaraan barang.\n\nSyarat Pendaftaran:\n1. Fotokopi STNK\n2. Fotokopi BPKB\n3. KTP pemilik kendaraan\n4. Buku Uji lama (perpanjangan)\n\nProsedur:\n1. Daftar secara online melalui portal ini\n2. Pilih jadwal dan slot waktu yang tersedia\n3. Datang ke kantor DISHUB pada jadwal yang telah dipilih\n4. Kendaraan akan diuji oleh petugas teknis\n\nBiaya: Sesuai Perda Kab. Probolinggo",
            'icon'        => 'fas fa-car-side',
            'category'    => 'Pengujian Kendaraan',
            'image_url'   => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
            'order'       => 1,
            'is_active'   => true,
            'created_by'  => $adminId,
        ]);

        Service::create([
            'title'       => 'Permohonan Izin Trayek Angkutan Umum',
            'slug'        => 'izin-trayek-angkutan-umum',
            'description' => 'Ajukan permohonan izin trayek angkutan umum (bus, angkot, angdes) resmi di wilayah Kabupaten Probolinggo.',
            'content'     => "Izin Trayek adalah dokumen wajib bagi operator angkutan umum yang beroperasi di wilayah Kabupaten Probolinggo.\n\nSyarat Dokumen:\n1. Akta pendirian perusahaan\n2. NPWP perusahaan\n3. Daftar kendaraan dengan STNK\n4. Sertifikat uji berkala kendaraan\n5. KTP direktur/pemilik\n\nBiaya: Gratis (sesuai kebijakan berlaku)",
            'icon'        => 'fas fa-bus',
            'category'    => 'Perizinan',
            'image_url'   => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600',
            'order'       => 2,
            'is_active'   => true,
            'created_by'  => $adminId,
        ]);

        Service::create([
            'title'       => 'Pengaduan Lalu Lintas & Infrastruktur Jalan',
            'slug'        => 'pengaduan-lalu-lintas',
            'description' => 'Laporkan permasalahan lalu lintas, rambu rusak, atau infrastruktur jalan yang membahayakan keselamatan.',
            'content'     => "Layanan pengaduan lalu lintas tersedia untuk masyarakat yang ingin melaporkan:\n\n- Rambu lalu lintas rusak/hilang\n- Lampu penerangan jalan mati (PJU)\n- Jalan rusak yang membahayakan\n- Kendaraan angkutan tidak laik jalan\n- Pelanggaran trayek angkutan umum\n\nCara Lapor:\n1. Layanan Cepat WhatsApp Halo SAE: 0821 3100 1001\n2. Isi form di halaman Kontak DISHUB\n3. Atau hubungi telepon: 0335-421554\n4. Atau melalui kanal SP4N LAPOR! online",
            'icon'        => 'fas fa-exclamation-triangle',
            'category'    => 'Pengaduan',
            'image_url'   => 'https://images.unsplash.com/photo-1508873696983-2df515122519?w=600',
            'order'       => 3,
            'is_active'   => true,
            'created_by'  => $adminId,
        ]);

        // 6. Sample Video Items
        VideoItem::truncate();
        VideoItem::create([
            'title'         => 'Sosialisasi Keselamatan Berlalulintas & Uji KIR Kendaraan Bermotor DISHUB Kabupaten Probolinggo',
            'slug'          => 'sosialisasi-keselamatan-berlalulintas-uji-kir',
            'video_url'     => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600',
            'description'   => 'Dokumentasi kegiatan sosialisasi tertib berlalu lintas serta pentingnya uji berkala kelaikan kendaraan bermotor.',
            'published_at'  => now()->subDays(5),
            'created_by'    => $adminId,
        ]);
        VideoItem::create([
            'title'         => 'Ramp Check Kesiapan Bus Antarkota Menjelang Libur Panjang di Terminal Tipe B Kraksaan',
            'slug'          => 'ramp-check-kesiapan-bus-antarkota',
            'video_url'     => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=600',
            'description'   => 'Pemeriksaan teknis dan kelaikan operasional bus penumpang umum demi menjamin kenyamanan penumpang.',
            'published_at'  => now()->subDays(12),
            'created_by'    => $adminId,
        ]);

        // 7. Sample Gallery Albums
        GalleryAlbum::truncate();
        GalleryAlbum::create([
            'title'       => 'Operasi Gabungan Penertiban Angkutan Barang & Uji Emisi',
            'slug'        => 'operasi-gabungan-penertiban-angkutan-barang',
            'description' => 'Dokumentasi penertiban kelebihan muatan (ODOL) dan uji emisi kendaraan barang di jalur Pantura Probolinggo.',
            'cover_image' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800',
            'photos'      => [
                'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800',
                'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800',
                'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800',
                'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800',
            ],
            'created_by'  => $adminId,
        ]);
        GalleryAlbum::create([
            'title'       => 'Apel Kesiapsiagaan Pos Pelayanan Terpadu LLAJ DISHUB',
            'slug'        => 'apel-kesiapsiagaan-pos-pelayanan-terpadu-llaj',
            'description' => 'Apel bersama petugas posko pemantauan arus mudik dan rekayasa jalur wisata.',
            'cover_image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800',
            'photos'      => [
                'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800',
                'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=800',
                'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800',
                'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800',
            ],
            'created_by'  => $adminId,
        ]);
    }
}
