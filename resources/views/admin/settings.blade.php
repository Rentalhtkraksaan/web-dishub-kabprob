@extends('admin.layout')

@section('page_title', 'Pengaturan Website & Identitas Instansi')

@section('content')
<div class="w-full space-y-6">
    
    <div>
        <h3 class="text-lg font-bold text-slate-800">Identitas & Kontak Instansi</h3>
        <p class="text-xs text-slate-500">Semua perubahan di sini akan secara otomatis memperbarui logo, nama dinas, email, telepon, dan footer publik</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6 text-xs">
        @csrf

        <!-- Site Title & Description -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-blue-700">
                1. Judul & Meta SEO
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Website (SEO Title)</label>
                    <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kabupaten / Daerah</label>
                    <input type="text" name="regency_name" value="{{ $settings['regency_name'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Resmi Instansi Dinas</label>
                <input type="text" name="agency_name" value="{{ $settings['agency_name'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat Footer</label>
                <textarea name="site_description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Logos & Branding -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-blue-700">
                2. Gambar Logo & Branding (Bisa Upload Berkas Foto & Tautan URL)
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800">1. Logo Topbar Frontend (Utama)</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Upload Berkas Foto:</span>
                        <input type="file" name="file_logo_frontend" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                    </div>
                    <div class="pt-1.5 border-t border-slate-200">
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Atau Link URL Logo:</span>
                        <input type="text" name="logo_frontend" value="{{ $settings['logo_frontend'] ?? '' }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800">2. Favicon Tab Browser (Persegi 1:1)</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Upload Berkas Foto 1:1:</span>
                        <input type="file" name="file_favicon" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                    </div>
                    <div class="pt-1.5 border-t border-slate-200">
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Atau Link URL Favicon:</span>
                        <input type="text" name="favicon" value="{{ $settings['favicon'] ?? '' }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800">3. Logo Footer / Backend Panel</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Upload Berkas Foto:</span>
                        <input type="file" name="file_logo_backend" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                    </div>
                    <div class="pt-1.5 border-t border-slate-200">
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Atau Link URL Logo:</span>
                        <input type="text" name="logo_backend" value="{{ $settings['logo_backend'] ?? '' }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800">3. Logo BerAKHLAK (Pojok Kanan Atas)</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Upload Berkas Foto:</span>
                        <input type="file" name="file_logo_berakhlak" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                    </div>
                    <div class="pt-1.5 border-t border-slate-200">
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Atau Link URL Logo:</span>
                        <input type="text" name="logo_berakhlak" value="{{ $settings['logo_berakhlak'] ?? '' }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800">4. Gambar Kode QR Survei SKM</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Upload Berkas Foto QR:</span>
                        <input type="file" name="file_qr_code_survey" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                    </div>
                    <div class="pt-1.5 border-t border-slate-200">
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Atau Link URL Gambar QR:</span>
                        <input type="text" name="qr_code_survey" value="{{ $settings['qr_code_survey'] ?? '' }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-blue-700">
                3. Informasi Alamat & Kontak
            </h4>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Kantor Lengkap</label>
                <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Telepon Kantor</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Resmi Kantor</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">
                    <i class="fab fa-whatsapp text-emerald-600 me-1"></i> Nomor WhatsApp Halo SAE (Pengaduan Cepat)
                </label>
                <input type="text" name="halo_sae_phone" value="{{ $settings['halo_sae_phone'] ?? '0821 3100 1001' }}" placeholder="0821 3100 1001" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                <span class="text-[11px] text-slate-400 mt-1 block">Nomor WhatsApp resmi Halo SAE untuk kanal pengaduan & aspirasi masyarakat.</span>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Teks Copyright Footer</label>
                <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>
        </div>

        <!-- 4. Pengaturan Survei Kepuasan Masyarakat (SKM) & Kontrol Tampil/Hide -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-blue-700 flex items-center justify-between">
                <span>4. Pengaturan & Kontrol Survei Kepuasan Masyarakat (SKM)</span>
                <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">Kontrol Tampil / Hide</span>
            </h4>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Status Visibilitas Widget & Link Survei</label>
                        <select name="show_survey" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-bold text-xs">
                            <option value="1" {{ ($settings['show_survey'] ?? '1') == '1' ? 'selected' : '' }}>
                                ✅ Tampilkan ke Publik (Aktif di Footer & Halaman)
                            </option>
                            <option value="0" {{ ($settings['show_survey'] ?? '1') == '0' ? 'selected' : '' }}>
                                🚫 Sembunyikan / Hide dari Publik
                            </option>
                        </select>
                        <span class="text-[11px] text-slate-500 mt-1 block">Jika disembunyikan, widget QR code survey di footer tidak akan tampil.</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 mb-1">URL Tautan Pengisian Survei (SUKMA / Form)</label>
                        <input type="text" name="survey_url" value="{{ $settings['survey_url'] ?? 'https://sukma.jatimprov.go.id/' }}" placeholder="https://sukma.jatimprov.go.id/..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none text-xs font-semibold">
                        <span class="text-[11px] text-slate-500 mt-1 block">Tautan tujuan pengisian kuesioner saat masyarakat mengklik tombol survei.</span>
                    </div>
                </div>

        <!-- 5. Media Sosial & Widget Instagram Embed Publik -->
        <div class="space-y-4">
            <h4 class="font-extrabold text-slate-800 text-xs tracking-wider uppercase pb-2 border-b border-slate-100 text-pink-600 flex items-center justify-between">
                <span><i class="fab fa-instagram text-base mr-1"></i> 5. Integrasi Feed & Profile Instagram Publik</span>
                <span class="text-[10px] bg-pink-100 text-pink-800 px-2 py-0.5 rounded font-bold">Homepage Embed</span>
            </h4>

            <div class="p-4 bg-pink-50/40 rounded-2xl border border-pink-200/70 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">
                            Username Instagram Official
                            <span class="text-pink-600 font-normal">(Contoh: dishubkabprobolinggo)</span>
                        </label>
                        @php
                            $rawIg = $settings['instagram_username'] ?? '';
                            if (empty($rawIg) && !empty($settings['instagram_url'])) {
                                if (preg_match('/instagram\.com\/([a-zA-Z0-9_\.]+)/i', $settings['instagram_url'], $m)) {
                                    $rawIg = rtrim($m[1], '/');
                                }
                            }
                            if (empty($rawIg)) {
                                $rawIg = 'dishubkabprobolinggo';
                            }
                        @endphp
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold">
                                @
                            </div>
                            <input type="text" name="instagram_username" value="{{ $rawIg }}" placeholder="dishubkabprobolinggo" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:outline-none text-xs font-bold text-slate-800">
                        </div>
                        <span class="text-[11px] text-slate-500 mt-1 block">Cukup masukkan username akun Instagram instansi. Widget publik di homepage akan otomatis menampilkan foto & feed akun ini.</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Tautan URL Profil Instagram (Otomatis)</label>
                        <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://www.instagram.com/dishubkabprobolinggo/' }}" placeholder="https://www.instagram.com/dishubkabprobolinggo/" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:outline-none text-xs font-semibold">
                        <span class="text-[11px] text-slate-500 mt-1 block">Link penuh profil Instagram instansi yang dibuka saat pengunjung mengklik link.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-md transition-all">
                <i class="fas fa-save me-1.5"></i> Simpan Semua Pengaturan
            </button>
        </div>

    </form>

</div>
@endsection
