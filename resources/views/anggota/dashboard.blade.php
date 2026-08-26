<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anggota Dashboard | DISHUB Kabupaten Probolinggo</title>
    
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
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen">

    <!-- Top Navigation Header -->
    <header class="bg-slate-900 text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center font-extrabold text-sm shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="font-extrabold text-sm tracking-tight flex items-center gap-2">
                        <span>Panel Staf Anggota DISHUB</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] uppercase tracking-wider font-extrabold border border-emerald-500/30">
                            Role: Anggota
                        </span>
                    </h1>
                    <p class="text-[10px] text-slate-400">Website Resmi Dinas Perhubungan Kab. Probolinggo</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    <i class="fas fa-external-link-alt text-xs"></i> Website Utama
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="relative z-10 space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-emerald-200 text-xs font-extrabold">
                    <i class="fas fa-user-shield"></i> Portal Operasional Anggota
                </div>
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-xs sm:text-sm text-emerald-100/90 font-medium leading-relaxed">
                    Anda telah berhasil login sebagai <strong class="text-white">Anggota Staf DISHUB</strong>. Anda memiliki akses untuk mengunggah & mengelola <strong class="text-white">Dokumen Publik Kinerja</strong> dan <strong class="text-white">Layanan Publik DISHUB</strong>.
                </p>
                <div class="pt-3 flex flex-wrap gap-3">
                    <a href="{{ route('admin.documents') }}" class="px-4 py-2.5 bg-white text-emerald-900 font-extrabold text-xs rounded-xl shadow-lg hover:bg-emerald-50 transition flex items-center gap-2">
                        <i class="fas fa-file-alt text-emerald-700"></i> Kelola Dokumen Publik
                    </a>
                    <a href="{{ route('admin.services') }}" class="px-4 py-2.5 bg-emerald-800/80 hover:bg-emerald-800 text-white font-extrabold text-xs rounded-xl border border-emerald-600/50 transition flex items-center gap-2">
                        <i class="fas fa-cogs text-emerald-300"></i> Kelola Layanan Publik
                    </a>
                </div>
            </div>
        </div>

        <!-- Metrics Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Berita Portal</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['news'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dokumen Kinerja</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['documents'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pembaca Berita</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_views']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">
                    <i class="fas fa-eye"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas Saya</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['my_activities'] }} Log</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                    <i class="fas fa-history"></i>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Latest News -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fas fa-newspaper text-purple-600"></i> Berita LLAJ Terbaru
                    </h3>
                    <a href="{{ route('admin.news') }}" class="text-xs text-blue-600 hover:underline font-bold">Kelola Semua &rarr;</a>
                </div>
                <div class="space-y-3">
                    @forelse($latestNews as $news)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-slate-100/80 transition border border-slate-100">
                            <div class="space-y-1 max-w-md">
                                <h4 class="font-bold text-slate-900 text-xs line-clamp-1">{{ $news->title }}</h4>
                                <p class="text-[10px] text-slate-500 font-medium">{{ $news->published_at }} &bull; Category: {{ $news->category }}</p>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-600 bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                <i class="fas fa-eye text-[10px] text-amber-500"></i> {{ $news->views }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada berita terpublikasi.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Personal Activity Logs -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fas fa-user-clock text-emerald-600"></i> Log Aktivitas Terakhir Saya
                    </h3>
                    <span class="text-xs text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full font-bold border border-emerald-200">
                        Terperekam Otomatis
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivities as $act)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-extrabold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded text-[10px]">
                                    {{ $act->action }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">
                                    {{ $act->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-700 font-medium">{{ $act->description }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada catatan aktivitas.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ===== STRUKTUR ORGANISASI DISHUB BAGAN VISUAL (ANGGOTA DASHBOARD) ===== -->
        @if(isset($orgNodes) && count($orgNodes) > 0)
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-sitemap text-indigo-600"></i> Bagan Struktur Organisasi DISHUB Kabupaten Probolinggo
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Susunan hirarki garis komando dan garis koordinasi pejabat internal DISHUB</p>
                </div>

                <div class="space-y-4 max-w-5xl mx-auto">
                    @foreach($orgNodes as $node)
                        <!-- KEPALA DINAS -->
                        <div class="border-2 border-amber-400 bg-amber-50/60 rounded-2xl p-5 shadow-sm space-y-4">
                            <div class="flex items-center gap-3.5">
                                @if($node->image_url)
                                    <img src="{{ $node->image_url }}" alt="{{ $node->name }}" class="w-12 h-12 rounded-2xl object-cover shadow-md shrink-0 border border-amber-300">
                                @else
                                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl font-extrabold shadow-md shrink-0">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-200 text-amber-900 font-extrabold text-[10px] uppercase tracking-wider">
                                        {{ $node->level_type ?? 'Pimpinan Instansi' }}
                                    </span>
                                    <h3 class="text-base font-black text-slate-900 mt-0.5">{{ $node->title }}</h3>
                                    <p class="text-xs text-amber-900 font-bold">
                                        {{ $node->name ?? $node->official_name ?? '-' }} <span class="text-slate-500 font-normal">| NIP. {{ $node->nip ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- SEKRETARIAT, BIDANG, FUNGSIONAL, UPT -->
                            @if($node->children && count($node->children) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-3 border-t border-amber-200/80">
                                    @foreach($node->children as $child)
                                        <div class="bg-white rounded-2xl p-4 border {{ $child->line_type === 'koordinasi' ? 'border-dashed border-purple-400 bg-purple-50/30' : 'border-slate-200 shadow-xs' }} space-y-3">
                                            <div class="flex items-start gap-3">
                                                @if($child->image_url)
                                                    <img src="{{ $child->image_url }}" alt="{{ $child->name }}" class="w-10 h-10 rounded-xl object-cover shadow-xs shrink-0 border border-slate-200">
                                                @endif
                                                <div class="flex-grow">
                                                    <span class="px-2 py-0.5 rounded-md font-bold text-[9px] uppercase tracking-wider {{ $child->line_type === 'koordinasi' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $child->line_type === 'koordinasi' ? '🔗 Garis Koordinasi' : '⬇️ Garis Komando' }}
                                                    </span>
                                                    <h4 class="font-extrabold text-slate-900 text-xs mt-1">{{ $child->title }}</h4>
                                                    <p class="text-[11px] text-slate-600 font-semibold">{{ $child->name ?? $child->official_name ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <!-- SUBBAG & SEKSI -->
                                            @if($child->children && count($child->children) > 0)
                                                <div class="space-y-2 pt-2 border-t border-slate-100 pl-3">
                                                    @foreach($child->children as $sub)
                                                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px]">
                                                            <span class="font-bold text-slate-800 block">{{ $sub->title }}</span>
                                                            <span class="text-[10px] text-slate-500">{{ $sub->name ?? $sub->official_name ?? '-' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </main>

</body>
</html>
