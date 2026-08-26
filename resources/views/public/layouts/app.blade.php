<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings['site_title'] ?? 'Website Resmi | Dinas Perhubungan Kabupaten Probolinggo')</title>
    <meta name="description" content="@yield('meta_description', $settings['site_description'] ?? 'Website Resmi Dinas Perhubungan Kabupaten Probolinggo - Pusat Informasi Transportasi, Pengujian Kendaraan Bermotor (Uji KIR), Rekayasa Lalu Lintas.')" />
    <meta name="keywords" content="dishub, perhubungan, probolinggo, uji kir, lalu lintas, transportasi, kraksaan" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />  
    <meta property="og:locale" content="id" />
    <meta property="og:type" content="Pemerintahan" />
    <meta property="og:title" content="@yield('title', $settings['site_title'] ?? 'Website Resmi Dinas Perhubungan Kabupaten Probolinggo')" />
    <meta property="og:site_name" content="{{ $settings['site_title'] ?? 'Dinas Perhubungan Kabupaten Probolinggo' }}" />
     
    <link rel="icon" type="image/png" href="{{ $settings['favicon'] ?? asset('images/logo_dishub.png') }}">
    <link rel="apple-touch-icon" href="{{ $settings['favicon'] ?? asset('images/logo_dishub.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
   
    <!-- Diskominfo Original Theme Stylesheets -->
    <link href="https://diskominfo.probolinggokab.go.id/frontend/css/bootstrap.css" rel="stylesheet">
    <link href="https://diskominfo.probolinggokab.go.id/frontend/style.css" rel="stylesheet">
    <link href="https://diskominfo.probolinggokab.go.id/frontend/css/colors.css" rel="stylesheet">
    <link href="https://diskominfo.probolinggokab.go.id/frontend/css/responsive.css" rel="stylesheet"> 
    
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <style>
        .select2,
        .select2-search__field,
        .select2-results__option {             
            font-size: 1.0em !important; 
        }
        .select2-selection__rendered {
            line-height: 2.6em !important;
            z-index: 1000;
        }
        .select2-container .select2-selection--single {
            height: 2.6em !important;
        }
        .select2-selection__arrow {
            height: 2.6em !important;
        }
        .back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: none;
            z-index: 999;
        }
        .tengah {
            padding: 70px 0;
            text-align: center;
        }
        .cover_survey {
            margin: auto;
            display: block;
        }
        .cover_tumb {
            object-fit: cover;
            width: 100%;
            height: 200px;
        }
        .ueTimeTag .textDay {
            background-color: #0f2b5c !important;
        }
        .ueTimeTag .textMonthYear {
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            color: #334155 !important;
            padding: 4px 8px !important;
            text-transform: uppercase !important;
            line-height: 1.2 !important;
            background: #ffffff !important;
            white-space: nowrap !important;
        }
        .cdColumnWidget {
            background: #ffffff;
        }

        /* Hero Slider Custom Styling (16:9 Landscape Aspect Ratio Desktop & Mobile) */
        .introBlock.ibSlider {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            max-height: 520px;
            background: #0f172a;
        }
        .ibSlider .slick-list,
        .ibSlider .slick-track {
            height: 100% !important;
        }
        .ibSlider .slick-prev,
        .ibSlider .slick-next {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 1000 !important;
            width: 52px !important;
            height: 52px !important;
            background: rgba(15, 23, 42, 0.8) !important;
            border: 2px solid rgba(255, 255, 255, 0.9) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #ffffff !important;
            font-size: 20px !important;
            cursor: pointer !important;
            pointer-events: auto !important;
            transition: all 0.25s ease-in-out !important;
            outline: none !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
        }
        .ibSlider .slick-prev *,
        .ibSlider .slick-next * {
            pointer-events: none !important;
        }
        .ibSlider .slick-prev { left: 25px !important; }
        .ibSlider .slick-next { right: 25px !important; }
        .ibSlider .slick-prev:hover,
        .ibSlider .slick-next:hover {
            background: #f59e0b !important;
            border-color: #ffffff !important;
            color: #0f172a !important;
            transform: translateY(-50%) scale(1.12) !important;
        }
        .ibSlider .slick-prev:before,
        .ibSlider .slick-next:before { display: none !important; }

        .ibColumn {
            width: 100%;
            height: 100%;
            aspect-ratio: 16 / 9;
            max-height: 520px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .ibBgImage {
            position: absolute !important;
            inset: 0;
            width: 100%;
            height: 100%;
            aspect-ratio: 16 / 9;
            z-index: 0;
            background-size: cover;
            background-position: center;
            opacity: 0.88;
            filter: brightness(0.72);
            transition: transform 8s ease;
            pointer-events: none;
        }
        .ibColumn .alignHolder {
            position: relative;
            z-index: 2;
        }
        .slick-active .ibBgImage {
            transform: scale(1.05);
        }

        /* Landscape 16:9 Mobile Scaling */
        @media (max-width: 767.98px) {
            .introBlock.ibSlider,
            .ibColumn {
                aspect-ratio: 16 / 9 !important;
                height: auto !important;
                min-height: 220px !important;
            }
            .ibColumn .h1Medium {
                font-size: 1.15rem !important;
                margin-bottom: 0.4rem !important;
                line-height: 1.3 !important;
            }
            .ibColumn .btn {
                padding: 4px 14px !important;
                font-size: 0.75rem !important;
            }
            .ibSlider .slick-prev,
            .ibSlider .slick-next {
                width: 38px !important;
                height: 38px !important;
                font-size: 14px !important;
            }
            .ibSlider .slick-prev { left: 10px !important; }
            .ibSlider .slick-next { right: 10px !important; }
        }

        /* News Cards & Calendar Badge */
        .ueEveColumn {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.3s ease;
        }
        .ueEveColumn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(15, 23, 42, 0.1) !important;
            border-color: #cbd5e1;
        }
        .ueEveColumn:hover .cover_tumb {
            transform: scale(1.04);
        }
        .cover_tumb {
            object-fit: cover;
            width: 100%;
            height: 200px;
            transition: transform 0.4s ease;
        }
        .ueTimeTag {
            top: 12px;
            left: 12px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: #ffffff;
        }
        .ueTimeTag .textDay {
            background: var(--dishub-blue);
            color: #ffffff;
            padding: 6px 10px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ueDescriptionWrap a {
            color: #1e293b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .ueDescriptionWrap a:hover {
            color: var(--dishub-blue);
        }

        /* Sidebar Widgets */
        .cdColumnWidget {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .cdDocsList li {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px dashed #e2e8f0;
        }
        .cdDocsList li:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        /* Partner Links / Link Terkait - Matching DISKOMINFO Reference */
        .logosAsideBlock {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 40px 0 !important;
        }
        .lgsImageWrap {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 80px;
            padding: 5px 15px;
        }
        .img_linkterkait {
            margin: 0 auto;
            display: block;
            max-width: 220px !important;
            max-height: 65px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain;
            transition: all 0.3s ease-in-out;
            opacity: 0.9;
        }
        .lgsImageWrap:hover .img_linkterkait {
            transform: scale(1.08);
            opacity: 1;
        }
        .cdDocsList .cover_tumb {
            max-height: 180px !important;
            object-fit: contain !important;
            margin: 0 auto;
        }

        /* Instagram Official Embed */
        .getStartedAsideBlock {
            padding: 2rem 0;
            background: #f1f5f9;
        }
        .getStartedAsideBlock .gsabHolder {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }
        .getStartedAsideBlock .instagram-media {
            margin: 0 auto !important;
        }

        /* Footer */
        .ftAreaWrap {
            background: linear-gradient(180deg, #0a1f3d 0%, #07152b 100%) !important;
            color: #94a3b8;
            border-top: 4px solid var(--dishub-gold);
        }
        .ftLogo img {
            max-height: 52px;
            width: auto;
        }
        .ftHeading {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.4px;
            position: relative;
            padding-bottom: 8px;
            margin-bottom: 1.2rem;
        }
        .ftHeading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 35px;
            height: 3px;
            background: var(--dishub-gold);
            border-radius: 2px;
        }
        .ftPlace a {
            color: #cbd5e1 !important;
            transition: color 0.2s;
        }
        .ftPlace a:hover {
            color: var(--dishub-gold-light) !important;
            text-decoration: underline;
        }
        #pageFooter {
            background: #050e1d !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.78rem;
            color: #64748b;
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: none;
            z-index: 999;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--dishub-gold) !important;
            color: #0f172a !important;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }
        .back-to-top:hover {
            background: var(--dishub-blue) !important;
            color: #ffffff !important;
            transform: translateY(-3px);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gLight fontMain d-flex flex-column" style="min-height: 100vh;" x-data="{ sidebarOpen: false }">
    <div id="pageWrapper" class="d-flex flex-column flex-grow-1">
        
        <!-- STICKY TOP NAVBAR -->
        <div class="phStickyWrap">
            <header id="pageHeader" class="bg-white"> 
                <div class="hdFixerWrap py-2 py-md-3 py-xl-2 sSticky bg-white">
                    <div class="container">
                        <nav class="navbar navbar-expand-md navbar-light p-0">
                            <!-- Logo Kiri Diskominfo Style DISHUB -->
                            <div class="logo flex-shrink-0 mr-3 mr-xl-6 d-flex align-items-center">
                                <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                                    <img src="{{ $settings['logo_frontend'] ?? asset('images/logo_dishub.png') }}" class="img-fluid shrink-0" alt="DISHUB Kabupaten Probolinggo" style="max-height: 48px; width: auto !important; height: auto !important; object-fit: contain !important;">
                                    <div class="ml-2.5 pl-2.5 border-left border-slate-300 d-none d-sm-block text-left" style="border-left-width: 2px !important; border-color: #cbd5e1 !important; line-height: 1.25;">
                                        <span class="d-block font-weight-extrabold text-uppercase text-dark" style="font-size: 0.82rem; letter-spacing: 0.5px; font-family: 'Outfit', sans-serif; color: #0f172a !important;">DISHUB</span>
                                        <span class="d-block font-weight-bold text-muted" style="font-size: 0.7rem; color: #64748b !important;">KAB. PROBOLINGGO</span>
                                    </div>
                                </a>
                            </div>

                            <!-- Menu Navigasi Tengah -->
                            <div class="hdNavWrap flex-grow-1 d-flex align-items-center justify-content-end justify-content-lg-start">
                                <div class="collapse navbar-collapse pageMainNavCollapse mt-2 mt-md-0" id="pageMainNavCollapse">
                                    <ul class="navbar-nav mainNavigation">
                                        @if(isset($navMenus) && count($navMenus) > 0)
                                            @php $loginRendered = false; @endphp
                                            @foreach($navMenus as $menu)
                                                @php
                                                    $menuTitle = strtoupper(trim($menu->title));
                                                    $isLoginItem = ($menuTitle === 'LOGIN' || trim($menu->url, '/') === 'login');
                                                @endphp
                                                @continue($menuTitle === 'PROFIL DISHUB')

                                                @if($isLoginItem)
                                                    @php $loginRendered = true; @endphp
                                                    <li class="nav-item">
                                                        @auth
                                                            <a class="nav-link" href="{{ auth()->user()->isAnggota() ? route('anggota.dashboard') : route('admin.dashboard') }}">DASHBOARD</a>
                                                        @else
                                                            <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                                                        @endauth
                                                    </li>
                                                    @continue
                                                @endif

                                                @php
                                                    $activeChildList = ($menu->activeChildren && count($menu->activeChildren) > 0) ? $menu->activeChildren : $menu->children->where('is_active', true);
                                                @endphp
                                                @if(count($activeChildList) > 0)
                                                    <li class="nav-item dropdown ddohOpener">
                                                        <a class="nav-link dropdown-toggle dropIcn" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $menuTitle }}</a>
                                                        <div class="dropdown-menu hdMainDropdown desktopDropOnHover">
                                                            <ul class="list-unstyled mb-0 hdDropdownList" style="max-height: 500px; overflow-y: auto;">
                                                                @foreach($activeChildList as $child)
                                                                    <li><a class="dropdown-item" href="{{ \Illuminate\Support\Str::startsWith($child->url, ['http', '#']) ? $child->url : url($child->url) }}" target="{{ $child->target ?? '_self' }}">{{ $child->title }}</a></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{{ \Illuminate\Support\Str::startsWith($menu->url, ['http', '#']) ? $menu->url : url($menu->url) }}">{{ $menuTitle }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            @if(!$loginRendered)
                                                <li class="nav-item">
                                                    @auth
                                                        <a class="nav-link" href="{{ auth()->user()->isAnggota() ? route('anggota.dashboard') : route('admin.dashboard') }}">DASHBOARD</a>
                                                    @else
                                                        <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                                                    @endauth
                                                </li>
                                            @endif
                                        @else
                                            <!-- Fallback Menu Presisi Single -->
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('home') }}">HOME</a>
                                            </li>
                                            <li class="nav-item dropdown ddohOpener">
                                                <a class="nav-link dropdown-toggle dropIcn" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PROFIL</a>
                                                <div class="dropdown-menu hdMainDropdown desktopDropOnHover">
                                                    <ul class="list-unstyled mb-0 hdDropdownList">
                                                        <li><a class="dropdown-item" href="{{ url('/halaman/struktur-organisasi') }}">Struktur Organisasi</a></li>
                                                        <li><a class="dropdown-item" href="{{ url('/halaman/visi-misi') }}">Visi Misi</a></li>
                                                        <li><a class="dropdown-item" href="{{ url('/halaman/tugas-dan-fungsi') }}">Tugas dan Fungsi</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item dropdown ddohOpener">
                                                <a class="nav-link dropdown-toggle dropIcn" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">LAYANAN</a>
                                                <div class="dropdown-menu hdMainDropdown desktopDropOnHover">
                                                    <ul class="list-unstyled mb-0 hdDropdownList">
                                                        <li><a class="dropdown-item font-weight-bold text-primary border-bottom pb-2" href="{{ route('layanan') }}"><i class="fas fa-th-list mr-1.5"></i> Semua Layanan Publik</a></li>
                                                        @if(isset($headerServices) && count($headerServices) > 0)
                                                            @foreach($headerServices as $hSvc)
                                                                <li><a class="dropdown-item" href="{{ route('layanan.detail', $hSvc->slug) }}">{{ $hSvc->title }}</a></li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item dropdown ddohOpener">
                                                <a class="nav-link dropdown-toggle dropIcn" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">DOKUMEN</a>
                                                <div class="dropdown-menu hdMainDropdown desktopDropOnHover">
                                                    <ul class="list-unstyled mb-0 hdDropdownList">
                                                        <li><a class="dropdown-item" href="{{ route('dokumen') }}">Perencanaan Kinerja</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('dokumen') }}">Pengukuran Kinerja</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item dropdown ddohOpener">
                                                <a class="nav-link dropdown-toggle dropIcn" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">INFORMASI</a>
                                                <div class="dropdown-menu hdMainDropdown desktopDropOnHover">
                                                    <ul class="list-unstyled mb-0 hdDropdownList">
                                                        <li><a class="dropdown-item" href="{{ route('informasi') }}">Berita</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item dropdown ddohOpener">
                                                <a class="nav-link dropdown-toggle dropIcn" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">HUBUNGI</a>
                                                <div class="dropdown-menu hdMainDropdown desktopDropOnHover">
                                                    <ul class="list-unstyled mb-0 hdDropdownList">
                                                        <li><a class="dropdown-item" href="{{ route('kontak') }}">Kontak</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                @auth
                                                    <a class="nav-link" href="{{ auth()->user()->isAnggota() ? route('anggota.dashboard') : route('admin.dashboard') }}">DASHBOARD</a>
                                                @else
                                                    <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                                                @endauth
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Logo Kanan (BerAKHLAK) -->
                            <div class="hdRighterWrap d-flex align-items-center justify-content-end">
                                <div class="logo flex-shrink-0 mr-3 mr-xl-8 mr-xlwd-16">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ $settings['logo_berakhlak'] ?? 'https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png' }}" class="img-fluid" alt="BerAKHLAK">
                                    </a>
                                </div>
                                <button class="navbar-toggler pgNavOpener ml-2 bdrWidthAlter position-relative" type="button" data-toggle="collapse" data-target="#pageMainNavCollapse" aria-controls="pageMainNavCollapse" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
        </div>

        <!-- MAIN PAGE CONTENT -->
        <main class="flex-grow-1">
            @yield('content')
        </main>

        		<!-- ftAreaWrap -->
		<div class="ftAreaWrap position-relative bg-gDark fontAlter">
			<a id="back-to-top" href="#" class="btn btn-warning btn-lg back-to-top shadow-lg rounded-circle" role="button" title="Kembali ke atas"><i class="fas fa-chevron-up text-dark"></i></a>
			
			<aside class="footerAside py-5 py-md-6" style="background: linear-gradient(180deg, #0f172a 0%, #09101d 100%);">
				<div class="container">
					<div class="row align-items-start">
						
						<!-- Left Col: Logo & Deskripsi Instansi -->
						<div class="col-12 col-sm-6 col-md-5 col-xl-5 mb-4 mb-md-0">
							<div class="ftLogo mb-4">
								<a href="{{ route('home') }}" class="d-inline-flex align-items-center text-decoration-none">
									<img src="{{ $settings['logo_frontend'] ?? 'https://diskominfo.probolinggokab.go.id/backend/gambar/logo_frontend.png' }}" class="img_footer shrink-0" alt="DISHUB" style="max-height: 54px; width: auto;">
									<div class="ml-3 pl-3 text-left" style="border-left: 2px solid rgba(255, 255, 255, 0.2) !important; line-height: 1.25;">
										<span class="d-block font-weight-extrabold text-uppercase text-white" style="font-size: 1rem; letter-spacing: 0.6px; font-family: 'Outfit', sans-serif;">DISHUB</span>
										<span class="d-block font-weight-bold text-warning" style="font-size: 0.76rem; letter-spacing: 0.3px;">KAB. PROBOLINGGO</span>
									</div>
								</a>
							</div>
							<p class="text-white-50 mb-0" style="font-size: 0.88rem; line-height: 1.7; max-width: 440px;">
								Website Resmi Dinas Perhubungan Kabupaten Probolinggo. Media informasi publik terpadu untuk pelayanan transportasi, pengujian kendaraan bermotor (Uji KIR), keselamatan jalan, serta manajemen lalu lintas.
							</p>
						</div>
						 
						<!-- Middle Col: SKM Survey QR Code -->
						@if(($settings['show_survey'] ?? '1') == '1')
						<div class="col-12 col-sm-6 col-md-3 col-xl-3 mb-4 mb-md-0">
							<div>
								<h3 class="ftHeading text-white mb-3 font-weight-bold" style="font-size: 1.05rem; font-family: 'Outfit', sans-serif; letter-spacing: 0.5px;">Survei Kepuasan (SKM)</h3>
								<a href="{{ route('survei') }}" class="d-inline-block p-3 bg-white rounded-xl shadow-sm text-decoration-none border transition-all" style="border: 2px solid #f59e0b !important; max-width: 170px; border-radius: 14px;" title="Isi Survei Kepuasan Masyarakat">
									<img src="{{ $settings['qr_code_survey'] ?? 'https://diskominfo.probolinggokab.go.id/backend/gambar/qr_code_kominfo.png' }}" class="img-fluid d-block mx-auto rounded" alt="Survei Kepuasan SKM" style="max-width: 125px; height: auto;">
									<span class="d-block text-center font-weight-bold mt-2 py-1 bg-warning text-dark rounded-pill" style="font-size: 0.72rem;">
										<i class="fas fa-edit mr-1"></i> Isi Survei SKM
									</span>
								</a>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-md-4 col-xl-4">
						@else
						<div class="col-12 col-sm-6 col-md-7 col-xl-7">
						@endif

							<!-- Right Col: Alamat Kantor & Kontak Item Cards -->
							<div>
								<h3 class="ftHeading text-white mb-3 font-weight-bold" style="font-size: 1.05rem; font-family: 'Outfit', sans-serif; letter-spacing: 0.5px;">Alamat & Kontak Kantor</h3>								 
								
								<!-- Alamat Utama -->
								<div class="d-flex align-items-start mb-3">
									<div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center mr-3 flex-shrink-0 shadow-sm" style="width: 38px; height: 38px; font-size: 0.95rem; margin-top: 2px;">
										<i class="fas fa-map-marker-alt"></i>
									</div>
									<div style="font-size: 0.88rem; color: #cbd5e1; line-height: 1.5;">
										<strong class="d-block text-white font-weight-bold mb-0.5" style="font-size: 0.9rem;">Alamat Kantor Utama:</strong>
										<span>{{ $settings['address'] ?? 'Jl. Suroyo No. 12, Kraksaan / Dringu - Probolinggo, Jawa Timur 67271' }}</span>
									</div>
								</div>

								<!-- Telepon Kantor -->
								<div class="d-flex align-items-center mb-3">
									<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0 shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
										<i class="fas fa-phone-alt"></i>
									</div>
									<div style="font-size: 0.88rem;">
										<span class="d-block text-white-50" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Telepon Kantor</span>
										<span class="text-white font-weight-bold" style="font-size: 0.9rem;">{{ $settings['phone'] ?? '(0335) 421554' }}</span>
									</div>
								</div>

								<!-- Email Resmi -->
								<div class="d-flex align-items-center">
									<div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0 shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
										<i class="fas fa-envelope"></i>
									</div>
									<div style="font-size: 0.88rem;">
										<span class="d-block text-white-50" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Email Resmi Kantor</span>
										<a href="mailto:{{ $settings['email'] ?? 'dishub@probolinggokab.go.id' }}" class="text-white font-weight-bold text-decoration-none hover-warning" style="font-size: 0.88rem;">{{ $settings['email'] ?? 'dishub@probolinggokab.go.id' }}</a>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>
			</aside>

			<!-- Bottom Copyright Bar -->
			<footer id="pageFooter" class="text-center text-white py-3" style="background: #040812; border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.82rem;">
				<div class="container">
					<p class="mb-0 text-white-50">
						<span class="text-white font-weight-bold">{{ $settings['agency_name'] ?? 'DISHUB' }} - {{ $settings['regency_name'] ?? 'Kabupaten Probolinggo' }}</span> &copy; 2026. {{ $settings['copyright_text'] ?? 'All Rights Reserved.' }}
					</p>
				</div>
			</footer>
		</div>
	</div>
</div><!-- end flex-column wrapper -->

    <!-- Scripts Asli Template Diskominfo -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://diskominfo.probolinggokab.go.id/frontend/js/jqueryCustom.js"></script> 
    <script src="https://diskominfo.probolinggokab.go.id/frontend/js/plugins.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mencegah script template lama menghalangi link WhatsApp HALO SAE
            $('body').on('click', 'a[href*="api.whatsapp.com"], a[href*="wa.me"], .btn-whatsapp', function(e) {
                e.stopPropagation();
            });

            $('.select2').select2({
                themes: 'bootstrap5' 
            });                     

            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });
            $('#back-to-top').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 400);
                return false;
            });
        });
    </script>
    <script async src="https://www.instagram.com/embed.js"></script>
    <script src="https://diskominfo.probolinggokab.go.id/backend/plugins/custom/fslightbox-basic-3.4.1/fslightbox.js"></script>

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
    <!-- ===== TEXT-TO-SPEECH (SUARA PEMBACA TEKS DISHUB) WIDGET ===== -->
    <div id="tts-floating-widget" style="position: fixed; bottom: 85px; right: 25px; z-index: 9999; display: none; background: #0f172a; color: #ffffff; padding: 8px 14px; border-radius: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: 1px solid #334155; font-size: 12px; font-family: 'Outfit', sans-serif; align-items: center; gap: 8px;">
        <span id="tts-status" style="display: flex; align-items: center; gap: 6px; font-weight: 700;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
            <i class="fas fa-volume-high text-warning"></i> Membaca Teks Terpilih...
        </span>
        <button id="tts-stop-btn" type="button" style="background: #ef4444; color: #fff; border: none; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: 800; cursor: pointer; margin-left: 6px;">
            <i class="fas fa-stop"></i> Stop
        </button>
    </div>

    <!-- Floating Popup Tooltip near Selected Text -->
    <div id="tts-selection-popup" style="position: absolute; z-index: 10000; display: none; background: #0f2b5c; color: #fff; padding: 6px 14px; border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.35); font-size: 12px; font-weight: 800; cursor: pointer; border: 1.5px solid #38bdf8; transition: transform 0.15s ease;">
        <i class="fas fa-volume-high text-warning mr-1"></i> Putar Suara
    </div>

    <script>
    (function() {
        if (!('speechSynthesis' in window)) return;
        if (window.location.pathname.includes('/survei')) {
            window.speechSynthesis.cancel();
            return; // DIEM TOTAL DI HALAMAN SURVEI
        }

        var synth = window.speechSynthesis;
        var voices = [];
        var isReading = false;
        var selectedText = '';

        function loadVoices() {
            voices = synth.getVoices();
        }
        loadVoices();
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = loadVoices;
        }

        function getIndonesianVoice() {
            for (var i = 0; i < voices.length; i++) {
                var v = voices[i];
                if (v.lang === 'id-ID' || v.lang === 'id_ID' || v.name.toLowerCase().includes('indonesia')) {
                    return v;
                }
            }
            return null;
        }

        function speakText(text) {
            if (!text || text.trim() === '') return;
            synth.cancel(); // Hentikan suara yang sedang berjalan

            var utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            utterance.rate = 1.0;
            utterance.pitch = 1.0;

            var idVoice = getIndonesianVoice();
            if (idVoice) {
                utterance.voice = idVoice;
            }

            var widget = document.getElementById('tts-floating-widget');
            if (widget) widget.style.display = 'flex';

            utterance.onend = function() {
                if (widget) widget.style.display = 'none';
                isReading = false;
            };

            utterance.onerror = function() {
                if (widget) widget.style.display = 'none';
                isReading = false;
            };

            isReading = true;
            synth.speak(utterance);
        }

        function stopText() {
            synth.cancel();
            var widget = document.getElementById('tts-floating-widget');
            if (widget) widget.style.display = 'none';
            isReading = false;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var popup = document.getElementById('tts-selection-popup');
            var stopBtn = document.getElementById('tts-stop-btn');

            if (stopBtn) {
                stopBtn.addEventListener('click', stopText);
            }

            // Putar suara menu yang diklik saat halaman baru dimuat
            try {
                var pendingAnnounce = sessionStorage.getItem('tts_page_announce');
                if (pendingAnnounce) {
                    sessionStorage.removeItem('tts_page_announce');
                    setTimeout(function() {
                        speakText(pendingAnnounce);
                    }, 350);
                }
            } catch(e) {}

            // 1. SUARA KETIKA KLIK MENU / LINK / TOMBOL / H1-H6
            document.addEventListener('click', function(e) {
                var target = e.target.closest('a, button, .nav-link, .dropdown-item, h1, h2, h3, h4, h5, h6, label, .card-title');
                if (target) {
                    var text = (target.innerText || target.textContent || '').trim();
                    text = text.replace(/[\s\n\r]+/g, ' ').trim();
                    if (text && text.length >= 2 && text.length <= 150) {
                        try {
                            sessionStorage.setItem('tts_page_announce', text);
                        } catch(e) {}
                        speakText(text);
                    }
                }
            }, true);

            // 2. SUARA KETIKA ARAS KURSOR (HOVER) KE MENU NAVIGASI
            var hoverTimer = null;
            document.addEventListener('mouseover', function(e) {
                var target = e.target.closest('.mainNavigation .nav-link, .hdDropdownList .dropdown-item, .btn');
                if (target) {
                    clearTimeout(hoverTimer);
                    hoverTimer = setTimeout(function() {
                        var text = (target.innerText || target.textContent || '').trim();
                        text = text.replace(/[\s\n\r]+/g, ' ').trim();
                        if (text && text.length >= 2 && text.length <= 100) {
                            speakText(text);
                        }
                    }, 250);
                }
            });

            document.addEventListener('mouseout', function(e) {
                var target = e.target.closest('.mainNavigation .nav-link, .hdDropdownList .dropdown-item, .btn');
                if (target) {
                    clearTimeout(hoverTimer);
                }
            });

            // 3. DETEKSI TEKS TERPILIH / DIBLOKIR PENGGUNA
            document.addEventListener('mouseup', function(e) {
                if (popup && popup.contains(e.target)) return;

                setTimeout(function() {
                    var sel = window.getSelection();
                    var text = sel ? sel.toString().trim() : '';

                    if (text.length > 0) {
                        selectedText = text;
                        try {
                            var range = sel.getRangeAt(0);
                            var rect = range.getBoundingClientRect();

                            popup.style.top = (window.scrollY + rect.top - 42) + 'px';
                            popup.style.left = (window.scrollX + rect.left + (rect.width / 2) - 55) + 'px';
                            popup.style.display = 'block';
                        } catch (err) {}

                        // Putar suara pembaca teks secara otomatis
                        speakText(selectedText);
                    } else {
                        if (popup) popup.style.display = 'none';
                    }
                }, 10);
            });

            if (popup) {
                popup.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (selectedText) {
                        speakText(selectedText);
                    }
                    popup.style.display = 'none';
                });
            }

            document.addEventListener('mousedown', function(e) {
                if (popup && !popup.contains(e.target)) {
                    popup.style.display = 'none';
                }
            });
        });
    })();
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
