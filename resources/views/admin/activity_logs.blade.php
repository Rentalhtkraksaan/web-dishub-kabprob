@extends('admin.layout')

@section('page_title', 'Log Aktivitas Sistem')

@section('content')
<div class="w-full space-y-6" x-data="{ clearLogsOpen: false, c1: '', c2: '', c3: '' }">

    <!-- ===== HEADER BANNER ===== -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Log Rekam Jejak Sistem
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Log Aktivitas Sistem</h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium">
                    Pencatatan rekam jejak otomatis yang merekam seluruh aksi login, logout, penambahan, pengeditan, serta penghapusan data pengguna di portal DISHUB Kabupaten Probolinggo.
                </p>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-slate-900/80 backdrop-blur-md px-4 py-3 rounded-2xl border border-slate-800 text-center">
                    <span class="block text-[10px] text-slate-400 font-bold uppercase">Zona Waktu</span>
                    <span class="text-xs font-black text-amber-400">Asia/Jakarta (WIB)</span>
                </div>
            </div>
        </div>
        <!-- Decorative Background Elements -->
        <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-blue-600/10 to-transparent pointer-events-none"></div>
        <i class="fas fa-shield-cat absolute -right-6 -bottom-8 opacity-10 text-9xl text-white pointer-events-none"></i>
    </div>

    <!-- ===== STATS CARDS GRID ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Log Terekam</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['total'] ?? 0) }}</h3>
                <span class="text-[10px] text-blue-600 font-semibold">Semua Rekam Jejak</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-list-check"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Sesi Login</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['login_count'] ?? 0) }}</h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Sesi Autentikasi Pengguna</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-right-to-bracket"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perubahan Data</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['changes_count'] ?? 0) }}</h3>
                <span class="text-[10px] text-amber-600 font-semibold">Tambah / Edit / Hapus</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-file-pen"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['today_count'] ?? 0) }}</h3>
                <span class="text-[10px] text-purple-600 font-semibold">Terjadi Hari Ini</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold border border-purple-100">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>

    </div>

    <!-- ===== TOOLBAR FILTER & SEARCH ===== -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-bold">
            <a href="{{ route('admin.logs') }}" 
               class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ !request('filter') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Events
            </a>
            <a href="{{ route('admin.logs', ['filter' => 'login_logout']) }}" 
               class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ request('filter') === 'login_logout' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🔐 Sesi Login/Logout
            </a>
            <a href="{{ route('admin.logs', ['filter' => 'tambah']) }}" 
               class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ request('filter') === 'tambah' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ➕ Penambahan Data
            </a>
            <a href="{{ route('admin.logs', ['filter' => 'edit']) }}" 
               class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ request('filter') === 'edit' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ✏️ Perubahan/Edit
            </a>
            <a href="{{ route('admin.logs', ['filter' => 'hapus']) }}" 
               class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ request('filter') === 'hapus' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🗑️ Penghapusan
            </a>
        </div>

        <!-- Filters & Search -->
        <form method="GET" action="{{ route('admin.logs') }}" class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            <select name="year" class="py-2 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium bg-slate-50 w-full md:w-auto cursor-pointer" onchange="this.form.submit()">
                <option value="">-- Tahun --</option>
                @php $currentYear = date('Y'); @endphp
                @for($y = $currentYear; $y >= 2026; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <select name="month" class="py-2 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium bg-slate-50 w-full md:w-auto cursor-pointer" onchange="this.form.submit()">
                <option value="">-- Bulan --</option>
                @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $m => $mName)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $mName }}</option>
                @endforeach
            </select>

            <div class="relative w-full md:w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari user, aksi, atau IP..." 
                       class="w-full pl-9 pr-8 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                @if(request('search') || request('year') || request('month'))
                    <a href="{{ route('admin.logs', ['filter' => request('filter')]) }}" class="absolute right-3 top-2.5 text-slate-400 hover:text-rose-600 text-xs" title="Reset Pencarian">
                        <i class="fas fa-times-circle"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="hidden">Cari</button>
        </form>
    </div>

    <!-- ===== LOG TABLE CARD ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-clock-rotate-left text-blue-600"></i> Riwayat Audit Trail System
                </h2>
                <span class="text-xs text-slate-400 font-medium">
                    Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data
                </span>
            </div>
            @if(auth()->user()->isSuperAdmin())
                <button @click="clearLogsOpen = true" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> Bersihkan Seluruh Log
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4 text-center w-12">#</th>
                        <th class="py-3.5 px-4">Waktu & Tanggal (WIB)</th>
                        <th class="py-3.5 px-4">Pengguna (Actor)</th>
                        <th class="py-3.5 px-4">Role Hak Akses</th>
                        <th class="py-3.5 px-4 text-center">Event Aksi</th>
                        <th class="py-3.5 px-4">Detail Aktivitas</th>
                        <th class="py-3.5 px-4 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Index -->
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ $logs->firstItem() + $index }}
                            </td>

                            <!-- Time & Date -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                    <i class="far fa-clock text-blue-600 text-[11px]"></i>
                                    <span>{{ $log->created_at->format('H:i:s') }} WIB</span>
                                </div>
                                <div class="text-[10px] text-slate-500 font-semibold mt-0.5">
                                    {{ $log->created_at->translatedFormat('d F Y') }}
                                </div>
                            </td>

                            <!-- User / Actor -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center border border-slate-200">
                                        {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                    </div>
                                    <span class="font-extrabold text-slate-900">{{ $log->user_name }}</span>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($log->user_role === 'super_admin')
                                    <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 font-extrabold text-[10px] border border-amber-200 inline-flex items-center gap-1">
                                        ⭐ Super Admin
                                    </span>
                                @elseif($log->user_role === 'admin')
                                    <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-extrabold text-[10px] border border-blue-200 inline-flex items-center gap-1">
                                        👑 Admin
                                    </span>
                                @elseif($log->user_role === 'anggota')
                                    <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 font-extrabold text-[10px] border border-emerald-200 inline-flex items-center gap-1">
                                        👤 Anggota Staf
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px]">
                                        🌐 Public / Guest
                                    </span>
                                @endif
                            </td>

                            <!-- Action Event Badge -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if(str_contains($log->action, 'LOGIN'))
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-500 text-white font-black text-[10px] uppercase shadow-xs inline-flex items-center gap-1">
                                        <i class="fas fa-right-to-bracket"></i> LOGIN
                                    </span>
                                @elseif(str_contains($log->action, 'LOGOUT'))
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-600 text-white font-black text-[10px] uppercase shadow-xs inline-flex items-center gap-1">
                                        <i class="fas fa-right-from-bracket"></i> LOGOUT
                                    </span>
                                @elseif(str_contains($log->action, 'TAMBAH'))
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-600 text-white font-black text-[10px] uppercase shadow-xs inline-flex items-center gap-1">
                                        <i class="fas fa-circle-plus"></i> {{ $log->action }}
                                    </span>
                                @elseif(str_contains($log->action, 'HAPUS'))
                                    <span class="px-2.5 py-1 rounded-lg bg-rose-600 text-white font-black text-[10px] uppercase shadow-xs inline-flex items-center gap-1">
                                        <i class="fas fa-trash-can"></i> {{ $log->action }}
                                    </span>
                                @elseif(str_contains($log->action, 'STATUS') || str_contains($log->action, 'TOGGLE'))
                                    <span class="px-2.5 py-1 rounded-lg bg-purple-600 text-white font-black text-[10px] uppercase shadow-xs inline-flex items-center gap-1">
                                        <i class="fas fa-toggle-on"></i> {{ $log->action }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white font-black text-[10px] uppercase shadow-xs inline-flex items-center gap-1">
                                        <i class="fas fa-pen-to-square"></i> {{ $log->action }}
                                    </span>
                                @endif
                            </td>

                            <!-- Description -->
                            <td class="py-3.5 px-4">
                                <p class="text-slate-800 font-semibold leading-relaxed max-w-xl">
                                    {{ $log->description }}
                                </p>
                            </td>

                            <!-- IP Address -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <span class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fas fa-file-circle-xmark text-4xl mb-3 block text-slate-300"></i>
                                <p class="font-bold text-sm text-slate-600">Belum ada catatan aktivitas log.</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan filter atau kata kunci lain untuk mencari data log.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/60">
                {{ $logs->links('pagination::tailwind') }}
            </div>
        @endif

    </div>

    <!-- MODAL CLEAR LOGS (SUPER ADMIN ONLY) -->
    @if(auth()->user()->isSuperAdmin())
    <div x-show="clearLogsOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="clearLogsOpen = false" class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col">
            <div class="p-6 bg-rose-600 text-white flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-2xl shrink-0">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black tracking-wide">Peringatan: Zona Bahaya!</h3>
                    <p class="text-rose-100 text-xs mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <div class="p-6 space-y-4 text-sm text-slate-600">
                <p>
                    Anda akan <strong>MENGHAPUS PERMANEN</strong> seluruh catatan riwayat audit trail sistem. 
                    Demi keamanan, silakan ketik ulang ke-3 kata kunci persetujuan di bawah ini menggunakan <strong>HURUF BESAR</strong>.
                </p>

                <form method="POST" action="{{ route('admin.logs.clear') }}" class="space-y-4 mt-2">
                    @csrf
                    @method('DELETE')
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Ketik kata "HAPUS"</label>
                        <input type="text" name="confirm_1" x-model="c1" class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none" required autocomplete="off">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Ketik kata "LANJUTKAN"</label>
                        <input type="text" name="confirm_2" x-model="c2" class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none" required autocomplete="off">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Ketik kata "SETUJU"</label>
                        <input type="text" name="confirm_3" x-model="c3" class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none" required autocomplete="off">
                    </div>

                    @if($errors->has('confirm_clear'))
                        <div class="p-3 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold border border-rose-200">
                            {{ $errors->first('confirm_clear') }}
                        </div>
                    @endif

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-2">
                        <button type="button" @click="clearLogsOpen = false" class="px-5 py-2 rounded-xl text-slate-500 hover:bg-slate-100 font-bold transition-colors">Batal</button>
                        <button type="submit" 
                                class="px-5 py-2 rounded-xl text-white font-bold transition-all shadow-md"
                                :class="(c1 === 'HAPUS' && c2 === 'LANJUTKAN' && c3 === 'SETUJU') ? 'bg-rose-600 hover:bg-rose-700' : 'bg-slate-300 cursor-not-allowed'"
                                :disabled="!(c1 === 'HAPUS' && c2 === 'LANJUTKAN' && c3 === 'SETUJU')">
                            Ya, Bersihkan Permanen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if($errors->has('confirm_clear'))
        <script>
            document.addEventListener('alpine:init', () => {
                // Biarkan modal langsung terbuka jika ada error
                Alpine.store('clearLogsError', true);
            });
        </script>
        <!-- Membuka modal jika ada error -->
        <div x-init="clearLogsOpen = true"></div>
    @endif
    @endif

</div>
@endsection
