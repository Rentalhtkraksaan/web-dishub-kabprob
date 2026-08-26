@extends('admin.layout')

@section('page_title', 'Kelola Video Dokumentasi & Kegiatan')

@section('content')
<div class="w-full space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentVideo: { title: '', video_url: '', thumbnail_url: '', description: '', published_at: '' },
    youtubePreview: '',
    updateYoutubePreview(url) {
        if (!url) {
            this.youtubePreview = '';
            return;
        }
        let match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/);
        if (match && match[1]) {
            this.youtubePreview = 'https://img.youtube.com/vi/' + match[1] + '/hqdefault.jpg';
        } else {
            this.youtubePreview = '';
        }
    },
    openModal(videoObj = null) {
        if (videoObj) {
            this.editMode = true;
            this.currentVideo = JSON.parse(JSON.stringify(videoObj));
            if (this.currentVideo.published_at) {
                this.currentVideo.published_at = this.currentVideo.published_at.substring(0, 10);
            }
            this.updateYoutubePreview(this.currentVideo.video_url);
        } else {
            this.editMode = false;
            this.currentVideo = { 
                title: '', 
                video_url: '', 
                thumbnail_url: '', 
                description: '', 
                published_at: new Date().toISOString().substring(0, 10) 
            };
            this.youtubePreview = '';
        }
        this.showModal = true;
    }
}">
    
    <!-- ===== HEADER EXECUTIVE BANNER ===== -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-rose-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-400/20 text-rose-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fab fa-youtube text-rose-500"></i>
                    Video Streaming & Gallery Management
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Video Dokumentasi & Kegiatan DISHUB</h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium">
                    Kelola materi dokumentasi video YouTube resmi kegiatan dinas, sosialisasi keselamatan jalan, uji kelaikan berkala KIR, hingga event transportasi umum dengan fitur pratinjau instan (*Instant Lightbox Play*).
                </p>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('video') }}" target="_blank" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs rounded-2xl border border-slate-700 flex items-center gap-2 transition-all">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat Halaman Publik</span>
                </a>
                <button @click="openModal(null)" 
                        class="px-5 py-3 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg hover:shadow-rose-600/30 flex items-center justify-center gap-2.5 transition-all hover:scale-105 active:scale-95">
                    <i class="fas fa-plus-circle text-base"></i>
                    <span>➕ Tambah Video Baru</span>
                </button>
            </div>
        </div>
        <!-- Background Decorative Element -->
        <i class="fab fa-youtube absolute -right-6 -bottom-8 opacity-10 text-9xl text-white pointer-events-none"></i>
    </div>

    <!-- ===== STAT CARDS GRID ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Video Publik</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $videos->total() }}</h3>
                <span class="text-[10px] text-rose-600 font-semibold">Tersedia di Menu Publik /video</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold border border-rose-100">
                <i class="fab fa-youtube"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sumber YouTube Embed</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $videos->count() }}</h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Kompatibel Auto-Play Chrome</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-play-circle"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Pemutar</p>
                <h3 class="text-2xl font-black text-blue-600">FsLightbox <span class="text-xs text-slate-400 font-normal">Aktif</span></h3>
                <span class="text-[10px] text-blue-600 font-semibold">Popup Populer Tanpa Reload</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-expand"></i>
            </div>
        </div>

    </div>

    <!-- ===== VIDEOS TABLE CARD ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-video text-rose-600"></i> Daftar Video Dokumentasi Resmi
            </h2>

            <form action="{{ route('admin.videos') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari judul video..." 
                           class="pl-8 pr-3 py-1.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none w-48 sm:w-64 font-medium">
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.videos') }}" class="px-2.5 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                        <th class="px-6 py-3.5">Thumbnail Video</th>
                        <th class="px-6 py-3.5">Judul Video & Slug</th>
                        <th class="px-6 py-3.5">Tautan YouTube</th>
                        <th class="px-6 py-3.5">Tanggal Publikasi</th>
                        <th class="px-6 py-3.5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($videos as $video)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Thumbnail (16:9 Aspect Ratio) -->
                            <td class="px-6 py-4 shrink-0">
                                <div class="w-32 h-20 rounded-xl overflow-hidden bg-slate-900 border border-slate-200 relative shadow-xs group">
                                    <img src="{{ $video->effective_thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                        <div class="w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow-sm">
                                            <i class="fas fa-play ml-0.5"></i>
                                        </div>
                                    </div>
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/70 text-white font-mono text-[9px]">16:9</span>
                                </div>
                            </td>

                            <!-- Title & Description -->
                            <td class="px-6 py-4 max-w-sm">
                                <div class="font-extrabold text-slate-900 text-xs mb-1">{{ $video->title }}</div>
                                @if($video->description)
                                    <p class="text-slate-500 text-[11px] line-clamp-2 leading-relaxed">
                                        {{ $video->description }}
                                    </p>
                                @endif
                            </td>

                            <!-- YouTube URL Link -->
                            <td class="px-6 py-4">
                                <a href="{{ $video->video_url }}" target="_blank" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold rounded-xl border border-rose-200 inline-flex items-center gap-1.5 transition-colors text-[11px]">
                                    <i class="fab fa-youtube text-rose-600 text-xs"></i>
                                    <span>Buka YouTube</span>
                                    <i class="fas fa-external-link-alt text-[9px]"></i>
                                </a>
                            </td>

                            <!-- Published At -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-700 text-xs">
                                    <i class="far fa-calendar-alt text-slate-400 mr-1"></i>
                                    {{ $video->published_at ? $video->published_at->translatedFormat('d F Y') : '-' }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Oleh: {{ $video->creator ? $video->creator->name : 'Administrator' }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <button @click="openModal({{ json_encode($video) }})" 
                                        class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Edit Video">
                                    <i class="fas fa-edit text-xs"></i> Edit
                                </button>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Hapus Video">
                                            <i class="fas fa-trash-alt text-xs"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-film text-4xl block mb-2 text-slate-300"></i>
                                Belum ada video dokumentasi yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($videos->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $videos->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    <!-- ===== MODAL FORM (ADD / EDIT VIDEO) ===== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-5 border border-slate-100 relative my-8">
            
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-bold">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? 'Edit Video Dokumentasi' : 'Tambah Video Baru'"></h3>
                        <p class="text-xs text-slate-500">Kelola Judul, Tautan YouTube, Thumbnail, dan Tanggal Publikasi</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/videos') }}/' + currentVideo.id : '{{ route('admin.videos.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <!-- Judul Video -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Video Dokumentasi</label>
                    <input type="text" 
                           name="title" 
                           required 
                           x-model="currentVideo.title" 
                           placeholder="Contoh: Kabupaten Probolinggo Istimewa (Official Music Video)" 
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-600 focus:outline-none font-bold text-slate-900">
                </div>

                <!-- URL Video YouTube -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>URL Tautan Video YouTube</span>
                        <span class="text-[10px] text-rose-600 font-bold">Wajib Diisi</span>
                    </label>
                    <input type="url" 
                           name="video_url" 
                           required 
                           x-model="currentVideo.video_url" 
                           @input="updateYoutubePreview($event.target.value)"
                           placeholder="https://www.youtube.com/watch?v=TMpwg9_M6xY" 
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-600 focus:outline-none font-mono text-[11px] text-slate-900">
                    <p class="text-[10px] text-slate-400 mt-1">Dapat berupa format: https://www.youtube.com/watch?v=... atau https://youtu.be/...</p>
                </div>

                <!-- Live YouTube Thumbnail Preview Box -->
                <template x-if="youtubePreview || currentVideo.thumbnail_url">
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                        <img :src="youtubePreview || currentVideo.thumbnail_url" alt="Preview Thumbnail" class="w-24 h-14 object-cover rounded-xl border border-slate-300 bg-slate-900 shrink-0">
                        <div class="text-[11px] text-slate-600">
                            <span class="font-bold text-emerald-600 flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Thumbnail Otomatis Terdeteksi
                            </span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Thumbnail HQ dari YouTube siap ditampilkan pada halaman publik.</span>
                        </div>
                    </div>
                </template>

                <!-- Custom Thumbnail Upload (Optional) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2">
                    <label class="block font-bold text-slate-800">
                        <i class="fas fa-image text-amber-500 mr-1"></i> Upload Thumbnail Kustom (Opsional)
                    </label>
                    <input type="file" 
                           name="thumbnail_file" 
                           accept="image/*" 
                           class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Kosongkan jika ingin menggunakan thumbnail cover bawaan dari YouTube secara otomatis.</p>
                </div>

                <!-- Tanggal Publikasi -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Publikasi Video</label>
                    <input type="date" 
                           name="published_at" 
                           x-model="currentVideo.published_at" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-600 focus:outline-none text-xs">
                </div>

                <!-- Deskripsi Singkat -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi / Uraian Singkat Video</label>
                    <textarea name="description" 
                              rows="3" 
                              x-model="currentVideo.description" 
                              placeholder="Tuliskan keterangan singkat mengenai materi video ini..." 
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-600 focus:outline-none text-xs"></textarea>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl shadow-lg shadow-rose-600/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Video</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
