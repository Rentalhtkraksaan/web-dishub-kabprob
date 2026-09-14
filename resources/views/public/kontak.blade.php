@extends('public.layouts.app')

@section('title', 'Kontak & Layanan Pengaduan Halo SAE | DISHUB Kabupaten Probolinggo')
@section('meta_description', 'Hubungi Dinas Perhubungan Kabupaten Probolinggo untuk informasi, pengaduan Halo SAE (WhatsApp: 0821 3100 1001), laporan gangguan lampu PJU, rambu, serta layanan keselamatan jalan.')

@section('content')
@php
    $rawWaPhone = $settings['halo_sae_phone'] ?? '0821 3100 1001';
    $cleanWaPhone = preg_replace('/[^0-9]/', '', $rawWaPhone);
    if (\Illuminate\Support\Str::startsWith($cleanWaPhone, '0')) {
        $cleanWaPhone = '62' . substr($cleanWaPhone, 1);
    }
    $waText = urlencode('Halo HALO SAE Kabupaten Probolinggo, saya ingin menyampaikan pengaduan/laporan:');
    $waUrl = "https://api.whatsapp.com/send?phone={$cleanWaPhone}&text={$waText}";
@endphp
<!-- Page Banner Header -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #0a1f3d 0%, #1e3a8a 100%); border-bottom: 3px solid var(--dishub-gold);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2 text-white-50" style="font-size: 0.82rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Kontak & Pengaduan</li>
                    </ol>
                </nav>
                <h1 class="font-weight-bold mb-2 text-white" style="font-size: 2.2rem; font-family: 'Outfit', sans-serif;">
                    Kontak & Layanan Pengaduan
                </h1>
                <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                    Sampaikan pertanyaan, masukan, permohonan informasi, atau laporan pengaduan melalui formulir portal dan WhatsApp Halo SAE.
                </p>
            </div>
            <div class="col-12 col-md-4 text-md-right mt-3 mt-md-0">
                <span class="badge badge-warning p-2 px-3 font-weight-bold text-dark" style="font-size: 0.85rem;">
                    <i class="fas fa-headset mr-1"></i> Call Center & Halo SAE
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Main Contact Form & Sidebar -->
<section class="py-5 bg-light">
    <div class="container">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show p-3 mb-4 rounded shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show p-3 mb-4 rounded shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            
            <!-- Full Width Content: Contact Info -->
            <div class="col-12 mb-4">
                
                <!-- Quick Info Cards (4 Columns) -->
                <div class="row mb-4">
                    <!-- WhatsApp Halo SAE -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                            <div class="bg-white p-3 rounded shadow-sm h-100 border text-center transition-all hover-shadow" style="border-top: 3px solid #25D366 !important;">
                                <div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle mb-2" style="width: 44px; height: 44px; background-color: #25D366;">
                                    <i class="fab fa-whatsapp" style="font-size: 1.35rem;"></i>
                                </div>
                                <span class="badge badge-success px-2 py-0.5 d-inline-block mb-1" style="font-size: 0.68rem; font-weight: 700;">HALO SAE</span>
                                <h4 class="font-weight-bold mb-1" style="font-size: 0.88rem; color: #0f2b5c;">WA Pengaduan</h4>
                                <p class="text-success font-weight-bold small m-0" style="font-size: 0.82rem;">{{ $settings['halo_sae_phone'] ?? '0821 3100 1001' }}</p>
                            </div>
                        </a>
                    </div>
                    <!-- Telepon Kantor -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                        <div class="bg-white p-3 rounded shadow-sm h-100 border text-center" style="border-top: 3px solid #1e40af !important;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-2" style="width: 44px; height: 44px;">
                                <i class="fas fa-phone-alt" style="font-size: 1.1rem;"></i>
                            </div>
                            <span class="badge badge-primary px-2 py-0.5 d-inline-block mb-1" style="font-size: 0.68rem; font-weight: 700;">OFFICE</span>
                            <h4 class="font-weight-bold mb-1" style="font-size: 0.88rem; color: #0f2b5c;">Telepon Kantor</h4>
                            <p class="text-muted small m-0" style="font-size: 0.8rem;">{{ $settings['phone'] ?? '(0335) 421554' }}</p>
                        </div>
                    </div>
                    <!-- Email Resmi -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                        <div class="bg-white p-3 rounded shadow-sm h-100 border text-center" style="border-top: 3px solid #f59e0b !important;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle mb-2" style="width: 44px; height: 44px;">
                                <i class="fas fa-envelope" style="font-size: 1.1rem;"></i>
                            </div>
                            <span class="badge badge-warning px-2 py-0.5 d-inline-block mb-1" style="font-size: 0.68rem; font-weight: 700;">EMAIL</span>
                            <h4 class="font-weight-bold mb-1" style="font-size: 0.88rem; color: #0f2b5c;">Email Resmi</h4>
                            <p class="text-muted small m-0 text-truncate" style="font-size: 0.76rem;" title="{{ $settings['email'] ?? 'dishub@probolinggokab.go.id' }}">{{ $settings['email'] ?? 'dishub@probolinggokab.go.id' }}</p>
                        </div>
                    </div>
                    <!-- Jam Pelayanan -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                        <div class="bg-white p-3 rounded shadow-sm h-100 border text-center" style="border-top: 3px solid #10b981 !important;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-2" style="width: 44px; height: 44px;">
                                <i class="fas fa-clock" style="font-size: 1.1rem;"></i>
                            </div>
                            <span class="badge badge-info px-2 py-0.5 d-inline-block mb-1" style="font-size: 0.68rem; font-weight: 700;">JAM KERJA</span>
                            <h4 class="font-weight-bold mb-1" style="font-size: 0.88rem; color: #0f2b5c;">Jam Pelayanan</h4>
                            <p class="text-muted small m-0" style="font-size: 0.76rem;">Senin - Jumat 07:30-15:30</p>
                        </div>
                    </div>
                </div>

                <!-- Halo SAE Highlight Banner -->
                <div class="p-3.5 p-md-4 rounded shadow-sm text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%); border-left: 5px solid #34d399;">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 52px; height: 52px; font-size: 1.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-0.5 mb-1" style="font-size: 0.72rem;">LAYANAN PENGADUAN CEPAT</span>
                                </div>
                                <h3 class="font-weight-bold mb-1 text-white" style="font-size: 1.2rem; font-family: 'Outfit', sans-serif;">
                                    Halo SAE: <span class="text-warning">{{ $settings['halo_sae_phone'] ?? '0821 3100 1001' }}</span>
                                </h3>
                                <p class="text-white-50 small mb-0" style="font-size: 0.85rem;">
                                    Lapor gangguan lampu penerangan jalan (PJU), rambu rusak, atau kendala lalu lintas langsung ke petugas kami melalui WhatsApp.
                                </p>
                            </div>
                        </div>
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-warning text-dark font-weight-bold px-3.5 py-2 rounded-pill shadow-sm flex-shrink-0 align-self-start align-self-md-center" style="font-size: 0.85rem;">
                            <i class="fab fa-whatsapp mr-1 text-success font-weight-bold"></i> Chat Halo SAE Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Widgets Grid Section -->
            @if(count($sidebarWidgets) > 0)
            <div class="col-12 mt-4">
                <h4 class="font-weight-bold mb-4 text-center" style="color: #0f2b5c;">Informasi Layanan</h4>
                <div class="row justify-content-center">
                    @foreach($sidebarWidgets as $widget)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <article class="p-3 shadow-sm bg-white h-100 rounded-xl transition-all hover-shadow" style="border: 1px solid #e2e8f0;">
                                @if($widget->title)
                                    <h6 class="font-weight-bold mb-3 text-dark text-center">{{ $widget->title }}</h6>
                                @endif
                                @if($widget->image_url)
                                    <img src="{{ $widget->image_url }}" class="img-fluid d-block mx-auto rounded shadow-sm" style="max-height: 250px; object-fit: cover;" alt="{{ $widget->title }}">
                                @endif
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
@endsection
