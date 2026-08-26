@extends('admin.layout')

@section('page_title', 'Kelola Tautan Terkait & Logo Mitra')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentLink: {} }">
    
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Tautan Terkait Logo Instansi</h3>
            <p class="text-xs text-slate-500">Atur logo & link instansi mitra (Kemenhub, DISHUB Jatim, BPTD, Pemkab)</p>
        </div>
        <button @click="showModal = true; editMode = false; currentLink = { is_active: true, order: 0 }" 
                class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Tautan Baru
        </button>
    </div>

    <!-- Links Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Urutan</th>
                    <th class="px-6 py-4">Logo Instansi</th>
                    <th class="px-6 py-4">Nama Instansi / Mitra</th>
                    <th class="px-6 py-4">Target URL Link</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($links as $link)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">#{{ $link->order }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $link->image_url }}" alt="Logo" class="h-10 w-auto max-w-[100px] object-contain">
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $link->title }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-500">
                            <a href="{{ $link->url }}" target="_blank" class="text-blue-700 hover:underline flex items-center gap-1">
                                <i class="fas fa-external-link-alt text-[10px]"></i> {{ $link->url }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="showModal = true; editMode = true; currentLink = {{ json_encode($link) }}" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fas fa-edit"></i></button>
                            @if(auth()->user()->isSuperAdmin())
                                <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tautan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Tautan"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Add / Edit Link) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm" x-text="editMode ? 'Edit Tautan Terkait' : 'Tambah Tautan Terkait Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form :action="editMode ? '{{ url('admin/links') }}/' + currentLink.id : '{{ route('admin.links.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Instansi / Mitra</label>
                    <input type="text" name="title" required x-model="currentLink.title" placeholder="Contoh: Kementerian Perhubungan RI" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                </div>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <label class="block font-extrabold text-slate-800 flex items-center gap-1.5">
                        <i class="fas fa-image text-blue-600"></i> Logo Gambar Instansi
                    </label>
                    <div>
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Upload Berkas Foto dari Laptop/HP:</span>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                    </div>
                    <div class="pt-2 border-t border-slate-200">
                        <span class="block text-[10px] text-slate-500 font-bold mb-1">Atau Gunakan Tautan URL Logo:</span>
                        <input type="text" name="image_url" x-model="currentLink.image_url" placeholder="https://..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Website URL</label>
                    <input type="text" name="url" required x-model="currentLink.url" placeholder="https://dephub.go.id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                        <input type="number" name="order" x-model="currentLink.order" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" :checked="currentLink.is_active" class="w-4 h-4 text-blue-700 rounded">
                            Aktifkan Link
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-md">Simpan Link</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
