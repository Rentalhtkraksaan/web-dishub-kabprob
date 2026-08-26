@extends('admin.layout')

@section('page_title', 'Kelola Album Galeri Foto Kegiatan')

@section('content')
<div class="w-full space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentAlbum: { title: '', description: '', cover_image: '', photos: [] },
    removedPhotos: [],
    openModal(albumObj = null) {
        this.removedPhotos = [];
        if (albumObj) {
            this.editMode = true;
            this.currentAlbum = JSON.parse(JSON.stringify(albumObj));
            if (!Array.isArray(this.currentAlbum.photos)) {
                this.currentAlbum.photos = this.currentAlbum.cover_image ? [this.currentAlbum.cover_image] : [];
            }
        } else {
            this.editMode = false;
            this.currentAlbum = { 
                title: '', 
                description: '', 
                cover_image: '', 
                photos: [] 
            };
        }
        this.showModal = true;
    },
    toggleRemovePhoto(photoUrl) {
        let idx = this.removedPhotos.indexOf(photoUrl);
        if (idx > -1) {
            this.removedPhotos.splice(idx, 1);
        } else {
            this.removedPhotos.push(photoUrl);
        }
    }
}">
    
    <!-- ===== HEADER EXECUTIVE BANNER ===== -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-400/20 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-camera text-amber-400"></i>
                    Photo Gallery & Activity Album Management
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Album Galeri Foto Kegiatan DISHUB</h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium">
                    Atur nama kegiatan, deskripsi, foto sampul (cover), serta unggah banyak foto sekaligus (*multiple photo upload*) di dalam album dokumentasi kegiatan Dinas Perhubungan.
                </p>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('galery') }}" target="_blank" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs rounded-2xl border border-slate-700 flex items-center gap-2 transition-all">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat Halaman Galeri</span>
                </a>
                <button @click="openModal(null)" 
                        class="px-5 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg hover:shadow-amber-500/30 flex items-center justify-center gap-2.5 transition-all hover:scale-105 active:scale-95">
                    <i class="fas fa-plus-circle text-base"></i>
                    <span>➕ Buat Album Kegiatan Baru</span>
                </button>
            </div>
        </div>
        <!-- Background Decorative Element -->
        <i class="fas fa-images absolute -right-6 -bottom-8 opacity-10 text-9xl text-white pointer-events-none"></i>
    </div>

    <!-- ===== STAT CARDS GRID ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Album Kegiatan</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $albums->total() }}</h3>
                <span class="text-[10px] text-amber-600 font-semibold">Tersedia di Menu Publik /galery</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Metode Pengunggahan</p>
                <h3 class="text-2xl font-black text-slate-900">Multiple Upload</h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Bisa Pilih Banyak Foto Sekaligus</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-images"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Fitur Fullscreen</p>
                <h3 class="text-2xl font-black text-blue-600">FsLightbox <span class="text-xs text-slate-400 font-normal">Aktif</span></h3>
                <span class="text-[10px] text-blue-600 font-semibold">Zoom & Slideshow Foto Publik</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-search-plus"></i>
            </div>
        </div>

    </div>

    <!-- ===== ALBUMS TABLE CARD ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-images text-amber-500"></i> Daftar Album Foto Kegiatan DISHUB
            </h2>

            <form action="{{ route('admin.gallery') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama kegiatan..." 
                           class="pl-8 pr-3 py-1.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none w-48 sm:w-64 font-medium">
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.gallery') }}" class="px-2.5 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                        <th class="px-6 py-3.5">Sampul Cover</th>
                        <th class="px-6 py-3.5">Nama Kegiatan / Judul Album</th>
                        <th class="px-6 py-3.5">Jumlah Foto</th>
                        <th class="px-6 py-3.5">Tanggal Dibuat</th>
                        <th class="px-6 py-3.5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($albums as $album)
                        @php
                            $photoCount = is_array($album->photos) ? count($album->photos) : 1;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Cover Image -->
                            <td class="px-6 py-4 shrink-0">
                                <div class="w-28 h-18 rounded-xl overflow-hidden bg-slate-900 border border-slate-200 relative shadow-xs group">
                                    <img src="{{ $album->cover_image ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600' }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/70 text-white font-extrabold text-[9px] flex items-center gap-1">
                                        <i class="far fa-image"></i> {{ $photoCount }}
                                    </span>
                                </div>
                            </td>

                            <!-- Title & Description -->
                            <td class="px-6 py-4 max-w-sm">
                                <div class="font-extrabold text-slate-900 text-xs mb-1">{{ $album->title }}</div>
                                @if($album->description)
                                    <p class="text-slate-500 text-[11px] line-clamp-2 leading-relaxed">
                                        {{ $album->description }}
                                    </p>
                                @endif
                                <div class="font-mono text-[10px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 inline-block mt-1">
                                    /galery/{{ $album->slug }}
                                </div>
                            </td>

                            <!-- Photos Count Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-amber-100 text-amber-900 font-extrabold rounded-xl text-xs border border-amber-200 inline-flex items-center gap-1.5">
                                    <i class="fas fa-images text-amber-600"></i>
                                    <span>{{ $photoCount }} Foto Kegiatan</span>
                                </span>
                            </td>

                            <!-- Created At & Uploader -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-700 text-xs">
                                    <i class="far fa-calendar-alt text-slate-400 mr-1"></i>
                                    {{ $album->created_at ? $album->created_at->translatedFormat('d F Y') : '-' }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Oleh: {{ $album->creator ? $album->creator->name : 'Administrator' }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('galery.detail', $album->slug) }}" target="_blank" 
                                   class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-[11px] inline-flex items-center gap-1" title="Lihat Halaman Album Publik">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                                <button @click="openModal({{ json_encode($album) }})" 
                                        class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Edit Album & Kelola Foto">
                                    <i class="fas fa-edit text-xs"></i> Edit & Kelola Foto
                                </button>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route('admin.gallery.destroy', $album->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus album kegiatan {{ $album->title }} beserta seluruh foto di dalamnya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Hapus Album">
                                            <i class="fas fa-trash-alt text-xs"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-images text-4xl block mb-2 text-slate-300"></i>
                                Belum ada album foto kegiatan yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($albums->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $albums->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    <!-- ===== MODAL FORM (ADD / EDIT ALBUM & MANAGE MULTIPLE PHOTOS) ===== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl space-y-5 border border-slate-100 relative my-8">
            
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? 'Edit Album Kegiatan & Foto' : 'Buat Album Kegiatan Baru'"></h3>
                        <p class="text-xs text-slate-500">Kelola Judul Kegiatan, Deskripsi, Foto Cover Sampul, dan Koleksi Banyak Foto</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/gallery') }}/' + currentAlbum.id : '{{ route('admin.gallery.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <!-- Hidden inputs for removed photos -->
                <template x-for="pUrl in removedPhotos" :key="pUrl">
                    <input type="hidden" name="removed_photos[]" :value="pUrl">
                </template>
                
                <!-- Nama Kegiatan / Judul Album -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kegiatan / Judul Album Foto</label>
                    <input type="text" 
                           name="title" 
                           required 
                           x-model="currentAlbum.title" 
                           placeholder="Contoh: Pemantauan Arus Mudik Lebaran di Terminal Kraksaan / Sosialisasi Safety Driving" 
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none font-bold text-slate-900">
                </div>

                <!-- Deskripsi Kegiatan -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi / Uraian Kegiatan</label>
                    <textarea name="description" 
                              rows="3" 
                              x-model="currentAlbum.description" 
                              placeholder="Tuliskan keterangan mengenai lokasi, waktu, dan pelaksanaan kegiatan ini..." 
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none leading-relaxed text-xs"></textarea>
                </div>

                <!-- Cover Foto Sampul Upload -->
                <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-extrabold text-amber-900 flex items-center gap-1.5">
                            <i class="fas fa-image text-amber-600"></i> Foto Sampul Utamanya (Album Cover)
                        </label>
                        <span class="text-[10px] bg-amber-200 text-amber-900 font-bold px-2 py-0.5 rounded">Tampilan Depan</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                        <div>
                            <input type="file" 
                                   name="cover_file" 
                                   accept="image/*" 
                                   class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Max 5MB)</p>
                        </div>
                        <div>
                            <input type="text" 
                                   name="cover_image" 
                                   x-model="currentAlbum.cover_image" 
                                   placeholder="Atau URL Foto Sampul (https://...)" 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-[11px]">
                        </div>
                    </div>
                </div>

                <!-- Multiple Photos Upload (Banyak Foto Sekaligus) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-extrabold text-slate-800 flex items-center gap-1.5">
                            <i class="fas fa-images text-emerald-600"></i> Unggah Banyak Foto Kegiatan Sekaligus (*Multiple Upload*)
                        </label>
                        <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded">Pilih Banyak Berkas</span>
                    </div>

                    <div>
                        <input type="file" 
                               name="photo_files[]" 
                               multiple 
                               accept="image/*" 
                               class="w-full text-[11px] text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1.5">
                            💡 <strong>Tips:</strong> Tekan tombol <kbd class="px-1.5 py-0.5 bg-slate-200 rounded">Ctrl</kbd> atau <kbd class="px-1.5 py-0.5 bg-slate-200 rounded">Shift</kbd> di keyboard Anda saat memilih foto dari laptop/komputer untuk memilih banyak foto sekaligus!
                        </p>
                    </div>

                    <div class="pt-2 border-t border-slate-200/80">
                        <label class="block font-bold text-slate-700 mb-1">Atau Masukkan Tautan/URL Foto Tambahan (Satu URL Per Baris)</label>
                        <textarea name="photo_urls" 
                                  rows="2" 
                                  placeholder="https://...&#10;https://..." 
                                  class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none font-mono text-[11px]"></textarea>
                    </div>
                </div>

                <!-- Existing Photos Grid Box (Mode Edit) -->
                <template x-if="editMode && currentAlbum.photos && currentAlbum.photos.length > 0">
                    <div class="space-y-2 p-4 bg-slate-100/70 rounded-2xl border border-slate-200">
                        <div class="flex items-center justify-between pb-1">
                            <span class="font-extrabold text-slate-800 text-xs">
                                📸 Koleksi Foto Saat Ini (<span x-text="currentAlbum.photos.length"></span> Foto)
                            </span>
                            <span class="text-[10px] text-slate-500">Klik foto untuk mencentang hapus</span>
                        </div>

                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5 max-h-[220px] overflow-y-auto p-1">
                            <template x-for="(pUrl, pIdx) in currentAlbum.photos" :key="pIdx">
                                <div @click="toggleRemovePhoto(pUrl)" 
                                     class="relative aspect-video rounded-xl overflow-hidden border-2 cursor-pointer transition-all shadow-xs group"
                                     :class="removedPhotos.includes(pUrl) ? 'border-rose-500 opacity-40 grayscale' : 'border-slate-300 hover:border-amber-500'">
                                    <img :src="pUrl" alt="Photo" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="fas text-white" :class="removedPhotos.includes(pUrl) ? 'fa-undo' : 'fa-trash-alt'"></i>
                                    </div>
                                    <template x-if="removedPhotos.includes(pUrl)">
                                        <span class="absolute inset-0 bg-rose-900/60 text-white font-extrabold text-[9px] flex items-center justify-center gap-1 uppercase tracking-wider">
                                            <i class="fas fa-times-circle"></i> Dihapus
                                        </span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold rounded-xl shadow-lg shadow-amber-500/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Album Kegiatan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
