@extends('public.layouts.app')

@section('title', ($service->title ?? 'Layanan') . ' | Dinas Perhubungan Kabupaten Probolinggo')
@section('meta_description', $service->description ?? 'Detail layanan publik Dinas Perhubungan Kabupaten Probolinggo.')

@section('content')
<!-- Page Banner -->
<section class="py-4 py-md-5 text-white" style="background: linear-gradient(135deg, #0a1f3d 0%, #0d6e6e 100%); border-bottom: 3px solid var(--dishub-gold);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2 text-white-50" style="font-size:0.82rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('layanan') }}" class="text-white-50">Layanan</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $service->title }}</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold mb-2 text-white" style="font-size:2.2rem; font-family:'Outfit',sans-serif;">
            {{ $service->title }}
        </h1>
        @if($service->category)
            <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold" style="font-size:0.8rem;">
                <i class="fas fa-tag mr-1"></i> {{ strtoupper($service->category) }}
            </span>
        @endif
    </div>
</section>

<!-- Main Service Content (Clean Diskominfo Style: Foto, PDF Reader, Deskripsi) -->
<section class="py-5 bg-light">
    <div class="container-fluid px-3 px-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                
                <article class="bg-white p-4 p-md-5 rounded shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                    
                    <!-- 1. FOTO / GAMBAR INFOGRAFIS LAYANAN (FULL WIDTH) -->
                    @if($service->image_url_full)
                        <div class="mb-5 rounded-2xl overflow-hidden shadow-sm border border-slate-200" style="width: 100%;">
                            <img src="{{ $service->image_url_full }}" alt="{{ $service->title }}" class="img-fluid w-100 d-block" style="width: 100% !important; max-height: 650px; object-fit: contain; background: #f8fafc;">
                        </div>
                    @endif

                    <!-- 2. INTERACTIVE EMBEDDED PDF VIEWER (EXACT DISKOMINFO STYLE) -->
                    @if($service->pdf_full_url)
                        <div class="mb-5">
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 text-white font-weight-bold shadow-xs" style="background: #16a34a; font-size: 0.85rem; border-radius: 8px 8px 0 0;">
                                <i class="fas fa-book-open"></i> Baca (Klik 2x)
                            </div>
                            <div class="overflow-hidden shadow-sm border bg-dark" style="border-color: #cbd5e1 !important; height: 720px; border-radius: 0 12px 12px 12px;">
                                <iframe src="{{ $service->pdf_full_url }}#toolbar=1" width="100%" height="100%" style="border: none;" title="Dokumen PDF Layanan Resmi">
                                    <p class="p-3 text-center text-white">
                                        Browser Anda tidak mendukung preview iframe. <a href="{{ $service->pdf_full_url }}" target="_blank" class="font-weight-bold text-warning">Klik di sini untuk mengunduh PDF</a>.
                                    </p>
                                </iframe>
                            </div>
                        </div>
                    @endif

                    <!-- 3. DESKRIPSI KONTEN TEKS PENJELASAN LAYANAN -->
                    @if($service->content || $service->description)
                        <div class="page-content-wrapper text-dark p-4 p-md-5 rounded-2xl bg-white border shadow-sm mt-4" style="font-size: 1.05rem; line-height: 1.85; color: #0f172a !important; border-color: #cbd5e1 !important;">
                            <h4 class="font-weight-bold text-dark mb-3 border-bottom pb-2" style="font-family:'Outfit',sans-serif; color:#0f2b5c !important;">
                                <i class="fas fa-info-circle text-primary mr-2"></i> Deskripsi & Informasi Layanan
                            </h4>
                            @if(strip_tags($service->content ?? $service->description) === ($service->content ?? $service->description))
                                {!! nl2br(e($service->content ?? $service->description)) !!}
                            @else
                                {!! $service->content ?? $service->description !!}
                            @endif
                        </div>
                    @endif

                    <!-- SLEEK MODERN FOOTER ACTION & SOCIAL SHARE BAR -->
                    <div class="mt-5 pt-3">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px 20px;" class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            
                            <!-- Kembali ke Daftar Layanan -->
                            <div class="mb-3 mb-md-0">
                                <a href="{{ route('layanan') }}" class="btn font-weight-bold px-3.5 py-2 d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; font-size: 0.82rem; background: #ffffff; border: 1px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Kembali ke Daftar Layanan
                                </a>
                            </div>

                            <!-- Tombol Sosial Media Share -->
                            <div class="d-flex align-items-center flex-wrap justify-content-center justify-content-md-end">
                                <span class="font-weight-extrabold text-muted text-nowrap" style="font-size: 0.8rem; color: #475569 !important; margin-right: 10px;">Bagikan info ini:</span>
                                
                                <!-- WhatsApp Status / Story / Chat -->
                                <a href="https://api.whatsapp.com/send?text={{ urlencode('*' . $service->title . '*' . "\n\n" . url()->current()) }}" target="_blank" class="btn text-white font-weight-bold d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; background-color: #25d366; border: none; font-size: 0.78rem; padding: 6px 12px; margin: 3px 4px 3px 0; box-shadow: 0 2px 5px rgba(37,211,102,0.22);">
                                    <i class="fab fa-whatsapp" style="font-size: 0.95rem; margin-right: 6px;"></i>
                                    <span>WhatsApp / Story</span>
                                </a>

                                <!-- Instagram Story / Share -->
                                <button type="button" onclick="shareServiceToInstagram('{{ url()->current() }}', '{{ addslashes($service->title) }}')" class="btn text-white font-weight-bold d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); border: none; font-size: 0.78rem; padding: 6px 12px; margin: 3px 4px 3px 0; box-shadow: 0 2px 5px rgba(220,39,67,0.22);">
                                    <i class="fab fa-instagram" style="font-size: 0.95rem; margin-right: 6px;"></i>
                                    <span>IG Story / Share</span>
                                </button>

                                <!-- Salin Tautan Layanan -->
                                <button type="button" onclick="copyServiceLink('{{ url()->current() }}')" class="btn font-weight-bold d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; font-size: 0.78rem; padding: 6px 12px; margin: 3px 0 3px 0; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" title="Salin Tautan Layanan">
                                    <i class="far fa-copy" style="font-size: 0.9rem; margin-right: 6px; color: #64748b;"></i>
                                    <span>Salin Link</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <script>
                    function shareServiceToInstagram(url, title) {
                        var shareText = title + ' ' + url;
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(shareText).then(function() {
                                alert('Tautan layanan berhasil disalin!\nBuka Instagram untuk membagikan ke Story / Pesan DM (Teks & Link sudah otomatis tersalin).');
                                window.open('https://www.instagram.com/', '_blank');
                            }).catch(function() {
                                window.open('https://www.instagram.com/', '_blank');
                            });
                        } else {
                            window.open('https://www.instagram.com/', '_blank');
                        }
                    }

                    function copyServiceLink(url) {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(url).then(function() {
                                alert('Tautan layanan berhasil disalin ke clipboard!');
                            });
                        } else {
                            var dummy = document.createElement('input');
                            document.body.appendChild(dummy);
                            dummy.value = url;
                            dummy.select();
                            document.execCommand('copy');
                            document.body.removeChild(dummy);
                            alert('Tautan layanan berhasil disalin ke clipboard!');
                        }
                    }
                    </script>
                    </div>

                </article>

            </div>
        </div>
    </div>
</section>
@endsection
