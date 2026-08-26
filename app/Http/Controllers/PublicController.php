<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\NewsItem;
use App\Models\SidebarWidget;
use App\Models\RelatedLink;
use App\Models\Page;
use App\Models\PublicDocument;
use App\Models\ContactMessage;
use App\Models\ActivityLog;
use App\Models\Service;
use App\Models\InformasiTab;
use App\Models\VideoItem;
use App\Models\GalleryAlbum;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    private function getCommonData()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('activeChildren')
            ->orderBy('order', 'asc')
            ->get();

        return compact('settings', 'navMenus');
    }

    public function index()
    {
        $common = $this->getCommonData();

        // Tracker Pengunjung (Hitung setiap kali reload)
        $today = now()->toDateString();
        \App\Models\VisitorStat::firstOrCreate(['date' => $today])->increment('views');
        $sliders = HeroSlider::where('is_active', true)->orderBy('order', 'asc')->get();
        $latestNews = NewsItem::orderBy('published_at', 'desc')->take(4)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();
        $relatedLinks = RelatedLink::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.index', array_merge($common, compact('sliders', 'latestNews', 'sidebarWidgets', 'relatedLinks')));
    }

    public function page($slug)
    {
        $common = $this->getCommonData();

        // Pemulihan otomatis jika halaman inti tidak sengaja terhapus di admin
        if ($slug === 'struktur-organisasi') {
            $page = Page::firstOrCreate(
                ['slug' => 'struktur-organisasi'],
                [
                    'title' => 'Struktur Organisasi DISHUB',
                    'content' => 'Bagan Struktur Organisasi dan Garis Komando resmi Dinas Perhubungan Kabupaten Probolinggo.',
                    'is_published' => true,
                ]
            );
            if (!$page->is_published) {
                $page->update(['is_published' => true]);
            }
        } elseif ($slug === 'visi-misi') {
            $page = Page::firstOrCreate(
                ['slug' => 'visi-misi'],
                [
                    'title' => 'Visi & Misi DISHUB',
                    'content' => "Visi:\nTerwujudnya Sistem Transportasi dan Lalu Lintas Kabupaten Probolinggo yang Handal, Safe, Tertib, dan Terintegrasi.\n\nMisi:\n1. Meningkatkan keselamatan dan ketertiban lalu lintas jalan.\n2. Memperkuat kualitas pelayanan pengujian kendaraan bermotor.\n3. Membangun dan merawat prasarana penerangan jalan umum & rambu keselamatan.",
                    'is_published' => true,
                ]
            );
            if (!$page->is_published) {
                $page->update(['is_published' => true]);
            }
        } elseif ($slug === 'tugas-dan-fungsi') {
            $page = Page::firstOrCreate(
                ['slug' => 'tugas-dan-fungsi'],
                [
                    'title' => 'Tugas dan Fungsi DISHUB',
                    'content' => "Tugas:\nDinas Perhubungan mempunyai tugas membantu Bupati melaksanakan urusan pemerintahan daerah di bidang Perhubungan.\n\nFungsi:\n1. Perumusan kebijakan teknis di bidang lalu lintas, angkutan, sarana dan prasarana transportasi.\n2. Pelaksanaan tugas dukungan teknis di bidang keselamatan lalu lintas dan kelaikan kendaraan bermotor.\n3. Pengelolaan dan pemeliharaan perlengkapan jalan, penerangan jalan umum, serta fasilitas perhubungan.",
                    'is_published' => true,
                ]
            );
            if (!$page->is_published) {
                $page->update(['is_published' => true]);
            }
        } else {
            $page = Page::where('slug', $slug)->first();
            $menuMatch = \App\Models\NavigationMenu::where('url', 'like', "%{$slug}%")->first();
            $cleanTitle = $menuMatch ? $menuMatch->title : ucwords(str_replace(['-', '_'], ' ', $slug));
            $defaultDesc = ($menuMatch && $menuMatch->description) ? $menuMatch->description : "Informasi resmi mengenai {$cleanTitle} Dinas Perhubungan Kabupaten Probolinggo.";

            if (!$page) {
                // Buat halaman otomatis jika diakses dari sub-menu baru agar tidak pernah error 404
                $page = Page::create([
                    'title' => $cleanTitle,
                    'slug' => $slug,
                    'image_url' => $menuMatch ? $menuMatch->image_url : null,
                    'pdf_url' => $menuMatch ? $menuMatch->pdf_url : null,
                    'content' => $defaultDesc,
                    'is_published' => true,
                ]);
            } else if ($menuMatch) {
                // Otomatis sinkronkan foto/gambar & berkas PDF dari Menu Navigasi ke Halaman Publik
                $dirty = false;
                if ($menuMatch->image_url && $page->image_url !== $menuMatch->image_url) {
                    $page->image_url = $menuMatch->image_url;
                    $dirty = true;
                }
                if ($menuMatch->pdf_url && $page->pdf_url !== $menuMatch->pdf_url) {
                    $page->pdf_url = $menuMatch->pdf_url;
                    $dirty = true;
                }
                if ($dirty) {
                    $page->save();
                }
            }
        }

        // Cek status publikasi (jika di-hide oleh admin dan pengunjung bukan admin/staf yang sedang login)
        if ($page && !$page->is_published && !auth()->check()) {
            abort(404, 'Halaman ini sedang tidak ditayangkan atau disembunyikan oleh administrator.');
        }

        $orgChartRoots = \App\Models\OrgChart::whereNull('parent_id')
            ->with('allChildren')
            ->orderBy('order_no', 'asc')
            ->get();

        $suggestions = collect();
        $avgScore = 4.0;
        $mutu = 'A (Sangat Baik)';
        if ($page && $page->slug === 'survei-kepuasan-masyarakat') {
            $suggestions = \App\Models\SurveyResponse::whereNotNull('feedback')->where('feedback', '!=', '')->orderBy('created_at', 'desc')->paginate(5);
            $avgScore = \App\Models\SurveyResponse::avg('score') ?? 4.0;
            $ikmPercentage = round(($avgScore / 4.0) * 100, 2);
            if ($ikmPercentage < 64.99) {
                $mutu = 'D (Tidak Baik)';
            } elseif ($ikmPercentage < 76.60) {
                $mutu = 'C (Kurang Baik)';
            } elseif ($ikmPercentage < 88.30) {
                $mutu = 'B (Baik)';
            }
        }

        return view('public.page', array_merge($common, compact('page', 'orgChartRoots', 'suggestions', 'avgScore', 'mutu')));
    }

    public function newsDetail($slug)
    {
        $common = $this->getCommonData();
        $news = NewsItem::where('slug', $slug)->firstOrFail();
        $news->increment('views');

        $latestNews = NewsItem::where('id', '!=', $news->id)->orderBy('published_at', 'desc')->take(3)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.news_detail', array_merge($common, compact('news', 'latestNews', 'sidebarWidgets')));
    }

    public function informasi(Request $request)
    {
        $common = $this->getCommonData();

        // Otomatis sinkronkan kategori berita publikasi yang belum punya tab menu
        $existingTabFilterValues = InformasiTab::pluck('filter_value')->filter()->toArray();
        $unmappedCategories = NewsItem::whereNotIn('category', $existingTabFilterValues)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        foreach ($unmappedCategories as $cat) {
            if (!empty($cat) && $cat !== 'CUSTOM') {
                $maxOrder = InformasiTab::max('order') ?? 4;
                $slug = Str::slug($cat);
                if (InformasiTab::where('slug', $slug)->exists()) {
                    $slug .= '-' . time();
                }
                InformasiTab::create([
                    'name'         => $cat,
                    'slug'         => $slug,
                    'icon'         => 'fas fa-newspaper',
                    'order'        => $maxOrder + 1,
                    'is_active'    => true,
                    'filter_type'  => 'category',
                    'filter_value' => $cat,
                ]);
            }
        }

        // Load tabs yang aktif
        $tabs = InformasiTab::where('is_active', true)->orderBy('order', 'asc')->get();

        // Jika tidak ada tab di DB, buat tab default
        if ($tabs->isEmpty()) {
            $tabs = collect([
                (object)['name' => 'Semua Berita', 'slug' => 'semua', 'icon' => 'fas fa-newspaper', 'filter_type' => 'all', 'filter_value' => null],
            ]);
        }

        // Tab aktif dari query string
        $activeTab = $request->get('tab', $tabs->first()->slug ?? 'semua');
        $currentTab = $tabs->firstWhere('slug', $activeTab) ?? $tabs->first();

        // Filter berita berdasarkan tab
        $query = NewsItem::with('creator')->orderBy('published_at', 'desc');

        if ($currentTab && isset($currentTab->filter_type)) {
            if ($currentTab->filter_type === 'category' && !empty($currentTab->filter_value)) {
                $query->where('category', $currentTab->filter_value);
            }
        }

        $newsList = $query->paginate(6)->appends(['tab' => $activeTab]);
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.informasi', array_merge($common, compact('newsList', 'sidebarWidgets', 'tabs', 'activeTab', 'currentTab')));
    }

    public function dokumen(Request $request, $type = null)
    {
        $pageTitles = [
            'perencanaan-kinerja' => 'Dokumen Perencanaan Kinerja',
            'pengukuran-kinerja' => 'Dokumen Pengukuran Kinerja',
            'pelaporan-kinerja' => 'Dokumen Pelaporan Kinerja',
            'evaluasi-kinerja' => 'Dokumen Evaluasi Kinerja',
        ];

        $pageTitle = $pageTitles[$type] ?? 'Dokumen Perencanaan Kinerja';
        $docTypeGroup = $type ?: 'perencanaan-kinerja';

        if ($request->wantsJson() || $request->ajax() || $request->isMethod('post')) {
            $query = PublicDocument::query();
            
            if ($type) {
                $query->where('type', $type);
            }
            if ($request->filled('judul') || $request->filled('name')) {
                $search = $request->input('judul') ?: $request->input('name');
                $query->where('title', 'like', '%' . $search . '%');
            }
            if ($request->filled('kategori')) {
                $query->where('category', $request->kategori);
            }
            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            $documents = $query->orderBy('created_at', 'desc')->paginate($request->input('length', 10));

            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => PublicDocument::count(),
                'recordsFiltered' => $documents->total(),
                'data' => $documents->items()
            ]);
        }

        $common = $this->getCommonData();
        
        $query = PublicDocument::query();
        if ($type) {
            $query->where('type', $type);
        }
        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('judul')) {
            $query->where('title', 'like', '%' . $request->judul . '%');
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.dokumen', array_merge($common, compact('documents', 'sidebarWidgets', 'pageTitle', 'type')));
    }

    public function downloadDocument($id)
    {
        $doc = PublicDocument::findOrFail($id);
        $doc->increment('download_count');
        
        if ($doc->file_path && file_exists(public_path($doc->file_path))) {
            return response()->download(public_path($doc->file_path));
        }

        return redirect()->away($doc->file_url);
    }

    public function downloadZip($id)
    {
        $doc = PublicDocument::findOrFail($id);
        
        if ($doc->file_zip_path && file_exists(public_path($doc->file_zip_path))) {
            return response()->download(public_path($doc->file_zip_path));
        }

        if ($doc->file_zip_url) {
            return redirect()->away($doc->file_zip_url);
        }

        return back()->with('error', 'File ZIP tidak tersedia.');
    }

    public function kontak()
    {
        $common = $this->getCommonData();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.kontak', array_merge($common, compact('sidebarWidgets')));
    }

    public function layanan(Request $request)
    {
        $common = $this->getCommonData();
        $services = Service::with('creator')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('public.layanan', array_merge($common, compact('services')));
    }

    public function layananDetail($slug)
    {
        $common = $this->getCommonData();
        $service = Service::with('creator')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $otherServices = Service::where('is_active', true)->where('id', '!=', $service->id)->orderBy('order', 'asc')->take(4)->get();

        return view('public.layanan_detail', array_merge($common, compact('service', 'otherServices')));
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $msg = ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 'baru',
        ]);

        ActivityLog::record('PENGADUAN_MASUK', "Menerima pesan pengaduan baru dari {$msg->name} ({$msg->email}) mengenai '{$msg->subject}'.");

        return back()->with('success', 'Terima kasih! Pesan / Laporan Pengaduan LLAJ Anda telah berhasil dikirimkan ke Dinas Perhubungan Kabupaten Probolinggo.');
    }

    public function video(Request $request)
    {
        $common = $this->getCommonData();
        $videos = VideoItem::orderBy('published_at', 'desc')->paginate(6);
        return view('public.video', array_merge($common, compact('videos')));
    }

    public function galery(Request $request)
    {
        $common = $this->getCommonData();
        $albums = GalleryAlbum::orderBy('created_at', 'desc')->paginate(6);
        return view('public.galery', array_merge($common, compact('albums')));
    }

    public function galeryDetail($slug)
    {
        $common = $this->getCommonData();
        $album = GalleryAlbum::where('slug', $slug)->firstOrFail();
        $otherAlbums = GalleryAlbum::where('id', '!=', $album->id)->take(3)->get();
        return view('public.galery_detail', array_merge($common, compact('album', 'otherAlbums')));
    }

    public function survei(Request $request)
    {
        $common = $this->getCommonData();
        $questions = SurveyQuestion::orderBy('step_number')->get()->keyBy('step_number');
        $avgScore = SurveyResponse::avg('score') ?? 4.0;
        $totalSurveys = SurveyResponse::count();
        $ikmPercentage = round(($avgScore / 4.0) * 100, 2);

        $mutu = 'A (Sangat Baik)';
        if ($ikmPercentage < 64.99) {
            $mutu = 'D (Tidak Baik)';
        } elseif ($ikmPercentage < 76.60) {
            $mutu = 'C (Kurang Baik)';
        } elseif ($ikmPercentage < 88.30) {
            $mutu = 'B (Baik)';
        }

        $suggestions = \App\Models\SurveyResponse::whereNotNull('feedback')->where('feedback', '!=', '')->orderBy('created_at', 'desc')->paginate(5);

        $common['questions'] = $questions;
        $common['avgScore'] = round($avgScore, 2);
        $common['totalSurveys'] = $totalSurveys;
        $common['ikmPercentage'] = $ikmPercentage;
        $common['mutu'] = $mutu;
        $common['suggestions'] = $suggestions;

        return view('public.survei', $common);
    }

    public function surveiStore(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:30',
            'gender'  => 'required|string',
            'age'     => 'required|numeric|min:17|max:120',
            'captcha' => 'required|string',
        ], [
            'name.required'    => 'Nama responden wajib diisi.',
            'name.max'         => 'Nama responden maksimal 255 karakter.',
            'phone.required'   => 'Nomor telepon / WA wajib diisi.',
            'phone.max'        => 'Nomor telepon / WA maksimal 30 karakter.',
            'gender.required'  => 'Jenis kelamin wajib dipilih.',
            'age.required'     => 'Umur wajib diisi.',
            'age.numeric'      => 'Umur harus berupa angka.',
            'age.min'          => 'Umur ditolak ga sesuai ketentuan',
            'age.max'          => 'Umur maksimal 120 tahun.',
            'captcha.required' => 'Kode captcha wajib diisi.',
        ]);

        $scores = [];
        for ($i = 1; $i <= 9; $i++) {
            $val = $request->input('q' . $i);
            if ($val) {
                if (str_contains($val, 'Sangat')) $scores[] = 4;
                elseif (str_contains($val, 'Tidak') && !str_contains($val, 'Sesuai')) $scores[] = 1;
                elseif (str_contains($val, 'Kurang')) $scores[] = 2;
                else $scores[] = 3;
            }
        }
        $avgScore = count($scores) > 0 ? (array_sum($scores) / count($scores)) : 4.0;

        $response = SurveyResponse::create([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'gender'     => $request->gender,
            'age'        => $request->age,
            'q1'         => $request->q1,
            'q2'         => $request->q2,
            'q3'         => $request->q3,
            'q4'         => $request->q4,
            'q5'         => $request->q5,
            'q6'         => $request->q6,
            'q7'         => $request->q7,
            'q8'         => $request->q8,
            'q9'         => $request->q9,
            'feedback'   => $request->feedback,
            'score'      => round($avgScore, 2),
            'ip_address' => $request->ip(),
        ]);

        ActivityLog::record('SURVEI_MASUK', "Responden {$response->name} ({$response->phone}) mengisi Survei Kepuasan SKM dengan skor " . round($avgScore, 2));

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Survei Kepuasan Masyarakat (SKM) Anda telah berhasil dikirimkan.'
        ]);
    }
}
