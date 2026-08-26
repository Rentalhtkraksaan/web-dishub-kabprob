@extends('admin.layout')

@section('page_title', 'Hasil Survei Kepuasan Masyarakat (SKM)')

@section('content')
<div class="space-y-6" x-data="{ 
    editQuestionsOpen: false,
    editFeedbackOpen: false,
    createSurveiOpen: false,
    feedbackId: '',
    feedbackText: '',
    scoreValue: '',
    openEditFeedback(id, text, score) {
        this.feedbackId = id;
        this.feedbackText = text;
        this.scoreValue = score;
        this.editFeedbackOpen = true;
    }
}">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-400/20 text-amber-300 rounded-full text-xs font-bold mb-2 border border-amber-400/30">
                <i class="fas fa-chart-line"></i> Indeks Kepuasan Masyarakat (IKM)
            </div>
            <h2 class="text-2xl font-black tracking-tight">Hasil Survei Kepuasan Masyarakat (SKM)</h2>
            <p class="text-slate-300 text-xs mt-1">Rekapitulasi tanggapan, penilaian, dan masukan responden publik terhadap pelayanan DISHUB.</p>
        </div>

        <div class="flex items-center gap-3">
            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
            <button type="button" @click="createSurveiOpen = true" class="bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-2.5 rounded-2xl font-extrabold text-xs shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Manual
            </button>
            <button type="button" @click="editQuestionsOpen = true" class="bg-white text-blue-900 hover:bg-blue-50 px-4 py-2.5 rounded-2xl font-extrabold text-xs shadow-lg transition-all flex items-center gap-2 border border-blue-200">
                <i class="fas fa-edit text-amber-500"></i> Edit 10 Pertanyaan SKM
            </button>
            @endif
            <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-center">
                <span class="block text-[10px] text-slate-300 font-bold uppercase tracking-wider">Total Responden</span>
                <span class="text-xl font-black text-white">{{ $totalSurveys }}</span>
            </div>
            <div class="bg-amber-400 text-slate-900 px-4 py-2.5 rounded-2xl font-bold text-center shadow-lg">
                <span class="block text-[10px] text-slate-800 uppercase tracking-wider font-extrabold">Rata-rata Skor</span>
                <span class="text-xl font-black">{{ number_format($avgScore, 2) }} / 4.0</span>
            </div>
        </div>
    </div>

    <!-- Table Responses Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                <i class="fas fa-users text-blue-600"></i> Daftar Tanggapan Responden ({{ $surveys->total() }})
            </h4>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-4">Waktu</th>
                        <th class="px-4 py-4">Responden</th>
                        <th class="px-4 py-4">Kontak / WA</th>
                        <th class="px-4 py-4">Gender & Umur</th>
                        <th class="px-4 py-4">Penilaian (Skor)</th>
                        <th class="px-4 py-4">Saran & Masukan (Essay)</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surveys as $item)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-slate-500 font-medium">
                                {{ $item->created_at->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 font-extrabold text-slate-900">
                                {{ $item->name }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-blue-700">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->phone) }}?text={{ urlencode('Halo ' . $item->name . ' terimakasih atas respon kamu atas survey dishub') }}" target="_blank" class="hover:underline flex items-center gap-1">
                                    <i class="fab fa-whatsapp text-emerald-600"></i> {{ $item->phone }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-slate-600 font-medium">
                                {{ $item->gender ?? '—' }} ({{ $item->age ?? '—' }} thn)
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $item->score >= 3.5 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    ⭐ {{ number_format($item->score ?? 4.0, 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-md">
                                <p class="text-slate-700 font-medium text-xs italic bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 line-clamp-3">
                                    "{{ $item->feedback ?: 'Tidak ada masukan tertulis.' }}"
                                </p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" @click="openEditFeedback({{ $item->id }}, `{{ addslashes($item->feedback) }}`, `{{ $item->score }}`)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 font-bold text-[10px] rounded-lg hover:bg-blue-600 hover:text-white transition-colors border border-blue-200 shadow-sm">
                                        <i class="fas fa-edit"></i> Edit Data
                                    </button>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                        <form action="{{ route('admin.survei.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data survei ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 font-bold text-[10px] rounded-lg hover:bg-rose-600 hover:text-white transition-colors border border-rose-200 shadow-sm" title="Hapus Data">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-clipboard-list text-4xl mb-3 block opacity-30"></i>
                                Belum ada data hasil survei yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $surveys->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- MODAL EDIT 10 PERTANYAAN SKM (ALPINE JS) -->
    <div x-show="editQuestionsOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="editQuestionsOpen = false" class="bg-white rounded-3xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="bg-slate-900 text-white p-5 border-0 flex justify-between items-start shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <div>
                        <h5 class="font-black text-base text-white">Kelola 10 Pertanyaan Survei (SKM)</h5>
                        <p class="text-slate-400 text-xs mb-0">Ubah teks kalimat pertanyaan yang tampil pada form survei publik.</p>
                    </div>
                </div>
                <button type="button" @click="editQuestionsOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.survei.questions.update') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto flex-1 space-y-4">
                    @php
                        $questionsMap = isset($questions) ? $questions->keyBy('step_number') : collect();
                    @endphp
                    @for($i = 1; $i <= 10; $i++)
                        @php
                            $qItem = $questionsMap->get($i);
                            $defaultText = $qItem ? $qItem->question : '';
                        @endphp
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <label class="block font-extrabold text-slate-800 text-xs mb-2">
                                <span class="px-2 py-0.5 bg-blue-600 text-white rounded-md mr-1 font-mono">Soal {{ $i }} / 10</span>
                                {{ $qItem ? $qItem->category : 'Kategori Soal ' . $i }}
                            </label>
                            <textarea name="questions[{{ $i }}]" rows="2" required class="w-full bg-white p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan teks pertanyaan soal ke-{{ $i }}...">{{ old("questions.$i", $defaultText) }}</textarea>
                        </div>
                    @endfor
                </div>

                <div class="p-6 pt-4 border-t border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <button type="button" @click="editQuestionsOpen = false" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-extrabold rounded-xl text-xs hover:bg-blue-700 shadow-md flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan 10 Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT SARAN (ALPINE JS) -->
    <div x-show="editFeedbackOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="editFeedbackOpen = false" class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-slate-900 text-white p-5 border-0 flex justify-between items-start shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-400/20 text-blue-300 flex items-center justify-center font-bold">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div>
                        <h5 class="font-black text-base text-white">Edit Saran Responden</h5>
                        <p class="text-slate-400 text-xs mb-0">Sesuaikan masukan sebelum ditampilkan publik.</p>
                    </div>
                </div>
                <button type="button" @click="editFeedbackOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form :action="'/admin/survei-responses/' + feedbackId" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block font-extrabold text-slate-800 text-xs mb-2">Penilaian (Skor) - 1.00 s/d 4.00</label>
                    <input type="number" step="0.01" min="1" max="4" name="score" x-model="scoreValue" class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: 3.50">
                </div>
                
                <div class="mb-4">
                    <label class="block font-extrabold text-slate-800 text-xs mb-2">Saran / Masukan</label>
                    <textarea name="feedback" x-model="feedbackText" rows="4" class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan teks saran..."></textarea>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="editFeedbackOpen = false" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-extrabold rounded-xl text-xs hover:bg-blue-700 shadow-md flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH DATA (ALPINE JS) -->
    <div x-show="createSurveiOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="createSurveiOpen = false" class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-slate-900 text-white p-5 border-0 flex justify-between items-start shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-400/20 text-emerald-300 flex items-center justify-center font-bold">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h5 class="font-black text-base text-white">Tambah Data Survei Manual</h5>
                        <p class="text-slate-400 text-xs mb-0">Masukkan data hasil survei secara manual.</p>
                    </div>
                </div>
                <button type="button" @click="createSurveiOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.survei.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-extrabold text-slate-800 text-xs mb-2">Nama Responden</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block font-extrabold text-slate-800 text-xs mb-2">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" required class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-extrabold text-slate-800 text-xs mb-2">Gender</label>
                        <select name="gender" class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-extrabold text-slate-800 text-xs mb-2">Umur</label>
                        <input type="number" name="age" min="17" max="100" required class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh: 25">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block font-extrabold text-slate-800 text-xs mb-2">Penilaian (Skor) - 1.00 s/d 4.00</label>
                    <input type="number" step="0.01" min="1" max="4" name="score" required class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh: 3.50">
                </div>
                
                <div class="mb-4">
                    <label class="block font-extrabold text-slate-800 text-xs mb-2">Saran / Masukan</label>
                    <textarea name="feedback" rows="3" class="w-full bg-slate-50 p-3 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Masukkan teks saran..."></textarea>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="createSurveiOpen = false" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-extrabold rounded-xl text-xs hover:bg-emerald-700 shadow-md flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
