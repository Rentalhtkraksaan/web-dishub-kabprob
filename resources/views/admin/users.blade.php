@extends('admin.layout')

@section('page_title', 'Manajemen Pengguna & Hak Akses')

@section('content')
<div class="w-full space-y-6" x-data="{
    showModal: false,
    editMode: false,
    currentUser: {},
    passwordInput: '',
    showPassword: false,
    get passLength() { return this.passwordInput.length >= 8 },
    get passUpper() { return /[A-Z]/.test(this.passwordInput) },
    get passLower() { return /[a-z]/.test(this.passwordInput) },
    get passNumber() { return /[0-9]/.test(this.passwordInput) },
    get passSymbol() { return /[@$!%*#?&^()_\-+=\[\]{}|\\:;<>,.]/.test(this.passwordInput) },
    get passScore() {
        if (!this.passwordInput) return 0;
        let score = 0;
        if (this.passLength) score++;
        if (this.passUpper) score++;
        if (this.passLower) score++;
        if (this.passNumber) score++;
        if (this.passSymbol) score++;
        return score;
    },
    get isEmailGmail() {
        if (!this.currentUser.email) return true;
        return /^[a-zA-Z0-9._%+-]+@gmail\.com$/i.test((this.currentUser.email || '').trim());
    },
    get isWaValid() {
        if (!this.currentUser.whatsapp) return true;
        return /^0[0-9]{10,12}$/.test((this.currentUser.whatsapp || '').trim());
    },
    get isRefValid() {
        if (!this.currentUser.referral_code) return true;
        return /^(?=(?:.*[a-zA-Z]){3})(?=(?:.*\d){3})[a-zA-Z\d]{6}$/.test((this.currentUser.referral_code || '').trim());
    }
}">
    
    <!-- ===== HEADER EXECUTIVE BANNER ===== -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-users-gear text-indigo-400"></i>
                    Hak Akses Pengguna
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Manajemen Akun Pengguna & Role</h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium">
                    Kelola hak akses pengguna internal DISHUB. Atur No. WhatsApp, Kode Referral, dan password akun pengguna.
                </p>
            </div>
            
            <button @click="showModal = true; editMode = false; passwordInput = ''; showPassword = false; currentUser = { role: 'anggota' }" 
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs rounded-2xl shadow-xl shadow-blue-600/30 flex items-center justify-center gap-2.5 transition-all shrink-0 border border-blue-400/30 hover:scale-[1.02] active:scale-[0.98]">
                <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center text-sm">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span>Tambah Pengguna Baru</span>
            </button>
        </div>
        <!-- Background Glow -->
        <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-indigo-600/10 to-transparent pointer-events-none"></div>
        <i class="fas fa-user-shield absolute -right-6 -bottom-8 opacity-10 text-9xl text-white pointer-events-none"></i>
    </div>

    <!-- ===== STAT CARDS GRID ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total User Terdaftar</p>
                <h3 class="text-2xl font-black text-slate-900">{{ count($users) }}</h3>
                <span class="text-[10px] text-blue-600 font-semibold">Pengguna Internal</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Super Admin / Admin</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $users->whereIn('role', ['super_admin', 'admin'])->count() }}</h3>
                <span class="text-[10px] text-amber-600 font-semibold">Akses Administrator</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Anggota Staf</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $users->where('role', 'anggota')->count() }}</h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Akses Terbatas Staf</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-id-badge"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Akun Aktif</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $users->where('is_active', true)->count() }} <span class="text-xs text-slate-400 font-normal">/ {{ count($users) }}</span></h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Dapat Mengakses Panel</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                <i class="fas fa-user-check"></i>
            </div>
        </div>

    </div>

    <!-- Error Alerts (if validation fails) -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-rose-800 text-xs shadow-xs space-y-1">
            <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle text-rose-500"></i> Terjadi kesalahan saat menyimpan data user:</p>
            <ul class="list-disc list-inside pl-4 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ===== USERS TABLE LIST ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-list-ol text-blue-600"></i> Daftar Akun Pengguna & Hak Akses Portal
            </h2>
            <span class="text-xs text-slate-400 font-medium">Total: {{ count($users) }} Akun</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                        <th class="px-6 py-3.5">Nama & Profil User</th>
                        <th class="px-6 py-3.5">Username Login</th>
                        <th class="px-6 py-3.5">Email Administrator</th>
                        <th class="px-6 py-3.5">No. WhatsApp (Val 1)</th>
                        <th class="px-6 py-3.5">Kode Referral (Val 2)</th>
                        <th class="px-6 py-3.5">Role / Hak Akses</th>
                        <th class="px-6 py-3.5">Status Akun</th>
                        <th class="px-6 py-3.5 text-right">Manajemen Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- User Name & Avatar -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="relative shrink-0">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="Avatar" class="w-10 h-10 rounded-2xl object-cover border-2 {{ $user->isSuperAdmin() ? 'border-amber-400' : ($user->isAdmin() ? 'border-indigo-400' : 'border-emerald-400') }} shadow-xs">
                                        @else
                                            <div class="w-10 h-10 rounded-2xl {{ $user->isSuperAdmin() ? 'bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-600 text-slate-950 font-black border-2 border-yellow-300' : ($user->isAdmin() ? 'bg-gradient-to-br from-blue-600 via-indigo-700 to-sky-800 text-white font-black border-2 border-indigo-200' : 'bg-gradient-to-br from-emerald-600 to-teal-800 text-white font-bold border-2 border-emerald-200') }} text-xs flex items-center justify-center shadow-xs">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-xs">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">User ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Username -->
                            <td class="px-6 py-4">
                                <span class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                    {{ $user->username ?? '-' }}
                                </span>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 font-mono text-[11px] text-slate-600 font-semibold">
                                {{ $user->email }}
                            </td>

                            <!-- WhatsApp -->
                            <td class="px-6 py-4 font-mono text-[11px] text-slate-700 font-bold">
                                @if($user->whatsapp)
                                    <span class="text-emerald-700 flex items-center gap-1">
                                        <i class="fab fa-whatsapp text-emerald-500"></i> {{ $user->whatsapp }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>

                            <!-- Kode Referral -->
                            <td class="px-6 py-4 font-mono text-[11px] font-bold">
                                @if($user->referral_code)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md border border-blue-200">
                                        🔑 {{ $user->referral_code }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>

                            <!-- Role Badge -->
                            <td class="px-6 py-4">
                                @if($user->isSuperAdmin())
                                    <span class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-500 text-slate-950 font-black text-[10px] border border-yellow-300 inline-flex items-center gap-1.5 shadow-xs">
                                        👑 Super Admin
                                    </span>
                                @elseif($user->isAdmin())
                                    <span class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-extrabold text-[10px] border border-indigo-400 inline-flex items-center gap-1.5 shadow-xs">
                                        🛡️ Admin
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 font-bold text-[10px] border border-emerald-200 inline-flex items-center gap-1.5">
                                        👤 Anggota Staf
                                    </span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 font-extrabold text-[10px] border border-emerald-200 inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-xl bg-rose-50 text-rose-700 font-extrabold text-[10px] border border-rose-200 inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-6 py-4 text-right space-x-1.5">
                                <button @click="showModal = true; editMode = true; passwordInput = ''; showPassword = false; currentUser = {{ json_encode($user) }}" 
                                        class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Edit Akun & Role">
                                    <i class="fas fa-edit text-xs"></i> Edit
                                </button>

                                @if(auth()->user()->isSuperAdmin() && auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan kembali' }} pengguna {{ $user->name }}?')">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->is_active)
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Nonaktifkan User">
                                                <i class="fas fa-user-slash text-xs"></i> Nonaktifkan
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-extrabold rounded-xl transition text-[11px] inline-flex items-center gap-1.5 shadow-xs" title="Aktifkan User">
                                                <i class="fas fa-user-check text-xs"></i> Aktifkan
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-400">
                                <i class="fas fa-users-slash text-3xl block mb-2 text-slate-300"></i>
                                Belum ada data pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== MODAL FORM (ADD / EDIT USER) ===== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="showModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-5 border border-slate-100 relative my-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-user-gear"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm" x-text="editMode ? 'Edit Akun Pengguna' : 'Tambah Pengguna Baru'"></h3>
                        <p class="text-[11px] text-slate-500">Atur profil, WhatsApp, Kode Referral, dan kata sandi pengguna</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/users') }}/' + currentUser.id : '{{ route('admin.users.store') }}'" method="POST" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">
                
                @if(auth()->user()->isSuperAdmin())
                    <!-- Super Admin: Akses Lengkap (Edit Nama, Username, Role, Email, WA, Kode Referral) -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required x-model="currentUser.name" placeholder="Contoh: Sukma / Staf LLAJ DISHUB" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Username Login</label>
                            <input type="text" name="username" x-model="currentUser.username" placeholder="sukma" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Role Hak Akses <span class="text-rose-500">*</span></label>
                            <select name="role" x-model="currentUser.role" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-bold text-blue-700">
                                <option value="anggota">👤 Anggota Staf</option>
                                <option value="admin">👑 Admin</option>
                                <option value="super_admin">⭐ Super Admin</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 flex justify-between items-center">
                            <span>Email Resmi Administrator <span class="text-rose-500">*</span></span>
                            <span x-show="currentUser.email" class="text-[10px] font-extrabold" :class="isEmailGmail ? 'text-emerald-600' : 'text-rose-500'" x-text="isEmailGmail ? '✓ Berakhiran @gmail.com' : '⚠️ Wajib Berakhiran @gmail.com'"></span>
                        </label>
                        <input type="email" name="email" required x-model="currentUser.email" placeholder="contoh@gmail.com" 
                               :class="{'border-rose-400 focus:ring-rose-500 bg-rose-50/20': currentUser.email && !isEmailGmail, 'border-emerald-400 focus:ring-emerald-500': currentUser.email && isEmailGmail}"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                            <i class="fas fa-info-circle text-blue-500"></i> Email wajib berakhiran <code class="text-blue-600 font-bold bg-blue-50 px-1 rounded">@gmail.com</code>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 flex justify-between items-center">
                                <span class="flex items-center gap-1">
                                    <i class="fab fa-whatsapp text-emerald-500"></i> No. WhatsApp (Validasi 1)
                                </span>
                                <span x-show="currentUser.whatsapp" class="text-[10px] font-extrabold" :class="isWaValid ? 'text-emerald-600' : 'text-rose-500'" x-text="isWaValid ? '✓ Valid (Depan 0, 11-13 Digit)' : '⚠️ Wajib 0 & 11-13 Digit'"></span>
                            </label>
                            <input type="text" name="whatsapp" x-model="currentUser.whatsapp" placeholder="Contoh: 085176871609" 
                                   :class="{'border-rose-400 focus:ring-rose-500 bg-rose-50/20': currentUser.whatsapp && !isWaValid, 'border-emerald-400 focus:ring-emerald-500': currentUser.whatsapp && isWaValid}"
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold text-slate-800">
                            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle text-emerald-500"></i> Diawali <code class="text-emerald-700 font-bold bg-emerald-50 px-1 rounded">0</code> & panjang 11 - 13 digit angka
                            </p>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 flex justify-between items-center">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-key text-blue-500"></i> Kode Referral (Validasi 2)
                                </span>
                                <span x-show="currentUser.referral_code" class="text-[10px] font-extrabold" :class="isRefValid ? 'text-emerald-600' : 'text-rose-500'" x-text="isRefValid ? '✓ Valid (3 Huruf + 3 Angka)' : '⚠️ Wajib 3 Huruf & 3 Angka'"></span>
                            </label>
                            <input type="text" name="referral_code" x-model="currentUser.referral_code" placeholder="Contoh: ADI123" 
                                   :class="{'border-rose-400 focus:ring-rose-500 bg-rose-50/20': currentUser.referral_code && !isRefValid, 'border-emerald-400 focus:ring-emerald-500': currentUser.referral_code && isRefValid}"
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold text-slate-800 uppercase">
                            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle text-blue-500"></i> Tepat 3 huruf & 3 angka (6 karakter, misal: <code class="text-blue-700 font-bold bg-blue-50 px-1 rounded">ADI123</code>)
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Admin Biasa: Nama, Username, Role, Email, WA, dan Kode Referral Dikunci (Hanya Password) -->
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3 text-[11px] text-amber-800 flex items-center gap-2 font-medium">
                        <i class="fas fa-lock text-amber-600 text-sm shrink-0"></i>
                        <span>Mode Admin: Profil, Email, WA, Kode Referral & Role dikunci. Anda hanya berhak mengubah <strong>Password Keamanan</strong>.</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-500 mb-1">Nama Lengkap Pengguna <span class="text-[10px] text-slate-400 font-normal">(Dikunci)</span></label>
                        <input type="text" x-model="currentUser.name" disabled class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-500 font-semibold cursor-not-allowed select-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-500 mb-1">Username Login <span class="text-[10px] text-slate-400 font-normal">(Dikunci)</span></label>
                            <input type="text" x-model="currentUser.username" disabled class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-500 font-semibold cursor-not-allowed select-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-500 mb-1">Role Hak Akses <span class="text-[10px] text-slate-400 font-normal">(Dikunci)</span></label>
                            <div class="px-3.5 py-2.5 border border-slate-200 bg-slate-100/80 rounded-xl font-bold text-slate-600 flex items-center gap-1.5 cursor-not-allowed select-none">
                                <span x-show="currentUser.role === 'super_admin'">⭐ Super Admin</span>
                                <span x-show="currentUser.role === 'admin'">👑 Admin</span>
                                <span x-show="currentUser.role === 'anggota'">👤 Anggota Staf</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-500 mb-1">Email Resmi Administrator <span class="text-[10px] text-slate-400 font-normal">(Dikunci)</span></label>
                        <input type="email" x-model="currentUser.email" disabled class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-500 font-medium cursor-not-allowed select-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-500 mb-1 flex items-center gap-1">
                                <i class="fab fa-whatsapp text-slate-400"></i> No. WhatsApp <span class="text-[10px] text-slate-400 font-normal">(Dikunci)</span>
                            </label>
                            <input type="text" x-model="currentUser.whatsapp" disabled class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-500 font-semibold cursor-not-allowed select-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-500 mb-1 flex items-center gap-1">
                                <i class="fas fa-key text-slate-400"></i> Kode Referral <span class="text-[10px] text-slate-400 font-normal">(Dikunci)</span>
                            </label>
                            <input type="text" x-model="currentUser.referral_code" disabled class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-500 font-semibold uppercase cursor-not-allowed select-none">
                        </div>
                    </div>
                @endif

                <!-- Password Field with Eye Toggle & Strength Checklist -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 flex justify-between items-center">
                        <span>
                            Password Keamanan 
                            <span x-show="editMode" class="text-[10px] text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span>
                        </span>
                        <span class="text-[10px] font-extrabold" :class="{
                            'text-slate-400': passScore === 0,
                            'text-rose-500': passScore > 0 && passScore <= 2,
                            'text-amber-500': passScore >= 3 && passScore <= 4,
                            'text-emerald-600': passScore === 5
                        }" x-text="passScore === 0 ? '' : (passScore <= 2 ? '⚠️ Password Lemah' : (passScore <= 4 ? '⚡ Password Sedang' : '🔒 Password Sangat Rumit & Aman'))"></span>
                    </label>

                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'"
                               name="password"
                               x-model="passwordInput"
                               :required="!editMode"
                               placeholder="Contoh: Dishub#2026!"
                               class="w-full px-3.5 py-2.5 pr-10 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors p-1" title="Lihat / Sembunyikan Password">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash text-blue-600' : 'fa-eye'"></i>
                        </button>
                    </div>

                    <!-- Password Strength Bar -->
                    <div class="w-full h-1.5 bg-slate-100 rounded-full mt-2 overflow-hidden flex gap-1">
                        <div class="h-full transition-all duration-300 rounded-full" :class="passScore >= 1 ? (passScore <= 2 ? 'bg-rose-500' : (passScore <= 4 ? 'bg-amber-500' : 'bg-emerald-500')) : 'bg-slate-200'" style="flex: 1;"></div>
                        <div class="h-full transition-all duration-300 rounded-full" :class="passScore >= 2 ? (passScore <= 2 ? 'bg-rose-500' : (passScore <= 4 ? 'bg-amber-500' : 'bg-emerald-500')) : 'bg-slate-200'" style="flex: 1;"></div>
                        <div class="h-full transition-all duration-300 rounded-full" :class="passScore >= 3 ? (passScore <= 4 ? 'bg-amber-500' : 'bg-emerald-500') : 'bg-slate-200'" style="flex: 1;"></div>
                        <div class="h-full transition-all duration-300 rounded-full" :class="passScore >= 4 ? (passScore <= 4 ? 'bg-amber-500' : 'bg-emerald-500') : 'bg-slate-200'" style="flex: 1;"></div>
                        <div class="h-full transition-all duration-300 rounded-full" :class="passScore >= 5 ? 'bg-emerald-500' : 'bg-slate-200'" style="flex: 1;"></div>
                    </div>

                    <!-- Gmail-Style Criteria Checklist -->
                    <div class="mt-2.5 p-3 bg-slate-50 border border-slate-200/80 rounded-xl space-y-1 text-[11px]">
                        <p class="font-bold text-slate-700 text-[10px] uppercase tracking-wider mb-1">Syarat Kombinasi Password Rumit & Aman (Gmail Style):</p>
                        <div class="grid grid-cols-2 gap-x-2 gap-y-1">
                            <div class="flex items-center gap-1.5" :class="passLength ? 'text-emerald-700 font-bold' : 'text-slate-400'">
                                <i class="fas text-[10px]" :class="passLength ? 'fa-check-circle text-emerald-500' : 'fa-circle-notch'"></i>
                                <span>Min. 8 Karakter</span>
                            </div>
                            <div class="flex items-center gap-1.5" :class="passUpper ? 'text-emerald-700 font-bold' : 'text-slate-400'">
                                <i class="fas text-[10px]" :class="passUpper ? 'fa-check-circle text-emerald-500' : 'fa-circle-notch'"></i>
                                <span>Huruf Besar (A-Z)</span>
                            </div>
                            <div class="flex items-center gap-1.5" :class="passLower ? 'text-emerald-700 font-bold' : 'text-slate-400'">
                                <i class="fas text-[10px]" :class="passLower ? 'fa-check-circle text-emerald-500' : 'fa-circle-notch'"></i>
                                <span>Huruf Kecil (a-z)</span>
                            </div>
                            <div class="flex items-center gap-1.5" :class="passNumber ? 'text-emerald-700 font-bold' : 'text-slate-400'">
                                <i class="fas text-[10px]" :class="passNumber ? 'fa-check-circle text-emerald-500' : 'fa-circle-notch'"></i>
                                <span>Angka (0-9)</span>
                            </div>
                            <div class="flex items-center gap-1.5 col-span-2" :class="passSymbol ? 'text-emerald-700 font-bold' : 'text-slate-400'">
                                <i class="fas text-[10px]" :class="passSymbol ? 'fa-check-circle text-emerald-500' : 'fa-circle-notch'"></i>
                                <span>Kode Unik / Simbol (@, #, $, %, !, *)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-extrabold rounded-xl shadow-lg shadow-blue-700/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Data User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
