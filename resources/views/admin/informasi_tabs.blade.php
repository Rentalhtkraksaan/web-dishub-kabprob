@extends('admin.layout')

@section('page_title', 'Kelola Tab Menu Informasi')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentTab: {}, isCustom: false }">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-violet-900 to-purple-900 rounded-3xl p-6 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="px-3 py-1 bg-violet-800/60 text-violet-200 text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                🗂️ Manajemen Tab Informasi
            </span>
            <h3 class="text-xl font-extrabold tracking-tight mt-1">Tab Menu Halaman Informasi</h3>
            <p class="text-xs text-violet-100 mt-1 max-w-xl">Atur 4 menu tab utama (Semua Berita, Pemerintahan, Lalu Lintas, Pelayanan) atau tambah tab kustom untuk memfilter publikasi berita.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('informasi') }}" target="_blank" class="px-4 py-2.5 bg-violet-700 hover:bg-violet-600 text-white font-bold text-xs rounded-xl flex items-center gap-1 transition-all">
                <i class="fas fa-eye"></i> Lihat Halaman Informasi
            </a>
            <button @click="showModal = true; editMode = false; isCustom = false; currentTab = { name: '', icon: 'fas fa-newspaper', order: 0, is_active: true, filter_type: 'all', filter_value: '' }" 
                    class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg flex items-center gap-2 transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-plus-circle text-base"></i> ➕ Tambah Tab
            </button>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-blue-800 text-xs">
        <p class="font-bold mb-1">💡 Cara kerja Tab Menu Informasi:</p>
        <ul class="list-disc list-inside space-y-1">
            <li><strong>Semua Berita</strong> — Menampilkan seluruh artikel berita tanpa filter</li>
            <li><strong>Pemerintahan</strong> — Menampilkan berita dengan kategori "Pemerintahan"</li>
            <li><strong>Lalu Lintas</strong> — Menampilkan berita dengan kategori "Lalu Lintas"</li>
            <li><strong>Pelayanan</strong> — Menampilkan berita dengan kategori "Pelayanan Publik"</li>
        </ul>
    </div>

    <!-- Tabs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                <i class="fas fa-table-columns text-violet-600"></i> Daftar Tab ({{ count($tabs) }})
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Nama Tab</th>
                        <th class="px-6 py-4">Ikon</th>
                        <th class="px-6 py-4">Target Filter Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tabs as $tab)
                        <tr class="hover:bg-violet-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-slate-700 text-sm">{{ $tab->order }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="{{ $tab->icon }} text-violet-600"></i>
                                    <span class="font-extrabold text-slate-900">{{ $tab->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-[10px]">{{ $tab->icon }}</td>
                            <td class="px-6 py-4">
                                @if($tab->filter_type === 'all' || empty($tab->filter_value))
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full font-bold text-[10px]">📰 Semua Berita</span>
                                @else
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-full font-bold text-[10px]">Kategori: {{ $tab->filter_value }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tab->is_active)
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full font-bold text-[10px]">✅ Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full font-bold text-[10px]">⛔ Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5">
                                <button @click="showModal = true; editMode = true; isCustom = !['all', 'Pemerintahan', 'Lalu Lintas', 'Pelayanan Publik'].includes('{{ $tab->filter_value ?: 'all' }}'); currentTab = {{ json_encode($tab) }}" 
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold rounded-xl transition-all border border-blue-200 inline-flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route('admin.informasi_tabs.destroy', $tab->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tab ini?')">
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-table-columns text-4xl mb-3 block opacity-30"></i>
                                <p class="font-bold text-sm">Belum ada tab</p>
                                <p class="text-xs mt-1">Klik "Tambah Tab" untuk membuat tab baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Add/Edit Tab) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base" x-text="editMode ? '✏️ Edit Tab Informasi' : '➕ Tambah Tab Baru'"></h3>
                    <p class="text-[11px] text-slate-500">Pilih salah satu dari 4 menu filter utama atau isi kategori custom.</p>
                </div>
                <button @click="showModal = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/informasi-tabs') }}/' + currentTab.id : '{{ route('admin.informasi_tabs.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="block font-extrabold text-slate-800 mb-1">Pilih Filter Menu Kategori <span class="text-rose-500">*</span></label>
                    <select name="preset_select" 
                            @change="
                                if ($event.target.value === 'all') {
                                    currentTab.filter_type = 'all';
                                    currentTab.filter_value = '';
                                    if (!currentTab.name) currentTab.name = 'Semua Berita';
                                    if (!currentTab.icon) currentTab.icon = 'fas fa-newspaper';
                                    isCustom = false;
                                } else if ($event.target.value === 'CUSTOM') {
                                    currentTab.filter_type = 'category';
                                    isCustom = true;
                                } else {
                                    currentTab.filter_type = 'category';
                                    currentTab.filter_value = $event.target.value;
                                    if ($event.target.value === 'Pemerintahan') { currentTab.name = 'Pemerintahan'; currentTab.icon = 'fas fa-landmark'; }
                                    if ($event.target.value === 'Lalu Lintas') { currentTab.name = 'Lalu Lintas'; currentTab.icon = 'fas fa-traffic-light'; }
                                    if ($event.target.value === 'Pelayanan Publik') { currentTab.name = 'Pelayanan'; currentTab.icon = 'fas fa-car-side'; }
                                    isCustom = false;
                                }
                            "
                            class="w-full px-4 py-2.5 bg-purple-50/50 border border-purple-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:outline-none font-bold text-slate-800 text-xs">
                        <option value="all" :selected="currentTab.filter_type === 'all'">📰 Semua Berita (Tampilkan Semua)</option>
                        <option value="Pemerintahan" :selected="currentTab.filter_value === 'Pemerintahan'">🏛️ Kategori: Pemerintahan</option>
                        <option value="Lalu Lintas" :selected="currentTab.filter_value === 'Lalu Lintas'">🚦 Kategori: Lalu Lintas</option>
                        <option value="Pelayanan Publik" :selected="currentTab.filter_value === 'Pelayanan Publik' || currentTab.filter_value === 'Pelayanan'">🚗 Kategori: Pelayanan Publik</option>
                        <option value="CUSTOM" :selected="isCustom">➕ Custom Kategori Lainnya...</option>
                    </select>
                </div>

                <input type="hidden" name="filter_type" :value="currentTab.filter_type || 'all'">
                <input type="hidden" name="filter_value" :value="currentTab.filter_value || ''">

                <div x-show="isCustom">
                    <label class="block font-extrabold text-slate-800 mb-1">Nama Kategori Custom</label>
                    <input type="text" x-model="currentTab.filter_value" placeholder="Contoh: Keselamatan Jalan, Kegiatan Dinas" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:outline-none font-bold text-slate-800">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Nama Judul Tab <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required x-model="currentTab.name" placeholder="Contoh: Pemerintahan" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:outline-none font-medium text-slate-800">
                    </div>

                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Ikon FontAwesome</label>
                        <input type="text" name="icon" x-model="currentTab.icon" placeholder="fas fa-newspaper" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:outline-none font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-extrabold text-slate-800 mb-1">Urutan Tampil</label>
                        <input type="number" name="order" x-model="currentTab.order" min="0" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:outline-none font-medium">
                    </div>

                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-violet-600" :checked="currentTab.is_active">
                            Aktif di Website
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-extrabold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Tab
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
