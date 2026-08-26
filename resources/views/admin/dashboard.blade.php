@extends('admin.layout')

@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-6" @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin') x-data='analyticsChart(@json($visitorStats))' @endif>
    
    <!-- Welcome Banner Card -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <span class="px-3 py-1 bg-blue-800/50 backdrop-blur-sm border border-blue-500/30 text-amber-300 text-xs font-bold rounded-full uppercase tracking-wider">
                Control Panel DISHUB System
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-xs text-blue-100 max-w-xl leading-relaxed">
                Anda masuk sebagai <strong class="uppercase text-amber-300 font-extrabold">{{ auth()->user()->role }}</strong>. 
                @if(auth()->user()->isSuperAdmin())
                    Anda memiliki akses penuh untuk mengelola seluruh konten, menu header, slider banner, dokumen, berita, dan hak akses pengguna.
                @else
                    Hak akses Anda terbatas pada pengelolaan berkas <strong>Dokumen Kinerja</strong> dan artikel <strong>Informasi/Berita</strong>.
                @endif
            </p>
        </div>
        <div class="absolute right-4 -bottom-6 opacity-10 text-white text-9xl pointer-events-none">
            <i class="fas fa-bus"></i>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Dokumen Kinerja</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['documents'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Total Berita</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['news'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Banner Foto Depan</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $stats['sliders'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500">Total Pembaca Web</p>
                <h3 class="text-2xl font-extrabold text-slate-800" @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin') x-text="currentTotal" @endif>{{ $stats['total_views'] }}</h3>
            </div>
        </div>

    </div>

    <!-- Chart Analytics -->
    @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
    <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-slate-200/80 shadow-md mt-6" id="visitorChart-container">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-6">
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-600"></i> Statistik Pengunjung Website
                </h3>
                <p class="text-xs text-slate-500 mt-1">Total Kunjungan: <strong class="text-blue-700" x-text="stats.total"></strong> views</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter Tahun -->
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center">
                    <select name="year" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl px-3 py-1.5 focus:ring-2 focus:ring-blue-600 focus:outline-none cursor-pointer shadow-sm">
                        @php $currentYear = now()->year; @endphp
                        @for($y = 2026; $y <= ($currentYear > 2026 ? $currentYear : 2026); $y++)
                            <option value="{{ $y }}" {{ $visitorStats['selectedYear'] == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endfor
                    </select>
                </form>

                <!-- Tabs Chart Type -->
                <div class="bg-slate-100 p-1 rounded-xl flex items-center text-xs font-bold">
                    <button @click="changeType('weekly')" :class="{'bg-white shadow-sm text-blue-700': type === 'weekly', 'text-slate-500 hover:text-slate-700': type !== 'weekly'}" class="px-3 py-1.5 rounded-lg transition-all">Harian</button>
                    <button @click="changeType('monthly')" :class="{'bg-white shadow-sm text-blue-700': type === 'monthly', 'text-slate-500 hover:text-slate-700': type !== 'monthly'}" class="px-3 py-1.5 rounded-lg transition-all">Mingguan</button>
                    <button @click="changeType('yearly')" :class="{'bg-white shadow-sm text-blue-700': type === 'yearly', 'text-slate-500 hover:text-slate-700': type !== 'yearly'}" class="px-3 py-1.5 rounded-lg transition-all">Bulanan</button>
                </div>
            </div>
        </div>

        <div class="relative h-[300px] w-full">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>
    @endif

    <!-- PINTASAN AKSES CEPAT (QUICK ACTIONS FOR NON-TECHNICAL USERS) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-blue-500/20 shadow-md space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600 animate-pulse"></span>
                    ⚡ Apa Yang Ingin Anda Tambah / Olah Hari Ini?
                </h3>
                <p class="text-xs text-slate-500">Pilih tombol pintasan di bawah ini untuk langsung menuju menu penambahan data.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-extrabold text-[11px] uppercase tracking-wider hidden sm:inline-block">
                Akses Pintar
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            
            <!-- 1. Upload Dokumen Baru (Semua Role) -->
            <a href="{{ route('admin.documents') }}" 
               class="group p-4 bg-gradient-to-br from-blue-50 to-cyan-50/50 hover:from-blue-600 hover:to-cyan-600 rounded-2xl border border-blue-200 hover:border-blue-600 transition-all duration-200 flex flex-col items-center text-center shadow-xs hover:shadow-lg hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 group-hover:bg-white text-white group-hover:text-blue-600 flex items-center justify-center text-xl shadow-md transition-colors mb-2">
                    <i class="fas fa-file-upload"></i>
                </div>
                <span class="text-xs font-extrabold text-blue-950 group-hover:text-white transition-colors">Upload Dokumen</span>
                <span class="text-[10px] text-blue-700 group-hover:text-blue-100 transition-colors mt-0.5">Laporan & Berkas</span>
            </a>

            <!-- 2. Kelola Layanan Publik (Semua Role) -->
            <a href="{{ route('admin.services') }}" 
               class="group p-4 bg-gradient-to-br from-teal-50 to-emerald-50/50 hover:from-teal-600 hover:to-emerald-600 rounded-2xl border border-teal-200 hover:border-teal-600 transition-all duration-200 flex flex-col items-center text-center shadow-xs hover:shadow-lg hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-teal-600 group-hover:bg-white text-white group-hover:text-teal-600 flex items-center justify-center text-xl shadow-md transition-colors mb-2">
                    <i class="fas fa-cogs"></i>
                </div>
                <span class="text-xs font-extrabold text-teal-950 group-hover:text-white transition-colors">Layanan Publik</span>
                <span class="text-[10px] text-teal-700 group-hover:text-teal-100 transition-colors mt-0.5">Tambah & Edit</span>
            </a>

            @if(!auth()->user()->isAnggota())
                <!-- 3. Tambah Berita Baru (Admin/Super Admin) -->
                <a href="{{ route('admin.news') }}" 
                   class="group p-4 bg-gradient-to-br from-purple-50 to-indigo-50/50 hover:from-purple-600 hover:to-indigo-600 rounded-2xl border border-purple-200 hover:border-purple-600 transition-all duration-200 flex flex-col items-center text-center shadow-xs hover:shadow-lg hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600 group-hover:bg-white text-white group-hover:text-purple-600 flex items-center justify-center text-xl shadow-md transition-colors mb-2">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <span class="text-xs font-extrabold text-purple-950 group-hover:text-white transition-colors">Kelola Berita</span>
                    <span class="text-[10px] text-purple-700 group-hover:text-purple-100 transition-colors mt-0.5">Artikel & Info</span>
                </a>

                <!-- 4. Pasang Banner Depan -->
                <a href="{{ route('admin.sliders') }}" 
                   class="group p-4 bg-gradient-to-br from-amber-50 to-orange-50/50 hover:from-amber-500 hover:to-orange-500 rounded-2xl border border-amber-200 hover:border-amber-500 transition-all duration-200 flex flex-col items-center text-center shadow-xs hover:shadow-lg hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 group-hover:bg-white text-white group-hover:text-amber-600 flex items-center justify-center text-xl shadow-md transition-colors mb-2">
                        <i class="fas fa-images"></i>
                    </div>
                    <span class="text-xs font-extrabold text-amber-950 group-hover:text-white transition-colors">Banner Depan</span>
                    <span class="text-[10px] text-amber-700 group-hover:text-amber-100 transition-colors mt-0.5">Slider Beranda</span>
                </a>

                <!-- 5. Pesan & Pengaduan Warga -->
                <a href="{{ route('admin.messages') }}" 
                   class="group p-4 bg-gradient-to-br from-red-50 to-rose-50/50 hover:from-red-600 hover:to-rose-600 rounded-2xl border border-red-200 hover:border-red-600 transition-all duration-200 flex flex-col items-center text-center shadow-xs hover:shadow-lg hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-red-600 group-hover:bg-white text-white group-hover:text-red-600 flex items-center justify-center text-xl shadow-md transition-colors mb-2">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span class="text-xs font-extrabold text-red-950 group-hover:text-white transition-colors">Pesan & Laporan</span>
                    <span class="text-[10px] text-red-700 group-hover:text-red-100 transition-colors mt-0.5">Aspirasi Warga</span>
                </a>

                <!-- 6. Akun & Pengguna -->
                <a href="{{ route('admin.users') }}" 
                   class="group p-4 bg-gradient-to-br from-emerald-50 to-teal-50/50 hover:from-emerald-600 hover:to-teal-600 rounded-2xl border border-emerald-200 hover:border-emerald-600 transition-all duration-200 flex flex-col items-center text-center shadow-xs hover:shadow-lg hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 group-hover:bg-white text-white group-hover:text-emerald-600 flex items-center justify-center text-xl shadow-md transition-colors mb-2">
                        <i class="fas fa-users-gear"></i>
                    </div>
                    <span class="text-xs font-extrabold text-emerald-950 group-hover:text-white transition-colors">Kelola Akun</span>
                    <span class="text-[10px] text-emerald-700 group-hover:text-emerald-100 transition-colors mt-0.5">Hak Akses</span>
                </a>
            @endif

        </div>
    </div>

    <!-- Quick Access Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Latest Documents Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i> Dokumen Kinerja Terkini
                </h3>
                <a href="{{ route('admin.documents') }}" class="text-xs text-blue-600 font-bold hover:underline">Kelola Dokumen &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($latestDocs as $doc)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <h4 class="font-bold text-slate-800 line-clamp-1">{{ $doc->title }}</h4>
                            <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">{{ $doc->category }}</span>
                        </div>
                        <a href="{{ $doc->file_url }}" target="_blank" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Latest News Management Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-newspaper text-purple-600"></i> Berita Terkini
                </h3>
                <a href="{{ route('admin.news') }}" class="text-xs text-purple-600 font-bold hover:underline">Kelola Berita &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($latestNews as $news)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957' }}" alt="News" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-grow">
                            <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $news->title }}</h4>
                            <p class="text-[10px] text-slate-400">{{ optional($news->published_at)->format('d M Y') }} • {{ $news->views }} views</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ===== STRUKTUR ORGANISASI DISHUB BAGAN VISUAL (DASHBOARD) ===== -->
    @if(isset($orgNodes) && count($orgNodes) > 0)
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-sitemap text-indigo-600"></i> Bagan Struktur Organisasi DISHUB Kabupaten Probolinggo
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Susunan hirarki garis komando dan garis koordinasi pejabat internal DISHUB</p>
                </div>
                <a href="{{ route('admin.org_chart') }}" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-extrabold text-xs rounded-xl transition-all shadow-xs inline-flex items-center gap-1.5 shrink-0">
                    <i class="fas fa-edit"></i> Edit Structure & Hirarki &rarr;
                </a>
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

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('analyticsChart', (stats) => ({
            stats: stats,
            type: 'weekly',
            chartInstance: null,
            currentTotal: 0,

            init() {
                this.updateTotal();
                this.$nextTick(() => {
                    this.initChart();
                });
            },

            updateTotal() {
                if (this.stats && this.stats[this.type] && this.stats[this.type].data) {
                    this.currentTotal = this.stats[this.type].data.reduce((a, b) => a + b, 0);
                } else {
                    this.currentTotal = 0;
                }
            },

            changeType(newType) {
                if (this.type === newType) return;
                this.type = newType;
                this.updateTotal();
                this.updateChart();
            },

            initChart() {
                const ctx = document.getElementById('visitorChart').getContext('2d');
                let currentData = JSON.parse(JSON.stringify(this.stats[this.type]));

                if (window.myVisitorChart) {
                    window.myVisitorChart.destroy();
                }

                window.myVisitorChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: currentData.labels,
                        datasets: [{
                            label: 'Pengunjung Web',
                            data: currentData.data,
                            borderColor: '#7cb5ec', // Light blue line
                            backgroundColor: '#7cb5ec', // Light blue dots
                            borderWidth: 2,
                            pointBackgroundColor: '#7cb5ec',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: false, // No gradient background
                            tension: 0 // Straight lines
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 400
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Diagram Statistik Pengunjung Website',
                                font: { size: 16, family: "'Inter', sans-serif", weight: 'normal' },
                                color: '#333'
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 13, family: "'Inter', sans-serif" },
                                bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' Views';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Pengunjung (Y)',
                                    font: { family: "'Inter', sans-serif", weight: 'bold' }
                                },
                                grid: {
                                    color: '#e2e8f0', // Visible horizontal grid
                                    drawBorder: true,
                                    borderColor: '#000000', // Solid Y axis line
                                    borderWidth: 1
                                },
                                ticks: {
                                    font: { family: "'Inter', sans-serif", size: 11 },
                                    color: '#333'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Waktu (X)',
                                    font: { family: "'Inter', sans-serif", weight: 'bold' }
                                },
                                grid: {
                                    display: false, // No vertical grid lines
                                    drawBorder: true,
                                    borderColor: '#000000', // Solid X axis line
                                    borderWidth: 1
                                },
                                ticks: {
                                    font: { family: "'Inter', sans-serif", size: 11 },
                                    color: '#333'
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            },

            updateChart() {
                if (window.myVisitorChart) {
                    let currentData = JSON.parse(JSON.stringify(this.stats[this.type]));
                    window.myVisitorChart.data.labels = currentData.labels;
                    window.myVisitorChart.data.datasets[0].data = currentData.data;
                    window.myVisitorChart.update();
                } else {
                    this.initChart();
                }
            }
        }));
    });
</script>
@endpush
@endsection
