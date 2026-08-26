@extends('admin.layout')

@section('title', 'Kelola Struktur Organisasi & Garis Komando')

@section('content')
<div class="space-y-6" x-data="{ 
    editModalOpen: false, 
    editFormUrl: '', 
    editData: { id: null, title: '', name: '', nip: '', parent_id: '', line_type: 'command', image_url: '', order_no: 0 },
    openEditModal(item) {
        this.editData = { 
            id: item.id, 
            title: item.title, 
            name: item.name, 
            nip: item.nip || '', 
            parent_id: item.parent_id || '', 
            line_type: item.line_type || 'command', 
            image_url: item.image_url || '', 
            order_no: item.order_no || 0 
        };
        this.editFormUrl = '{{ url('/admin/org-chart') }}/' + item.id;
        this.editModalOpen = true;
    }
}">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/60 p-5 rounded-2xl border border-slate-800 backdrop-blur-sm shadow-xl">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2.5">
                <i class="fas fa-sitemap text-indigo-400"></i> Kelola Struktur Organisasi & Garis Komando
            </h1>
            <p class="text-slate-400 text-xs mt-1">Ubah tata letak atasan/bawahan (si A di bawah si B), ganti nama, foto, serta jenis garis komando secara langsung.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url('/halaman/struktur-organisasi') }}" target="_blank" class="px-4 py-2.5 bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 border border-indigo-500/40 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-950/40">
                <i class="fas fa-external-link-alt"></i> Lihat Tampilan Publik Terhubung
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form Tambah Pejabat / Jabatan Baru -->
        <div class="lg:col-span-4">
            <div class="bg-slate-900/60 rounded-2xl border border-slate-800 p-5 space-y-4 shadow-xl">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-slate-800">
                    <i class="fas fa-user-plus text-amber-400"></i> Tambah Pejabat / Jabatan
                </h2>

                <form action="{{ route('admin.org_chart.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Jabatan <span class="text-rose-400">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Kepala Dinas / Kabid LLAJ" 
                               class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Pejabat & Gelar <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: EDWAN YUDIYANTO, S.Sos., M.Si." 
                               class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">NIP Pejabat (Opsional)</label>
                        <input type="text" name="nip" placeholder="Contoh: 19740512 199311 1 001" 
                               class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Atasan (Garis Komando Ke)</label>
                        <select name="parent_id" class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                            <option value="">-- Puncak Hirarki (Tanpa Atasan) --</option>
                            @foreach($allNodes as $parentItem)
                                <option value="{{ $parentItem->id }}">
                                    {{ $parentItem->title }} ({{ $parentItem->name }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Pilih atasan langsung untuk menentukan letak struktur.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Tipe Hubungan / Garis</label>
                        <select name="line_type" class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                            <option value="command">Garis Komando (Instruksi Langsung / Solid)</option>
                            <option value="coordination">Garis Koordinasi (Fungsional / Dashed)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Upload Foto Pejabat</label>
                        <input type="file" name="image_file" accept="image/*"
                               class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-amber-400 hover:file:bg-slate-700 cursor-pointer bg-slate-800/50 rounded-xl border border-slate-700/80">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Atau URL Foto (Opsional)</label>
                        <input type="text" name="image_url" placeholder="https://..." 
                               class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Urutan Posisi (Order)</label>
                        <input type="number" name="order_no" value="0" 
                               class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                    </div>

                    <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 mt-2">
                        <i class="fas fa-plus-circle"></i> Simpan Pejabat Baru
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Pejabat & Hirarki (Langsung Bisa Ubah Atasan & Posisi) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-4 bg-slate-800/50 border-b border-slate-800 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-list text-cyan-400"></i> Daftar Struktur Organisasi & Pengaturan Posisi
                    </h2>
                    <span class="px-2.5 py-1 bg-blue-500/20 text-blue-300 border border-blue-500/30 rounded-lg text-xs font-bold">
                        {{ count($allNodes) }} Jabatan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-800/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-800">
                            <tr>
                                <th class="p-3.5">Foto</th>
                                <th class="p-3.5">Jabatan & Pejabat</th>
                                <th class="p-3.5">Ubah Atasan Direct (Pindah Letak)</th>
                                <th class="p-3.5 text-center">Urutan</th>
                                <th class="p-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-slate-300">
                            @forelse($allNodes as $item)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="p-3.5">
                                        <img src="{{ $item->image_url ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop' }}" 
                                             class="w-10 h-10 rounded-xl object-cover border border-slate-700 shadow-md" alt="{{ $item->name }}">
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-white text-xs">{{ $item->title }}</div>
                                        <div class="text-slate-300 text-[11px] mt-0.5">{{ $item->name }}</div>
                                        @if($item->nip)
                                            <div class="text-slate-500 text-[10px]">NIP. {{ $item->nip }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3.5">
                                        <!-- Inline Quick Select Parent / Atasan -->
                                        <form action="{{ route('admin.org_chart.quick_move', $item->id) }}" method="POST" class="space-y-1">
                                            @csrf
                                            <div class="flex items-center gap-1.5">
                                                <select name="parent_id" onchange="this.form.submit()" 
                                                        class="w-full px-2.5 py-1.5 bg-slate-800 border border-slate-700/80 rounded-lg text-[11px] text-amber-300 focus:outline-none focus:border-amber-400">
                                                    <option value="">-- Puncak Hirarki (Tanpa Atasan) --</option>
                                                    @foreach($allNodes as $pSelect)
                                                        @if($pSelect->id != $item->id)
                                                            <option value="{{ $pSelect->id }}" {{ $item->parent_id == $pSelect->id ? 'selected' : '' }}>
                                                                &rarr; {{ $pSelect->title }} ({{ $pSelect->name }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex items-center justify-between text-[9px] text-slate-400">
                                                <span>
                                                    @if($item->line_type === 'coordination')
                                                        <span class="text-purple-400 font-semibold"><i class="fas fa-arrows-alt-h"></i> Garis Koordinasi</span>
                                                    @else
                                                        <span class="text-emerald-400 font-semibold"><i class="fas fa-arrow-down"></i> Garis Komando</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="p-3.5 text-center font-mono">
                                        <form action="{{ route('admin.org_chart.quick_move', $item->id) }}" method="POST" class="inline-flex items-center gap-1">
                                            @csrf
                                            <input type="number" name="order_no" value="{{ $item->order_no }}" onchange="this.form.submit()"
                                                   class="w-12 px-1.5 py-1 bg-slate-800 border border-slate-700 rounded-md text-center text-xs font-bold text-white focus:border-amber-400">
                                        </form>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Edit Button -->
                                            <button type="button" 
                                                    @click="openEditModal({{ json_encode($item) }})" 
                                                    class="p-2 text-amber-400 hover:bg-amber-500/20 rounded-lg transition-all" title="Edit Detail Data">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            @if(auth()->user()->isSuperAdmin())
                                                <form action="{{ route('admin.org_chart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $item->title }} ({{ $item->name }})?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-400 hover:bg-rose-500/20 rounded-lg transition-all" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-500">
                                        Belum ada data struktur organisasi. Silakan tambahkan pejabat pertama di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Preview Live Interactive Hierarchy Visual dengan Fitur Quick Relocate -->
            <div class="bg-slate-900/60 rounded-2xl border border-slate-800 p-5 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-800">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-network-wired text-indigo-400"></i> Visual Hubungan Terhubung (Semua Tingkat & Garis)
                    </h2>
                    <span class="text-[11px] text-emerald-400 font-semibold flex items-center gap-1">
                        <i class="fas fa-globe"></i> Terhubung Langsung ke Laman Publik
                    </span>
                </div>

                <div class="p-4 bg-slate-950/80 rounded-xl border border-slate-800 overflow-x-auto min-h-[180px] space-y-4">
                    @if(count($rootNodes) > 0)
                        @foreach($rootNodes as $root)
                            @include('admin.partials.org_tree_node', ['node' => $root, 'allNodes' => $allNodes])
                        @endforeach
                    @else
                        <div class="text-center py-6 text-slate-500 text-xs">
                            Belum ada hirarki terhubung. Silakan tambahkan pejabat di atas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT PEJABAT & GARIS KOMANDO -->
    <div x-show="editModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.away="editModalOpen = false" 
             class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl space-y-4">
            
            <div class="p-4 bg-slate-800/80 border-b border-slate-700 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fas fa-edit text-amber-400"></i> Edit Detail Pejabat & Foto
                </h3>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <form :action="editFormUrl" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Jabatan <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" x-model="editData.title" required 
                           class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Pejabat & Gelar <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required 
                           class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">NIP Pejabat (Opsional)</label>
                    <input type="text" name="nip" x-model="editData.nip" 
                           class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Atasan (Garis Komando Ke)</label>
                    <select name="parent_id" x-model="editData.parent_id" class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                        <option value="">-- Puncak Hirarki (Tanpa Atasan) --</option>
                        @foreach($allNodes as $parentItem)
                            <option value="{{ $parentItem->id }}" x-show="editData.id != {{ $parentItem->id }}">
                                {{ $parentItem->title }} ({{ $parentItem->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tipe Hubungan / Garis</label>
                    <select name="line_type" x-model="editData.line_type" class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                        <option value="command">Garis Komando (Instruksi Langsung / Solid)</option>
                        <option value="coordination">Garis Koordinasi (Fungsional / Dashed)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Ganti Foto Pejabat (Upload File)</label>
                    <input type="file" name="image_file" accept="image/*" 
                           class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-amber-400 hover:file:bg-slate-700 cursor-pointer bg-slate-800/50 rounded-xl border border-slate-700/80">
                    <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti file foto.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Atau URL Foto</label>
                    <input type="text" name="image_url" x-model="editData.image_url" 
                           class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Urutan Posisi (Order)</label>
                    <input type="number" name="order_no" x-model="editData.order_no" 
                           class="w-full px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:border-amber-400 transition-all">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
