@extends('admin.layout')

@section('page_title', 'Struktur Menu Navigasi Header Topbar')

@section('content')
<div class="w-full space-y-6" x-data="{ showModal: false, editMode: false, currentMenu: {} }">
    
    <!-- ===== HEADER EXECUTIVE BANNER ===== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Daftar Menu & Sub-Menu Navigasi Header</h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola status tayang menu header portal DISHUB (Aktif/Sembunyi) lengkap dengan Foto Banner & Berkas PDF.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            
            <!-- UNDO BUTTON (Tampil Otomatis Jika Ada Menu Baru Saja Terhapus) -->
            @if(session('last_deleted_menu'))
                <form action="{{ route('admin.menus.undo') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-amber-500/30 flex items-center gap-2 transition-all animate-bounce" title="Pulihkan menu {{ session('last_deleted_menu')['title'] }}">
                        <i class="fas fa-rotate-left"></i> Undo Pulihkan "{{ session('last_deleted_menu')['title'] }}"
                    </button>
                </form>
            @endif

            <!-- TAMBAH MENU BARU BUTTON -->
            <button @click="showModal = true; editMode = false; currentMenu = { is_active: true, target: '_self', order: 0 }" 
                    class="px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-extrabold text-xs rounded-xl shadow-md flex items-center gap-2 transition-all">
                <i class="fas fa-plus"></i> Tambah Menu Baru
            </button>
        </div>
    </div>

    <!-- Menus Tree Card List -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        @foreach($menus as $menu)
            <div class="border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs hover:border-slate-300 transition-colors">
                
                <!-- Main Parent Menu Row -->
                <div class="bg-slate-50/90 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-blue-700 text-white text-[11px] font-black flex items-center justify-center shadow-xs">#{{ $menu->order }}</span>
                        
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-black text-slate-900 text-xs tracking-wider uppercase">{{ $menu->title }}</h4>
                                <span class="text-[11px] text-blue-600 font-mono font-semibold bg-blue-50 px-2 py-0.5 rounded border border-blue-100">({{ $menu->url }})</span>
                                
                                @if($menu->image_url)
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] rounded border border-amber-200 flex items-center gap-1">
                                        <i class="fas fa-image text-amber-500"></i> Foto
                                    </span>
                                @endif

                                @if($menu->pdf_url)
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold text-[10px] rounded border border-rose-200 flex items-center gap-1">
                                        <i class="fas fa-file-pdf text-rose-600"></i> PDF
                                    </span>
                                @endif
                            </div>
                            @if($menu->description)
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5 italic line-clamp-1">{{ $menu->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Status Active Toggle Switch Button -->
                        <form action="{{ route('admin.menus.toggle_active', $menu->id) }}" method="POST" class="inline">
                            @csrf
                            @if($menu->is_active)
                                <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 font-extrabold text-[10px] border border-emerald-200 inline-flex items-center gap-1 hover:bg-emerald-200 transition-colors shadow-xs" title="Klik untuk Sembunyikan Menu">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                    Tayang (Aktif)
                                </button>
                            @else
                                <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 font-extrabold text-[10px] border border-slate-300 inline-flex items-center gap-1 hover:bg-slate-200 transition-colors" title="Klik untuk Tampilkan Menu">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Sembunyi (Nonaktif)
                                </button>
                            @endif
                        </form>

                        <button @click="showModal = true; editMode = true; currentMenu = {{ json_encode($menu) }}" 
                                class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1 shadow-xs" title="Edit Menu Utama">
                            <i class="fas fa-edit text-xs"></i> Edit
                        </button>
                    </div>
                </div>

                <!-- Children Sub-Menus Tree -->
                @if($menu->children && count($menu->children) > 0)
                    <div class="p-3 bg-white divide-y divide-slate-100 pl-8 border-t border-slate-100">
                        @foreach($menu->children as $child)
                            <div class="py-2.5 flex items-center justify-between gap-4 text-xs hover:bg-slate-50/50 px-2 rounded-xl transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-level-up-alt rotate-90 text-slate-300"></i>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-800">{{ $child->title }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono bg-slate-100 px-1.5 py-0.5 rounded">({{ $child->url }})</span>
                                            
                                            @if($child->image_url)
                                                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 font-bold text-[9px] rounded border border-amber-200">
                                                    📷 Foto
                                                </span>
                                            @endif

                                            @if($child->pdf_url)
                                                <span class="px-1.5 py-0.5 bg-rose-50 text-rose-700 font-bold text-[9px] rounded border border-rose-200">
                                                    📄 PDF
                                                </span>
                                            @endif
                                        </div>
                                        @if($child->description)
                                            <p class="text-[10px] text-slate-400 italic">{{ $child->description }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <!-- Status Active Toggle Switch Button for Child -->
                                    <form action="{{ route('admin.menus.toggle_active', $child->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($child->is_active)
                                            <button type="submit" class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-extrabold text-[10px] border border-emerald-200 inline-flex items-center gap-1 hover:bg-emerald-200 transition-colors shadow-xs" title="Klik untuk Sembunyikan Submenu">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                                Tayang (Aktif)
                                            </button>
                                        @else
                                            <button type="submit" class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 font-extrabold text-[10px] border border-slate-300 inline-flex items-center gap-1 hover:bg-slate-200 transition-colors" title="Klik untuk Tampilkan Submenu">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Sembunyi (Nonaktif)
                                            </button>
                                        @endif
                                    </form>

                                    <button @click="showModal = true; editMode = true; currentMenu = {{ json_encode($child) }}" 
                                            class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-bold rounded-lg transition text-[10px] inline-flex items-center gap-1" title="Edit Sub-menu">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @endforeach
    </div>

    <!-- ===== MODAL FORM (ADD / EDIT MENU WITH IMAGE, PDF, & CAPTION) ===== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 border border-slate-100 relative my-8">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm" x-text="editMode ? 'Edit Menu Navigasi' : 'Tambah Menu Navigasi Baru'"></h3>
                        <p class="text-xs text-slate-500">Atur Judul, Link URL, Foto Banner, Berkas PDF, dan Caption</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/menus') }}/' + currentMenu.id : '{{ route('admin.menus.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Parent Menu (Kosongkan Jika Ini Menu Utama)</label>
                    <select name="parent_id" x-model="currentMenu.parent_id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                        <option value="">-- Main Menu Utama Header --</option>
                        @foreach($allParents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Menu Navigasi</label>
                        <input type="text" name="title" required x-model="currentMenu.title" placeholder="Contoh: PROFIL / Visi Misi" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-extrabold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Target URL Link</span>
                            <span class="text-[10px] text-blue-600 font-semibold">Opsional (Otomatis dari Judul)</span>
                        </label>
                        <input type="text" name="url" x-model="currentMenu.url" placeholder="Kosongkan untuk buat URL /halaman/ otomatis dari Judul" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-mono text-xs">
                    </div>
                </div>

                <!-- Foto Banner / Gambar Section -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 space-y-2">
                    <label class="block font-bold text-slate-800 flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><i class="fas fa-image text-amber-500"></i> Upload Foto Banner Menu / Sub-Menu</span>
                        <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded">Rasio 16:9</span>
                    </label>
                    <input type="file" name="image_file" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white cursor-pointer">
                    <input type="text" name="image_url" x-model="currentMenu.image_url" placeholder="Atau masukkan URL Foto (https://...)" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-[11px] focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    
                    <template x-if="currentMenu.image_url">
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="remove_image" value="1" id="menu_remove_image_cb" class="rounded text-amber-600 focus:ring-amber-500">
                            <label for="menu_remove_image_cb" class="font-bold cursor-pointer text-[11px] text-rose-700">
                                <i class="fas fa-trash-alt"></i> Hapus Foto Banner saat ini
                            </label>
                        </div>
                    </template>
                </div>

                <!-- Berkas PDF Attachment Section -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 space-y-2">
                    <label class="block font-bold text-slate-800 flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><i class="fas fa-file-pdf text-rose-600"></i> Upload Berkas PDF Menu / Sub-Menu</span>
                        <span class="text-[10px] bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded">PDF Dokumen</span>
                    </label>
                    <input type="file" name="pdf_file" accept="application/pdf" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white cursor-pointer">
                    <input type="text" name="pdf_url" x-model="currentMenu.pdf_url" placeholder="Atau URL Dokumen PDF (https://...)" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-[11px] focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    
                    <template x-if="currentMenu.pdf_url">
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="remove_pdf" value="1" id="menu_remove_pdf_cb" class="rounded text-rose-600 focus:ring-rose-500">
                            <label for="menu_remove_pdf_cb" class="font-bold cursor-pointer text-[11px] text-rose-700">
                                <i class="fas fa-trash-alt"></i> Hapus Berkas PDF saat ini
                            </label>
                        </div>
                    </template>
                </div>

                <!-- Caption / Deskripsi Teks -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Caption / Deskripsi Teks Keterangan (Opsional)</label>
                    <textarea name="description" rows="2" x-model="currentMenu.description" placeholder="Tuliskan keterangan singkat atau caption penjelasan..." class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-sans text-xs"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan Tampil (Order)</label>
                        <input type="number" name="order" x-model="currentMenu.order" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-bold">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-extrabold text-slate-800">
                            <input type="checkbox" name="is_active" value="1" :checked="currentMenu.is_active" class="w-4 h-4 text-blue-700 rounded focus:ring-blue-600">
                            Aktifkan Menu Ini
                        </label>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-extrabold rounded-xl shadow-lg shadow-blue-700/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Menu Navigasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
