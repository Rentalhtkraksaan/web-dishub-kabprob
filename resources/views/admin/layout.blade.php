<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Control Panel') | DISHUB Portal</title>
    <link rel="icon" type="image/png" href="{{ $settings['favicon'] ?? asset('images/logo_dishub.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            dark: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex" x-data="{ 
    sidebarOpen: false, 
    profileModalOpen: {{ session('show_force_change_password') || $errors->hasAny(['name', 'username', 'email', 'whatsapp', 'referral_code', 'password', 'avatar_file']) ? 'true' : 'false' }},
    showPassword: false 
}">

    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transform transition-transform duration-200 lg:translate-x-0 flex flex-col justify-between shadow-2xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 bg-slate-950 border-b border-slate-800 justify-between">
                <button type="button" @click="profileModalOpen = true" class="flex items-center gap-3 text-left hover:opacity-90 transition-opacity">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-9 h-9 rounded-xl object-cover border-2 {{ auth()->user()->isSuperAdmin() ? 'border-amber-400' : (auth()->user()->isAdmin() ? 'border-blue-400' : 'border-emerald-500') }} shadow-md">
                    @else
                        <div class="w-9 h-9 rounded-xl {{ auth()->user()->isSuperAdmin() ? 'bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-600 text-slate-950 font-black' : (auth()->user()->isAdmin() ? 'bg-gradient-to-br from-blue-600 to-indigo-800 text-white font-extrabold' : 'bg-gradient-to-br from-emerald-600 to-teal-800 text-white font-bold') }} flex items-center justify-center text-xs shadow-md">
                            {{ auth()->user()->isSuperAdmin() ? 'SA' : (auth()->user()->isAdmin() ? 'AD' : 'ST') }}
                        </div>
                    @endif
                    <div>
                        <h1 class="font-extrabold text-white text-sm tracking-wide line-clamp-1">{{ auth()->user()->name }}</h1>
                        @if(auth()->user()->isSuperAdmin())
                            <span class="px-2 py-0.5 rounded-md bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-black text-[9px] uppercase tracking-wider inline-flex items-center gap-1 shadow-xs">
                                👑 Super Admin
                            </span>
                        @elseif(auth()->user()->isAdmin())
                            <span class="px-2 py-0.5 rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black text-[9px] uppercase tracking-wider inline-flex items-center gap-1 shadow-xs">
                                🛡️ Admin
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-md bg-emerald-900/60 text-emerald-300 border border-emerald-700/50 font-bold text-[9px] uppercase tracking-wider inline-flex items-center gap-1">
                                👤 Anggota Staf
                            </span>
                        @endif
                    </div>
                </button>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <!-- Sidebar Navigation Menu Links -->
            <nav class="p-4 space-y-1 text-xs font-semibold overflow-y-auto max-h-[calc(100vh-140px)]">

                <!-- ===== KHUSUS STAF / ANGGOTA (HANYA DOKUMEN & LAYANAN) ===== -->
                @if(auth()->user()->isAnggota())
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest px-3 py-2 font-bold">Portal Petugas & Staf</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-home text-sm text-blue-400"></i> Dashboard Staf
                    </a>

                    <a href="{{ route('admin.documents') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.documents') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-file-alt text-sm text-blue-400"></i> Upload Dokumen & Berkas
                    </a>

                    <a href="{{ route('admin.services') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.services') ? 'bg-teal-700 text-white shadow-md shadow-teal-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-cogs text-sm text-teal-400"></i> Layanan Publik
                    </a>

                    <a href="{{ route('admin.messages') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.messages') ? 'bg-red-700 text-white shadow-md shadow-red-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-comments text-sm text-red-400"></i> Pesan & Pengaduan Warga
                    </a>

                    <a href="{{ route('admin.survei.responses') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.survei.responses') ? 'bg-amber-600 text-white shadow-md shadow-amber-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-chart-line text-sm text-amber-400"></i> Hasil Survei SKM Publik
                    </a>

                <!-- ===== KHUSUS ADMIN & SUPER ADMIN (LENGKAP & PROFESIONAL) ===== -->
                @else
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest px-3 py-2 font-bold">Menu Utama & Data</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-home text-sm text-blue-400"></i> Dashboard Utama
                    </a>


                    <a href="{{ route('admin.documents') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.documents') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-file-alt text-sm text-blue-400"></i> Dokumen & Berkas
                    </a>

                    <a href="{{ route('admin.services') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.services') ? 'bg-teal-700 text-white shadow-md shadow-teal-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-cogs text-sm text-teal-400"></i> Layanan Publik
                    </a>

                    <a href="{{ route('admin.news') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.news') ? 'bg-purple-700 text-white shadow-md shadow-purple-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-newspaper text-sm text-purple-400"></i> Berita & Informasi
                    </a>

                    <a href="{{ route('admin.videos') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.videos') ? 'bg-rose-700 text-white shadow-md shadow-rose-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fab fa-youtube text-sm text-rose-400"></i> Video Dokumentasi
                    </a>

                    <a href="{{ route('admin.gallery') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.gallery') ? 'bg-amber-600 text-white shadow-md shadow-amber-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-images text-sm text-amber-400"></i> Album Galeri Foto
                    </a>

                    <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Konten & Tampilan Web</div>

                    <!-- FOLDER / DROPDOWN: KELOLA TAMPILAN DEPAN -->
                    <div x-data="{ homeFolderOpen: {{ request()->routeIs(['admin.sliders', 'admin.menus', 'admin.pages', 'admin.widgets', 'admin.links', 'admin.org_chart', 'admin.informasi_tabs']) ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="homeFolderOpen = !homeFolderOpen" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all font-bold text-xs {{ request()->routeIs(['admin.sliders', 'admin.menus', 'admin.pages', 'admin.widgets', 'admin.links', 'admin.org_chart', 'admin.informasi_tabs']) ? 'bg-blue-900/60 text-white border border-blue-700/50' : 'hover:bg-slate-800 text-slate-300' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-desktop text-amber-400 text-sm"></i>
                                <span>Kelola Halaman Web</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': homeFolderOpen }"></i>
                        </button>

                        <div x-show="homeFolderOpen" x-cloak class="pl-4 space-y-1 border-l-2 border-slate-800 ml-3 pt-1">
                            <a href="{{ route('admin.sliders') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.sliders') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-images text-amber-400"></i> Foto Banner Depan
                            </a>

                            <a href="{{ route('admin.pages') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.pages') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-file-signature text-rose-400"></i> Halaman Profil & Layanan
                            </a>

                            <a href="{{ route('admin.org_chart') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.org_chart') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-sitemap text-indigo-400"></i> Bagan Struktur Organisasi
                            </a>

                            <a href="{{ route('admin.menus') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.menus') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-bars text-cyan-400"></i> Menu Navigasi Atas
                            </a>

                            <a href="{{ route('admin.informasi_tabs') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.informasi_tabs') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-table-columns text-violet-400"></i> Tab Menu Informasi
                            </a>

                            <a href="{{ route('admin.widgets') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.widgets') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-th-large text-emerald-400"></i> Kotak Informasi Samping
                            </a>

                            <a href="{{ route('admin.links') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all text-xs {{ request()->routeIs('admin.links') ? 'bg-blue-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                <i class="fas fa-link text-teal-400"></i> Link Terkait
                            </a>
                        </div>
                    </div>

                    <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Layanan & Pengaturan</div>

                    <a href="{{ route('admin.messages') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.messages') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-comments text-sm text-red-400"></i> Pesan & Pengaduan Warga
                    </a>

                    <a href="{{ route('admin.survei.responses') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.survei.responses') ? 'bg-amber-600 text-white shadow-md shadow-amber-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-chart-line text-sm text-amber-400"></i> Hasil Survei SKM Publik
                    </a>

                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-users-cog text-sm text-indigo-400"></i> Akun Pengguna & Petugas
                    </a>

                    <a href="{{ route('admin.logs') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.logs') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-history text-sm text-emerald-400"></i> Catatan Aktivitas Sistem
                    </a>

                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings') ? 'bg-blue-700 text-white shadow-md shadow-blue-900/40 font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                        <i class="fas fa-cog text-sm text-slate-400"></i> Pengaturan Website
                    </a>
                @endif

                <div class="pt-4 text-[10px] text-slate-500 uppercase tracking-widest px-3 py-1 font-bold">Public Portal</div>
                
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition-all">
                    <i class="fas fa-external-link-alt text-sm"></i> Lihat Website Utama
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    <i class="fas fa-sign-out-alt"></i> Logout Panel
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-grow lg:ml-64 flex flex-col min-h-screen w-full overflow-x-hidden">
        
        <!-- Topbar Header -->
        <header class="h-20 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="font-extrabold text-slate-800 text-base sm:text-lg tracking-tight">@yield('page_title', 'Control Panel')</h2>
                    <p class="text-[11px] text-slate-500 hidden sm:block">Control Panel DISHUB Kabupaten Probolinggo</p>
                </div>
            </div>
            
            <button type="button" @click="profileModalOpen = true" class="flex items-center gap-3 text-left hover:bg-slate-50 p-2 rounded-2xl transition-all cursor-pointer border border-transparent hover:border-slate-200">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-800 leading-tight flex items-center gap-1.5 justify-end">
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fas fa-edit text-[10px] text-blue-600"></i>
                    </p>
                    <p class="text-[10px] text-blue-700 font-semibold uppercase">{{ auth()->user()->role }}</p>
                </div>
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-10 h-10 rounded-xl object-cover border border-blue-200 shadow-sm">
                @else
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm border border-blue-200 shadow-sm">
                        <i class="fas fa-user-shield"></i>
                    </div>
                @endif
            </button>
        </header>

        <!-- Dynamic Content Body -->
        <main class="p-4 sm:p-8 flex-grow space-y-6">
            
            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Flash Error Message -->
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Admin Footer -->
        <footer class="bg-white border-t border-slate-200 py-4 px-8 text-center text-xs text-slate-400">
            DISHUB Kabupaten Probolinggo &copy; 2026. Super Admin Control Panel.
        </footer>
    </div>

    <!-- MODAL EDIT PROFIL & AKUN SAAT DIKLIK Lencana USN/PP -->
    <div x-show="profileModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="profileModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 border border-slate-100 relative">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-base">Edit Data Diri & Profil Saya</h3>
                        <p class="text-xs text-slate-500">Kelola Nama Lengkap, Username, Email, Password, dan Foto Profil (PP)</p>
                    </div>
                </div>
                <button type="button" @click="profileModalOpen = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                
                @if($errors->hasAny(['name', 'username', 'email', 'whatsapp', 'referral_code', 'password', 'avatar_file']))
                    <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-rose-700 mb-2">
                        <strong class="block mb-1"><i class="fas fa-exclamation-circle"></i> Terdapat Kesalahan:</strong>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach(['name', 'username', 'email', 'whatsapp', 'referral_code', 'password', 'avatar_file'] as $field)
                                @if($errors->has($field))
                                    @foreach($errors->get($field) as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Preview Foto Profil (PP) -->
                <div class="flex items-center gap-4 bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80">
                    <div class="relative shrink-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Preview PP" class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-xl shadow-md">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow space-y-1">
                        <label class="block font-bold text-slate-700">Unggah Foto Profil (PP) Baru</label>
                        <input type="file" name="avatar_file" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                        <p class="text-[10px] text-slate-400">Format: JPG, PNG, WEBP (Max 2MB)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Username Login</label>
                        <input type="text" name="username" value="{{ auth()->user()->username }}" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Resmi (Wajib @gmail.com)</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" required placeholder="contoh@gmail.com" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 flex items-center gap-1">
                            <i class="fab fa-whatsapp text-emerald-500"></i> No. WhatsApp (Validasi 1)
                        </label>
                        <input type="text" name="whatsapp" value="{{ auth()->user()->whatsapp }}" placeholder="081234567890" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 flex items-center gap-1">
                            <i class="fas fa-key text-blue-500"></i> Kode Referral (Validasi 2)
                        </label>
                        <input type="text" name="referral_code" value="{{ auth()->user()->referral_code }}" placeholder="MAMAD2026" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-semibold uppercase">
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <label class="block font-bold text-slate-700 mb-1">Password Baru (Biarkan Kosong Jika Tidak Ingin Mengubah)</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Dishub#2026!" class="w-full px-3.5 py-2.5 pr-10 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors p-1" title="Lihat / Sembunyikan Password">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash text-blue-600' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" @click="profileModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-extrabold shadow-lg shadow-blue-700/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan Akun</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Auto CSRF Keep-Alive (Refresh every 15 mins to maintain 30m-2h standard session) -->
    <script>
        (function() {
            setInterval(function() {
                fetch('{{ route("csrf.token") }}', {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data && data.token) {
                        var meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta) meta.setAttribute('content', data.token);
                        document.querySelectorAll('input[name="_token"]').forEach(function(el) {
                            el.value = data.token;
                        });
                    }
                })
                .catch(function() {});
            }, 15 * 60 * 1000);
        })();
    </script>
    @stack('scripts')
</body>
</html>
