@extends('public.layouts.app')

@section('title', 'Dokumen Perencanaan Kinerja | Dinas Perhubungan Kabupaten Probolinggo')
@section('meta_description', 'Website Resmi Dinas Perhubungan Kabupaten Probolinggo - Dokumen Perencanaan Kinerja & Akuntabilitas.')

@section('styles')
<link href="https://diskominfo.probolinggokab.go.id/frontend/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="https://diskominfo.probolinggokab.go.id/backend/plugins/custom/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<link href="https://diskominfo.probolinggokab.go.id/flipbook/css/flipbook.style.css" rel="stylesheet" type="text/css"/>
<style>
    table.dataTable tbody tr:hover {
        background-color: #8aa884 !important;
        opacity: 0.8;
    }
    .th {
        background-color: #0f2b5c;
    }
    td {
        vertical-align: middle;
    }
    .tengah {
        text-align: center;
    }
    .modal-fullscreen {
        max-width: 100% !important;
        height: 90%;
        margin: 0;
    }
    .modal-fullscreen .modal-content {
        height: 100%;
        border-radius: 0;
    }
    .btn-pdf {
        background-color: #27ae60;
        color: #fff;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.85rem;
    }
    .btn-pdf:hover {
        background-color: #219150;
        color: #fff;
    }
    .btn-zip {
        background-color: #f39c12;
        color: #fff;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.85rem;
    }
    .btn-zip:hover {
        background-color: #d68910;
        color: #fff;
    }
</style>
@endsection

@section('content')
<header class="pageMainHead d-flex position-relative bgCover w-100 text-white" style="background: linear-gradient(135deg, #0a1f3d 0%, #2d6a4f 100%); padding: 50px 0;">
    <div class="alignHolder d-flex w-100 align-items-center">
        <div class="align w-100 position-relative">
            <div class="container">
                <h3 class="text-white mb-2 font-weight-bold" style="font-size: 2rem;">{{ $pageTitle ?? 'Dokumen Perencanaan Kinerja' }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrWhite rounded-0 border-0 p-0 fontAlter mb-0" style="background: transparent;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Dokumen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>
 
<section class="ItemfullBlock pt-7 pb-7 pb-lg-13 pb-xl-19 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <article class="npbColumn shadow-sm bg-white mb-6 p-4 rounded border">                     
                    <div class="npbDescriptionWrap px-3 pt-4 pb-3">
                        <section class="widget widgetSearch mb-4"> 
                            <form id="search-form" method="GET" action="{{ $type ? route('dokumen.type', $type) : route('dokumen') }}">
                                <div class="row">
                                    <div class="col-md-2 mb-2"> 
                                        <input type="text" class="form-control" name="tahun" id="year" placeholder="Tahun" value="{{ request('tahun') }}">
                                    </div>
                                    <div class="col-md-4 mb-2"> 
                                        <select class="form-control" name="kategori">     
                                            <option value="">-- Semua Kategori --</option>
                                            @if(($type ?? 'perencanaan-kinerja') == 'perencanaan-kinerja')
                                                <option value="Rencana Strategis" {{ request('kategori') == 'Rencana Strategis' ? 'selected' : '' }}>Rencana Strategis</option> 
                                                <option value="Pohon Kinerja" {{ request('kategori') == 'Pohon Kinerja' ? 'selected' : '' }}>Pohon Kinerja</option> 
                                                <option value="Cascading" {{ request('kategori') == 'Cascading' ? 'selected' : '' }}>Cascading</option> 
                                                <option value="Indikator Kinerja Utama" {{ request('kategori') == 'Indikator Kinerja Utama' ? 'selected' : '' }}>Indikator Kinerja Utama</option> 
                                                <option value="Rencana Kerja" {{ request('kategori') == 'Rencana Kerja' ? 'selected' : '' }}>Rencana Kerja</option> 
                                                <option value="Rencana Aksi" {{ request('kategori') == 'Rencana Aksi' ? 'selected' : '' }}>Rencana Aksi</option> 
                                                <option value="Perjanjian Kinerja" {{ request('kategori') == 'Perjanjian Kinerja' ? 'selected' : '' }}>Perjanjian Kinerja</option> 
                                                <option value="Dokumen Perencanaan Anggaran" {{ request('kategori') == 'Dokumen Perencanaan Anggaran' ? 'selected' : '' }}>Dokumen Perencanaan Anggaran</option>
                                            @elseif($type == 'pengukuran-kinerja')
                                                <option value="Capaian Kinerja" {{ request('kategori') == 'Capaian Kinerja' ? 'selected' : '' }}>Capaian Kinerja</option> 
                                                <option value="Indikator Pengukuran" {{ request('kategori') == 'Indikator Pengukuran' ? 'selected' : '' }}>Indikator Pengukuran</option>
                                            @elseif($type == 'pelaporan-kinerja')
                                                <option value="LAKIP / LKjIP" {{ request('kategori') == 'LAKIP / LKjIP' ? 'selected' : '' }}>LAKIP / LKjIP</option> 
                                                <option value="Laporan Kinerja Tahunan" {{ request('kategori') == 'Laporan Kinerja Tahunan' ? 'selected' : '' }}>Laporan Kinerja Tahunan</option>
                                            @elseif($type == 'evaluasi-kinerja')
                                                <option value="Lembar Hasil Evaluasi (LHE)" {{ request('kategori') == 'Lembar Hasil Evaluasi (LHE)' ? 'selected' : '' }}>Lembar Hasil Evaluasi (LHE)</option> 
                                                <option value="Evaluasi AKIP" {{ request('kategori') == 'Evaluasi AKIP' ? 'selected' : '' }}>Evaluasi AKIP</option>
                                            @endif
                                        </select> 
                                    </div>
                                    <div class="col-md-6 mb-2"> 
                                        <div class="input-group">
                                            <input name="judul" type="text" class="form-control" placeholder="Search Here…" value="{{ request('judul') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary font-weight-bold px-4" type="submit" style="background: #0f2b5c;">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100" id="akuntabilitas">
                                <thead style="background-color: #0f2b5c; color: #ffffff;">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>                                 
                                        <th width="45%">Judul</th>
                                        <th width="10%" class="text-center">PDF</th>
                                        <th width="10%" class="text-center">Zip</th>
                                        <th width="15%">Kategori</th>                                   
                                        <th width="15%" class="text-center">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documents as $idx => $doc)
                                        <tr>
                                            <td class="text-center align-middle font-weight-bold">{{ $documents->firstItem() + $idx }}</td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                    {{ $doc->title }}
                                                </div>
                                                @if($doc->tahun)
                                                    <span class="badge badge-secondary" style="font-size: 0.75rem;">Tahun: {{ $doc->tahun }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($doc->pdf_full_url)
                                                    <button type="button" class="btn btn-pdf view_data shadow-xs" data-eid="{{ $doc->id }}" data-efile="{{ $doc->pdf_full_url }}" data-ejudul="{{ $doc->title }}">
                                                        <i class="fas fa-file-pdf mr-1"></i> Lihat
                                                    </button>
                                                @else
                                                    <span class="badge badge-secondary">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($doc->file_zip_url || $doc->file_zip_path)
                                                    <a href="{{ route('dokumen.download_zip', $doc->id) }}" class="btn btn-zip">
                                                        <i class="fas fa-file-archive mr-1"></i> Zip
                                                    </a>
                                                @else
                                                    <span class="badge badge-secondary">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-light border text-dark font-weight-normal px-2 py-1" style="font-size: 0.82rem;">{{ $doc->category }}</span>
                                            </td>
                                            <td class="text-center align-middle text-muted" style="font-size: 0.85rem;">
                                                {{ $doc->created_at ? $doc->created_at->format('d-m-Y') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-folder-open mb-2" style="font-size: 2.5rem;"></i>
                                                <p class="m-0">Belum ada dokumen yang dipublikasikan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table> 
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $documents->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>                    
                </article>
            </div>             
        </div>         
    </div>
</section>
 
<!-- Modal View PDF & Flipbook -->
<div class="modal fade" id="data_modal_viewpdf" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold" id="pdf_title">Dokumen PDF</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" id="datapdf" style="height: 75vh;"></div>
            <div class="modal-footer bg-light">
                <div id="baca_buku" class="mr-auto"></div>
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://diskominfo.probolinggokab.go.id/flipbook/js/flipbook.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://diskominfo.probolinggokab.go.id/backend/plugins/custom/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#year").datepicker({
            enableOnReadonly: true,
            keyboardNavigation: true,
            showOnFocus: true,
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose: true
        });

        $('body').on('click', '.view_data', function() {
            $('#datapdf').empty();
            $('#baca_buku').empty();
            var file = $(this).data('efile');
            var judul = $(this).data('ejudul');
            $('#pdf_title').text(judul);

            if(!file || file === '') {
                swal({
                    title: judul,
                    text: 'File PDF tidak tersedia.',
                    timer: 4000,
                    showConfirmButton: false,
                    type: "error"
                });
            } else {
                $('#datapdf').html('<iframe src="'+file+'#toolbar=1" height="100%" width="100%" frameborder="0"></iframe>'); 
                $('#baca_buku').html('<a class="baca_pdf btn btn-warning font-weight-bold text-dark px-3.5 py-2 shadow-xs" href="javascript:void(0)" data-efiles="'+file+'" data-ejuduls="'+judul+'"><i class="fas fa-book-reader mr-1.5"></i> Baca Flipbook Mode</a>');               
                $('#data_modal_viewpdf').modal('show');
            }        
        });

        $('body').on('click', '.baca_pdf', function(e) {
            e.preventDefault();
            var file = $(this).data('efiles');
            var judul = $(this).data('ejuduls');

            // Render Flipbook 3D langsung di dalam modal body #datapdf
            $('#datapdf').empty().html('<div id="fb_canvas" style="width:100%; height:100%;"></div>');

            if (typeof $.fn.flipBook === 'function') {
                try {
                    $('#fb_canvas').flipBook({
                        pdfUrl: file,
                        lightBox: false,
                        layout: 3,
                        webgl: true,
                        btnShare: { enabled: false },
                        btnPrint: { hideOnMobile: true },
                        btnDownloadPages: { enabled: true, url: file, name: judul },
                        btnColor: '#f59e0b',
                        sideBtnColor: '#0f2b5c'
                    });
                } catch(err) {
                    console.log('FlipBook error:', err);
                    $('#datapdf').html('<iframe src="'+file+'#toolbar=1" height="100%" width="100%" frameborder="0"></iframe>');
                }
            } else {
                $('#datapdf').html('<iframe src="'+file+'#toolbar=1" height="100%" width="100%" frameborder="0"></iframe>');
            }
        });
    });
</script>
@endsection
