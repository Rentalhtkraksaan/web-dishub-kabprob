@extends('public.layouts.app')

@section('title', $settings['site_title'] ?? 'Website Resmi | Dinas Perhubungan (DISHUB) Kabupaten Probolinggo')
@section('meta_description', $settings['site_description'] ?? 'Website Resmi Dinas Perhubungan Kabupaten Probolinggo - Pusat Informasi Transportasi, Pengujian Kendaraan Bermotor (Uji KIR), Rekayasa Lalu Lintas.')

@section('content')
<!-- Hero Slider (Identik Template Diskominfo) -->
<div class="introBlock ibSlider">
    @forelse($sliders as $slider)
        <div>
            <article class="d-flex w-100 position-relative ibColumn text-white overflow-hidden">
                <div class="alignHolder d-flex align-items-center w-100">
                    <div class="align w-100 pt-20 pb-20 pt-md-40 pb-md-30 px-md-17">
                        <div class="container position-relative">
                            <div class="row">
                                <div class="col-12 col-md-9 col-xl-7 fzMedium">
                                    <h2 class="text-white mb-4 h1Medium">{{ $slider->title }}</h2>
                                    @if($slider->subtitle)
                                        <h3 class="text-white mb-4 h1Medium">{{ $slider->subtitle }}</h3>
                                    @endif
                                    @if(!empty($slider->button_text))
                                        <a href="{{ $slider->button_url ?? '#' }}" class="btn btn-warning font-weight-bold px-4 py-2 text-dark">
                                            {{ $slider->button_text }} <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="ibBgImage bgCover position-absolute" style="background-image: url('{{ $slider->image_url }}');"></span>
            </article>
        </div>
    @empty
        <div>
            <article class="d-flex w-100 position-relative ibColumn text-white overflow-hidden">
                <div class="alignHolder d-flex align-items-center w-100">
                    <div class="align w-100 pt-20 pb-20 pt-md-40 pb-md-30 px-md-17">
                        <div class="container position-relative">
                            <div class="row">
                                <div class="col-12 col-md-9 col-xl-7 fzMedium">
                                    <h2 class="text-white mb-4 h1Medium">Selamat Datang di Website</h2>
                                    <h3 class="text-white mb-4 h1Medium">Dinas Perhubungan Kabupaten Probolinggo</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="ibBgImage bgCover position-absolute" style="background-image: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=1600&auto=format&fit=crop');"></span>
            </article>
        </div>
        <div>
            <article class="d-flex w-100 position-relative ibColumn text-white overflow-hidden">
                <div class="alignHolder d-flex align-items-center w-100">
                    <div class="align w-100 pt-20 pb-20 pt-md-40 pb-md-30 px-md-17">
                        <div class="container position-relative">
                            <div class="row">
                                <div class="col-12 col-md-7 col-xl-7 fzMedium">
                                    <h2 class="text-white mb-4 h1Medium">Pelayanan Prima & Terintegrasi</h2>
                                    <h3 class="text-white mb-4 h1Medium">Dinas Perhubungan Kabupaten Probolinggo</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="ibBgImage bgCover position-absolute" style="background-image: url('https://images.unsplash.com/photo-1570125909232-eb263c188f7e?q=80&w=1600&auto=format&fit=crop');"></span>
            </article>
        </div>
    @endforelse
</div>

<!-- Main Content (Informasi Terbaru) + Sidebar Widgets -->
<section class="upcomeventsBlock position-relative pt-7 pb-3 pt-md-9 pb-md-6 pt-lg-14 pb-lg-11 pt-xl-3 pb-xl-2">
    <div class="container">
        <div class="row">
            <!-- Left 8 Cols: Informasi & Berita Terbaru -->
            <div class="col-12 col-lg-8 shadow">
                <div class="pr-lg-8">
                    <header class="headingHead mb-6 mb-lg-8 mb-xl-12">
                        <div class="row align-items-end">
                            <div class="col-12 col-sm-6 col-md-7">
                                <h2 class="mb-sm-0 fwSemiBold h2Small">Informasi Terbaru</h2>
                            </div>
                            <div class="col-12 col-sm-6 col-md-5 d-sm-flex justify-content-sm-end">
                                <a href="{{ route('informasi') }}" class="btn-link fontAlter">Lihat Semua <i class="fas fa-chevron-right blIcn"><span class="sr-only">icon</span></i></a>
                            </div>
                        </div>
                    </header>

                    <div class="row">
                        @forelse($latestNews as $news)
                            <div class="col-12 col-md-6">
                                <article class="ueEveColumn position-relative shadow bg-white mb-6">
                                    <div class="imgHolder position-relative">
                                        <img src="{{ $news->image_url ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600' }}" 
                                             class="img-fluid d-block w-100 cover_tumb" 
                                             alt="{{ $news->title }}">
                                        <time class="ueTimeTag position-absolute text-center d-flex bg-white fontAlter fwSemiBold text-lDark text-uppercase" datetime="{{ $news->published_at ? $news->published_at->format('Y-m-d') : '' }}">
                                            <span class="d-block text-white textDay flex-shrink-0 font-weight-bold">{{ $news->published_at ? $news->published_at->format('d') : '01' }}</span>
                                            <span class="d-block py-2 px-3">
                                                {{ $news->published_at ? $news->published_at->translatedFormat('F Y') : 'Januari 2026' }}
                                            </span>
                                        </time>
                                    </div>
                                    <div class="ueDescriptionWrap pt-5 pb-8 px-5">
                                        <h3 class="h3Small fwMedium mb-2">
                                            <a href="{{ route('news.detail', $news->slug) }}">
                                                {{ \Illuminate\Support\Str::limit($news->title, 85) }}
                                            </a>
                                        </h3>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted m-0">Belum ada informasi terbaru yang dipublikasikan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right 4 Cols: Sidebar Widgets -->
            <aside class="col-12 col-lg-4">
                <div class="pl-lg-2">
                    <article class="cdColumnWidget bg-white">
                        <ul class="list-unstyled cdDocsList mb-0">
                            @forelse($sidebarWidgets as $widget)
                                <li>
                                    @if($widget->title)
                                        <h3 class="cdTitle">
                                            {{ $widget->title }}
                                        </h3>
                                    @endif

                                    @if($widget->image_url)
                                        @if($widget->link_url)
                                            <a href="{{ $widget->link_url }}" target="_blank" rel="noopener noreferrer">
                                                <img src="{{ $widget->image_url }}" class="img-fluid d-block w-100" alt="{{ $widget->title }}">
                                            </a>
                                        @else
                                            <img src="{{ $widget->image_url }}" class="img-fluid d-block w-100" alt="{{ $widget->title }}">
                                        @endif
                                    @endif

                                    @if($widget->content)
                                        <div class="mt-2 small text-muted">{!! $widget->content !!}</div>
                                    @endif
                                </li>
                            @empty
                                <!-- Fallback DISHUB Widgets -->
                                <li>
                                    <h3 class="cdTitle">Maklumat Pelayanan</h3>
                                    <img src="https://diskominfo.probolinggokab.go.id//storage/photos/4/maklumat%20pelayanan%20diskominfo.jpg" class="img-fluid d-block w-100" alt="Maklumat Pelayanan">
                                </li>
                                <li>
                                    <h3 class="cdTitle">Kepala Dinas</h3>
                                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=500" class="img-fluid d-block w-100" alt="Kepala Dinas Perhubungan">
                                </li>
                                <li>
                                    <h3 class="cdTitle">HASIL SKM 2025/2026</h3>
                                    <img src="https://diskominfo.probolinggokab.go.id//storage/photos/shares/6a5856ad83078.png" class="img-fluid d-block w-100" alt="Hasil SKM">
                                </li>
                                <li>
                                    <h3 class="cdTitle">Aspirasi dan Pengaduan</h3>
                                    <a href="https://www.lapor.go.id/" target="_blank" rel="noopener noreferrer">
                                        <img src="https://diskominfo.probolinggokab.go.id/frontend/img/sp4n.jpg" class="img-fluid d-block w-100" alt="Aspirasi dan Pengaduan">
                                    </a>
                                </li>
                            @endforelse
                        </ul>
                    </article>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Logo Link Terkait / Mitra Section -->
<aside class="logosAsideBlock text-center py-5 py-md-7 py-lg-5 py-xl-7">
    <div class="lgsImagesSlider">
        @forelse($relatedLinks as $link)
            <div>
                <div class="lgsImageWrap px-2">
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $link->image_url }}" class="img_linkterkait" alt="{{ $link->title }}">
                    </a>
                </div>
            </div>
        @empty
            <div>
                <div class="lgsImageWrap px-2">
                    <a href="https://www.lapor.go.id/" target="_blank" rel="noopener noreferrer">
                        <img src="https://diskominfo.probolinggokab.go.id//storage/photos/shares/Logo-s4pan.jpg" class="img_linkterkait" alt="SP4N Lapor">
                    </a>
                </div>
            </div>
            <div>
                <div class="lgsImageWrap px-2">
                    <a href="https://kominfo.jatimprov.go.id/" target="_blank" rel="noopener noreferrer">
                        <img src="https://diskominfo.probolinggokab.go.id//storage/photos/shares/Logo-kominfojatim.png" class="img_linkterkait" alt="Kominfo Jatim">
                    </a>
                </div>
            </div>
            <div>
                <div class="lgsImageWrap px-2">
                    <a href="https://www.komdigi.go.id/" target="_blank" rel="noopener noreferrer">
                        <img src="https://diskominfo.probolinggokab.go.id//storage/photos/shares/logo-komdigi1.png" class="img_linkterkait" alt="Komdigi">
                    </a>
                </div>
            </div>
            <div>
                <div class="lgsImageWrap px-2">
                    <a href="https://satudata.probolinggokab.go.id/" target="_blank" rel="noopener noreferrer">
                        <img src="https://diskominfo.probolinggokab.go.id//storage/photos/shares/Logo-satudata.png" class="img_linkterkait" alt="Satu Data">
                    </a>
                </div>
            </div>
            <div>
                <div class="lgsImageWrap px-2">
                    <a href="https://probolinggokab.go.id/" target="_blank" rel="noopener noreferrer">
                        <img src="https://diskominfo.probolinggokab.go.id//storage/photos/shares/logo-kabprob.png" class="img_linkterkait" alt="Pemkab Probolinggo">
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</aside>

@php
    $igUsername = $settings['instagram_username'] ?? '';
    if (empty($igUsername) && !empty($settings['instagram_url'])) {
        if (preg_match('/instagram\.com\/([a-zA-Z0-9_\.]+)/i', $settings['instagram_url'], $m)) {
            $igUsername = rtrim($m[1], '/');
        }
    }
    if (empty($igUsername)) {
        $igUsername = 'dishubkabprobolinggo';
    }
    $igUrl = $settings['instagram_url'] ?? ('https://www.instagram.com/' . $igUsername . '/');
@endphp

<!-- Instagram Embed (Embed Resmi Profile & Feed) -->
<aside class="getStartedAsideBlock py-6">
    <div class="container">
        <div class="gsabHolder bg-white shadowLg fzMedium position-relative overflow-hidden rounded-2xl border">
            <div class="px-4 py-3 bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mr-2" style="width: 36px; height: 36px;">
                        <i class="fab fa-instagram" style="font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h4 class="m-0 font-weight-bold text-dark" style="font-size: 1rem; font-family: 'Outfit', sans-serif;">
                            Instagram Resmi @<span class="text-primary">{{ $igUsername }}</span>
                        </h4>
                        <span class="text-muted" style="font-size: 0.75rem;">Dokumentasi & Informasi Terupdate Publik</span>
                    </div>
                </div>
                <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-danger font-weight-bold px-3 py-1.5 rounded-lg shadow-sm">
                    <i class="fab fa-instagram mr-1"></i> Kunjungi Profile Instagram
                </a>
            </div>
            <div class="w-100 p-2 bg-light">
                <blockquote class="instagram-media"
                    data-instgrm-permalink="{{ $igUrl }}?utm_source=ig_embed&amp;utm_campaign=loading"
                    data-instgrm-version="14"
                    style="
                        background: #fff;
                        border: 0;
                        border-radius: 8px;
                        box-shadow: 0 0 1px 0 rgba(0, 0, 0, 0.5), 0 1px 10px 0 rgba(0, 0, 0, 0.15);
                        margin: 1px auto;
                        max-width: 100%;
                        min-width: 326px;
                        padding: 0;
                        width: 99.375%;
                        width: calc(100% - 2px);
                    ">
                </blockquote>
            </div>
        </div>
    </div>
</aside>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Hero Slick Slider
        var $heroSlider = $('.ibSlider');
        if ($heroSlider.length) {
            if ($heroSlider.hasClass('slick-initialized')) {
                $heroSlider.slick('unslick');
            }
            $heroSlider.slick({
                accessibility: false,
                dots: true,
                arrows: true,
                infinite: true,
                speed: 600,
                fade: true,
                cssEase: 'linear',
                autoplay: true,
                autoplaySpeed: 5000,
                prevArrow: '<button type="button" class="slick-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>'
            });

            // Explicit click handlers for arrow buttons to guarantee smooth sliding left/right
            $(document).on('click', '.ibSlider .slick-prev', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $heroSlider.slick('slickPrev');
            });
            $(document).on('click', '.ibSlider .slick-next', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $heroSlider.slick('slickNext');
            });
        }

        // Initialize Partner Logos Slider
        if ($('.lgsImagesSlider').length) {
            $('.lgsImagesSlider').slick({
                accessibility: false,
                dots: false,
                arrows: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    { breakpoint: 992, settings: { slidesToShow: 3 } },
                    { breakpoint: 576, settings: { slidesToShow: 2 } }
                ]
            });
        }
    });
</script>
@endsection
