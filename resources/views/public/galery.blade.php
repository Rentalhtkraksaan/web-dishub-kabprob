@extends('public.layouts.app')

@section('title', 'Album Galery | Dinas Perhubungan Kabupaten Probolinggo')
@section('meta_description', 'Dokumentasi album galeri foto kegiatan, operasional, dan pelayanan resmi Dinas Perhubungan Kabupaten Probolinggo.')

@section('content')
<!-- Page Banner Header Diskominfo Style -->
<header class="pageMainHead d-flex position-relative bgCover w-100 text-white" style="background: linear-gradient(135deg, #1b3d2f 0%, #2e5939 100%); min-height: 180px; align-items: center;">
    <div class="alignHolder d-flex w-100 align-items-center py-5">
        <div class="align w-100 position-relative">
            <div class="container">
                <h1 class="text-white mb-2 font-weight-bold" style="font-size: 2.2rem; font-family: 'Outfit', sans-serif;">Album Galery</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 fontAlter mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Album</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>

<!-- Main Gallery Albums Grid Section -->
<section class="ItemfullBlock py-5 bg-light">
    <div class="container">
        @if($albums->isEmpty())
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <i class="far fa-images text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <h4 class="font-weight-bold text-muted">Belum Ada Album</h4>
                <p class="text-muted small">Album foto kegiatan belum dipublikasikan saat ini.</p>
            </div>
        @else
            <div class="row">
                @foreach($albums as $album)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <article class="call-to-action npbColumn shadow-sm bg-white position-relative" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; transition: transform 0.3s, box-shadow 0.3s;"> 
                            <div class="isoCol business">
                                <div class="echColumn echColumnii d-block w-100 position-relative" 
                                     style="background-image: url('{{ $album->cover_image_full }}'); height: 260px; background-size: cover; background-position: center; border-radius: 12px; overflow: hidden;">
                                    
                                    <!-- Photo Count Tag -->
                                    <span class="echCountTag position-absolute text-white px-3 py-1 font-weight-bold d-inline-flex align-items-center" 
                                          style="top: 15px; right: 15px; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); border-radius: 20px; font-size: 0.8rem; z-index: 10;">
                                        <i class="far fa-image mr-1"></i>
                                        {{ is_array($album->photos) ? count($album->photos) : 1 }}
                                    </span>

                                    <!-- Overlay Caption -->
                                    <div class="echcCaptionWrap position-absolute w-100 text-white px-3 py-3 px-sm-4 py-sm-3 d-flex align-items-end justify-content-between"
                                         style="bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 60%, rgba(0, 0, 0, 0) 100%);">
                                        <div class="pr-2">
                                            <strong class="d-block font-weight-bold text-warning mb-1" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px;">Album</strong>
                                            <h3 class="mb-0 text-white font-weight-bold" style="font-size: 1.05rem; line-height: 1.35; font-family: 'Outfit', sans-serif;">
                                                <a href="{{ route('galery.detail', $album->slug) }}" class="text-white text-decoration-none hover-warning">
                                                    {{ $album->title }}
                                                </a>
                                            </h3>
                                        </div>
                                        <a href="{{ route('galery.detail', $album->slug) }}" 
                                           class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0 shadow-sm"
                                           style="width: 38px; height: 38px; background: #557a50; text-decoration: none; transition: transform 0.2s;"
                                           title="Buka Album">
                                            <i class="fas fa-arrow-right" style="font-size: 0.85rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $albums->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
