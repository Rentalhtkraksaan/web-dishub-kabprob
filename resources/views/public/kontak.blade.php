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
            
            <!-- Left 8 Cols: Contact & Complaint Form -->
            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                
                <!-- Quick Info Cards (4 Columns) -->
                <div class="row mb-4">
                    <!-- WhatsApp Halo SAE -->
                    <div class="col-12 col-sm-6 col-md-6 col-xl-3 mb-3">
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
                    <div class="col-12 col-sm-6 col-md-6 col-xl-3 mb-3">
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
                    <div class="col-12 col-sm-6 col-md-6 col-xl-3 mb-3">
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
                    <div class="col-12 col-sm-6 col-md-6 col-xl-3 mb-3">
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
                <div class="p-3.5 p-md-4 rounded shadow-sm mb-4 text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%); border-left: 5px solid #34d399;">
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

                <!-- Form Container -->
                <div class="bg-white p-4 p-md-5 rounded shadow-sm" style="border: 1px solid #e2e8f0;">
                    <h2 class="font-weight-bold mb-2" style="font-size: 1.4rem; color: #0f2b5c;">Formulir Pesan & Pengaduan</h2>
                    <p class="text-muted small mb-4">Silakan lengkapi data di bawah ini untuk mengirimkan pesan atau laporan gangguan lalu lintas / penerangan jalan umum.</p>

                    <form method="POST" action="{{ route('kontak.store') }}" id="contactForm">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="font-weight-bold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama Anda..." value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label class="font-weight-bold small text-dark">Nomor WhatsApp / Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="font-weight-bold small text-dark">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label class="font-weight-bold small text-dark">Kategori Layanan <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="Pengaduan Lampu PJU">Pengaduan Lampu Penerangan Jalan (PJU)</option>
                                    <option value="Pengaduan Rambu & Traffic Light">Pengaduan Rambu & Traffic Light</option>
                                    <option value="Pertanyaan Layanan Uji KIR">Pertanyaan Layanan Uji KIR</option>
                                    <option value="Izin Trayek & Angkutan">Izin Trayek & Angkutan Umum</option>
                                    <option value="Aspirasi / Masukan Umum">Aspirasi / Masukan Umum</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-dark">Subjek / Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Tuliskan pokok pengaduan atau pertanyaan..." value="{{ old('subject') }}" required>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-dark">Isi Pesan / Rincian Laporan <span class="text-danger">*</span></label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Tuliskan kronologi, lokasi detail (jika laporan PJU/rambu), serta keterangan lengkap..." required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2.5 shadow-sm" style="border-radius: 50px; background: linear-gradient(135deg, #1e40af 0%, #0f2b5c 100%);">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan / Pesan
                        </button>
                    </form>
                </div>

            </div>

            <!-- Right 4 Cols: Sidebar Widgets -->
            <aside class="col-12 col-lg-4">
                
                <!-- Halo SAE Widget Card -->
                <div class="p-4 shadow-sm bg-white mb-4 rounded-xl border" style="border-radius: 14px; border: 1px solid #e2e8f0; border-top: 4px solid #25D366 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge badge-success px-2.5 py-1 font-weight-bold" style="font-size: 0.75rem;">
                            <i class="fab fa-whatsapp mr-1"></i> RESMI DISHUB
                        </span>
                        <span class="badge badge-light border text-muted" style="font-size: 0.72rem;">24 Jam Online</span>
                    </div>
                    <h4 class="font-weight-bold mb-2" style="font-size: 1.05rem; color: #0f2b5c;">Halo SAE (Pengaduan WA)</h4>
                    <p class="text-muted small mb-3" style="line-height: 1.5;">
                        Kanal pengaduan cepat masyarakat Kabupaten Probolinggo via WhatsApp. Tim kami siap merespons laporan Anda.
                    </p>
                    <div class="p-3 bg-light rounded-lg border mb-3 text-center">
                        <span class="d-block text-muted small font-weight-bold">Nomor WhatsApp Halo SAE:</span>
                        <span class="d-block font-weight-extrabold text-success mt-1" style="font-size: 1.25rem; letter-spacing: 0.5px;">
                            <i class="fab fa-whatsapp mr-1"></i> {{ $settings['halo_sae_phone'] ?? '0821 3100 1001' }}
                        </span>
                    </div>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-block font-weight-bold py-2 shadow-xs" style="border-radius: 10px; background-color: #25D366; border-color: #25D366;">
                        <i class="fab fa-whatsapp mr-1.5 font-weight-bold"></i> Buka Chat WhatsApp
                    </a>
                </div>

                <!-- SP4N LAPOR & Sidebar Widgets -->
                <article class="cdColumnWidget p-4 shadow-sm bg-white" style="border-radius: 14px; border: 1px solid #e2e8f0;">
                    <ul class="list-unstyled mb-0">
                        <li class="widget-item-wrap">
                            <span class="widget-header-badge">SP4N LAPOR</span>
                            <h4 class="font-weight-bold mb-2" style="font-size: 0.95rem; color: #0f2b5c;">Aspirasi dan Pengaduan Nasional</h4>
                            <p class="text-muted small mb-2">Gunakan juga kanal resmi SP4N LAPOR untuk laporan terintegrasi kementerian & lembaga pemerintah.</p>
                            <a href="https://www.lapor.go.id/" target="_blank" rel="noopener noreferrer">
                                <img src="https://diskominfo.probolinggokab.go.id/frontend/img/sp4n.jpg" class="img-fluid d-block w-100 rounded shadow-sm" alt="SP4N LAPOR">
                            </a>
                        </li>
                        @forelse($sidebarWidgets as $widget)
                            <li class="widget-item-wrap">
                                @if($widget->title)
                                    <span class="widget-header-badge">{{ $widget->title }}</span>
                                @endif
                                @if($widget->image_url)
                                    <img src="{{ $widget->image_url }}" class="img-fluid d-block w-100 rounded shadow-sm" style="max-height: 200px; object-fit: cover;" alt="{{ $widget->title }}">
                                @endif
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </article>
            </aside>

        </div>
    </div>
</section>
@endsection
