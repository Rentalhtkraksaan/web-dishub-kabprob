@extends('admin.layout')

@section('page_title', 'Halaman Profil & Layanan Publik')

@section('content')
<div class="w-full space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentPage: {},
    isVisiMisiMode: false,
    isTugasFungsiMode: false,
    visiItems: [''],
    misiItems: [''],
    tugasInput: '',
    fungsiItems: [''],
    openModal(pageObj = null) {
        if (pageObj) {
            this.editMode = true;
            this.currentPage = JSON.parse(JSON.stringify(pageObj));
        } else {
            this.editMode = false;
            this.currentPage = { title: '', slug: '', content: '', image_url: '', pdf_url: '', is_published: true };
        }
        
        let slug = this.currentPage.slug || '';
        let title = (this.currentPage.title || '').toLowerCase();

        // Mode Visi Misi
        if (slug === 'visi-misi' || title.includes('visi')) {
            this.isVisiMisiMode = true;
            this.isTugasFungsiMode = false;
            this.parseContentToVisiMisi(this.currentPage.content || '');
        } 
        // Mode Tugas & Fungsi
        else if (slug === 'tugas-dan-fungsi' || title.includes('tugas')) {
            this.isVisiMisiMode = false;
            this.isTugasFungsiMode = true;
            this.parseContentToTugasFungsi(this.currentPage.content || '');
        } 
        else {
            this.isVisiMisiMode = false;
            this.isTugasFungsiMode = false;
        }

        this.showModal = true;
    },
    parseContentToVisiMisi(content) {
        let clean = content.replace(/<[^>]*>?/gm, '');
        let visiMatch = clean.match(/Visi:\s*(.*?)(?=Misi:|$)/is);
        let misiMatch = clean.match(/Misi:\s*(.*)/is);

        if (visiMatch && visiMatch[1].trim()) {
            let splitVisi = visiMatch[1].trim().split(/\d+\.\s*/).map(s => s.trim()).filter(Boolean);
            if (splitVisi.length > 0) {
                this.visiItems = splitVisi;
            } else {
                this.visiItems = [visiMatch[1].trim()];
            }
        } else {
            this.visiItems = ['Terwujudnya Sistem Transportasi dan Lalu Lintas Kabupaten Probolinggo yang Handal, Safe, Tertib, dan Terintegrasi.'];
        }

        if (misiMatch && misiMatch[1].trim()) {
            let splitMisi = misiMatch[1].trim().split(/\d+\.\s*/).map(s => s.trim()).filter(Boolean);
            if (splitMisi.length > 0) {
                this.misiItems = splitMisi;
            } else {
                this.misiItems = [''];
            }
        } else {
            this.misiItems = [
                'Meningkatkan keselamatan dan ketertiban lalu lintas jalan.',
                'Memperkuat kualitas pelayanan pengujian kendaraan bermotor.',
                'Membangun dan merawat prasarana penerangan jalan umum & rambu keselamatan.'
            ];
        }
    },
    parseContentToTugasFungsi(content) {
        let clean = content.replace(/<[^>]*>?/gm, '');
        let tugasMatch = clean.match(/Tugas:\s*(.*?)(?=Fungsi:|$)/is);
        let fungsiMatch = clean.match(/Fungsi:\s*(.*)/is);

        if (tugasMatch && tugasMatch[1].trim()) {
            this.tugasInput = tugasMatch[1].trim();
        } else if (!fungsiMatch && clean.trim()) {
            this.tugasInput = clean.trim();
        } else {
            this.tugasInput = 'Dinas Perhubungan mempunyai tugas membantu Bupati melaksanakan urusan pemerintahan daerah di bidang Perhubungan.';
        }

        if (fungsiMatch && fungsiMatch[1].trim()) {
            let splitFungsi = fungsiMatch[1].trim().split(/\d+\.\s*/).map(s => s.trim()).filter(Boolean);
            if (splitFungsi.length > 0) {
                this.fungsiItems = splitFungsi;
            } else {
                this.fungsiItems = [''];
            }
        } else {
            this.fungsiItems = [
                'Perumusan kebijakan teknis di bidang lalu lintas, angkutan, sarana dan prasarana transportasi.',
                'Pelaksanaan tugas dukungan teknis di bidang keselamatan lalu lintas dan kelaikan kendaraan bermotor.',
                'Pengelolaan dan pemeliharaan perlengkapan jalan, penerangan jalan umum, serta fasilitas perhubungan.'
            ];
        }
    },
    addVisiRow() {
        this.visiItems.push('');
    },
    removeVisiRow(index) {
        if (this.visiItems.length > 1) {
            this.visiItems.splice(index, 1);
        } else {
            this.visiItems = [''];
        }
    },
    addMisiRow() {
        this.misiItems.push('');
    },
    removeMisiRow(index) {
        if (this.misiItems.length > 1) {
            this.misiItems.splice(index, 1);
        } else {
            this.misiItems = [''];
        }
    },
    addFungsiRow() {
        this.fungsiItems.push('');
    },
    removeFungsiRow(index) {
        if (this.fungsiItems.length > 1) {
            this.fungsiItems.splice(index, 1);
        } else {
            this.fungsiItems = [''];
        }
    },
    prepareSave() {
        if (this.isVisiMisiMode) {
            let compiled = 'Visi:\n';
            let validVisi = this.visiItems.map(v => v.trim()).filter(Boolean);
            validVisi.forEach((v, idx) => {
                compiled += (idx + 1) + '. ' + v + '\n';
            });

            compiled += '\nMisi:\n';
            let validMisi = this.misiItems.map(m => m.trim()).filter(Boolean);
            validMisi.forEach((m, idx) => {
                compiled += (idx + 1) + '. ' + m + '\n';
            });
            this.currentPage.content = compiled;
        } else if (this.isTugasFungsiMode) {
            let compiled = 'Tugas:\n' + (this.tugasInput ? this.tugasInput.trim() : '') + '\n\nFungsi:\n';
            let validFungsi = this.fungsiItems.map(f => f.trim()).filter(Boolean);
            validFungsi.forEach((f, idx) => {
                compiled += (idx + 1) + '. ' + f + '\n';
            });
            this.currentPage.content = compiled;
        }
    }
}">
    
    <!-- ===== HEADER EXECUTIVE BANNER ===== -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-rose-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-400/20 text-rose-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-file-signature text-rose-400"></i>
                    Static Content & Public Service Pages
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Halaman Profil & Layanan DISHUB</h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium">
                    Kelola materi Halaman Profil (Visi Misi, Struktur Organisasi, Tugas & Fungsi) dan Halaman Layanan Publik (Uji KIR, Pengujian LLAJ, Standar Pelayanan) lengkap dengan Banner 16:9, Lampiran PDF, dan Caption Teks.
                </p>
            </div>
            
            <button @click="openModal(null)" 
                    class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg hover:shadow-emerald-500/30 flex items-center justify-center gap-2.5 transition-all shrink-0 hover:scale-105 active:scale-95">
                <i class="fas fa-plus-circle text-base"></i>
                <span>➕ Buat Halaman Baru</span>
            </button>
        </div>
        <!-- Background Decorative Element -->
        <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-rose-600/10 to-transparent pointer-events-none"></div>
        <i class="fas fa-file-invoice absolute -right-6 -bottom-8 opacity-10 text-9xl text-white pointer-events-none"></i>
    </div>

    <!-- ===== STAT CARDS GRID ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Halaman Publik</p>
                <h3 class="text-2xl font-black text-slate-900">{{ count($pages) }}</h3>
                <span class="text-[10px] text-blue-600 font-semibold">Profil & Layanan</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-file-lines"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Berbanner Foto 16:9</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $pages->whereNotNull('image_url')->count() }}</h3>
                <span class="text-[10px] text-amber-600 font-semibold">Media Banner Visual</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-image"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Berdokumen Lampiran PDF</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $pages->whereNotNull('pdf_url')->count() }}</h3>
                <span class="text-[10px] text-rose-600 font-semibold">Berkas Unduhan PDF</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold border border-rose-100">
                <i class="fas fa-file-pdf"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Publikasi</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $pages->where('is_published', true)->count() }} <span class="text-xs text-slate-400 font-normal">Aktif</span></h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Tayang di Website Utama</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-globe"></i>
            </div>
        </div>

    </div>

    <!-- ===== QUICK VISI MISI ACTION CARD ===== -->
    @php
        $visiMisiPage = $pages->firstWhere('slug', 'visi-misi');
        $tugasFungsiPage = $pages->firstWhere('slug', 'tugas-dan-fungsi');
    @endphp
    @if($visiMisiPage)
        <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent rounded-2xl p-5 border border-amber-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl font-bold shrink-0 border border-amber-500/40">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-amber-500/20 text-amber-300 font-extrabold text-[10px] uppercase">
                        <i class="fas fa-star text-[9px]"></i> Kelola Visi & Misi Instansi (Bisa Tambah Baris Visi & Misi)
                    </div>
                    <h3 class="text-sm font-extrabold text-white mt-1">Visi & Misi Perhubungan</h3>
                    <p class="text-xs text-slate-400">Tambah Baris Visi dan Tambah Baris Misi secara dinamis tanpa perlu mengetik kode HTML.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('page', 'visi-misi') }}" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700">
                    <i class="fas fa-external-link-alt text-[10px]"></i> Lihat Halaman Umum
                </a>
                <button @click="openModal({{ json_encode($visiMisiPage) }})" 
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-xl text-xs font-extrabold transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit & Tambah Baris Visi/Misi
                </button>
            </div>
        </div>
    @endif

    @if($tugasFungsiPage)
        <div class="bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent rounded-2xl p-5 border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl font-bold shrink-0 border border-emerald-500/40">
                    <i class="fas fa-tasks"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 font-extrabold text-[10px] uppercase">
                        <i class="fas fa-check-circle text-[9px]"></i> Kelola Tugas & Fungsi Instansi (Lengkap Foto & PDF)
                    </div>
                    <h3 class="text-sm font-extrabold text-white mt-1">Tugas dan Fungsi DISHUB</h3>
                    <p class="text-xs text-slate-400">Sunting Tugas Utama, Tambah Baris Poin Fungsi, Upload Foto Banner 16:9 & Dokumen PDF Lampiran.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('page', 'tugas-dan-fungsi') }}" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700">
                    <i class="fas fa-external-link-alt text-[10px]"></i> Lihat Halaman Umum
                </a>
                <button @click="openModal({{ json_encode($tugasFungsiPage) }})" 
                        class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 rounded-xl text-xs font-extrabold transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Tugas, Foto & PDF
                </button>
            </div>
        </div>
    @endif

    <!-- ===== PAGES TABLE CARD ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-layer-group text-blue-600"></i> Daftar Halaman Profil & Layanan Publik
            </h2>
            <span class="text-xs text-slate-400 font-medium">Total: {{ count($pages) }} Halaman</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                        <th class="px-6 py-3.5">Banner Foto (16:9)</th>
                        <th class="px-6 py-3.5">Judul & Slug URL</th>
                        <th class="px-6 py-3.5">Lampiran Berkas PDF</th>
                        <th class="px-6 py-3.5">Ringkasan Tulisan / Caption</th>
                        <th class="px-6 py-3.5">Status Publikasi</th>
                        <th class="px-6 py-3.5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Banner Image (16:9 Aspect Ratio) -->
                            <td class="px-6 py-4 shrink-0">
                                <div class="w-28 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 relative shadow-xs group">
                                    @if($page->image_url)
                                        <img src="{{ $page->image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/60 text-white font-mono text-[9px]">16:9</span>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                            <i class="fas fa-image text-lg"></i>
                                            <span class="text-[9px] font-semibold">Tanpa Foto</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Title & Slug -->
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900 text-xs mb-1">{{ $page->title }}</div>
                                <div class="font-mono text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 inline-block">
                                    /halaman/{{ $page->slug }}
                                </div>
                            </td>

                            <!-- PDF File Attachment -->
                            <td class="px-6 py-4">
                                @if($page->pdf_url)
                                    <a href="{{ $page->pdf_url }}" target="_blank" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold rounded-xl border border-rose-200 inline-flex items-center gap-1.5 transition-colors text-[11px]">
                                        <i class="fas fa-file-pdf text-rose-600 text-xs"></i>
                                        <span>Unduh PDF</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">- Tidak Ada PDF -</span>
                                @endif
                            </td>

                            <!-- Content Preview / Caption -->
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-slate-700 line-clamp-2 leading-relaxed text-[11px]">
                                    {{ Str::limit(strip_tags($page->content), 90) }}
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.pages.toggle_publish', $page->id) }}" method="POST">
                                    @csrf
                                    @if($page->is_published)
                                        <button type="submit" class="px-2.5 py-1 rounded-xl bg-emerald-100 text-emerald-800 font-extrabold text-[10px] border border-emerald-200 inline-flex items-center gap-1 hover:bg-emerald-200 transition-colors shadow-xs" title="Klik untuk ubah ke Draf (Tidak Tayang)">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                            Tayang (Published)
                                        </button>
                                    @else
                                        <button type="submit" class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-600 font-extrabold text-[10px] border border-slate-300 inline-flex items-center gap-1 hover:bg-slate-200 transition-colors" title="Klik untuk Terbitkan (Tayang)">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Draf (Tidak Tayang)
                                        </button>
                                    @endif
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('page', $page->slug) }}" target="_blank" 
                                   class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-[11px] inline-flex items-center gap-1" title="Pratinjau Halaman">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                                <button @click="openModal({{ json_encode($page) }})" 
                                        class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Edit Halaman">
                                    <i class="fas fa-edit text-xs"></i> Edit
                                </button>
                                @if(auth()->user()->isSuperAdmin() && !in_array($page->slug, ['struktur-organisasi', 'visi-misi', 'tugas-dan-fungsi']))
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman {{ $page->title }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Hapus Halaman">
                                            <i class="fas fa-trash-alt text-xs"></i> Hapus
                                        </button>
                                    </form>
                                @elseif(in_array($page->slug, ['struktur-organisasi', 'visi-misi', 'tugas-dan-fungsi']))
                                    <span class="px-2.5 py-1.5 bg-slate-100 text-slate-400 font-bold rounded-xl text-[10px] cursor-not-allowed" title="Halaman Utama Sistem (Tidak dapat dihapus)">
                                        <i class="fas fa-lock text-[9px]"></i> Halaman Inti
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                <i class="fas fa-file-circle-xmark text-4xl block mb-2 text-slate-300"></i>
                                Belum ada halaman profil atau layanan yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== MODAL FORM (ADD / EDIT PAGE WITH DYNAMIC VISI MISI & TUGAS FUNGSI ROWS) ===== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl space-y-5 border border-slate-100 relative my-8">
            
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-file-pen"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? 'Edit Halaman Profil & Layanan' : 'Tambah Halaman Baru'"></h3>
                        <p class="text-xs text-slate-500">Kelola Judul, Foto Banner 16:9, Berkas PDF, serta Teks Profil & Poin</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/pages') }}/' + currentPage.id : '{{ route('admin.pages.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  @submit="prepareSave()"
                  class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <!-- Hidden content field compiled when submitting -->
                <input type="hidden" name="content" x-model="currentPage.content">
                
                <!-- Judul Halaman -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Halaman Profil / Layanan</label>
                    <input type="text" 
                           name="title" 
                           required 
                           x-model="currentPage.title" 
                           placeholder="Contoh: Pendaftaran Uji Berkala KIR / Tugas dan Fungsi DISHUB" 
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-extrabold text-slate-900">
                </div>

                <!-- 16:9 Banner Image Upload Section -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-extrabold text-slate-800 flex items-center gap-1.5">
                            <i class="fas fa-image text-amber-500"></i> Upload Foto Banner (Rasio Aspect 16:9)
                        </label>
                        <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded">Rasio Ideal 16:9</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                        <div>
                            <input type="file" 
                                   name="image_file" 
                                   accept="image/*" 
                                   class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Max 3MB). Rekomendasi: 1280x720 / 1920x1080</p>
                        </div>
                        <div>
                            <input type="text" 
                                   name="image_url" 
                                   x-model="currentPage.image_url" 
                                   placeholder="Atau masukkan URL Foto (https://...)" 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none text-[11px]">
                        </div>
                    </div>

                    <template x-if="currentPage.image_url">
                        <div class="mt-2 flex items-center gap-2 p-2 bg-amber-50 rounded-xl border border-amber-200/80 text-[11px] text-amber-900">
                            <input type="checkbox" name="remove_image" value="1" id="remove_image_cb" class="rounded text-amber-600 focus:ring-amber-500">
                            <label for="remove_image_cb" class="font-bold cursor-pointer flex items-center gap-1 text-rose-700">
                                <i class="fas fa-trash-alt"></i> Centang untuk Hapus Foto Banner saat ini
                            </label>
                        </div>
                    </template>
                </div>

                <!-- PDF File Upload Section -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-extrabold text-slate-800 flex items-center gap-1.5">
                            <i class="fas fa-file-pdf text-rose-600"></i> Upload Berkas Lampiran PDF (PDF Document)
                        </label>
                        <span class="text-[10px] bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded">Dokumen Resmi PDF</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                        <div>
                            <input type="file" 
                                   name="pdf_file" 
                                   accept="application/pdf" 
                                   class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Format PDF (Max 10MB)</p>
                        </div>
                        <div>
                            <input type="text" 
                                   name="pdf_url" 
                                   x-model="currentPage.pdf_url" 
                                   placeholder="Atau URL Dokumen PDF (https://...)" 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none text-[11px]">
                        </div>
                    </div>

                    <template x-if="currentPage.pdf_url">
                        <div class="mt-2 flex items-center gap-2 p-2 bg-rose-50 rounded-xl border border-rose-200/80 text-[11px] text-rose-900">
                            <input type="checkbox" name="remove_pdf" value="1" id="remove_pdf_cb" class="rounded text-rose-600 focus:ring-rose-500">
                            <label for="remove_pdf_cb" class="font-bold cursor-pointer flex items-center gap-1 text-rose-700">
                                <i class="fas fa-trash-alt"></i> Centang untuk Hapus Berkas PDF saat ini
                            </label>
                        </div>
                    </template>
                </div>

                <!-- Status Publikasi Toggle Switch -->
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <label class="block font-bold text-slate-800 text-xs">Status Publikasi Halaman</label>
                        <p class="text-[10px] text-slate-500">Centang agar halaman langsung Diterbitkan (Tayang) di website publik.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" :checked="currentPage.is_published || !editMode" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- DYNAMIC VISI MISI INPUT SECTION -->
                <template x-if="isVisiMisiMode">
                    <div class="space-y-4 bg-amber-50/50 p-4 rounded-2xl border border-amber-200">
                        <div class="flex items-center justify-between pb-2 border-b border-amber-200/80">
                            <h4 class="font-extrabold text-amber-900 text-xs flex items-center gap-1.5">
                                <i class="fas fa-bullseye text-amber-500"></i> Form Khusus Visi & Misi Instansi
                            </h4>
                            <span class="text-[10px] bg-amber-200 text-amber-900 font-bold px-2 py-0.5 rounded">Teks Biasa (Tanpa HTML)</span>
                        </div>

                        <!-- Dynamic Visi Rows Adder -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-slate-800">1. Daftar Poin Visi Utama (Bisa Tambah Baris Visi)</label>
                                <button type="button" @click="addVisiRow()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-extrabold rounded-xl text-[11px] transition-all flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-plus-circle"></i> + Tambah Baris Visi
                                </button>
                            </div>

                            <div class="space-y-2 max-h-[160px] overflow-y-auto pr-1">
                                <template x-for="(visiItem, vIdx) in visiItems" :key="vIdx">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-2 bg-blue-600 text-white font-extrabold rounded-xl text-xs shrink-0 w-8 text-center" x-text="vIdx + 1"></span>
                                        <input type="text" 
                                               x-model="visiItems[vIdx]" 
                                               placeholder="Tuliskan poin Visi..." 
                                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-xs text-slate-900 font-semibold">
                                        <button type="button" @click="removeVisiRow(vIdx)" class="p-2 text-rose-500 hover:bg-rose-100 rounded-xl transition-colors shrink-0" title="Hapus Baris Visi Ini">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Dynamic Misi Rows Adder -->
                        <div class="space-y-2 pt-2 border-t border-amber-200/80">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-slate-800">2. Daftar Poin Misi Strategis (Bisa Tambah Baris Misi)</label>
                                <button type="button" @click="addMisiRow()" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl text-[11px] transition-all flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-plus-circle"></i> + Tambah Baris Misi
                                </button>
                            </div>

                            <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1">
                                <template x-for="(misiItem, mIdx) in misiItems" :key="mIdx">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-2 bg-amber-500 text-slate-950 font-extrabold rounded-xl text-xs shrink-0 w-8 text-center" x-text="mIdx + 1"></span>
                                        <input type="text" 
                                               x-model="misiItems[mIdx]" 
                                               placeholder="Tuliskan poin Misi..." 
                                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs text-slate-900 font-semibold">
                                        <button type="button" @click="removeMisiRow(mIdx)" class="p-2 text-rose-500 hover:bg-rose-100 rounded-xl transition-colors shrink-0" title="Hapus Baris Misi Ini">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- DYNAMIC TUGAS & FUNGSI INPUT SECTION -->
                <template x-if="isTugasFungsiMode">
                    <div class="space-y-4 bg-emerald-50/50 p-4 rounded-2xl border border-emerald-200">
                        <div class="flex items-center justify-between pb-2 border-b border-emerald-200/80">
                            <h4 class="font-extrabold text-emerald-900 text-xs flex items-center gap-1.5">
                                <i class="fas fa-tasks text-emerald-600"></i> Form Khusus Tugas & Fungsi Instansi
                            </h4>
                            <span class="text-[10px] bg-emerald-200 text-emerald-900 font-bold px-2 py-0.5 rounded">Teks Biasa (Tanpa HTML)</span>
                        </div>

                        <!-- Uraian Tugas Utama -->
                        <div>
                            <label class="block font-bold text-slate-800 mb-1 flex items-center justify-between">
                                <span>1. Uraian Tugas Utama DISHUB</span>
                                <span class="text-[10px] text-emerald-600 font-bold">Wajib Diisi</span>
                            </label>
                            <textarea x-model="tugasInput" 
                                      rows="3" 
                                      placeholder="Tuliskan uraian Tugas Utama di sini..." 
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-xs text-slate-900 font-semibold leading-relaxed"></textarea>
                        </div>

                        <!-- Dynamic Fungsi Rows Adder -->
                        <div class="space-y-2 pt-2 border-t border-emerald-200/80">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-slate-800">2. Daftar Poin Fungsi Strategis (Bisa Tambah Baris)</label>
                                <button type="button" @click="addFungsiRow()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-xl text-[11px] transition-all flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-plus-circle"></i> + Tambah Baris Fungsi
                                </button>
                            </div>

                            <div class="space-y-2 max-h-[220px] overflow-y-auto pr-1">
                                <template x-for="(fungsiItem, fIdx) in fungsiItems" :key="fIdx">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-2 bg-emerald-600 text-white font-extrabold rounded-xl text-xs shrink-0 w-8 text-center" x-text="fIdx + 1"></span>
                                        <input type="text" 
                                               x-model="fungsiItems[fIdx]" 
                                               placeholder="Tuliskan poin fungsi di sini..." 
                                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-xs text-slate-900 font-semibold">
                                        <button type="button" @click="removeFungsiRow(fIdx)" class="p-2 text-rose-500 hover:bg-rose-100 rounded-xl transition-colors shrink-0" title="Hapus Baris Fungsi Ini">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Standard Content Textarea for Other Pages -->
                <template x-if="!isVisiMisiMode && !isTugasFungsiMode">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1 flex items-center justify-between">
                            <span>Isi Konten Teks / Caption Halaman</span>
                            <span class="text-[10px] text-emerald-600 font-bold"><i class="fas fa-check-circle"></i> Mudah: Cukup Ketik Teks Biasa</span>
                        </label>
                        <textarea name="content" 
                                  rows="6" 
                                  x-model="currentPage.content" 
                                  placeholder="Tuliskan deskripsi lengkap, persyaratan layanan, atau uraian..." 
                                  class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none leading-relaxed text-xs font-sans text-slate-900"></textarea>
                    </div>
                </template>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-extrabold rounded-xl shadow-lg shadow-blue-700/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Halaman Profil/Layanan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
