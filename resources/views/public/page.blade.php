@extends('public.layouts.app')

@section('title', $page->title . ' | DISHUB Kabupaten Probolinggo')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($page->content), 150))

@section('content')
<!-- Page Banner Header -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #0a1f3d 0%, #1e3a8a 100%); border-bottom: 3px solid var(--dishub-gold);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2 text-white-50" style="font-size: 0.82rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $page->title }}</li>
                    </ol>
                </nav>
                <h1 class="font-weight-bold mb-2 text-white" style="font-size: 2.2rem; font-family: 'Outfit', sans-serif;">
                    {{ $page->title }}
                </h1>
                <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                    Portal Informasi & Pelayanan Publik Dinas Perhubungan Kabupaten Probolinggo
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Main Page Content -->
<section class="py-5 bg-light">
    <div class="container-fluid px-3 px-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                
                <article class="bg-white p-4 p-md-5 rounded shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                    
                    <!-- DISPLAY HASIL SKM & TOMBOL ISI SURVEI -->
                    @if($page->slug === 'survei-kepuasan-masyarakat' || request()->is('*survei-kepuasan-masyarakat*'))
                        <div class="mb-5 p-4 rounded-3xl text-white shadow-lg" style="background: linear-gradient(135deg, #0f2b5c 0%, #1e40af 100%); border-radius: 24px;">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-8 mb-3 mb-md-0">
                                    <span class="badge badge-warning text-dark font-weight-extrabold text-xs px-3 py-1 mb-2" style="border-radius: 20px;">
                                        <i class="fas fa-chart-line mr-1"></i> RESMI & TRANSPARAN
                                    </span>
                                    <h3 class="font-weight-black text-white mb-2" style="font-size: 1.5rem;">Hasil Indeks Kepuasan Masyarakat (IKM)</h3>
                                    <p class="text-white-50 text-sm mb-0">
                                        Survei ini mengukur 9 unsur pelayanan publik Dinas Perhubungan Kabupaten Probolinggo secara berkesinambungan.
                                    </p>
                                </div>
                                <div class="col-12 col-md-4 text-md-right">
                                    <a href="{{ route('survei') }}" class="btn btn-warning text-dark font-weight-extrabold px-4 py-3 shadow-lg hover-scale" style="border-radius: 14px; font-size: 0.95rem; outline: none; box-shadow: none !important;">
                                        <i class="fas fa-edit mr-2"></i> Isi Survei SKM Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- BAGAN STRUKTUR ORGANISASI INTERAKTIF & GARIS KOMANDO -->
                    @if(isset($orgChartRoots) && count($orgChartRoots) > 0 && ($page->slug === 'struktur-organisasi' || request()->is('*struktur-organisasi*')))
                        <div class="mb-5 p-3 p-md-4 rounded bg-light border shadow-sm">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
                                <div>
                                    <span class="badge badge-pill badge-primary px-3 py-1 mb-2 font-weight-bold" style="background: rgba(37,99,235,0.15); color: #1e40af;">
                                        <i class="fas fa-sitemap mr-1"></i> HIRARKI RESMI DISHUB
                                    </span>
                                    <h3 class="font-weight-bold m-0" style="font-size: 1.35rem; color: #0f2b5c;">
                                        Bagan Struktur Organisasi & Garis Komando
                                    </h3>
                                </div>
                                <div class="mt-3 mt-md-0 d-flex gap-2 align-items-center">
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-arrow-down mr-1"></i> Garis Komando</span>
                                    <span class="badge badge-warning text-dark px-2 py-1 ml-2"><i class="fas fa-arrows-alt-h mr-1"></i> Garis Koordinasi</span>
                                </div>
                            </div>

                            <!-- Interactive Org Chart Tree Container -->
                            <div class="org-tree-wrapper overflow-auto py-3">
                                <ul class="org-tree-root list-unstyled text-center mb-0">
                                    @foreach($orgChartRoots as $root)
                                        @include('public.partials.org_node', ['node' => $root])
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- HIGH IMPACT CLEAN PRESENTATION UNTUK VISI MISI -->
                    @if($page->slug === 'visi-misi' || request()->is('*visi-misi*'))
                        @php
                            $cleanContent = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '-------------', '</h2>', '</h1>'], "\n", $page->content));
                            
                            $visiItems = [
                                "Terwujudnya Sistem Transportasi dan Lalu Lintas Kabupaten Probolinggo yang Handal, Safe, Tertib, dan Terintegrasi."
                            ];
                            $misiItems = [
                                "Meningkatkan keselamatan dan ketertiban lalu lintas jalan.",
                                "Memperkuat kualitas pelayanan pengujian kendaraan bermotor.",
                                "Membangun dan merawat prasarana penerangan jalan umum & rambu keselamatan."
                            ];

                            if (preg_match('/Visi:\s*(.*?)(?=Misi:|$)/is', $cleanContent, $visiMatch)) {
                                $parsedVisiStr = trim($visiMatch[1]);
                                $splitVisi = preg_split('/\d+\.\s*/', $parsedVisiStr, -1, PREG_SPLIT_NO_EMPTY);
                                if (count($splitVisi) > 0) {
                                    $visiItems = array_map('trim', array_filter($splitVisi));
                                } elseif (!empty($parsedVisiStr)) {
                                    $visiItems = [$parsedVisiStr];
                                }
                            } elseif (!empty(trim($cleanContent)) && !str_contains($cleanContent, 'Misi:')) {
                                $visiItems = [trim($cleanContent)];
                            }

                            if (preg_match('/Misi:\s*(.*)/is', $cleanContent, $misiMatch)) {
                                $parsedMisiStr = trim($misiMatch[1]);
                                $splitMisi = preg_split('/\d+\.\s*/', $parsedMisiStr, -1, PREG_SPLIT_NO_EMPTY);
                                if (count($splitMisi) > 0) {
                                    $misiItems = array_map('trim', array_filter($splitMisi));
                                }
                            }
                        @endphp

                        <!-- HEADER BANNER VISI MISI -->
                        <div class="mb-4 p-4 rounded-2xl text-white shadow-md" style="background: linear-gradient(135deg, #0f2b5c 0%, #1e40af 100%); border-left: 6px solid #f59e0b;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold text-uppercase" style="letter-spacing: 1px;">
                                    <i class="fas fa-bullseye mr-1"></i> VISI & MISI RESMI
                                </span>
                            </div>
                            <h3 class="font-weight-bold text-white mb-1" style="font-family: 'Outfit', sans-serif;">Dinas Perhubungan Kabupaten Probolinggo</h3>
                            <p class="text-white-50 mb-0 small">Pedoman arah pembangunan & komitmen pelayanan transportasi publik terpadu.</p>
                        </div>

                        <!-- KARTU VISI UTAMA -->
                        <div class="mb-5 p-4 p-md-5 rounded-2xl bg-white border shadow-sm" style="border-color: #bfdbfe !important; border-left: 6px solid #2563eb !important; background: #f8fafc;">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shrink-0" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div>
                                    <span class="badge badge-pill badge-primary px-3 py-1 font-weight-bold text-uppercase" style="background: rgba(37,99,235,0.15); color: #1e40af; font-size: 0.75rem;">
                                        VISI UTAMA
                                    </span>
                                    <h4 class="font-weight-bold text-dark m-0 mt-1" style="font-size: 1.25rem; color: #0f172a !important; font-family: 'Outfit', sans-serif;">
                                        Visi Dinas Perhubungan
                                    </h4>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @foreach($visiItems as $vIndex => $visi)
                                    <div class="p-3.5 p-md-4 rounded-xl bg-white border shadow-sm d-flex align-items-center gap-3" style="border-color: #cbd5e1 !important; border-left: 4px solid #f59e0b !important;">
                                        @if(count($visiItems) > 1)
                                            <span class="badge badge-warning text-dark rounded-circle d-flex align-items-center justify-content-center font-weight-bold shrink-0 shadow-sm" style="width: 32px; height: 32px; font-size: 0.95rem; background: #f59e0b; color: #000;">
                                                {{ $vIndex + 1 }}
                                            </span>
                                        @else
                                            <i class="fas fa-quote-left text-primary mr-1 shrink-0" style="font-size: 1.2rem;"></i>
                                        @endif
                                        <div class="text-dark font-weight-bold" style="font-size: 1.1rem; line-height: 1.65; color: #0f172a !important;">
                                            {{ trim(trim($visi, '"\'')) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- KARTU DAFTAR MISI -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center shrink-0 font-weight-bold" style="width: 40px; height: 40px; font-size: 1.1rem; background: #f59e0b; color: #000;">
                                    <i class="fas fa-list-check"></i>
                                </div>
                                <div>
                                    <span class="badge badge-pill badge-warning text-dark px-3 py-1 font-weight-bold text-uppercase" style="font-size: 0.75rem;">
                                        MISI STRATEGIS
                                    </span>
                                    <h4 class="font-weight-bold text-dark m-0 mt-1" style="font-size: 1.25rem; color: #0f172a !important; font-family: 'Outfit', sans-serif;">
                                        Misi Dinas Perhubungan
                                    </h4>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($misiItems as $mIndex => $misi)
                                    <div class="col-12 mb-3">
                                        <div class="p-3.5 p-md-4 rounded-xl bg-white border shadow-sm d-flex align-items-center gap-3" style="border-color: #e2e8f0 !important; border-left: 5px solid #2563eb !important;">
                                            <span class="badge badge-primary rounded-circle d-flex align-items-center justify-content-center font-weight-bold shrink-0 shadow-sm" style="width: 34px; height: 34px; font-size: 0.95rem; background: #2563eb; color: #fff;">
                                                {{ $mIndex + 1 }}
                                            </span>
                                            <div class="text-dark font-weight-bold" style="font-size: 1.05rem; line-height: 1.65; color: #0f172a !important;">
                                                {{ trim(trim($misi, '"\'')) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @elseif($page->slug === 'tugas-dan-fungsi' || request()->is('*tugas-dan-fungsi*'))
                        @php
                            $cleanContent = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '-------------', '</h2>', '</h1>'], "\n", $page->content));
                            
                            $tugasText = "Dinas Perhubungan mempunyai tugas membantu Bupati melaksanakan urusan pemerintahan daerah di bidang Perhubungan.";
                            $fungsiItems = [
                                "Perumusan kebijakan teknis di bidang lalu lintas, angkutan, sarana dan prasarana transportasi.",
                                "Pelaksanaan tugas dukungan teknis di bidang keselamatan lalu lintas dan kelaikan kendaraan bermotor.",
                                "Pengelolaan dan pemeliharaan perlengkapan jalan, penerangan jalan umum, serta fasilitas perhubungan."
                            ];

                            if (preg_match('/Tugas:\s*(.*?)(?=Fungsi:|$)/is', $cleanContent, $tugasMatch)) {
                                $parsedTugas = trim($tugasMatch[1]);
                                if (!empty($parsedTugas)) {
                                    $tugasText = $parsedTugas;
                                }
                            } elseif (!empty(trim($cleanContent)) && !str_contains($cleanContent, 'Fungsi:')) {
                                $tugasText = trim($cleanContent);
                            }

                            if (preg_match('/Fungsi:\s*(.*)/is', $cleanContent, $fungsiMatch)) {
                                $parsedFungsiStr = trim($fungsiMatch[1]);
                                $splitFungsi = preg_split('/\d+\.\s*/', $parsedFungsiStr, -1, PREG_SPLIT_NO_EMPTY);
                                if (count($splitFungsi) > 0) {
                                    $fungsiItems = array_map('trim', array_filter($splitFungsi));
                                }
                            }
                        @endphp

                        <!-- HEADER BANNER TUGAS & FUNGSI -->
                        <div class="mb-4 p-4 rounded-2xl text-white shadow-md" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%); border-left: 6px solid #10b981;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge badge-success text-white px-3 py-1 font-weight-bold text-uppercase" style="letter-spacing: 1px; background: #059669;">
                                    <i class="fas fa-tasks mr-1"></i> TUGAS & FUNGSI RESMI
                                </span>
                            </div>
                            <h3 class="font-weight-bold text-white mb-1" style="font-family: 'Outfit', sans-serif;">Dinas Perhubungan Kabupaten Probolinggo</h3>
                            <p class="text-white-50 mb-0 small">Uraian kewenangan, fungsi teknis, dan tanggung jawab pelayanan perhubungan daerah.</p>
                        </div>

                        <!-- FOTO BANNER UTAMA (FULL WIDTH LEBAR 100% SAMA DENGAN CONTAINER PDF VIEWER) -->
                        @if($page->image_url_full)
                            <div class="mb-4 rounded-2xl overflow-hidden shadow-sm border border-slate-200" style="width: 100%;">
                                <img src="{{ $page->image_url_full }}" alt="Banner Tugas dan Fungsi" class="img-fluid w-100 d-block" style="width: 100% !important; max-height: 650px; object-fit: contain; background: #f8fafc;">
                            </div>
                        @endif

                        <!-- INTERACTIVE EMBEDDED PDF VIEWER (EXACT MATCH TO USER SCREENSHOT) -->
                        @if($page->pdf_full_url)
                            <div class="mb-5">
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 text-white font-weight-bold shadow-xs" style="background: #16a34a; font-size: 0.85rem; border-radius: 8px 8px 0 0;">
                                    <i class="fas fa-book-open"></i> Pratinjau Berkas PDF (Klik 2x / Scroll)
                                </div>
                                <div class="overflow-hidden shadow-sm border bg-dark" style="border-color: #cbd5e1 !important; height: 680px; border-radius: 0 12px 12px 12px;">
                                    <iframe src="{{ $page->pdf_full_url }}#toolbar=1" width="100%" height="100%" style="border: none;" title="Dokumen PDF Resmi">
                                        <p class="p-3 text-center text-white">
                                            Browser Anda tidak mendukung preview iframe. <a href="{{ $page->pdf_full_url }}" target="_blank" class="font-weight-bold text-warning">Klik di sini untuk mengunduh PDF</a>.
                                        </p>
                                    </iframe>
                                </div>
                            </div>
                        @endif

                        <!-- KARTU URAIAN TUGAS UTAMA -->
                        <div class="mb-5 p-4 p-md-5 rounded-2xl bg-white border shadow-sm" style="border-color: #a7f3d0 !important; border-left: 6px solid #10b981 !important; background: #f0fdf4;">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom border-emerald-200">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shrink-0" style="width: 44px; height: 44px; font-size: 1.2rem; background: #10b981 !important;">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <span class="badge badge-pill badge-success px-3 py-1 font-weight-bold text-uppercase" style="background: rgba(16,185,129,0.2); color: #065f46; font-size: 0.75rem;">
                                        TUGAS UTAMA
                                    </span>
                                    <h4 class="font-weight-bold text-dark m-0 mt-1" style="font-size: 1.25rem; color: #0f172a !important; font-family: 'Outfit', sans-serif;">
                                        Tugas Pokok Perhubungan
                                    </h4>
                                </div>
                            </div>

                            <div class="p-3.5 p-md-4 rounded-xl bg-white border shadow-sm d-flex align-items-center gap-3" style="border-color: #cbd5e1 !important;">
                                <i class="fas fa-quote-left text-success mr-1 shrink-0" style="font-size: 1.2rem; color: #10b981 !important;"></i>
                                <div class="text-dark font-weight-bold" style="font-size: 1.1rem; line-height: 1.7; color: #0f172a !important;">
                                    {{ trim(trim($tugasText, '"\'')) }}
                                </div>
                            </div>
                        </div>

                        <!-- KARTU POIN FUNGSI STRATEGIS -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center shrink-0 font-weight-bold" style="width: 40px; height: 40px; font-size: 1.1rem; background: #059669;">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <span class="badge badge-pill badge-success text-white px-3 py-1 font-weight-bold text-uppercase" style="font-size: 0.75rem; background: #059669;">
                                        FUNGSI STRATEGIS
                                    </span>
                                    <h4 class="font-weight-bold text-dark m-0 mt-1" style="font-size: 1.25rem; color: #0f172a !important; font-family: 'Outfit', sans-serif;">
                                        Rincian Fungsi Teknis
                                    </h4>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($fungsiItems as $fIndex => $fungsi)
                                    <div class="col-12 mb-3">
                                        <div class="p-3.5 p-md-4 rounded-xl bg-white border shadow-sm d-flex align-items-center gap-3" style="border-color: #e2e8f0 !important; border-left: 5px solid #10b981 !important;">
                                            <span class="badge rounded-circle d-flex align-items-center justify-content-center font-weight-bold shrink-0 shadow-sm" style="width: 34px; height: 34px; font-size: 0.95rem; background: #10b981; color: #fff;">
                                                {{ $fIndex + 1 }}
                                            </span>
                                            <div class="text-dark font-weight-bold" style="font-size: 1.05rem; line-height: 1.65; color: #0f172a !important;">
                                                {{ trim(trim($fungsi, '"\'')) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                    @else
                        <!-- FOTO BANNER UTAMA (FULL WIDTH LEBAR 100% SAMA DENGAN CONTAINER PDF VIEWER) -->
                        @if($page->image_url_full)
                            <div class="mb-4 rounded-2xl overflow-hidden shadow-sm border border-slate-200" style="width: 100%;">
                                <img src="{{ $page->image_url_full }}" alt="{{ $page->title }}" class="img-fluid w-100 d-block" style="width: 100% !important; max-height: 650px; object-fit: contain; background: #f8fafc;">
                            </div>
                        @endif

                        <!-- INTERACTIVE EMBEDDED PDF VIEWER (EXACT MATCH TO USER SCREENSHOT) -->
                        @if($page->pdf_full_url)
                            <div class="mb-5">
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 text-white font-weight-bold shadow-xs" style="background: #16a34a; font-size: 0.85rem; border-radius: 8px 8px 0 0;">
                                    <i class="fas fa-book-open"></i> Pratinjau Berkas PDF (Klik 2x / Scroll)
                                </div>
                                <div class="overflow-hidden shadow-sm border bg-dark" style="border-color: #cbd5e1 !important; height: 680px; border-radius: 0 12px 12px 12px;">
                                    <iframe src="{{ $page->pdf_full_url }}#toolbar=1" width="100%" height="100%" style="border: none;" title="Dokumen PDF Resmi">
                                        <p class="p-3 text-center text-white">
                                            Browser Anda tidak mendukung preview iframe. <a href="{{ $page->pdf_full_url }}" target="_blank" class="font-weight-bold text-warning">Klik di sini untuk mengunduh PDF</a>.
                                        </p>
                                    </iframe>
                                </div>
                            </div>
                        @endif

                        <!-- DESKRIPSI KONTEN / CAPTION TEKS PENJELASAN -->
                        @if($page->content)
                            @if($page->slug === 'survei-kepuasan-masyarakat' || request()->is('*survei-kepuasan-masyarakat*'))
                                <div class="mt-4">
                                    <!-- IKM Skor & Mutu Card -->
                                    <div class="p-4 rounded-2xl mb-4 shadow-sm" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                            <div>
                                                <h5 class="font-weight-black text-dark mb-3" style="font-size: 1.25rem;"><i class="fas fa-star text-warning mr-2"></i> Rata-rata Skor IKM</h5>
                                                <div class="d-flex align-items-center gap-2">
                                                    <p class="m-0 text-muted font-weight-bold" style="font-size: 0.95rem;">Predikat Mutu Pelayanan:</p>
                                                    <span class="badge badge-success px-3 py-1 font-weight-black shadow-sm" style="font-size: 1rem; border-radius: 8px; background: #16a34a;">{{ $mutu ?? 'A (Sangat Baik)' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-warning text-dark text-center px-4 py-3 rounded-xl font-weight-bold shadow-sm mt-4 mt-md-0 shrink-0" style="border-radius: 16px;">
                                                <span class="font-weight-black d-block" style="font-size: 1.6rem; line-height: 1;">{{ number_format($avgScore ?? 4.0, 2) }}</span>
                                                <small class="d-block text-dark-50 font-weight-extrabold mt-1" style="font-size: 0.8rem;">/ 4.0</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Saran & Masukan -->
                                    @if(isset($suggestions) && $suggestions->count() > 0)
                                        <div class="p-4 rounded-2xl shadow-sm" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                            <div class="d-flex align-items-center mb-4">
                                                <span class="badge px-2 py-1 font-weight-bold mr-2 text-white shadow-sm" style="background: #f59e0b;"><i class="fas fa-comments"></i></span>
                                                <h5 class="font-weight-black m-0 text-dark" style="font-size: 1.1rem;">Saran & Masukan Terakhir</h5>
                                            </div>
                                            
                                            <div>
                                                @foreach($suggestions as $sug)
                                                    <div class="p-3 bg-white border rounded mb-3 shadow-sm" style="border-left: 4px solid #f59e0b !important; border-radius: 12px !important; border-color: #e2e8f0 !important;">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="font-weight-bold m-0 text-dark" style="font-size: 0.9rem;"><i class="fas fa-user-circle text-muted mr-1"></i> {{ $sug->name }}</h6>
                                                            <small class="text-muted font-weight-medium" style="font-size: 0.75rem;">{{ $sug->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <p class="m-0 text-secondary" style="font-size: 0.85rem; font-style: italic; line-height: 1.5;">"{{ $sug->feedback }}"</p>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="mt-4 d-flex justify-content-center">
                                                {{ $suggestions->withQueryString()->links('pagination::bootstrap-4') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="page-content-wrapper text-dark p-4 p-md-5 rounded-2xl bg-white border shadow-sm mt-4" style="font-size: 1.05rem; line-height: 1.85; color: #0f172a !important; border-color: #cbd5e1 !important;">
                                    @if(strip_tags($page->content) === $page->content)
                                        {!! nl2br(e($page->content)) !!}
                                    @else
                                        {!! $page->content !!}
                                    @endif
                                </div>
                            @endif
                        @endif
                    @endif

                </article>

            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* ORG TREE STYLING - HIRARKI GARIS KOMANDO */
    .org-tree-wrapper {
        width: 100%;
        background: #ffffff;
        border-radius: 12px;
        padding: 2rem 1rem;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
    }

    .org-tree-root, .org-tree-root ul {
        display: flex;
        justify-content: center;
        position: relative;
        padding-top: 20px;
        transition: all 0.5s;
    }

    .org-tree-root li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 8px 0 8px;
        transition: all 0.5s;
    }

    /* Connecting Lines / Garis Komando */
    .org-tree-root li::before, .org-tree-root li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid #3b82f6;
        width: 50%;
        height: 20px;
    }
    .org-tree-root li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid #3b82f6;
    }

    .org-tree-root li:only-child::after, .org-tree-root li:only-child::before {
        display: none;
    }

    .org-tree-root li:only-child {
        padding-top: 0;
    }

    .org-tree-root li:first-child::before, .org-tree-root li:last-child::after {
        border: 0 none;
    }

    .org-tree-root li:last-child::before {
        border-right: 2px solid #3b82f6;
        border-radius: 0 8px 0 0;
    }

    .org-tree-root li:first-child::after {
        border-radius: 8px 0 0 0;
    }

    .org-tree-root ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid #3b82f6;
        width: 0;
        height: 20px;
    }

    /* Card Node Styling */
    .org-card-node {
        display: inline-block;
        border-radius: 12px;
        background: #ffffff;
        border: 2px solid #0f2b5c;
        box-shadow: 0 6px 16px rgba(15, 43, 92, 0.08);
        padding: 12px;
        min-width: 220px;
        max-width: 260px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .org-card-node:hover {
        transform: translateY(-50px) scale(1.03);
        box-shadow: 0 12px 25px rgba(15, 43, 92, 0.18);
        border-color: #2563eb;
    }

    .org-card-root-node {
        border-color: #f59e0b;
        background: linear-gradient(180deg, #ffffff 0%, #fffbe6 100%);
    }

    .org-node-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f59e0b;
        margin-bottom: 8px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    .org-node-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.88rem;
        font-weight: 800;
        color: #0f2b5c;
        line-height: 1.25;
        margin-bottom: 4px;
    }

    .org-node-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .org-node-nip {
        font-size: 0.68rem;
        color: #64748b;
    }

    /* Horizontal scrollbar styling */
    .org-tree-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    .org-tree-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
</style>
@endsection
