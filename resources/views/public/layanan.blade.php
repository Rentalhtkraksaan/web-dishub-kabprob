@extends('public.layouts.app')

@section('title', 'Layanan Publik | Dinas Perhubungan Kabupaten Probolinggo')
@section('meta_description', 'Daftar layanan publik resmi Dinas Perhubungan Kabupaten Probolinggo — Uji KIR, Izin Trayek, Rekayasa Lalu Lintas, dan lainnya.')

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #0a1f3d 0%, #0d6e6e 100%); border-bottom: 3px solid var(--dishub-gold);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2 text-white-50" style="font-size:0.82rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Layanan Publik</li>
                    </ol>
                </nav>
                <h1 class="font-weight-bold mb-2 text-white" style="font-size:2.2rem; font-family:'Outfit',sans-serif;">
                    Layanan Publik DISHUB
                </h1>
                <p class="text-white-50 mb-0" style="font-size:0.95rem;">
                    Daftar layanan resmi yang tersedia di Dinas Perhubungan Kabupaten Probolinggo untuk masyarakat.
                </p>
            </div>
            <div class="col-12 col-md-4 text-md-right mt-3 mt-md-0">
                <span class="badge badge-warning p-2 px-3 font-weight-bold text-dark" style="font-size:0.85rem;">
                    <i class="fas fa-cogs mr-1"></i> {{ $services->total() }} Layanan Tersedia
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5 bg-light">
    <div class="container">
        @if($services->isEmpty())
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <i class="fas fa-cogs text-muted mb-3" style="font-size:3rem; opacity:0.3;"></i>
                <h4 class="font-weight-bold text-muted">Belum Ada Layanan</h4>
                <p class="text-muted small">Layanan publik belum tersedia saat ini.</p>
            </div>
        @else
            <div class="row">
                @foreach($services as $service)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <article class="h-100 bg-white shadow-sm d-flex flex-column" style="border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; transition: box-shadow 0.3s, transform 0.3s;">
                            <!-- Image or Icon Header -->
                            <div class="position-relative overflow-hidden" style="height:180px;">
                                @if($service->image_url)
                                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}"
                                         class="w-100 h-100" style="object-fit:cover; transition: transform 0.4s;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                                         style="background: linear-gradient(135deg, #0d6e6e, #1e3a8a);">
                                        <i class="{{ $service->icon ?? 'fas fa-cogs' }} text-white" style="font-size:3rem; opacity:0.8;"></i>
                                    </div>
                                @endif
                                @if($service->category)
                                    <span class="position-absolute badge badge-dark px-2 py-1" style="top:10px; right:10px; font-size:0.68rem; background:rgba(15,23,42,0.85); letter-spacing:0.5px;">
                                        {{ strtoupper($service->category) }}
                                    </span>
                                @endif
                                @if($service->pdf_url)
                                    <span class="position-absolute badge badge-danger px-2 py-1 shadow-sm" style="top:10px; left:10px; font-size:0.68rem; letter-spacing:0.5px;">
                                        <i class="fas fa-file-pdf mr-1"></i> BERKAS PDF
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="flex-grow-1">
                                    <h3 class="font-weight-bold mb-2" style="font-size:1rem; line-height:1.45; font-family:'Outfit',sans-serif;">
                                        <a href="{{ route('layanan.detail', $service->slug) }}" class="text-dark" style="text-decoration:none;">
                                            {{ $service->title }}
                                        </a>
                                    </h3>
                                    @if($service->description)
                                        <p class="text-muted small mb-3" style="line-height:1.6;">
                                            {{ \Illuminate\Support\Str::limit($service->description, 110) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Footer Card -->
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                    <span class="text-muted" style="font-size:0.75rem;">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $service->created_at ? $service->created_at->translatedFormat('d M Y') : '' }}
                                    </span>
                                    <a href="{{ route('layanan.detail', $service->slug) }}" class="text-primary font-weight-bold small">
                                        Selengkapnya &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Hover effect on cards
    document.querySelectorAll('article').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 8px 30px rgba(0,0,0,0.12)';
            this.style.transform = 'translateY(-4px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
            this.style.transform = '';
        });
    });
</script>
@endsection
