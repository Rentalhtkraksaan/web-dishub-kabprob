@extends('admin.layout')

@section('page_title', 'Banner Hero Slider')

@section('content')

{{-- Inject PHP routes ke JavaScript agar tidak konflik dengan Blade parser --}}
<script>
    var SLIDER_STORE_URL = '{{ route("admin.sliders.store") }}';
    var SLIDER_BASE_URL  = '{{ url("admin/sliders") }}';
</script>

<div class="space-y-6" x-data="{
    showModal: false,
    editMode: false,
    currentSlider: {},
    imageMode: 'url',
    previewUrl: '',
    formAction: '',
    init() {
        this.formAction = SLIDER_STORE_URL;
        this.$watch('imageMode', () => { this.previewUrl = ''; });
    },
    openAdd() {
        this.editMode = false;
        this.currentSlider = { is_active: true, order: 0 };
        this.imageMode = 'url';
        this.previewUrl = '';
        this.formAction = SLIDER_STORE_URL;
        this.showModal = true;
    },
    openEdit(slider) {
        this.editMode = true;
        this.currentSlider = slider;
        this.imageMode = (slider.image_url && slider.image_url.startsWith('/uploads/banners/')) ? 'upload' : 'url';
        this.previewUrl = slider.image_url || '';
        this.formAction = SLIDER_BASE_URL + '/' + slider.id;
        this.showModal = true;
    },
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
        }
    }
}">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Banner Hero Slider</h3>
            <p class="text-xs text-slate-500">Ubah judul, subtitle, gambar background, dan urutan banner homepage DISHUB</p>
        </div>
        <button @click="openAdd()"
                class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Banner Baru
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold px-4 py-3 rounded-xl">
            <i class="fas fa-exclamation-circle text-rose-500 mr-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Urutan</th>
                    <th class="px-6 py-4">Preview</th>
                    <th class="px-6 py-4">Judul Banner</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sliders as $slider)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">#{{ $slider->order }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $slider->image_url }}" alt="Preview"
                                 class="h-14 w-28 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4">
                            <h4 class="font-bold text-slate-800 text-xs">{{ $slider->title }}</h4>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $slider->subtitle }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold
                                {{ $slider->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ $slider->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button @click="openEdit({{ json_encode($slider) }})"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if(auth()->user()->isSuperAdmin())
                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Banner">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 text-xs">
                            <i class="fas fa-image text-2xl mb-2 block"></i>
                            Belum ada banner. Klik "Tambah Banner Baru" untuk memulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm"
                    x-text="editMode ? 'Edit Banner Hero Slider' : 'Tambah Banner Slider Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form --}}
            <form :action="formAction" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- Judul --}}
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Banner</label>
                    <input type="text" name="title" required x-model="currentSlider.title"
                           placeholder="Contoh: Selamat Datang di Portal Resmi DISHUB"
                           class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                {{-- Subtitle --}}
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Subtitle / Keterangan</label>
                    <input type="text" name="subtitle" x-model="currentSlider.subtitle"
                           placeholder="Contoh: Dinas Perhubungan Kabupaten Probolinggo..."
                           class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                {{-- Toggle URL vs Upload --}}
                <div>
                    <label class="block font-bold text-slate-700 mb-2">Sumber Gambar Background</label>
                    <div class="flex rounded-xl border border-slate-300 overflow-hidden text-[11px] font-bold">
                        <button type="button"
                                @click="imageMode = 'url'"
                                :class="imageMode === 'url' ? 'bg-blue-700 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                                class="flex-1 py-2 transition flex items-center justify-center gap-1.5">
                            <i class="fas fa-link"></i> Pakai URL Link
                        </button>
                        <button type="button"
                                @click="imageMode = 'upload'"
                                :class="imageMode === 'upload' ? 'bg-blue-700 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                                class="flex-1 py-2 transition flex items-center justify-center gap-1.5 border-l border-slate-300">
                            <i class="fas fa-upload"></i> Upload Foto
                        </button>
                    </div>
                </div>

                {{-- Input URL --}}
                {{-- Input URL --}}
                <div x-show="imageMode === 'url'">
                    <label class="block font-bold text-slate-700 mb-1">
                        URL Gambar Background
                        <span class="font-normal text-amber-600 ml-1">(Ukuran Standar: Landscape 16:9)</span>
                    </label>
                    <input type="text" name="image_url" x-model="currentSlider.image_url"
                           @input="previewUrl = $event.target.value"
                           placeholder="https://example.com/foto-banner-16by9.jpg"
                           class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                {{-- Input Upload File --}}
                <div x-show="imageMode === 'upload'">
                    <label class="block font-bold text-slate-700 mb-1">
                        Upload Foto Banner
                        <span class="font-normal text-slate-400">(JPG, PNG, WEBP — maks. 5MB)</span>
                    </label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-4 text-center hover:border-blue-400 transition cursor-pointer bg-slate-50/50"
                         @click="$refs.fileInput.click()">
                        <input type="file" name="image_file" x-ref="fileInput" accept="image/*"
                               @change="handleFileChange($event)"
                               class="hidden">
                        <div class="text-slate-500">
                            <i class="fas fa-cloud-upload-alt text-3xl mb-1 text-blue-600"></i>
                            <p class="text-xs font-bold text-slate-700">Klik atau seret foto banner ke sini</p>
                            <p class="text-[10px] text-amber-600 font-semibold mt-1">Disarankan rasio Landscape 16:9 (1920x1080 atau 1280x720)</p>
                        </div>
                    </div>
                </div>

                {{-- Review Format Landscape 16:9 --}}
                <div x-show="previewUrl" class="space-y-1">
                    <div class="flex justify-between items-center text-[10px] text-slate-600 font-bold px-1">
                        <span>Review Banner (Landscape 16:9):</span>
                        <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200"><i class="fas fa-desktop mr-1"></i> Desktop & HP Standard 16:9</span>
                    </div>
                    <div class="relative w-full aspect-[16/9] rounded-xl overflow-hidden border-2 border-slate-300 bg-slate-900 shadow-inner">
                        <img :src="previewUrl" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/30 p-3 flex flex-col justify-between pointer-events-none">
                            <span class="self-start text-[10px] font-bold bg-blue-600 text-white px-2 py-0.5 rounded-md shadow">
                                Preview Live 16:9
                            </span>
                            <div class="text-white">
                                <h4 class="font-bold text-xs line-clamp-1" x-text="currentSlider.title || 'Judul Banner Hero'"></h4>
                                <p class="text-[10px] text-slate-200 line-clamp-1" x-text="currentSlider.subtitle || 'Keterangan Subtitle Banner'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol CTA & URL --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teks Tombol CTA</label>
                        <input type="text" name="button_text" x-model="currentSlider.button_text"
                               placeholder="Layanan Uji KIR Online"
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">URL Link Tombol</label>
                        <input type="text" name="button_url" x-model="currentSlider.button_url"
                               placeholder="/halaman/uji-kir-online"
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                {{-- Order & Status --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Urutan Tampil (Order)</label>
                        <input type="number" name="order" x-model="currentSlider.order"
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="is_active" value="1"
                                   :checked="currentSlider.is_active"
                                   class="w-4 h-4 text-blue-700 rounded">
                            Aktifkan Slide
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false"
                            class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-md">
                        Simpan Banner
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
