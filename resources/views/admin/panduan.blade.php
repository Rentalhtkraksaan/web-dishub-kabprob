@extends('admin.layout')
@section('page_title', 'Buku Panduan Penggunaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    
    <!-- HEADER BUKU PANDUAN -->
    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-6 print:hidden">
        <div class="text-white space-y-2">
            <h1 class="text-3xl font-extrabold flex items-center gap-3">
                <i class="fas fa-book-reader text-amber-400"></i> Buku Panduan Penggunaan
            </h1>
            <p class="text-blue-100 max-w-2xl text-sm leading-relaxed">
                Panduan interaktif dan langkah-langkah penggunaan portal admin Dinas Perhubungan. 
                Panduan ini telah disesuaikan khusus untuk akses Anda sebagai 
                <span class="font-bold text-amber-400 px-1 bg-amber-400/20 rounded uppercase">{{ auth()->user()->role }}</span>.
            </p>
        </div>
        <div>
            <button onclick="window.print()" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold py-3 px-6 rounded-2xl shadow-lg transition-transform hover:-translate-y-1 flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-print"></i> Cetak / Download PDF
            </button>
        </div>
    </div>

    <!-- KONTEN PANDUAN UNTUK PRINT & READ -->
    <div class="p-8 sm:p-12 max-w-5xl mx-auto space-y-12 text-slate-700" id="panduan-content">
        
        <!-- COVER BUKU PANDUAN (ONLY FOR PRINT) -->
        <div class="hidden print:flex flex-col items-center justify-center h-screen border-b-4 border-slate-800 pb-10 mb-10 text-center space-y-6">
            <img src="{{ $settings['logo_frontend'] ?? asset('images/logo_dishub.png') }}" class="w-32 h-auto" />
            <h1 class="text-5xl font-extrabold text-slate-900">BUKU PANDUAN<br>PENGGUNAAN WEBSITE</h1>
            <h2 class="text-2xl text-slate-600 font-bold uppercase">Hak Akses: {{ str_replace('_', ' ', auth()->user()->role) }}</h2>
            <p class="text-slate-500 mt-10">Dinas Perhubungan Kabupaten Probolinggo<br>{{ date('Y') }}</p>
            <div class="page-break" style="page-break-after: always;"></div>
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl print:hidden">
                <h4 class="font-bold text-amber-800"><i class="fas fa-crown"></i> Mode Tampilan Super Admin</h4>
                <p class="text-sm text-amber-700 mt-1">Anda sedang melihat versi panduan paling lengkap (Full Version) karena Anda adalah Super Admin.</p>
            </div>
        @endif

        <!-- BAB 1: PENDAHULUAN & LOGIN -->
        <section class="space-y-4">
            <h2 class="text-2xl font-extrabold text-slate-900 border-b-2 border-slate-100 pb-2 flex items-center gap-2">
                <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center text-sm">1</span> 
                Cara Login & Manajemen Akun
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <h3 class="font-bold text-lg mb-3 text-blue-800"><i class="fas fa-sign-in-alt"></i> Login ke Sistem</h3>
                    <ol class="list-decimal pl-5 space-y-2 text-sm">
                        <li>Buka halaman utama website dinas.</li>
                        <li>Tambahkan <code class="bg-slate-200 px-1 rounded text-rose-600">/login</code> pada alamat URL (Contoh: <code class="bg-slate-200 px-1 rounded">namasitus.go.id/login</code>).</li>
                        <li>Masukkan <strong>Username / Email</strong> dan <strong>Password</strong>.</li>
                        <li>Klik tombol <strong>Login</strong>. Anda akan diarahkan ke Dashboard ini.</li>
                    </ol>
                </div>
                
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <h3 class="font-bold text-lg mb-3 text-blue-800"><i class="fas fa-user-edit"></i> Update Profil & Ganti Password</h3>
                    <ol class="list-decimal pl-5 space-y-2 text-sm">
                        <li>Klik <strong>Foto Profil / Nama Anda</strong> di pojok kanan atas layar.</li>
                        <li>Sebuah popup (jendela profil) akan muncul.</li>
                        <li>Perbarui data seperti <strong>Nama, Email, No. WA</strong> atau upload foto.</li>
                        <li>Untuk mengganti sandi, ketik pada kolom <strong>Password Baru</strong>.</li>
                        <li>Klik <strong>Simpan Perubahan Akun</strong>.</li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- BAB 2: PANDUAN SESUAI ROLE -->
        <section class="space-y-6">
            <h2 class="text-2xl font-extrabold text-slate-900 border-b-2 border-slate-100 pb-2 flex items-center gap-2">
                <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center text-sm">2</span> 
                Panduan Manajemen Konten
            </h2>
            
            <p class="text-sm">Berikut adalah langkah-langkah untuk mengelola konten website yang menjadi tanggung jawab dan hak akses Anda:</p>

            <div class="space-y-8 mt-4">

                <!-- MODUL STAF / ALL ROLES -->
                <div class="border-l-4 border-indigo-500 pl-5 space-y-3">
                    <h3 class="font-bold text-xl text-indigo-900"><i class="fas fa-file-alt text-indigo-500"></i> Kelola Dokumen & Berkas</h3>
                    <ol class="list-decimal pl-5 space-y-1.5 text-sm">
                        <li>Pilih menu <strong>Dokumen & Berkas</strong> di menu navigasi samping (sidebar).</li>
                        <li>Klik tombol biru <strong>Tambah Dokumen Baru</strong>.</li>
                        <li>Ketik <strong>Judul Dokumen</strong> dan opsional Nomor/Tahun dokumen.</li>
                        <li>Pilih <strong>Kategori Dokumen</strong> (Misal: Renstra, IKU, LAKIP).</li>
                        <li>Klik tombol <strong>Pilih File</strong> dan unggah file berformat PDF atau ZIP (Maksimal 10MB).</li>
                        <li>Klik <strong>Simpan & Unggah Dokumen</strong>.</li>
                    </ol>
                </div>

                <div class="border-l-4 border-amber-500 pl-5 space-y-3">
                    <h3 class="font-bold text-xl text-amber-900"><i class="fas fa-images text-amber-500"></i> Kelola Galeri Foto & Video</h3>
                    <ul class="list-disc pl-5 space-y-2 text-sm">
                        <li><strong>Galeri Foto:</strong> Masuk ke menu <strong>Album Galeri Foto</strong>. Klik Tambah Foto, isikan Judul, dan unggah foto (Maks 5MB, format JPG/PNG).</li>
                        <li><strong>Video Dokumentasi:</strong> Masuk ke menu <strong>Video Dokumentasi</strong>. Klik Tambah Video, isikan Judul, dan masukkan ID/Link YouTube dari video kegiatan tersebut.</li>
                    </ul>
                </div>

                <div class="border-l-4 border-teal-500 pl-5 space-y-3">
                    <h3 class="font-bold text-xl text-teal-900"><i class="fas fa-cogs text-teal-500"></i> Kelola Layanan Publik</h3>
                    <ol class="list-decimal pl-5 space-y-1.5 text-sm">
                        <li>Buka menu <strong>Layanan Publik</strong>.</li>
                        <li>Klik <strong>Tambah Layanan Baru</strong>.</li>
                        <li>Isikan Informasi Layanan (Nama Layanan, Persyaratan, Prosedur, dan Waktu Pelayanan).</li>
                        <li>Pilih ikon yang merepresentasikan layanan.</li>
                        <li>Klik <strong>Simpan</strong>. Anda juga bisa mengatur urutan tampilan layanan dengan klik tombol <em>Naik/Turun</em> pada tabel layanan.</li>
                    </ol>
                </div>

                <!-- MODUL ADMIN & SUPER ADMIN -->
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    
                    <div class="border-l-4 border-purple-500 pl-5 space-y-3 mt-8">
                        <h3 class="font-bold text-xl text-purple-900"><i class="fas fa-newspaper text-purple-500"></i> Kelola Berita & Informasi</h3>
                        <ol class="list-decimal pl-5 space-y-1.5 text-sm">
                            <li>Buka menu <strong>Berita & Informasi</strong>.</li>
                            <li>Klik tombol <strong>Tulis Berita Baru</strong>.</li>
                            <li>Ketik <strong>Judul Berita</strong> dan pilih Kategori (Gunakan "Kategori Baru" jika kategori yang Anda inginkan tidak ada di daftar).</li>
                            <li>Ketik detail isi berita pada kotak editor konten.</li>
                            <li>Unggah <strong>Foto Utama (Thumbnail/Cover)</strong> berformat JPG/PNG.</li>
                            <li>Klik <strong>Simpan Publikasi</strong>.</li>
                        </ol>
                    </div>

                    <div class="border-l-4 border-cyan-500 pl-5 space-y-3">
                        <h3 class="font-bold text-xl text-cyan-900"><i class="fas fa-bars text-cyan-500"></i> Pengaturan Menu Web & Halaman</h3>
                        <ul class="list-disc pl-5 space-y-3 text-sm">
                            <li>
                                <strong>Membuat Halaman Artikel (Custom Page):</strong> Masuk ke menu <em>Kelola Halaman Web > Halaman Profil</em>. 
                                Fitur ini untuk membuat artikel statis (sejarah, visi misi). Salin (Copy) bagian URL-nya setelah disimpan.
                            </li>
                            <li>
                                <strong>Menambahkan Menu Navigasi (Header):</strong> Masuk ke menu <em>Kelola Halaman Web > Menu Navigasi Atas</em>. 
                                Tambahkan judul menu baru dan <em>Paste</em> (tempel) URL halaman yang sudah dibuat sebelumnya.
                                Jika Anda mengatur "Induk Menu", maka menu tersebut akan menjadi Dropdown.
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </section>

        <!-- BAB 3: FITUR KHUSUS SUPER ADMIN -->
        @if(auth()->user()->isSuperAdmin())
        <section class="space-y-4 print:mt-10">
            <h2 class="text-2xl font-extrabold text-rose-700 border-b-2 border-rose-100 pb-2 flex items-center gap-2">
                <span class="bg-rose-600 text-white w-8 h-8 rounded-lg flex items-center justify-center text-sm">3</span> 
                Fitur Eksklusif Super Admin
            </h2>
            
            <p class="text-sm font-semibold text-rose-800">
                Sebagai Super Admin, Anda memegang tanggung jawab tertinggi atas keamanan dan kebersihan database sistem.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
                <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100">
                    <h4 class="font-bold text-rose-900 mb-2"><i class="fas fa-trash-alt"></i> Hak Akses Hapus (Delete) Data</h4>
                    <p class="text-slate-600">Hanya akun Super Admin yang memiliki tombol Hapus (merah) di setiap tabel data (Berita, Dokumen, Menu, Layanan, dll). Admin dan Staf hanya bisa mengedit/menonaktifkan. Pastikan Anda berhati-hati sebelum menghapus karena data akan terhapus permanen dari sistem.</p>
                </div>
                
                <div class="bg-slate-100 rounded-2xl p-5 border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-2"><i class="fas fa-history"></i> Pemantauan Log Aktivitas</h4>
                    <p class="text-slate-600">Menu <strong>Catatan Aktivitas Sistem</strong> merekam setiap jejak rekam pergerakan pengguna (login, edit profil, hapus berita). Anda dapat membersihkan (Clear) log ini menggunakan sandi keamanan khusus jika jumlah data sudah terlalu besar.</p>
                </div>
            </div>
        </section>
        @endif

        <div class="mt-16 pt-8 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400">Dicetak / Diunduh langsung dari Control Panel Website Dinas Perhubungan.</p>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #panduan-content, #panduan-content * {
            visibility: visible;
        }
        #panduan-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
        }
        /* Menghilangkan bayangan dan border untuk versi cetak */
        .shadow-sm, .rounded-3xl, .rounded-2xl {
            box-shadow: none !important;
            border-radius: 0 !important;
            border-color: #e2e8f0 !important; /* warna abu-abu muda */
        }
        .border-l-4 {
            border-left-width: 4px !important;
        }
    }
</style>
@endsection
