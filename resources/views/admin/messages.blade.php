@extends('admin.layout')

@section('page_title', 'Pengaduan LLAJ & Pesan Masuk')

@section('content')
<div class="space-y-6">
    
    <div>
        <h3 class="text-lg font-bold text-slate-800">Daftar Pengaduan Perhubungan & Pesan Masuk</h3>
        <p class="text-xs text-slate-500">Pengaduan LLAJ, Lampu PJU, Uji KIR, & Layanan Angkutan dari masyarakat melalui portal DISHUB</p>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Pelapor</th>
                    <th class="px-6 py-4">Kontak Email</th>
                    <th class="px-6 py-4">Isi Pengaduan / Pesan</th>
                    <th class="px-6 py-4">Status Pengaduan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($messages as $msg)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $msg->name }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                            {{ $msg->email }}
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            <h4 class="font-bold text-slate-800 text-xs">{{ $msg->subject ?? 'Pengaduan LLAJ' }}</h4>
                            <p class="text-slate-500 text-[11px] line-clamp-2 mt-0.5">{{ $msg->message }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.messages.status', $msg->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" 
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border border-slate-200 focus:outline-none cursor-pointer
                                               {{ $msg->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($msg->status === 'diproses' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    <option value="baru" {{ $msg->status === 'baru' ? 'selected' : '' }}>BARU</option>
                                    <option value="diproses" {{ $msg->status === 'diproses' ? 'selected' : '' }}>DIPROSES</option>
                                    <option value="selesai" {{ $msg->status === 'selesai' ? 'selected' : '' }}>SELESAI</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(auth()->user()->isSuperAdmin())
                                <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pengaduan"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">
                            <i class="fas fa-inbox text-3xl mb-2 block text-slate-300"></i>
                            Belum ada pengaduan perhubungan atau pesan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $messages->links('pagination::tailwind') }}
        </div>
    </div>

</div>
@endsection
