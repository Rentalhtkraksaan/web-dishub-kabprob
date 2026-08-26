@extends('public.layouts.app')

@section('title', $news->title . ' | DISHUB Kabupaten Probolinggo')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($news->content), 150))

@section('content')
<!-- Breadcrumb Header -->
<section class="py-4 text-white" style="background: linear-gradient(135deg, #0a1f3d 0%, #1e3a8a 100%); border-bottom: 3px solid var(--dishub-gold);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-1 text-white-50" style="font-size: 0.82rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('informasi') }}" class="text-warning">Informasi</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ \Illuminate\Support\Str::limit($news->title, 40) }}</li>
            </ol>
        </nav>
        <span class="badge badge-warning text-dark font-weight-bold uppercase px-3 py-1 mb-2" style="font-size: 0.75rem;">
            {{ strtoupper($news->category ?? 'BERITA DISHUB') }}
        </span>
        <h1 class="font-weight-bold text-white mb-2" style="font-size: 1.9rem; line-height: 1.35; font-family: 'Outfit', sans-serif;">
            {{ $news->title }}
        </h1>
        <div class="d-flex flex-wrap align-items-center text-white-50 small gap-3 pt-1">
            <span class="mr-3"><i class="far fa-calendar-alt text-warning mr-1"></i> {{ optional($news->published_at)->translatedFormat('d F Y') ?? '01 Jan 2026' }}</span>
            <span class="mr-3"><i class="far fa-eye text-warning mr-1"></i> {{ $news->views ?? 0 }} kali dibaca</span>
        </div>
    </div>
</section>

<!-- Main Article & Sidebar -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            
            <!-- Left 8 Cols: Article Body -->
            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                <article class="bg-white p-4 p-md-5 rounded shadow-sm" style="border: 1px solid #e2e8f0;">
                    
                    @if($news->image_url)
                        <div class="mb-4 rounded overflow-hidden shadow-sm" style="max-height: 480px;">
                            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-fluid w-100" style="object-fit: cover;">
                        </div>
                    @endif

                    <div class="article-body text-dark" style="font-size: 1.05rem; line-height: 1.85; color: #0f172a !important;">
                        @if(strip_tags($news->content) === $news->content)
                            {!! nl2br(e($news->content)) !!}
                        @else
                            {!! $news->content !!}
                        @endif
                    </div>

                    <!-- SLEEK MODERN FOOTER ACTION & SOCIAL SHARE BAR -->
                    <div class="mt-5 pt-3">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px 20px;" class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            
                            <!-- Kembali ke Daftar Berita -->
                            <div class="mb-3 mb-md-0">
                                <a href="{{ route('informasi') }}" class="btn font-weight-bold px-3.5 py-2 d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; font-size: 0.82rem; background: #ffffff; border: 1px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Kembali ke Daftar Berita
                                </a>
                            </div>

                            <!-- Tombol Sosial Media Share -->
                            <div class="d-flex align-items-center flex-wrap justify-content-center justify-content-md-end">
                                <span class="font-weight-extrabold text-muted text-nowrap" style="font-size: 0.8rem; color: #475569 !important; margin-right: 10px;">Bagikan info ini:</span>
                                
                                <!-- WhatsApp Status / Story / Chat -->
                                <a href="https://api.whatsapp.com/send?text={{ urlencode('*' . $news->title . '*' . "\n\n" . url()->current()) }}" target="_blank" class="btn text-white font-weight-bold d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; background-color: #25d366; border: none; font-size: 0.78rem; padding: 6px 12px; margin: 3px 4px 3px 0; box-shadow: 0 2px 5px rgba(37,211,102,0.22);">
                                    <i class="fab fa-whatsapp" style="font-size: 0.95rem; margin-right: 6px;"></i>
                                    <span>WhatsApp / Story</span>
                                </a>

                                <!-- Instagram Story / Share -->
                                <button type="button" onclick="shareToInstagram('{{ url()->current() }}', '{{ addslashes($news->title) }}')" class="btn text-white font-weight-bold d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); border: none; font-size: 0.78rem; padding: 6px 12px; margin: 3px 4px 3px 0; box-shadow: 0 2px 5px rgba(220,39,67,0.22);">
                                    <i class="fab fa-instagram" style="font-size: 0.95rem; margin-right: 6px;"></i>
                                    <span>IG Story / Share</span>
                                </button>

                                <!-- Salin Tautan Berita -->
                                <button type="button" onclick="copyNewsLink('{{ url()->current() }}')" class="btn font-weight-bold d-inline-flex align-items-center text-nowrap" style="border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; font-size: 0.78rem; padding: 6px 12px; margin: 3px 0 3px 0; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" title="Salin Tautan Berita">
                                    <i class="far fa-copy" style="font-size: 0.9rem; margin-right: 6px; color: #64748b;"></i>
                                    <span>Salin Link</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <script>
                    function shareToInstagram(url, title) {
                        var shareText = title + ' ' + url;
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(shareText).then(function() {
                                alert('Tautan berita berhasil disalin!\nBuka Instagram untuk membagikan ke Story / Pesan DM (Teks & Link sudah otomatis tersalin).');
                                window.open('https://www.instagram.com/', '_blank');
                            }).catch(function() {
                                window.open('https://www.instagram.com/', '_blank');
                            });
                        } else {
                            window.open('https://www.instagram.com/', '_blank');
                        }
                    }

                    function copyNewsLink(url) {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(url).then(function() {
                                alert('Tautan berita berhasil disalin ke clipboard!');
                            });
                        } else {
                            var dummy = document.createElement('input');
                            document.body.appendChild(dummy);
                            dummy.value = url;
                            dummy.select();
                            document.execCommand('copy');
                            document.body.removeChild(dummy);
                            alert('Tautan berita berhasil disalin ke clipboard!');
                        }
                    }
                    </script>

                </article>
            </div>

            <!-- Right 4 Cols: Related News & Widgets -->
            <aside class="col-12 col-lg-4">
                
                <!-- Berita Lainnya -->
                @if(isset($latestNews) && count($latestNews) > 0)
                    <div class="bg-white p-4 rounded shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                        <h4 class="font-weight-bold mb-3 pb-2 border-bottom" style="font-size: 1.1rem; color: #0f2b5c;">
                            <i class="far fa-newspaper text-primary mr-2"></i> Berita Lainnya
                        </h4>
                        <ul class="list-unstyled mb-0">
                            @foreach($latestNews as $item)
                                <li class="mb-3 pb-3 border-bottom d-flex gap-3">
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rounded mr-2" style="width: 70px; height: 60px; object-fit: cover; flex-shrink: 0;">
                                    @endif
                                    <div>
                                        <a href="{{ route('news.detail', $item->slug) }}" class="text-dark font-weight-bold small d-block mb-1" style="line-height: 1.35; text-decoration: none;">
                                            {{ \Illuminate\Support\Str::limit($item->title, 55) }}
                                        </a>
                                        <span class="text-muted" style="font-size: 0.72rem;">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ optional($item->published_at)->format('d M Y') }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Sidebar Widgets -->
                <article class="cdColumnWidget p-4 shadow-sm bg-white" style="border-radius: 12px; border: 1px solid #e2e8f0;">
                    <ul class="list-unstyled mb-0">
                        @forelse($sidebarWidgets as $widget)
                            <li class="widget-item-wrap">
                                @if($widget->title)
                                    <span class="widget-header-badge">{{ $widget->title }}</span>
                                @endif

                                @if($widget->image_url)
                                    @if($widget->link_url)
                                        <a href="{{ $widget->link_url }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ $widget->image_url }}" class="img-fluid d-block w-100 rounded shadow-sm" style="max-height: 220px; object-fit: cover;" alt="{{ $widget->title }}">
                                        </a>
                                    @else
                                        <img src="{{ $widget->image_url }}" class="img-fluid d-block w-100 rounded shadow-sm" style="max-height: 220px; object-fit: cover;" alt="{{ $widget->title }}">
                                    @endif
                                @endif

                                @if($widget->content)
                                    <div class="mt-2 small text-muted leading-relaxed">{!! $widget->content !!}</div>
                                @endif
                            </li>
                        @empty
                            <li class="widget-item-wrap">
                                <span class="widget-header-badge">Pengaduan LLAJ</span>
                                <h4 class="font-weight-bold mb-2" style="font-size: 0.95rem; color: #0f2b5c;">Layanan Pengaduan Dishub</h4>
                                <a href="{{ route('kontak') }}">
                                    <img src="https://diskominfo.probolinggokab.go.id/frontend/img/sp4n.jpg" class="img-fluid d-block w-100 rounded" alt="Pengaduan">
                                </a>
                            </li>
                        @endforelse
                    </ul>
                </article>

            </aside>

        </div>
    </div>
</section>
@endsection
