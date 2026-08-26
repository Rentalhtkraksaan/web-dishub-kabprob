@extends('public.layouts.app')

@section('title', 'Informasi & Berita Terkini | DISHUB Kabupaten Probolinggo')
@section('meta_description', 'Pusat publikasi berita resmi, info rekayasa lalu lintas, pengujian kelaikan kendaraan bermotor, dan program kerja Dinas Perhubungan Kabupaten Probolinggo.')

@section('content')
<!-- Page Banner Header Diskominfo Style -->
<header class="pageMainHead d-flex position-relative bgCover w-100 text-white" style="background: linear-gradient(135deg, #1b3d2f 0%, #2e5939 100%); min-height: 180px; align-items: center;">
    <div class="alignHolder d-flex w-100 align-items-center py-5">
        <div class="align w-100 position-relative">
            <div class="container">
                <h1 class="text-white mb-2 font-weight-bold" style="font-size: 2.2rem; font-family: 'Outfit', sans-serif;">Informasi</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 fontAlter mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Informasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>

<!-- TAB NAVIGATION (FILTER KATEGORI) -->
@if($tabs->count() > 0)
<div class="bg-white border-bottom shadow-sm sticky-top" style="z-index: 100; top: 0;">
    <div class="container">
        <div class="d-flex overflow-auto" style="gap: 0; white-space: nowrap;">
            @foreach($tabs as $tab)
                <a href="{{ route('informasi', ['tab' => $tab->slug]) }}"
                   class="d-inline-flex align-items-center px-4 py-3 font-weight-bold text-decoration-none transition-all"
                   style="font-size: 0.85rem; border-bottom: 3px solid {{ $activeTab === $tab->slug ? '#557a50' : 'transparent' }};
                          color: {{ $activeTab === $tab->slug ? '#557a50' : '#64748b' }};
                          background: {{ $activeTab === $tab->slug ? '#f0fdf4' : 'transparent' }};
                          flex-shrink: 0;">
                    <i class="{{ $tab->icon ?? 'fas fa-newspaper' }} mr-2" style="font-size: 0.85rem;"></i>
                    {{ $tab->name }}
                    @if($activeTab === $tab->slug)
                        <span class="ml-2 badge badge-success" style="font-size: 0.65rem; background: #557a50;">{{ $newsList->total() }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Main News Content Grid (Diskominfo Style: 3 Columns Full Width) -->
<section class="ItemfullBlock py-5 bg-light">
    <div class="container">
        @if($newsList->isEmpty())
            <div class="text-center py-5 bg-white rounded-2xl shadow-sm border p-4">
                <i class="far fa-newspaper text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <h4 class="font-weight-bold text-dark">Belum Ada Berita di Tab "{{ $currentTab->name ?? 'Ini' }}"</h4>
                <p class="text-muted small max-w-md mx-auto">
                    Artikel berita untuk kategori <strong>{{ $currentTab->filter_value ?? $currentTab->name }}</strong> belum ditambahkan oleh administrator.
                </p>
                @auth
                    <div class="mt-3">
                        <a href="{{ route('admin.news') }}" class="btn btn-success font-weight-bold px-4 py-2 rounded-xl shadow-sm text-white inline-flex align-items-center gap-2">
                            <i class="fas fa-plus-circle"></i> ➕ Upload Berita Kategori "{{ $currentTab->filter_value ?? $currentTab->name }}" di Admin Panel
                        </a>
                    </div>
                @endauth
            </div>
        @else
            <div class="row">
                @foreach($newsList as $news)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <article class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 12px; background: #ffffff; transition: transform 0.3s, box-shadow 0.3s;">
                            <!-- Image with Date Ribbon -->
                            <div class="position-relative overflow-hidden" style="height: 210px; background: #0f172a;">
                                <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600' }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: cover; transition: transform 0.4s ease;" 
                                     alt="{{ $news->title }}">
                                
                                <!-- Date Ribbon (Diskominfo Style: Green Box at bottom left of thumbnail) -->
                                <div class="position-absolute text-white font-weight-bold px-3 py-1 text-uppercase" 
                                     style="bottom: 0; left: 0; background: #557a50; font-size: 0.72rem; letter-spacing: 0.5px; z-index: 10;">
                                    {{ $news->published_at ? $news->published_at->translatedFormat('d F Y') : ($news->created_at ? $news->created_at->translatedFormat('d F Y') : '01 JANUARI 2026') }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="card-body p-3 p-md-4 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Category in Olive Color -->
                                    <div class="mb-1" style="color: #557a50; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                        {{ $news->category ?? 'Pemerintahan' }}
                                    </div>
                                    <!-- Title -->
                                    <h3 class="font-weight-bold mb-2" style="font-size: 1.05rem; line-height: 1.45; font-family: 'Outfit', sans-serif;">
                                        <a href="{{ route('news.detail', $news->slug) }}" class="text-dark hover-theme text-decoration-none">
                                            {{ $news->title }}
                                        </a>
                                    </h3>
                                    <!-- Summary -->
                                    <p class="text-muted small mb-0" style="line-height: 1.55;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($news->summary ?? $news->content), 100) }}
                                    </p>
                                </div>

                                <div class="pt-3 mt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">
                                        <i class="far fa-eye mr-1"></i> {{ $news->views ?? 0 }} views
                                    </span>
                                    <a href="{{ route('news.detail', $news->slug) }}" class="font-weight-bold small text-decoration-none" style="color: #557a50;">
                                        Baca Selengkapnya &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                <style>
                    .pagination {
                        display: flex !important;
                        padding-left: 0 !important;
                        list-style: none !important;
                        gap: 6px !important;
                        margin-bottom: 0 !important;
                    }
                    .pagination .page-item .page-link {
                        color: #557a50 !important;
                        border-radius: 10px !important;
                        padding: 8px 16px !important;
                        font-weight: 700 !important;
                        font-size: 0.85rem !important;
                        border: 1px solid #cbd5e1 !important;
                        background: #ffffff !important;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
                        transition: all 0.2s ease !important;
                    }
                    .pagination .page-item.active .page-link {
                        background-color: #557a50 !important;
                        border-color: #557a50 !important;
                        color: #ffffff !important;
                        box-shadow: 0 4px 12px rgba(85, 122, 80, 0.3) !important;
                    }
                    .pagination .page-item.disabled .page-link {
                        color: #94a3b8 !important;
                        background-color: #f8fafc !important;
                        border-color: #e2e8f0 !important;
                        opacity: 0.7 !important;
                    }
                    .pagination .page-item:hover:not(.active):not(.disabled) .page-link {
                        background-color: #f0fdf4 !important;
                        border-color: #557a50 !important;
                        color: #557a50 !important;
                        transform: translateY(-1px) !important;
                    }
                </style>
                {{ $newsList->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</section>
@endsection
