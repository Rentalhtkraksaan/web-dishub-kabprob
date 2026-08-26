@extends('public.layouts.app')

@section('title', ($album->title ?? 'Album Galeri') . ' | Dinas Perhubungan Kabupaten Probolinggo')
@section('meta_description', $album->description ?? 'Dokumentasi foto kegiatan Dinas Perhubungan Kabupaten Probolinggo.')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
<style>
    .fancybox-bg {
        background: rgba(15, 23, 42, 0.94) !important;
    }
    .gallery-card:hover img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<!-- Page Banner Header -->
<header class="pageMainHead d-flex position-relative bgCover w-100 text-white" style="background: linear-gradient(135deg, #1b3d2f 0%, #2e5939 100%); min-height: 180px; align-items: center;">
    <div class="alignHolder d-flex w-100 align-items-center py-5">
        <div class="align w-100 position-relative">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 fontAlter mb-2" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('galery') }}" class="text-white-50">Album Galery</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $album->title }}</li>
                    </ol>
                </nav>
                <h1 class="text-white mb-2 font-weight-bold" style="font-size: 2rem; font-family: 'Outfit', sans-serif;">{{ $album->title }}</h1>
                @if($album->description)
                    <p class="text-white-50 mb-0" style="font-size: 0.95rem; max-width: 800px;">
                        {{ $album->description }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</header>

<!-- Photos Grid with Fancybox -->
<section class="ItemfullBlock py-5 bg-light">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="font-weight-bold text-dark mb-1" style="font-size: 1.25rem;">Foto-foto Kegiatan</h3>
                <p class="text-muted small mb-0">Klik pada salah satu foto untuk melihat dalam ukuran penuh (fullscreen)</p>
            </div>
            <a href="{{ route('galery') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 font-weight-bold">
                &larr; Kembali ke Daftar Album
            </a>
        </div>

        <div class="row">
            @foreach($album->photo_urls as $index => $photoUrl)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <a data-fancybox="gallery-{{ $album->id }}" href="{{ $photoUrl }}" data-caption="{{ $album->title }} - Foto {{ $index + 1 }}" class="gallery-card d-block card border-0 shadow-sm overflow-hidden h-100 position-relative" style="border-radius: 12px;">
                        <div style="height: 220px; overflow: hidden; background: #0f172a;">
                            <img src="{{ $photoUrl }}" alt="{{ $album->title }} - Foto {{ $index + 1 }}" 
                                 class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;">
                        </div>
                        <div class="position-absolute d-flex align-items-center justify-content-center text-white" 
                             style="inset: 0; background: rgba(0,0,0,0.35); opacity: 0; transition: opacity 0.3s;"
                             onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                            <i class="fas fa-search-plus" style="font-size: 1.8rem;"></i>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Other Albums -->
        @if(isset($otherAlbums) && $otherAlbums->count() > 0)
            <div class="mt-5 pt-4 border-top">
                <h4 class="font-weight-bold text-dark mb-4" style="font-size: 1.15rem;">Album Lainnya</h4>
                <div class="row">
                    @foreach($otherAlbums as $other)
                        <div class="col-12 col-md-4 mb-3">
                            <a href="{{ route('galery.detail', $other->slug) }}" class="card border-0 shadow-sm text-decoration-none text-dark h-100" style="border-radius: 10px; overflow: hidden;">
                                <div style="height: 140px; background-image: url('{{ $other->cover_image_full }}'); background-size: cover; background-position: center;"></div>
                                <div class="p-3">
                                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $other->title }}</h5>
                                    <span class="text-muted small"><i class="far fa-image mr-1"></i> {{ is_array($other->photos) ? count($other->photos) : 1 }} Foto</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('[data-fancybox^="gallery"]').fancybox({
            buttons: [
                "zoom",
                "slideShow",
                "fullScreen",
                "download",
                "thumbs",
                "close"
            ],
            loop: true,
            protect: false,
            animationEffect: "fade",
            transitionEffect: "slide"
        });
    });
</script>
@endsection
