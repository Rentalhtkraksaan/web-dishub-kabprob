@extends('admin.layout')

@section('page_title', 'Layanan Publik DISHUB')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentService: {} }">
    
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-teal-900 to-cyan-900 rounded-3xl p-6 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="px-3 py-1 bg-teal-800/60 text-teal-200 text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                🛠️ Kelola Layanan Publik
            </span>
            <h3 class="text-xl font-extrabold tracking-tight mt-1">Daftar Layanan DISHUB</h3>
            <p class="text-xs text-teal-100 mt-1 max-w-xl">Tambah, edit, atau hapus layanan yang ditampilkan kepada masyarakat di halaman publik website.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.informasi_tabs') }}" class="px-4 py-2.5 bg-teal-700 hover:bg-teal-600 text-white font-bold text-xs rounded-xl flex items-center gap-1 transition-all">
                <i class="fas fa-table-columns"></i> Kelola Tab Informasi
            </a>
            <button @click="showModal = true; editMode = false; currentService = { icon: 'fas fa-cogs', is_active: true, order: 0 }" 
                    class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg flex items-center gap-2 transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-plus-circle text-base"></i> ➕ Tambah Layanan
            </button>
        </div>
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                <i class="fas fa-list text-teal-600"></i> Semua Layanan ({{ $services->total() }})
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-4">Ikon/Gambar</th>
                        <th class="px-4 py-4">Nama Layanan</th>
                        <th class="px-4 py-4">Urutan & Posisi</th>
                        <th class="px-4 py-4">Kategori</th>
                        <th class="px-4 py-4">Dibuat Oleh</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($services as $service)
                        <tr class="hover:bg-teal-50/40 transition-colors">
                            <td class="px-4 py-3">
                                @if($service->image_url)
                                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="h-12 w-16 object-cover rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div class="h-12 w-16 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-xl flex items-center justify-center border border-teal-200">
                                        <i class="{{ $service->icon ?? 'fas fa-cogs' }} text-teal-600 text-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-extrabold text-slate-900 text-xs line-clamp-2">{{ $service->title }}</h4>
                                    @if($service->pdf_url)
                                        <a href="{{ $service->pdf_url }}" target="_blank" class="px-1.5 py-0.5 bg-rose-50 text-rose-700 font-extrabold text-[9px] rounded border border-rose-200 hover:bg-rose-600 hover:text-white transition-colors shrink-0" title="Unduh / Lihat Dokumen PDF">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    @endif
                                </div>
                                <p class="text-slate-400 text-[10px] mt-0.5 line-clamp-1">{{ $service->description }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-black bg-teal-50 text-teal-800 border border-teal-200/80 shadow-2xs">
                                        #{{ $loop->iteration }}
                                    </span>

                                    <!-- Dropdown Pindah Posisi -->
                                    <form action="{{ route('admin.services.reorder', $service->id) }}" method="POST" class="inline">
                                        @csrf
                                        <select name="position" onchange="this.form.submit()" class="px-2 py-1 bg-white border border-slate-300 rounded-xl text-[10px] font-extrabold text-slate-700 focus:ring-2 focus:ring-teal-500 cursor-pointer shadow-2xs">
                                            @for($i = 1; $i <= $services->total(); $i++)
                                                <option value="{{ $i }}" {{ $loop->iteration == $i ? 'selected' : '' }}>
                                                    {{ $i == 1 ? '🥇 Posisi 1 (Atas)' : 'Posisi ' . $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </form>

                                    <!-- Tombol Naik ⬆️ / Turun ⬇️ -->
                                    <div class="inline-flex rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
                                        @if(!$loop->first)
                                            <form action="{{ route('admin.services.reorder', $service->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="direction" value="up">
                                                <button type="submit" class="px-1.5 py-1 hover:bg-teal-50 text-slate-600 hover:text-teal-700 transition-colors" title="Naikkan Posisi ke Atas">
                                                    <i class="fas fa-chevron-up text-[10px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(!$loop->last)
                                            <form action="{{ route('admin.services.reorder', $service->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="direction" value="down">
                                                <button type="submit" class="px-1.5 py-1 hover:bg-teal-50 text-slate-600 hover:text-teal-700 transition-colors" title="Turunkan Posisi ke Bawah">
                                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                    {{ $service->category }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 font-semibold">
                                @if($service->creator)
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center text-[9px] font-bold text-teal-700">
                                            {{ strtoupper(substr($service->creator->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $service->creator->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($service->is_active)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">✅ Aktif</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">⛔ Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-1.5">
                                <a href="{{ route('layanan.detail', $service->slug) }}" target="_blank" class="px-2 py-1.5 bg-slate-50 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-all border border-slate-200 inline-flex items-center gap-1" title="Lihat di Website">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button @click="showModal = true; editMode = true; currentService = {{ json_encode($service) }}" 
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold rounded-xl transition-all border border-blue-200 inline-flex items-center gap-1" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white font-bold rounded-xl transition-all border border-rose-200 inline-flex items-center gap-1">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-cogs text-4xl mb-3 block opacity-30"></i>
                                Belum ada data layanan publik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $services->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal Form Tambah / Edit Layanan -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 space-y-4 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? '✏️ Edit Layanan Publik' : '➕ Tambah Layanan Publik Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form :action="editMode ? '{{ url('admin/services') }}/' + currentService.id : '{{ route('admin.services.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-3">
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1 text-xs">Nama Layanan <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" x-model="currentService.title" required placeholder="Contoh: Pendaftaran Uji Berkala Kendaraan (KIR) Online" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-medium text-xs">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-extrabold text-slate-800 mb-1 text-xs">Kategori Layanan</label>
                            <input type="text" name="category" x-model="currentService.category" placeholder="Perizinan / Pengujian / Umum" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-medium text-xs">
                        </div>
                        <div>
                            <label class="block font-extrabold text-slate-800 mb-1 text-xs">Ikon FontAwesome</label>
                            <input type="text" name="icon" x-model="currentService.icon" placeholder="fas fa-bus / fas fa-cogs" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-medium text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1 text-xs">Ringkasan Singkat (Muncul di Halaman Depan)</label>
                        <textarea name="description" x-model="currentService.description" rows="2" placeholder="Jelaskan secara singkat mengenai layanan ini..." class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-medium text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1 text-xs">Deskripsi Lengkap & Prosedur Layanan</label>
                        <textarea name="content" x-model="currentService.content" rows="4" placeholder="Tuliskan persyaratan, alur, jadwal, dan biaya (jika ada)..." class="w-full px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-medium text-xs"></textarea>
                    </div>

                    <!-- Upload Foto Banner / Gambar Layanan -->
                    <div class="p-3 bg-teal-50/50 border border-teal-200/80 rounded-2xl sm:col-span-2 space-y-2">
                        <label class="block font-extrabold text-slate-800 text-xs flex items-center gap-1.5">
                            <i class="fas fa-image text-teal-600"></i>
                            <span>Foto / Gambar Sampul Layanan</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1 text-[11px]">Upload File Gambar</label>
                                <input type="file" name="image_file" accept="image/*" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none text-slate-600 text-[11px]">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 mb-1 text-[11px]">Atau URL Gambar</label>
                                <input type="text" name="image_url" x-model="currentService.image_url" placeholder="https://..." class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-medium text-[11px]">
                            </div>
                        </div>
                    </div>

                    <!-- Upload PDF Dokumen Layanan -->
                    <div class="p-3 bg-rose-50/50 border border-rose-200/80 rounded-2xl sm:col-span-2 space-y-2">
                        <label class="block font-extrabold text-slate-800 text-xs flex items-center gap-1.5">
                            <i class="fas fa-file-pdf text-rose-600"></i>
                            <span>Berkas Dokumen PDF Layanan</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1 text-[11px]">Upload File PDF Baru</label>
                                <input type="file" name="pdf_file" accept=".pdf" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none text-slate-600 text-[11px]">
                                <p class="text-[10px] text-slate-400 mt-1">Maksimal 25MB (Format: .pdf)</p>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 mb-1 text-[11px]">Atau Tautan / URL PDF</label>
                                <input type="text" name="pdf_url" x-model="currentService.pdf_url" placeholder="https://.../persyaratan.pdf" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none font-medium text-[11px]">
                                <p class="text-[10px] text-slate-400 mt-1">Gunakan URL atau upload berkas lokal</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                        <div>
                            <label class="block font-extrabold text-slate-800 mb-1 text-xs">📌 Posisi Urutan Tampil</label>
                            <select name="order" x-model="currentService.order" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold text-xs text-slate-800">
                                <option value="0">🥇 Otomatis Paling Atas (Terbaru)</option>
                                <option value="1">Posisi #2</option>
                                <option value="2">Posisi #3</option>
                                <option value="3">Posisi #4</option>
                                <option value="4">Posisi #5</option>
                                <option value="5">Posisi #6</option>
                                <option value="10">Paling Bawah</option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Layanan terbaru otomatis di urutan pertama paling atas secara default.</p>
                        </div>
                        <div class="flex items-center pt-3">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700 text-xs">
                                <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-teal-600" :checked="currentService.is_active">
                                Aktif (Tampil di Website)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
