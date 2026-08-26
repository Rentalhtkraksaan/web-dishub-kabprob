@extends('public.layouts.app')

@section('title', 'Video Kegiatan | Dinas Perhubungan Kabupaten Probolinggo')
@section('meta_description', 'Kumpulan dokumentasi video resmi kegiatan, sosialisasi keselamatan lalu lintas, dan pengujian kendaraan bermotor Dinas Perhubungan Kabupaten Probolinggo.')

@section('styles')
<style>
    /* Header Banner Diskominfo Style with SAE Background */
    .pageMainHead {
        background-color: #557a50;
        background-image: url('https://diskominfo.probolinggokab.go.id/frontend/images/img156.jpg');
        background-size: cover;
        background-position: center right;
        background-repeat: no-repeat;
        min-height: 140px;
    }

    /* Video Card Call To Action Style */
    .call-to-action {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        background: #ffffff;
    }
    .call-to-action:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1) !important;
    }

    .video-card-link {
        display: block;
        text-decoration: none !important;
        color: inherit;
    }

    .video-thumb-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background: #0f172a;
    }

    .video-thumb-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .video-card-link:hover .video-thumb-img {
        transform: scale(1.05);
    }

    /* Red / White YouTube Play Button Overlay */
    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }
    .video-card-link:hover .play-overlay {
        background: rgba(0, 0, 0, 0.35);
    }

    .play-btn-circle {
        width: 58px;
        height: 58px;
        background: #ff0000;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        padding-left: 4px;
        box-shadow: 0 6px 20px rgba(255, 0, 0, 0.45);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.3s ease;
    }
    .video-card-link:hover .play-btn-circle {
        transform: scale(1.15);
        background: #e60000;
    }

    .volunteer_text {
        color: #4a6847;
        font-size: 0.95rem;
        line-height: 1.45;
        font-weight: 700;
        transition: color 0.2s ease;
        padding-top: 10px;
        min-height: 52px;
    }
    .video-card-link:hover .volunteer_text {
        color: #264e24;
    }
</style>
@endsection

@section('content')
<!-- Page Banner Header Diskominfo Style with SAE -->
<header class="pageMainHead d-flex position-relative bgCover w-100 text-white align-items-center">
    <div class="alignHolder d-flex w-100 align-items-center py-4 py-md-5">
        <div class="align w-100 position-relative">
            <div class="container">
                <h3 class="text-white mb-1 font-weight-bold" style="font-size: 2rem; font-family: 'Outfit', sans-serif;">Video</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrWhite rounded-0 border-0 p-0 fontAlter mb-0" style="background: transparent;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Video</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>

<!-- Main Video Grid Section -->
<section class="ItemfullBlock py-5" style="background-color: #f8fafc; min-height: 480px;">
    <div class="container">
        @if($videos->isEmpty())
            <div class="text-center py-5 bg-white rounded-2xl shadow-sm border border-slate-200">
                <i class="fas fa-video-slash text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <h4 class="font-weight-bold text-muted">Belum Ada Video</h4>
                <p class="text-muted small">Dokumentasi video belum dipublikasikan saat ini.</p>
            </div>
        @else
            <div class="row">
                @foreach($videos as $video)
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-xl-6">
                        <article class="call-to-action npbColumn shadow bg-white h-100"> 
                            <div class="gallery-item p-3"> 
                                <div class="bookshelf">
                                    <!-- Lightbox link using fslightbox to play video instantly -->
                                    <a data-fslightbox="gallery" href="{{ $video->embed_url }}" class="video-card-link" title="{{ $video->title }}">
                                        <div class="video-thumb-wrap mb-2">
                                            <img class="thumb img-thumbnail video-thumb-img border-0 p-0" 
                                                 src="{{ $video->effective_thumbnail }}" 
                                                 alt="{{ $video->title }}">
                                            
                                            <!-- YouTube Style Play Button Overlay -->
                                            <div class="play-overlay">
                                                <div class="play-btn-circle">
                                                    <i class="fas fa-play"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="volunteer_text text-center">                             
                                            {{ $video->title }}
                                        </div>
                                    </a>
                                    
                                    <!-- Fallback Direct YouTube Link -->
                                    <div class="text-center pt-2 pb-1">
                                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light text-danger font-weight-bold border shadow-xs" style="border-radius: 20px; font-size: 0.75rem; padding: 4px 12px;">
                                            <i class="fab fa-youtube text-danger mr-1"></i> Buka di YouTube ↗
                                        </a>
                                    </div>                             
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $videos->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script src="https://diskominfo.probolinggokab.go.id/backend/plugins/custom/fslightbox-basic-3.4.1/fslightbox.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof refreshFsLightbox === 'function') {
            refreshFsLightbox();
        }
    });
</script>
@endsection
