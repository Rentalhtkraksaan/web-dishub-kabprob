@extends('admin.layout')

@section('page_title', 'Kelola Dokumen & Akuntabilitas Kinerja')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentDoc: {} }">
    
    <!-- Header Banner & Action Button -->
    <div class="bg-gradient-to-r from-blue-900 to-emerald-900 rounded-3xl p-6 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="px-3 py-1 bg-emerald-800/60 text-emerald-200 text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                📄 Kelola Dokumen & Berkas Publik DISHUB
            </span>
            <h3 class="text-xl font-extrabold tracking-tight mt-1">Dokumen Perencanaan & Akuntabilitas Kinerja</h3>
            <p class="text-xs text-blue-100 mt-1 max-w-xl">Pilih kelompok dokumen (Perencanaan, Pengukuran, Pelaporan, Evaluasi Kinerja) agar berkas PDF dan ZIP otomatis tampil pada menu publik yang sesuai.</p>
        </div>
        <button @click="showModal = true; editMode = false; currentDoc = { type: 'perencanaan-kinerja', category: 'Rencana Strategis', tahun: '{{ date('Y') }}' }" 
                class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg hover:shadow-emerald-500/30 flex items-center gap-2 transition-all shrink-0 hover:scale-105 active:scale-95">
            <i class="fas fa-file-upload text-base"></i> ➕ Upload Dokumen Baru
        </button>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.documents') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul dokumen..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Kelompok Dokumen</label>
                <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-bold text-slate-800">
                    <option value="">-- Semua Kelompok --</option>
                    <option value="perencanaan-kinerja" {{ request('type') == 'perencanaan-kinerja' ? 'selected' : '' }}>📂 Perencanaan Kinerja</option>
                    <option value="pengukuran-kinerja" {{ request('type') == 'pengukuran-kinerja' ? 'selected' : '' }}>📐 Pengukuran Kinerja</option>
                    <option value="pelaporan-kinerja" {{ request('type') == 'pelaporan-kinerja' ? 'selected' : '' }}>📊 Pelaporan Kinerja</option>
                    <option value="evaluasi-kinerja" {{ request('type') == 'evaluasi-kinerja' ? 'selected' : '' }}>📋 Evaluasi Kinerja</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Kategori</label>
                <select name="category" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Rencana Strategis" {{ request('category') == 'Rencana Strategis' ? 'selected' : '' }}>Rencana Strategis</option>
                    <option value="Pohon Kinerja" {{ request('category') == 'Pohon Kinerja' ? 'selected' : '' }}>Pohon Kinerja</option>
                    <option value="Cascading" {{ request('category') == 'Cascading' ? 'selected' : '' }}>Cascading</option>
                    <option value="Indikator Kinerja Utama" {{ request('category') == 'Indikator Kinerja Utama' ? 'selected' : '' }}>Indikator Kinerja Utama</option>
                    <option value="Rencana Kerja" {{ request('category') == 'Rencana Kerja' ? 'selected' : '' }}>Rencana Kerja</option>
                    <option value="Perjanjian Kinerja" {{ request('category') == 'Perjanjian Kinerja' ? 'selected' : '' }}>Perjanjian Kinerja</option>
                    <option value="Capaian Kinerja" {{ request('category') == 'Capaian Kinerja' ? 'selected' : '' }}>Capaian Kinerja</option>
                    <option value="LAKIP / LKjIP" {{ request('category') == 'LAKIP / LKjIP' ? 'selected' : '' }}>LAKIP / LKjIP</option>
                    <option value="Lembar Hasil Evaluasi (LHE)" {{ request('category') == 'Lembar Hasil Evaluasi (LHE)' ? 'selected' : '' }}>Lembar Hasil Evaluasi (LHE)</option>
                    <option value="Evaluasi AKIP" {{ request('category') == 'Evaluasi AKIP' ? 'selected' : '' }}>Evaluasi AKIP</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Tahun</label>
                <input type="text" name="tahun" value="{{ request('tahun') }}" placeholder="Contoh: 2026" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-4 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl transition-all shadow-xs">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.documents') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-all text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Documents Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-2">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                <i class="fas fa-folder-open text-blue-600"></i> Total Dokumen Terdaftar ({{ $documents->total() }})
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Judul Dokumen / Berkas</th>
                        <th class="px-6 py-4">Kelompok Menu</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Tahun</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4">Berkas PDF</th>
                        <th class="px-6 py-4">Berkas ZIP</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-slate-900 text-xs max-w-xs">
                                📄 {{ $doc->title }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeLabels = [
                                        'perencanaan-kinerja' => ['📂 Perencanaan Kinerja', 'bg-blue-100 text-blue-800 border-blue-200'],
                                        'pengukuran-kinerja'  => ['📐 Pengukuran Kinerja', 'bg-purple-100 text-purple-800 border-purple-200'],
                                        'pelaporan-kinerja'   => ['📊 Pelaporan Kinerja', 'bg-indigo-100 text-indigo-800 border-indigo-200'],
                                        'evaluasi-kinerja'    => ['📋 Evaluasi Kinerja', 'bg-amber-100 text-amber-800 border-amber-200'],
                                    ];
                                    $labelData = $typeLabels[$doc->type] ?? [$doc->type, 'bg-slate-100 text-slate-800 border-slate-200'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold border {{ $labelData[1] }}">
                                    {{ $labelData[0] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    {{ $doc->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $doc->tahun ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-semibold">
                                @if($doc->creator)
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[9px] font-bold text-blue-700">
                                            {{ strtoupper(substr($doc->creator->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $doc->creator->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($doc->file_url || $doc->file_path)
                                    <a href="{{ $doc->file_url ?: asset($doc->file_path) }}" target="_blank" class="px-3 py-1 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white font-bold rounded-xl border border-emerald-200 inline-flex items-center gap-1 transition-all text-[11px]">
                                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
                                    </a>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($doc->file_zip_url || $doc->file_zip_path)
                                    <a href="{{ $doc->file_zip_url ?: asset($doc->file_zip_path) }}" target="_blank" class="px-3 py-1 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white font-bold rounded-xl border border-amber-200 inline-flex items-center gap-1 transition-all text-[11px]">
                                        <i class="fas fa-file-archive text-amber-500"></i> ZIP
                                    </a>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="showModal = true; editMode = true; currentDoc = {{ json_encode($doc) }}" 
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold rounded-xl transition-all border border-blue-200 inline-flex items-center gap-1 shadow-xs" title="Edit Dokumen">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white font-bold rounded-xl transition-all border border-rose-200 inline-flex items-center gap-1 shadow-xs" title="Hapus Dokumen">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                Belum ada dokumen publik yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $documents->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal Form (Add / Edit Document) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4 border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? '✏️ Edit Data Dokumen' : '➕ Upload Dokumen Baru'"></h3>
                    <p class="text-[11px] text-slate-500">Tentukan kelompok menu publik, kategori, serta berkas PDF/ZIP.</p>
                </div>
                <button @click="showModal = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '{{ url('admin/documents') }}/' + currentDoc.id : '{{ route('admin.documents.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-extrabold text-slate-800 mb-1">Judul Dokumen / Laporan <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required x-model="currentDoc.title" placeholder="Contoh: Rencana Strategis (Renstra) DISHUB 2024-2029" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium text-slate-800">
                </div>

                <!-- Kelompok / Menu Dokumen -->
                <div>
                    <label class="block font-extrabold text-slate-800 mb-1">
                        Kelompok / Menu Dokumen Publik <span class="text-rose-500">*</span>
                        <span class="text-[10px] text-blue-600 font-bold ml-1">(Pilih Lokasi Tampil di Website Publik)</span>
                    </label>
                    <select name="type" required x-model="currentDoc.type" class="w-full px-3 py-2.5 bg-blue-50/50 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-bold text-slate-800 text-xs">
                        <option value="perencanaan-kinerja">📂 Perencanaan Kinerja (/dokumen/perencanaan-kinerja)</option>
                        <option value="pengukuran-kinerja">📐 Pengukuran Kinerja (/dokumen/pengukuran-kinerja)</option>
                        <option value="pelaporan-kinerja">📊 Pelaporan Kinerja (/dokumen/pelaporan-kinerja)</option>
                        <option value="evaluasi-kinerja">📋 Evaluasi Kinerja (/dokumen/evaluasi-kinerja)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3" x-data="{ isCustomCat: false }">
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Kategori Dokumen <span class="text-rose-500">*</span></label>
                        <select name="category" required x-model="currentDoc.category" @change="isCustomCat = ($event.target.value === 'CUSTOM')" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium text-xs">
                            <optgroup label="Perencanaan Kinerja">
                                <option value="Rencana Strategis">Rencana Strategis</option>
                                <option value="Pohon Kinerja">Pohon Kinerja</option>
                                <option value="Cascading">Cascading</option>
                                <option value="Indikator Kinerja Utama">Indikator Kinerja Utama</option>
                                <option value="Rencana Kerja">Rencana Kerja</option>
                                <option value="Rencana Aksi">Rencana Aksi</option>
                                <option value="Perjanjian Kinerja">Perjanjian Kinerja</option>
                                <option value="Dokumen Perencanaan Anggaran">Dokumen Perencanaan Anggaran</option>
                            </optgroup>
                            <optgroup label="Pengukuran Kinerja">
                                <option value="Capaian Kinerja">Capaian Kinerja</option>
                                <option value="Indikator Pengukuran">Indikator Pengukuran</option>
                            </optgroup>
                            <optgroup label="Pelaporan Kinerja">
                                <option value="LAKIP / LKjIP">LAKIP / LKjIP</option>
                                <option value="Laporan Kinerja Tahunan">Laporan Kinerja Tahunan</option>
                            </optgroup>
                            <optgroup label="Evaluasi Kinerja">
                                <option value="Lembar Hasil Evaluasi (LHE)">Lembar Hasil Evaluasi (LHE)</option>
                                <option value="Evaluasi AKIP">Evaluasi AKIP</option>
                            </optgroup>
                            <option value="CUSTOM">➕ Tambah Kategori Baru (Custom)...</option>
                        </select>
                        <input x-show="isCustomCat" type="text" name="custom_category" placeholder="Tuliskan nama kategori baru..." class="w-full mt-2 px-3 py-2 border border-blue-400 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-bold text-blue-900">
                    </div>

                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Tahun Dokumen</label>
                        <input type="text" name="tahun" x-model="currentDoc.tahun" placeholder="2026" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block font-extrabold text-slate-800">📄 Berkas PDF (Untuk Reader & Flipbook)</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 mb-1">Upload Komputer:</span>
                        <input type="file" name="file_pdf" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-500 mb-1">Atau Link URL PDF:</span>
                        <input type="text" name="file_url" x-model="currentDoc.file_url" placeholder="https://domain.com/file.pdf" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block font-extrabold text-slate-800">📦 Berkas ZIP Archive (Khusus Format .zip)</label>
                    <div>
                        <span class="block text-[10px] text-slate-500 mb-1">Upload Komputer:</span>
                        <input type="file" name="file_zip" accept=".zip" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200">
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-500 mb-1">Atau Link URL ZIP:</span>
                        <input type="text" name="file_zip_url" x-model="currentDoc.file_zip_url" placeholder="https://domain.com/file.zip" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
