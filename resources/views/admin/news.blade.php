@extends('admin.layout')

@section('page_title', 'Berita & Informasi DISHUB')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    currentNews: {}, 
    isCustomCat: false,
    isSubmitDisabled: false,
    openAdd() {
        this.editMode = false;
        this.isCustomCat = false;
        this.currentNews = { category: '{{ !empty($categories) ? array_values($categories)[0] : 'Berita' }}', published_at: '{{ date('Y-m-d') }}', title: '', content: '', image_url: '' };
        this.isSubmitDisabled = false;
        this.showModal = true;
        this.initSummernote('');
    },
    openEdit(newsData) {
        this.editMode = true;
        this.isCustomCat = !{{ json_encode(array_values($categories)) }}.includes(newsData.category);
        this.currentNews = newsData;
        this.isSubmitDisabled = false;
        this.showModal = true;
        this.initSummernote(newsData.content || '');
    },
    initSummernote(content) {
        setTimeout(() => {
            if ($('#newsContent').hasClass('summernote-initialized')) {
                $('#newsContent').summernote('code', content);
            } else {
                $('#newsContent').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onChange: (contents, $editable) => {
                            this.currentNews.content = contents;
                        }
                    }
                });
                $('#newsContent').addClass('summernote-initialized');
                $('#newsContent').summernote('code', content);
            }
        }, 100);
    }
}">
    
    <!-- Header Banner & Action Button -->
    <div class="bg-gradient-to-r from-purple-900 to-indigo-900 rounded-3xl p-6 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="px-3 py-1 bg-purple-800/60 text-purple-200 text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                📰 Kelola Informasi & Berita Portal
            </span>
            <h3 class="text-xl font-extrabold tracking-tight mt-1">Daftar Berita & Pengumuman DISHUB</h3>
            <p class="text-xs text-purple-100 mt-1 max-w-xl">Pilih kategori berita (Pemerintahan, Lalu Lintas, Pelayanan Publik) agar artikel otomatis tampil pada Tab Menu Informasi publik yang sesuai.</p>
        </div>
        <button @click="openAdd()" 
                class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg hover:shadow-emerald-500/30 flex items-center gap-2 transition-all shrink-0 hover:scale-105 active:scale-95">
            <i class="fas fa-plus-circle text-base"></i> ➕ Upload Berita Baru
        </button>
    </div>

    <!-- Validation Error Alert (if any) -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-rose-800 text-xs shadow-xs space-y-1">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle text-rose-500"></i> Terjadi kesalahan validasi:</p>
            <ul class="list-disc list-inside pl-4 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.news') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Cari Judul Berita</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci judul berita..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-medium">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Kategori Berita</label>
                <select name="category" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-bold text-slate-800">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Pemerintahan" {{ request('category') == 'Pemerintahan' ? 'selected' : '' }}>🏛️ Pemerintahan</option>
                    <option value="Lalu Lintas" {{ request('category') == 'Lalu Lintas' ? 'selected' : '' }}>🚦 Lalu Lintas</option>
                    <option value="Pelayanan Publik" {{ request('category') == 'Pelayanan Publik' ? 'selected' : '' }}>🚗 Pelayanan Publik</option>
                    @foreach($categories as $cat)
                        @if(!in_array($cat, ['Pemerintahan', 'Lalu Lintas', 'Pelayanan Publik']))
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-4 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl transition-all shadow-xs">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.news') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-all text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- News Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-2">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                <i class="fas fa-list text-purple-600"></i> Semua Berita Yang Sudah Terbit ({{ $newsList->total() }})
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Foto / Gambar</th>
                        <th class="px-6 py-4">Judul Berita</th>
                        <th class="px-6 py-4">Kategori Menu</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4">Tanggal Publish</th>
                        <th class="px-6 py-4">Pembaca</th>
                        <th class="px-6 py-4 text-right">Pilihan Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($newsList as $news)
                        <tr class="hover:bg-purple-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957' }}" alt="News" class="h-12 w-16 object-cover rounded-xl border border-slate-200 shadow-xs">
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <h4 class="font-extrabold text-slate-900 text-xs line-clamp-2">{{ $news->title }}</h4>
                                <p class="text-slate-400 text-[10px] line-clamp-1 mt-0.5">{{ Str::limit(strip_tags($news->content), 60) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                    {{ $news->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($news->creator)
                                    <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                        <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[9px]">
                                            {{ strtoupper(substr($news->creator->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $news->creator->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-semibold">
                                📅 {{ optional($news->published_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-extrabold">
                                👁️ {{ $news->views }}x
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5">
                                <a href="{{ route('news.detail', $news->slug) }}" target="_blank" class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-all border border-slate-200 inline-flex items-center gap-1 shadow-xs" title="Lihat Berita">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->isSuperAdmin() || $news->created_by == auth()->id())
                                    <button @click="openEdit({{ json_encode(array_merge($news->toArray(), ['published_at' => $news->published_at ? $news->published_at->format('Y-m-d') : date('Y-m-d')])) }})" 
                                            class="px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold rounded-xl transition-all border border-blue-200 inline-flex items-center gap-1 shadow-xs" title="Edit Berita">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white font-bold rounded-xl transition-all border border-rose-200 inline-flex items-center gap-1 shadow-xs" title="Hapus Berita">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-newspaper text-4xl mb-3 block opacity-30"></i>
                                <p class="font-bold text-sm">Belum ada artikel berita</p>
                                <p class="text-xs mt-1">Klik tombol "➕ Upload Berita Baru" di atas untuk membuat artikel pertama.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $newsList->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit News) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-start justify-center pt-6 pb-6 px-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-7xl w-full p-6 sm:p-8 shadow-2xl space-y-4 border border-slate-100 my-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? '✏️ Edit Data Berita' : '➕ Upload Berita Baru'"></h3>
                    <p class="text-[11px] text-slate-500">Pilih kategori menu publik yang sesuai agar berita otomatis tampil di tab publik.</p>
                </div>
                <button @click="showModal = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '{{ url('admin/news') }}/' + currentNews.id : '{{ route('admin.news.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">
                
                <div>
                    <label class="block font-extrabold text-slate-800 mb-1">Judul Berita / Pengumuman <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required x-model="currentNews.title" placeholder="Contoh: Rapat Koordinasi Sinergi Pelayanan Sektor Perhubungan 2026" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-medium text-slate-800">
                    <p class="text-[10px] text-slate-400 mt-1">Tulis judul berita yang singkat dan mudah dipahami warga.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">
                            Kategori Berita <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" x-model="currentNews.category" @change="isCustomCat = ($event.target.value === 'CUSTOM')" class="w-full px-3 py-2.5 bg-purple-50/50 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-bold text-slate-800 text-xs">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">📌 {{ $cat }} (Tab {{ $cat }})</option>
                            @endforeach
                            <option value="CUSTOM">➕ Tambah Kategori Baru (Custom)...</option>
                        </select>
                        <input x-show="isCustomCat" type="text" name="custom_category" placeholder="Tuliskan nama kategori berita baru..." class="w-full mt-2 px-3 py-2 border border-purple-400 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-bold text-purple-900 text-xs">
                    </div>
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Tanggal Terbit / Publish</label>
                        <input type="date" name="published_at" x-model="currentNews.published_at" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Upload Foto Sampul</label>
                        <input type="file" name="image_file" accept="image/jpeg, image/png, image/jpg, image/webp" @change="isSubmitDisabled = !window.validateGlobalFile($event, 'image', 5)" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none text-slate-600 text-xs">
                        <p class="text-[10px] text-slate-400 mt-0.5">Format: JPG, JPEG, PNG (Maks: 5MB)</p>
                    </div>
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Atau Link Gambar / URL</label>
                        <input type="text" name="image_url" x-model="currentNews.image_url" placeholder="https://domain.com/foto.jpg" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-medium">
                    </div>
                </div>

                <div>
                    <label class="block font-extrabold text-slate-800 mb-1">Isi Berita Lengkap <span class="text-rose-500">*</span></label>
                    <div x-ignore>
                        <textarea id="newsContent" name="content" rows="10" placeholder="Tuliskan detail berita atau informasi pengumuman di sini..." class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:outline-none font-medium text-slate-800"></textarea>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false; isSubmitDisabled = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl transition-all">Batal</button>
                    <button type="submit" 
                            :disabled="isSubmitDisabled"
                            :class="{'opacity-50 cursor-not-allowed': isSubmitDisabled}"
                            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan & Terbitkan Berita
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    // Bypass focus trap inside modal so Summernote dropdowns/modals can be clicked/typed in
    document.addEventListener('focusin', function (e) {
        if (e.target.closest('.note-editor, .note-modal, .note-popover')) {
            e.stopImmediatePropagation();
        }
    }, true);
</script>
@endpush
