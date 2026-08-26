<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\NewsItem;
use App\Models\SidebarWidget;
use App\Models\RelatedLink;
use App\Models\PublicDocument;
use App\Models\Page;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\OrgChart;
use App\Models\Service;
use App\Models\InformasiTab;
use App\Models\VideoItem;
use App\Models\GalleryAlbum;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private function validateStrictFile($file, $allowedType, $fieldName = 'file')
    {
        if (!$file || !$file->isValid()) {
            return;
        }

        $origName = strtolower($file->getClientOriginalName());
        $ext = strtolower($file->getClientOriginalExtension());

        // Blokir total ekstensi beresiko (PHP, script, executable, svg, html, dll)
        $blocked = ['php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'phar', 'inc', 'cgi', 'pl', 'py', 'asp', 'aspx', 'jsp', 'exe', 'sh', 'bat', 'cmd', 'js', 'html', 'htm', 'svg'];
        if (in_array($ext, $blocked) || preg_match('/\.(php|phtml|phar|inc|exe|sh|bat|cmd|js|html|svg)($|\.)/i', $origName)) {
            ActivityLog::record('BLOCKED_FILE_UPLOAD', "Mencoba mengunggah file berbahaya: {$origName}");
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => "File '{$origName}' DITOLAK! Unggah file PHP, Script, atau SVG dilarang keras demi keamanan sistem."
            ]);
        }

        if ($allowedType === 'image') {
            $allowedExts = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowedExts)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fieldName => "Validasi Gagal: File foto/gambar HANYA boleh berformat JPG, JPEG, atau PNG (Format '.{$ext}' tidak diizinkan)."
                ]);
            }
        } elseif ($allowedType === 'pdf') {
            if ($ext !== 'pdf') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fieldName => "Validasi Gagal: File dokumen HANYA boleh berformat PDF (.pdf)."
                ]);
            }
        } elseif ($allowedType === 'zip') {
            if ($ext !== 'zip') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fieldName => "Validasi Gagal: File arsip HANYA boleh berformat ZIP (.zip)."
                ]);
            }
        } elseif ($allowedType === 'favicon') {
            $allowedExts = ['jpg', 'jpeg', 'png', 'ico'];
            if (!in_array($ext, $allowedExts)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fieldName => "Validasi Gagal: File favicon HANYA boleh berformat ICO, PNG, JPG, atau JPEG."
                ]);
            }
        }
    }

    public function toggleVisitorTracking(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Akses Ditolak');
        }
        
        if ($request->has('active')) {
            \App\Models\SiteSetting::updateOrCreate(['key' => 'visitor_tracking_active'], ['value' => $request->active]);
            $status = $request->active == '1' ? 'diaktifkan' : 'dinonaktifkan';
            ActivityLog::record('TOGGLE_ANALYTICS', "Perekaman statistik pengunjung {$status}.");
        }
        
        return back()->with('success', 'Pengaturan analitik berhasil diperbarui.');
    }

    public function clearActivityLogs(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Akses Ditolak');
        }

        if ($request->input('confirm_1') === 'HAPUS' && 
            $request->input('confirm_2') === 'LANJUTKAN' && 
            $request->input('confirm_3') === 'SETUJU') {
            
            ActivityLog::truncate();
            ActivityLog::record('CLEAR_ALL_LOGS', 'Super Admin menghapus seluruh catatan aktivitas sistem.');
            
            return back()->with('success', 'Seluruh catatan aktivitas sistem berhasil dihapus permanen.');
        }

        return back()->withErrors(['confirm_clear' => 'Validasi keamanan gagal. Pastikan 3 kata kunci diketik dengan benar menggunakan HURUF BESAR: HAPUS, LANJUTKAN, SETUJU.']);
    }

    public function dashboard()
    {
        $stats = [
            'sliders' => HeroSlider::count(),
            'menus' => NavigationMenu::count(),
            'news' => NewsItem::count(),
            'documents' => PublicDocument::count(),
            'pages' => Page::count(),
            'widgets' => SidebarWidget::count(),
            'links' => RelatedLink::count(),
            'total_views' => NewsItem::sum('views'),
        ];

        $selectedYear = request('year', now()->year);
        if ($selectedYear < 2026) $selectedYear = 2026;
        
        $visitorStats = [
            'weekly' => ['labels' => [], 'data' => []],
            'monthly' => ['labels' => [], 'data' => []],
            'yearly' => ['labels' => [], 'data' => []],
            'total' => 0,
            'selectedYear' => $selectedYear
        ];

        // Weekly (Last 7 Days) - If looking at past year, show last 7 days of that year
        $baseDate = $selectedYear == now()->year ? now() : \Carbon\Carbon::create($selectedYear, 12, 31);
        for ($i = 6; $i >= 0; $i--) {
            $date = $baseDate->copy()->subDays($i)->format('Y-m-d');
            $visitorStats['weekly']['labels'][] = $baseDate->copy()->subDays($i)->locale('id')->translatedFormat('l');
            $visitorStats['weekly']['data'][] = (int)\App\Models\VisitorStat::where('date', $date)->sum('views');
        }
        
        // Monthly (Weeks of current month in selected year)
        $targetMonth = $selectedYear == now()->year ? now()->month : 12;
        for ($i = 1; $i <= 4; $i++) {
            $visitorStats['monthly']['labels'][] = 'Minggu ' . $i;
            // Roughly 7 days per week
            $startDay = ($i - 1) * 7 + 1;
            $endDay = $i * 7;
            if ($i === 4) $endDay = 31;
            
            $views = \App\Models\VisitorStat::whereMonth('date', $targetMonth)
                        ->whereYear('date', $selectedYear)
                        ->whereRaw('DAY(date) >= ? AND DAY(date) <= ?', [$startDay, $endDay])
                        ->sum('views');
            $visitorStats['monthly']['data'][] = (int)$views;
        }

        // Yearly (Months of selected year)
        $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 1; $i <= 12; $i++) {
            $visitorStats['yearly']['labels'][] = $bulanNames[$i-1];
            $views = \App\Models\VisitorStat::whereMonth('date', $i)->whereYear('date', $selectedYear)->sum('views');
            $visitorStats['yearly']['data'][] = (int)$views;
        }
        
        $visitorStats['total'] = (int)\App\Models\VisitorStat::whereYear('date', $selectedYear)->sum('views');

        $latestNews = NewsItem::orderBy('created_at', 'desc')->take(4)->get();
        $latestDocs = PublicDocument::orderBy('created_at', 'desc')->take(4)->get();
        $orgNodes = OrgChart::whereNull('parent_id')->with('children.children')->orderBy('order_no', 'asc')->get();

        return view('admin.dashboard', compact('stats', 'latestNews', 'latestDocs', 'orgNodes', 'visitorStats'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'         => ['required', 'email', 'max:255', 'ends_with:@gmail.com', 'unique:users,email,' . $user->id],
            'whatsapp'      => ['nullable', 'string', 'regex:/^0[0-9]{10,12}$/'],
            'referral_code' => ['nullable', 'string', 'regex:/^(?=(?:.*[a-zA-Z]){3})(?=(?:.*\d){3})[a-zA-Z\d]{6}$/'],
            'avatar_file'   => empty($user->avatar) ? 'required|file|mimes:jpeg,jpg,png|max:5120' : 'nullable|file|mimes:jpeg,jpg,png|max:5120',
        ];

        $messages = [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username ini sudah digunakan akun lain.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.ends_with'     => 'Email wajib berakhiran @gmail.com (contoh: nama@gmail.com).',
            'email.unique'        => 'Email ini sudah terdaftar pada akun lain.',
            'whatsapp.regex'      => 'No. WhatsApp harus diawali angka 0, hanya berupa angka, dan berpanjang 11 sampai 13 digit (contoh: 081234567890).',
            'referral_code.regex' => 'Kode Referral harus terdiri dari tepat 3 huruf dan 3 angka (total 6 karakter, contoh: ADI123).',
            'avatar_file.required'=> 'Foto Profil (PP) baru wajib diunggah!',
            'avatar_file.mimes'   => 'Foto Profil hanya boleh berupa file JPG, JPEG, atau PNG.',
            'avatar_file.max'     => 'Ukuran Foto Profil maksimal 5MB.',
        ];

        if ($request->filled('password')) {
            $rules['password'] = [
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&^()_\-+=\[\]{}|\\:;<>,.]/'
            ];
            $messages['password.min']   = 'Password minimal 8 karakter.';
            $messages['password.regex'] = 'Password wajib kombinasi huruf besar, huruf kecil, angka, dan kode unik/simbol.';
        }

        $request->validate($rules, $messages);
        if ($request->hasFile('avatar_file')) {
            $this->validateStrictFile($request->file('avatar_file'), 'image', 'avatar_file');
        }

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $avatarUrl = url('uploads/avatars/' . $filename);
        } else {
            $avatarUrl = $user->avatar;
        }

        $data = [
            'name'     => trim($request->name),
            'username' => trim($request->username),
            'email'    => trim($request->email),
            'avatar'   => $avatarUrl,
        ];

        if ($request->filled('whatsapp')) {
            $data['whatsapp'] = trim($request->whatsapp);
        }
        if ($request->filled('referral_code')) {
            $data['referral_code'] = trim($request->referral_code);
        }
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        session()->forget('show_force_change_password');

        ActivityLog::record('UPDATE_PROFILE', "Pengguna \"{$user->name}\" memperbarui data profil/password pribadinya.");

        return back()->with('success', 'Profil dan Password berhasil diperbarui!');
    }

    // --- 1. HERO SLIDERS CRUD ---
    public function sliders()
    {
        $sliders = HeroSlider::orderBy('order', 'asc')->get();
        return view('admin.sliders', compact('sliders'));
    }

    public function sliderStore(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'image_file'  => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'image_url'   => 'nullable|string',
        ], [
            'image_file.mimes' => 'Banner slider HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        // Prioritaskan upload file, lalu fallback ke URL teks
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);
            $imageUrl = url('uploads/banners/' . $filename);
        }

        if (!$imageUrl) {
            return back()->withErrors(['image_file' => 'Harap isi URL gambar atau upload foto (JPG/PNG).'])->withInput();
        }

        $slider = HeroSlider::create([
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image_url'   => $imageUrl,
            'button_text' => $request->button_text,
            'button_url'  => $request->button_url,
            'order'       => $request->order ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        ActivityLog::record('TAMBAH_SLIDER', "Menambahkan banner slider baru \"{$slider->title}\".");

        return back()->with('success', 'Banner Slider berhasil ditambahkan!');
    }

    public function sliderUpdate(Request $request, $id)
    {
        $request->validate([
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'image_url'  => 'nullable|string',
        ], [
            'image_file.mimes' => 'Banner slider HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $slider = HeroSlider::findOrFail($id);

        // Jika ada file baru diupload, hapus file lama (jika lokal) lalu simpan yang baru
        $imageUrl = $request->image_url ?? $slider->image_url;
        if ($request->hasFile('image_file')) {
            // Hapus file lama jika disimpan lokal (path berisi /uploads/banners/)
            if (str_contains($slider->image_url, 'uploads/banners/')) {
                // Ekstrak path dari URL penuh maupun path relatif
                $parsed = parse_url($slider->image_url, PHP_URL_PATH);
                $oldPath = public_path(ltrim($parsed, '/'));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $file = $request->file('image_file');
            $filename = time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);
            $imageUrl = url('uploads/banners/' . $filename);
        }

        $slider->update([
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image_url'   => $imageUrl,
            'button_text' => $request->button_text,
            'button_url'  => $request->button_url,
            'order'       => $request->order ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        ActivityLog::record('EDIT_SLIDER', "Memperbarui banner slider \"{$slider->title}\".");

        return back()->with('success', 'Banner Slider berhasil diperbarui!');
    }

    public function sliderDestroy($id)
    {
        $slider = HeroSlider::findOrFail($id);
        $title = $slider->title;
        $slider->delete();

        ActivityLog::record('HAPUS_SLIDER', "Menghapus banner slider \"{$title}\".");

        return back()->with('success', 'Banner Slider berhasil dihapus!');
    }

    // --- 2. NAVIGATION MENUS CRUD ---
    public function menus()
    {
        $menus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $allParents = NavigationMenu::whereNull('parent_id')->orderBy('order', 'asc')->get();
        return view('admin.menus', compact('menus', 'allParents'));
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'image_url' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'pdf_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar menu HANYA boleh berformat JPG, JPEG, atau PNG.',
            'pdf_file.mimes'   => 'Dokumen menu HANYA boleh berformat PDF.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }
        if ($request->hasFile('pdf_file')) {
            $this->validateStrictFile($request->file('pdf_file'), 'pdf', 'pdf_file');
        }

        $targetUrl = trim($request->url);
        if (empty($targetUrl)) {
            $slug = Str::slug($request->title);
            $targetUrl = '/halaman/' . $slug;
        }

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'menu_img_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $filename);
            $imageUrl = asset('uploads/menus/' . $filename);
        }

        $pdfUrl = $request->pdf_url;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'menu_doc_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $filename);
            $pdfUrl = asset('uploads/menus/' . $filename);
        }

        $fallbackDesc = "Informasi resmi mengenai {$request->title} Dinas Perhubungan Kabupaten Probolinggo.";
        $descriptionInput = $request->filled('description') ? trim($request->description) : $fallbackDesc;

        $menu = NavigationMenu::create([
            'title' => $request->title,
            'url' => $targetUrl,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'image_url' => $imageUrl,
            'pdf_url' => $pdfUrl,
            'description' => $descriptionInput,
            'is_active' => $request->has('is_active'),
        ]);

        // Otomatis buat/sinkronkan Halaman Publik di website utama agar langsung tayang saat diklik tanpa 404
        if (str_contains($targetUrl, '/halaman/') || (!str_starts_with($targetUrl, 'http') && !in_array($targetUrl, ['#', '/']))) {
            $slug = str_replace('/halaman/', '', trim($targetUrl, '/'));
            if (!empty($slug)) {
                $page = \App\Models\Page::firstOrNew(['slug' => $slug]);
                $page->title = $request->title;
                if ($imageUrl) {
                    $page->image_url = $imageUrl;
                }
                if ($pdfUrl) {
                    $page->pdf_url = $pdfUrl;
                }
                $page->content = $descriptionInput;
                $page->is_published = true;
                $page->save();
            }
        }

        ActivityLog::record('TAMBAH_MENU', "Menambahkan menu navigasi header baru \"{$menu->title}\".");

        return back()->with('success', 'Menu Navigasi berhasil ditambahkan & halaman publik otomatis siap tayang!');
    }

    public function menuUpdate(Request $request, $id)
    {
        $menu = NavigationMenu::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'image_url' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'pdf_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar menu HANYA boleh berformat JPG, JPEG, atau PNG.',
            'pdf_file.mimes'   => 'Dokumen menu HANYA boleh berformat PDF.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }
        if ($request->hasFile('pdf_file')) {
            $this->validateStrictFile($request->file('pdf_file'), 'pdf', 'pdf_file');
        }

        $targetUrl = trim($request->url);
        if (empty($targetUrl)) {
            $slug = Str::slug($request->title);
            $targetUrl = '/halaman/' . $slug;
        }

        $imageUrl = $request->filled('image_url') ? $request->image_url : $menu->image_url;
        if ($request->has('remove_image')) {
            $imageUrl = null;
        } elseif ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'menu_img_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $filename);
            $imageUrl = asset('uploads/menus/' . $filename);
        }

        $pdfUrl = $request->filled('pdf_url') ? $request->pdf_url : $menu->pdf_url;
        if ($request->has('remove_pdf')) {
            $pdfUrl = null;
        } elseif ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'menu_doc_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $filename);
            $pdfUrl = asset('uploads/menus/' . $filename);
        }

        $fallbackDesc = "Informasi resmi mengenai {$request->title} Dinas Perhubungan Kabupaten Probolinggo.";
        $descriptionInput = $request->filled('description') ? trim($request->description) : $fallbackDesc;

        $menu->update([
            'title' => $request->title,
            'url' => $targetUrl,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'image_url' => $imageUrl,
            'pdf_url' => $pdfUrl,
            'description' => $descriptionInput,
            'is_active' => $request->has('is_active'),
        ]);

        // Otomatis perbarui/sinkronkan Halaman Publik di website utama
        if (str_contains($targetUrl, '/halaman/') || (!str_starts_with($targetUrl, 'http') && !in_array($targetUrl, ['#', '/']))) {
            $slug = str_replace('/halaman/', '', trim($targetUrl, '/'));
            if (!empty($slug)) {
                $page = \App\Models\Page::firstOrNew(['slug' => $slug]);
                $page->title = $request->title;
                if ($imageUrl !== null || $request->has('remove_image')) {
                    $page->image_url = $imageUrl;
                }
                if ($pdfUrl !== null || $request->has('remove_pdf')) {
                    $page->pdf_url = $pdfUrl;
                }
                $page->content = $descriptionInput;
                $page->is_published = true;
                $page->save();
            }
        }

        ActivityLog::record('EDIT_MENU', "Memperbarui menu navigasi header \"{$menu->title}\".");

        return back()->with('success', 'Menu Navigasi berhasil diperbarui!');
    }

    public function menuDestroy($id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $title = $menu->title;

        // Save deleted menu attributes in session for UNDO capability
        session(['last_deleted_menu' => [
            'title'       => $menu->title,
            'url'         => $menu->url,
            'parent_id'   => $menu->parent_id,
            'order'       => $menu->order,
            'target'      => $menu->target,
            'image_url'   => $menu->image_url,
            'pdf_url'     => $menu->pdf_url,
            'description' => $menu->description,
            'is_active'   => $menu->is_active,
        ]]);

        $menu->delete();

        ActivityLog::record('HAPUS_MENU', "Menghapus menu navigasi header \"{$title}\".");

        return back()->with('success', "Menu Navigasi \"{$title}\" berhasil dihapus! Klik tombol 'Undo' untuk memulihkannya.");
    }

    public function menuToggleActive($id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $menu->is_active = !$menu->is_active;
        $menu->save();

        $statusText = $menu->is_active ? 'diaktifkan (Tayang)' : 'dinonaktifkan (Sembunyi)';
        ActivityLog::record('TOGGLE_ACTIVE_MENU', "Mengubah status menu header \"{$menu->title}\" menjadi {$statusText}.");

        return back()->with('success', "Status menu \"{$menu->title}\" berhasil {$statusText}!");
    }

    public function menuUndo()
    {
        $lastDeleted = session('last_deleted_menu');

        if (!$lastDeleted) {
            return back()->with('error', 'Tidak ada data menu terhapus yang dapat dipulihkan!');
        }

        $menu = NavigationMenu::create($lastDeleted);
        session()->forget('last_deleted_menu');

        ActivityLog::record('UNDO_HAPUS_MENU', "Memulihkan (Undo) menu navigasi header \"{$menu->title}\" yang sebelumnya terhapus.");

        return back()->with('success', "Menu Navigasi \"{$menu->title}\" berhasil dipulihkan (Undo)!");
    }

    public function menuResetDefault()
    {
        $defaultMenus = [
            ['title' => 'BERANDA', 'url' => '/', 'order' => 1],
            ['title' => 'PROFIL DISHUB', 'url' => '#', 'order' => 2, 'children' => [
                ['title' => 'Visi & Misi', 'url' => '/halaman/visi-misi', 'order' => 1],
                ['title' => 'Struktur Organisasi', 'url' => '/halaman/struktur-organisasi', 'order' => 2],
                ['title' => 'Tugas & Fungsi', 'url' => '/halaman/tugas-fungsi', 'order' => 3],
            ]],
            ['title' => 'BERITA & INFORMASI', 'url' => '/informasi', 'order' => 3],
            ['title' => 'DOKUMEN KINERJA', 'url' => '/dokumen', 'order' => 4],
            ['title' => 'KONTAK PENGADUAN', 'url' => '/kontak', 'order' => 5],
        ];

        foreach ($defaultMenus as $m) {
            $children = $m['children'] ?? [];
            unset($m['children']);
            $parent = NavigationMenu::updateOrCreate(
                ['title' => $m['title']],
                array_merge($m, ['is_active' => true])
            );

            foreach ($children as $c) {
                NavigationMenu::updateOrCreate(
                    ['title' => $c['title'], 'parent_id' => $parent->id],
                    array_merge($c, ['is_active' => true])
                );
            }
        }

        ActivityLog::record('RESET_MENU', 'Melakukan refresh dan penyegaran data menu header navigasi ke standar default.');

        return back()->with('success', 'Menu Header Navigasi berhasil dipulihkan & disegarkan ke data standar default!');
    }

    // --- 3. NEWS & INFORMASI CRUD ---
    public function news(Request $request)
    {
        $query = NewsItem::with('creator');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $newsList = $query->orderBy('published_at', 'desc')->paginate(10);
        $tabCategories = InformasiTab::where('is_active', true)->whereNotNull('filter_value')->pluck('filter_value')->toArray();
        $defaultCategories = ['Pemerintahan', 'Lalu Lintas', 'Pelayanan Publik'];
        $categories = array_unique(array_merge($defaultCategories, $tabCategories));
        return view('admin.news', compact('newsList', 'categories'));
    }

    public function newsStore(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'nullable|string',
            'category'     => 'nullable|string|max:100',
            'image_file'   => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'image_url'    => 'nullable|string',
            'published_at' => 'nullable|date',
        ], [
            'image_file.mimes' => 'Gambar berita HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $category = $request->filled('custom_category') ? trim($request->custom_category) : ($request->category ?: 'Pemerintahan');
        $this->ensureCategoryTabExists($category);

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $imageUrl = asset('uploads/news/' . $filename);
        }

        if (!$imageUrl) {
            $imageUrl = 'https://images.unsplash.com/photo-1508873696983-2df515122519?w=800';
        }

        $news = NewsItem::create([
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . time(),
            'summary'      => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content'      => $request->content,
            'image_url'    => $imageUrl,
            'category'     => $category,
            'published_at' => $request->published_at ? $request->published_at : now(),
            'created_by'   => auth()->id(),
        ]);

        ActivityLog::record('TAMBAH_BERITA', "Menambahkan berita baru \"{$news->title}\" pada kategori [{$news->category}].");

        return back()->with('success', 'Berita berhasil ditambahkan!');
    }

    private function ensureCategoryTabExists($category)
    {
        if (empty($category) || $category === 'CUSTOM') {
            return;
        }

        $exists = InformasiTab::where('filter_value', $category)
            ->orWhere('name', $category)
            ->exists();

        if (!$exists) {
            $maxOrder = InformasiTab::max('order') ?? 4;
            $slug = Str::slug($category);
            if (InformasiTab::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }

            InformasiTab::create([
                'name'         => $category,
                'slug'         => $slug,
                'icon'         => 'fas fa-newspaper',
                'order'        => $maxOrder + 1,
                'is_active'    => true,
                'filter_type'  => 'category',
                'filter_value' => $category,
            ]);
        }
    }

    public function newsUpdate(Request $request, $id)
    {
        $news = NewsItem::findOrFail($id);
        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'nullable|string',
            'category'     => 'nullable|string|max:100',
            'image_file'   => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'published_at' => 'nullable|date',
        ], [
            'image_file.mimes' => 'Gambar berita HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $category = $request->filled('custom_category') ? trim($request->custom_category) : ($request->category ?: $news->category ?: 'Pemerintahan');
        $this->ensureCategoryTabExists($category);

        $imageUrl = $request->filled('image_url') ? $request->image_url : $news->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $imageUrl = asset('uploads/news/' . $filename);
        }

        $news->update([
            'title'        => $request->title,
            'summary'      => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content'      => $request->content,
            'image_url'    => $imageUrl,
            'category'     => $category,
            'published_at' => $request->published_at ? $request->published_at : $news->published_at,
            'created_by'   => $news->created_by ?: auth()->id(),
        ]);

        ActivityLog::record('EDIT_BERITA', "Memperbarui data berita \"{$news->title}\".");

        return back()->with('success', 'Berita berhasil diperbarui!');
    }

    public function newsDestroy($id)
    {
        $news = NewsItem::findOrFail($id);
        $title = $news->title;
        $news->delete();

        ActivityLog::record('HAPUS_BERITA', "Menghapus artikel berita \"{$title}\".");

        return back()->with('success', 'Berita berhasil dihapus!');
    }

    // --- 4. PUBLIC DOCUMENTS CRUD ---
    public function documents(Request $request)
    {
        $query = PublicDocument::with('creator');
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.documents', compact('documents'));
    }

    public function documentStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string',
            'category' => 'required|string',
            'tahun' => 'nullable|string|max:4',
            'file_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'file_zip' => 'nullable|file|mimes:zip|max:51200',
            'file_url' => 'nullable|string',
            'file_zip_url' => 'nullable|string',
        ], [
            'file_pdf.mimes' => 'Dokumen publik HANYA boleh berformat PDF (.pdf).',
            'file_zip.mimes' => 'File arsip HANYA boleh berformat ZIP (.zip).',
        ]);

        if ($request->hasFile('file_pdf')) {
            $this->validateStrictFile($request->file('file_pdf'), 'pdf', 'file_pdf');
        }
        if ($request->hasFile('file_zip')) {
            $this->validateStrictFile($request->file('file_zip'), 'zip', 'file_zip');
        }

        $category = $request->filled('custom_category') ? trim($request->custom_category) : $request->category;
        
        $type = $request->type;
        if (!$type) {
            $type = 'perencanaan-kinerja';
            if (in_array($category, ['Capaian Kinerja', 'Indikator Pengukuran'])) {
                $type = 'pengukuran-kinerja';
            } elseif (in_array($category, ['LAKIP / LKjIP', 'Laporan Kinerja Tahunan'])) {
                $type = 'pelaporan-kinerja';
            } elseif (in_array($category, ['Lembar Hasil Evaluasi (LHE)', 'Evaluasi AKIP'])) {
                $type = 'evaluasi-kinerja';
            }
        }

        $pdfUrl = $request->file_url;
        $pdfPath = null;
        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('documents/pdf', 'public');
            $pdfPath = 'storage/' . $path;
            $pdfUrl = asset($pdfPath);
        }

        $zipUrl = $request->file_zip_url;
        $zipPath = null;
        if ($request->hasFile('file_zip')) {
            $path = $request->file('file_zip')->store('documents/zip', 'public');
            $zipPath = 'storage/' . $path;
            $zipUrl = asset($zipPath);
        }

        $doc = PublicDocument::create([
            'title' => $request->title,
            'type' => $type,
            'category' => $category,
            'tahun' => $request->tahun ?? date('Y'),
            'file_path' => $pdfPath,
            'file_url' => $pdfUrl,
            'file_zip_path' => $zipPath,
            'file_zip_url' => $zipUrl,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::record('TAMBAH_DOKUMEN', "Mengunggah dokumen publik baru \"{$doc->title}\" pada kelompok [{$type}].");

        return back()->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function documentUpdate(Request $request, $id)
    {
        $doc = PublicDocument::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string',
            'category' => 'required|string',
            'tahun' => 'nullable|string|max:4',
            'file_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'file_zip' => 'nullable|file|mimes:zip|max:51200',
            'file_url' => 'nullable|string',
            'file_zip_url' => 'nullable|string',
        ], [
            'file_pdf.mimes' => 'Dokumen publik HANYA boleh berformat PDF (.pdf).',
            'file_zip.mimes' => 'File arsip HANYA boleh berformat ZIP (.zip).',
        ]);

        if ($request->hasFile('file_pdf')) {
            $this->validateStrictFile($request->file('file_pdf'), 'pdf', 'file_pdf');
        }
        if ($request->hasFile('file_zip')) {
            $this->validateStrictFile($request->file('file_zip'), 'zip', 'file_zip');
        }

        $category = $request->filled('custom_category') ? trim($request->custom_category) : $request->category;

        $type = $request->type;
        if (!$type) {
            $type = $doc->type ?: 'perencanaan-kinerja';
            if (in_array($category, ['Capaian Kinerja', 'Indikator Pengukuran'])) {
                $type = 'pengukuran-kinerja';
            } elseif (in_array($category, ['LAKIP / LKjIP', 'Laporan Kinerja Tahunan'])) {
                $type = 'pelaporan-kinerja';
            } elseif (in_array($category, ['Lembar Hasil Evaluasi (LHE)', 'Evaluasi AKIP'])) {
                $type = 'evaluasi-kinerja';
            }
        }

        $pdfUrl = $request->file_url ?: $doc->file_url;
        $pdfPath = $doc->file_path;
        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('documents/pdf', 'public');
            $pdfPath = 'storage/' . $path;
            $pdfUrl = asset($pdfPath);
        }

        $zipUrl = $request->file_zip_url ?: $doc->file_zip_url;
        $zipPath = $doc->file_zip_path;
        if ($request->hasFile('file_zip')) {
            $path = $request->file('file_zip')->store('documents/zip', 'public');
            $zipPath = 'storage/' . $path;
            $zipUrl = asset($zipPath);
        }

        $doc->update([
            'title'         => $request->title,
            'type'          => $type,
            'category'      => $category,
            'tahun'         => $request->tahun ?? $doc->tahun,
            'file_path'     => $pdfPath,
            'file_url'      => $pdfUrl,
            'file_zip_path' => $zipPath,
            'file_zip_url'  => $zipUrl,
            'created_by'    => $doc->created_by ?: auth()->id(),
        ]);

        ActivityLog::record('EDIT_DOKUMEN', "Memperbarui data dokumen publik \"{$doc->title}\".");

        return back()->with('success', 'Dokumen berhasil diperbarui!');
    }

    public function documentDestroy($id)
    {
        $doc = PublicDocument::findOrFail($id);
        $title = $doc->title;
        $doc->delete();

        ActivityLog::record('HAPUS_DOKUMEN', "Menghapus dokumen publik \"{$title}\".");

        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    // --- 5. PAGES CRUD ---
    public function pages()
    {
        $pages = Page::orderBy('updated_at', 'desc')->get();
        return view('admin.pages', compact('pages'));
    }

    public function pageStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'image_url' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'pdf_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar halaman HANYA boleh berformat JPG, JPEG, atau PNG.',
            'pdf_file.mimes'   => 'Dokumen halaman HANYA boleh berformat PDF.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }
        if ($request->hasFile('pdf_file')) {
            $this->validateStrictFile($request->file('pdf_file'), 'pdf', 'pdf_file');
        }

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'page_img_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $imageUrl = asset('uploads/pages/' . $filename);
        }

        $pdfUrl = $request->pdf_url;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'page_doc_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $pdfUrl = asset('uploads/pages/' . $filename);
        }

        $page = Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image_url' => $imageUrl,
            'pdf_url' => $pdfUrl,
            'is_published' => $request->has('is_published') ? true : false,
        ]);

        ActivityLog::record('TAMBAH_HALAMAN', "Menambahkan halaman profil/layanan baru \"{$page->title}\".");

        return back()->with('success', 'Halaman Profil & Layanan berhasil dibuat!');
    }

    public function pageTogglePublish($id)
    {
        $page = Page::findOrFail($id);
        $page->update([
            'is_published' => !$page->is_published,
        ]);

        $statusStr = $page->is_published ? 'Diterbitkan (Tayang)' : 'Disimpan sebagai Draf (Tidak Tayang)';
        ActivityLog::record('TOGGLE_HALAMAN', "Mengubah status publikasi \"{$page->title}\" menjadi {$statusStr}.");

        return back()->with('success', "Status \"{$page->title}\" berhasil diubah menjadi {$statusStr}!");
    }

    public function pageUpdate(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'image_url' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'pdf_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar halaman HANYA boleh berformat JPG, JPEG, atau PNG.',
            'pdf_file.mimes'   => 'Dokumen halaman HANYA boleh berformat PDF.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }
        if ($request->hasFile('pdf_file')) {
            $this->validateStrictFile($request->file('pdf_file'), 'pdf', 'pdf_file');
        }

        $imageUrl = $page->image_url;
        if ($request->has('remove_image') && $request->remove_image) {
            $imageUrl = null;
        } elseif ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'page_img_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $imageUrl = asset('uploads/pages/' . $filename);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
        }

        $pdfUrl = $page->pdf_url;
        if ($request->has('remove_pdf') && $request->remove_pdf) {
            $pdfUrl = null;
        } elseif ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'page_doc_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $pdfUrl = asset('uploads/pages/' . $filename);
        } elseif ($request->filled('pdf_url')) {
            $pdfUrl = $request->pdf_url;
        }

        $page->update([
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'pdf_url' => $pdfUrl,
            'is_published' => $request->has('is_published') ? true : false,
        ]);

        ActivityLog::record('EDIT_HALAMAN', "Memperbarui halaman profil/layanan \"{$page->title}\".");

        return back()->with('success', 'Halaman Profil & Layanan berhasil diperbarui!');
    }

    public function pageDestroy($id)
    {
        $page = Page::findOrFail($id);
        $title = $page->title;
        $page->delete();

        ActivityLog::record('HAPUS_HALAMAN', "Menghapus halaman publik \"{$title}\".");

        return back()->with('success', 'Halaman berhasil dihapus!');
    }

    // --- 6. SIDEBAR WIDGETS CRUD ---
    public function widgets()
    {
        $widgets = SidebarWidget::orderBy('order', 'asc')->get();
        return view('admin.widgets', compact('widgets'));
    }

    public function widgetStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'image_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar widget HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'widget_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/widgets'), $filename);
            $imageUrl = url('uploads/widgets/' . $filename);
        }

        if (!$imageUrl) {
            $imageUrl = asset('frontend/images/img24.jpg');
        }

        $widget = SidebarWidget::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('TAMBAH_WIDGET', "Menambahkan widget sidebar baru \"{$widget->title}\".");

        return back()->with('success', 'Widget Sidebar berhasil ditambahkan!');
    }

    public function widgetUpdate(Request $request, $id)
    {
        $widget = SidebarWidget::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'image_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar widget HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $imageUrl = $request->image_url ?? $widget->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'widget_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/widgets'), $filename);
            $imageUrl = url('uploads/widgets/' . $filename);
        }

        $widget->update([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('EDIT_WIDGET', "Memperbarui widget sidebar \"{$widget->title}\".");

        return back()->with('success', 'Widget Sidebar berhasil diperbarui!');
    }

    public function widgetDestroy($id)
    {
        $widget = SidebarWidget::findOrFail($id);
        $title = $widget->title;
        $widget->delete();

        ActivityLog::record('HAPUS_WIDGET', "Menghapus widget sidebar \"{$title}\".");

        return back()->with('success', 'Widget Sidebar berhasil dihapus!');
    }

    // --- 7. RELATED LINKS CRUD ---
    public function links()
    {
        $links = RelatedLink::orderBy('order', 'asc')->get();
        return view('admin.links', compact('links'));
    }

    public function linkStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'image_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar tautan HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'link_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/links'), $filename);
            $imageUrl = url('uploads/links/' . $filename);
        }

        if (!$imageUrl) {
            $imageUrl = asset('frontend/images/img24.jpg');
        }

        $link = RelatedLink::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('TAMBAH_LINK', "Menambahkan tautan terkait baru \"{$link->title}\".");

        return back()->with('success', 'Tautan Terkait berhasil ditambahkan!');
    }

    public function linkUpdate(Request $request, $id)
    {
        $link = RelatedLink::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'image_url' => 'nullable|string',
        ], [
            'image_file.mimes' => 'Gambar tautan HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $imageUrl = $request->image_url ?? $link->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'link_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/links'), $filename);
            $imageUrl = url('uploads/links/' . $filename);
        }

        $link->update([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('EDIT_LINK', "Memperbarui tautan terkait \"{$link->title}\".");

        return back()->with('success', 'Tautan Terkait berhasil diperbarui!');
    }

    public function linkDestroy($id)
    {
        $link = RelatedLink::findOrFail($id);
        $title = $link->title;
        $link->delete();

        ActivityLog::record('HAPUS_LINK', "Menghapus tautan terkait \"{$title}\".");

        return back()->with('success', 'Tautan Terkait berhasil dihapus!');
    }

    // --- 8. CONTACT MESSAGES & LAPOR BENCANA CRUD ---
    public function messages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.messages', compact('messages'));
    }

    public function messageStatus(Request $request, $id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update(['status' => $request->status]);

        ActivityLog::record('STATUS_PENGADUAN', "Memperbarui status pengaduan #{$msg->id} ({$msg->name}) menjadi [{$request->status}].");

        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    public function messageDestroy($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msgId = $msg->id;
        $name = $msg->name;
        $msg->delete();

        ActivityLog::record('HAPUS_PENGADUAN', "Menghapus laporan pengaduan #{$msgId} dari {$name}.");

        return back()->with('success', 'Laporan berhasil dihapus!');
    }

    // --- 9. USERS & ROLES CRUD ---
    public function users()
    {
        // Filter out hidden super admin (aditya) so it's not visible or editable in the UI
        $users = User::where('is_hidden', false)->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'nullable|string|max:255|unique:users,username',
            'email'         => ['required', 'email', 'max:255', 'ends_with:@gmail.com', 'unique:users,email'],
            'whatsapp'      => ['nullable', 'string', 'regex:/^0[0-9]{10,12}$/'],
            'referral_code' => ['nullable', 'string', 'regex:/^(?=(?:.*[a-zA-Z]){3})(?=(?:.*\d){3})[a-zA-Z\d]{6}$/'],
            'password'      => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&^()_\-+=\[\]{}|\\:;<>,.]/'
            ],
            'role'          => 'required|in:super_admin,admin,anggota',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.ends_with'     => 'Email wajib berakhiran @gmail.com (contoh: nama@gmail.com).',
            'email.unique'        => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'username.unique'     => 'Username ini sudah digunakan. Silakan pilih username lain.',
            'whatsapp.regex'      => 'No. WhatsApp harus diawali angka 0, hanya berupa angka, dan berpanjang 11 sampai 13 digit (contoh: 081234567890).',
            'referral_code.regex' => 'Kode Referral harus terdiri dari tepat 3 huruf dan 3 angka (total 6 karakter, contoh: ADI123).',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal harus 8 karakter.',
            'password.regex'      => 'Password wajib mengandung kombinasi huruf besar (A-Z), huruf kecil (a-z), angka (0-9), dan kode unik/simbol (seperti @, #, $, %, !).',
            'role.required'       => 'Role hak akses wajib dipilih.',
        ]);

        $username = $request->filled('username') ? trim($request->username) : null;

        $user = User::create([
            'name'          => trim($request->name),
            'username'      => $username,
            'email'         => trim($request->email),
            'whatsapp'      => $request->whatsapp ? trim($request->whatsapp) : null,
            'referral_code' => $request->referral_code ? trim($request->referral_code) : null,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'is_hidden'     => false,
            'is_active'     => true,
        ]);

        ActivityLog::record('TAMBAH_USER', "Menambahkan pengguna baru \"{$user->name}\" ({$user->email}) dengan role [" . strtoupper($user->role) . "].");

        return back()->with('success', 'User ' . $user->name . ' dengan role ' . ucfirst($user->role) . ' berhasil ditambahkan!');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->is_hidden && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah user ini!');
        }

        if (auth()->user()->isSuperAdmin()) {
            // Super Admin: Akses Lengkap (Name, Username, Email, WA, Referral Code, Role, Password)
            $rules = [
                'name'          => 'required|string|max:255',
                'email'         => ['required', 'email', 'max:255', 'ends_with:@gmail.com', 'unique:users,email,' . $id],
                'username'      => 'nullable|string|max:255|unique:users,username,' . $id,
                'whatsapp'      => ['nullable', 'string', 'regex:/^0[0-9]{10,12}$/'],
                'referral_code' => ['nullable', 'string', 'regex:/^(?=(?:.*[a-zA-Z]){3})(?=(?:.*\d){3})[a-zA-Z\d]{6}$/'],
                'role'          => 'required|in:super_admin,admin,anggota',
            ];

            $messages = [
                'name.required'       => 'Nama lengkap wajib diisi.',
                'email.required'      => 'Email wajib diisi.',
                'email.email'         => 'Format email tidak valid.',
                'email.ends_with'     => 'Email wajib berakhiran @gmail.com (contoh: nama@gmail.com).',
                'email.unique'        => 'Email ini sudah terdaftar pada akun lain.',
                'username.unique'     => 'Username ini sudah digunakan pada akun lain.',
                'whatsapp.regex'      => 'No. WhatsApp harus diawali angka 0, hanya berupa angka, dan berpanjang 11 sampai 13 digit (contoh: 081234567890).',
                'referral_code.regex' => 'Kode Referral harus terdiri dari tepat 3 huruf dan 3 angka (total 6 karakter, contoh: ADI123).',
                'role.required'       => 'Role hak akses wajib dipilih.',
            ];

            if ($request->filled('password')) {
                $rules['password'] = [
                    'string',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&^()_\-+=\[\]{}|\\:;<>,.]/'
                ];
                $messages['password.min']   = 'Password minimal harus 8 karakter.';
                $messages['password.regex'] = 'Password wajib mengandung kombinasi huruf besar (A-Z), huruf kecil (a-z), angka (0-9), dan kode unik/simbol (seperti @, #, $, %, !).';
            }

            $request->validate($rules, $messages);

            $username = $request->filled('username') ? trim($request->username) : null;

            $data = [
                'name'          => trim($request->name),
                'username'      => $username,
                'email'         => trim($request->email),
                'whatsapp'      => $request->whatsapp ? trim($request->whatsapp) : null,
                'referral_code' => $request->referral_code ? strtoupper(trim($request->referral_code)) : null,
                'role'          => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            ActivityLog::record('EDIT_USER', "Memperbarui data pengguna \"{$user->name}\" (Role: " . strtoupper($user->role) . ").");

            return back()->with('success', 'Data User berhasil diperbarui!');
        } else {
            // Admin Biasa: Hanya Memiliki Akses Mengubah Password
            if ($request->filled('password')) {
                $request->validate([
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'regex:/[A-Z]/',
                        'regex:/[a-z]/',
                        'regex:/[0-9]/',
                        'regex:/[@$!%*#?&^()_\-+=\[\]{}|\\:;<>,.]/'
                    ]
                ], [
                    'password.required' => 'Password wajib diisi jika ingin mengubah password user.',
                    'password.min'      => 'Password minimal harus 8 karakter.',
                    'password.regex'    => 'Password wajib mengandung kombinasi huruf besar (A-Z), huruf kecil (a-z), angka (0-9), dan kode unik/simbol (seperti @, #, $, %, !).',
                ]);

                $user->update([
                    'password' => Hash::make($request->password)
                ]);

                ActivityLog::record('EDIT_USER_PASSWORD', "Memperbarui password untuk pengguna \"{$user->name}\".");

                return back()->with('success', "Password pengguna \"{$user->name}\" berhasil diperbarui!");
            }

            return back()->with('info', 'Tidak ada perubahan password yang dilakukan. (Perubahan nama/email/wa/role hanya untuk Super Admin)');
        }
    }

    public function userToggleStatus($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun sendiri!');
        }

        $user = User::findOrFail($id);
        if ($user->is_hidden) {
            return back()->with('error', 'User terproteksi tidak dapat diubah statusnya!');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';
        ActivityLog::record('STATUS_USER', "Akun pengguna \"{$user->name}\" ({$user->email}) telah {$statusText}.");

        return back()->with('success', "Akun pengguna \"{$user->name}\" berhasil {$statusText}!");
    }

    public function userDestroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        if ($user->is_hidden) {
            return back()->with('error', 'User terproteksi tidak dapat dihapus!');
        }

        $userName = $user->name;
        $userRole = $user->role;
        $user->delete();

        ActivityLog::record('HAPUS_USER', "Menghapus akun pengguna \"{$userName}\" (Role: " . strtoupper($userRole) . ").");

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // --- 10. LOG AKTIVITAS SISTEM ---
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            $filter = $request->filter;
            if ($filter === 'login_logout') {
                $query->whereIn('action', ['LOGIN', 'LOGOUT']);
            } elseif ($filter === 'tambah') {
                $query->where('action', 'like', 'TAMBAH%');
            } elseif ($filter === 'edit') {
                $query->where(function($q) {
                    $q->where('action', 'like', 'EDIT%')
                      ->orWhere('action', 'like', 'UPDATE%')
                      ->orWhere('action', 'like', 'STATUS%')
                      ->orWhere('action', 'like', 'TOGGLE%');
                });
            } elseif ($filter === 'hapus') {
                $query->where('action', 'like', 'HAPUS%');
            }
        }

        $baseQuery = ActivityLog::query();

        if ($request->filled('year')) {
            $year = (int)$request->year;
            $query->whereYear('created_at', $year);
            $baseQuery->whereYear('created_at', $year);
        }
        
        if ($request->filled('month')) {
            $month = (int)$request->month;
            $query->whereMonth('created_at', $month);
            $baseQuery->whereMonth('created_at', $month);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'login_count' => (clone $baseQuery)->where('action', 'LOGIN')->count(),
            'changes_count' => (clone $baseQuery)->where('action', 'not like', 'LOGIN')->where('action', 'not like', 'LOGOUT')->count(),
            'today_count' => (clone $baseQuery)->whereDate('created_at', now()->today())->count(),
        ];

        $logs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.activity_logs', compact('logs', 'stats'));
    }

    // --- 11. SITE SETTINGS ---
    public function settings()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'file_logo_frontend'  => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'file_logo_backend'   => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'file_favicon'        => 'nullable|file|mimes:jpeg,png,jpg,ico|max:5120',
            'file_logo_berakhlak' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'file_qr_code_survey' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ], [
            'file_logo_frontend.mimes'  => 'Logo Frontend HANYA boleh berformat JPG, JPEG, atau PNG.',
            'file_logo_backend.mimes'   => 'Logo Backend HANYA boleh berformat JPG, JPEG, atau PNG.',
            'file_favicon.mimes'        => 'Favicon HANYA boleh berformat ICO, PNG, JPG, atau JPEG.',
            'file_logo_berakhlak.mimes' => 'Logo Berakhlak HANYA boleh berformat JPG, JPEG, atau PNG.',
            'file_qr_code_survey.mimes' => 'QR Code Survey HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        $fileKeys = [
            'file_logo_frontend'  => ['logo_frontend', 'image'],
            'file_logo_backend'   => ['logo_backend', 'image'],
            'file_favicon'        => ['favicon', 'favicon'],
            'file_logo_berakhlak' => ['logo_berakhlak', 'image'],
            'file_qr_code_survey' => ['qr_code_survey', 'image'],
        ];

        foreach ($fileKeys as $fileInput => $info) {
            if ($request->hasFile($fileInput)) {
                $this->validateStrictFile($request->file($fileInput), $info[1], $fileInput);
            }
        }

        $inputs = $request->except(['_token', 'file_logo_frontend', 'file_logo_backend', 'file_favicon', 'file_logo_berakhlak', 'file_qr_code_survey']);

        // Normalize Instagram Username & URL if provided
        if ($request->filled('instagram_username') || $request->filled('instagram_url')) {
            $rawIg = trim($request->input('instagram_username') ?? $request->input('instagram_url'));
            $rawIg = ltrim($rawIg, '@');
            
            // Extract username from full URL if pasted
            if (preg_match('/instagram\.com\/([a-zA-Z0-9_\.]+)/i', $rawIg, $matches)) {
                $cleanUsername = rtrim($matches[1], '/');
            } else {
                $cleanUsername = preg_replace('/[^a-zA-Z0-9_\.]/', '', $rawIg);
            }

            if (!empty($cleanUsername)) {
                $inputs['instagram_username'] = $cleanUsername;
                $inputs['instagram_url'] = 'https://www.instagram.com/' . $cleanUsername . '/';
            }
        }

        // Handle file uploads for Settings Logos, Favicon & QR Code
        $fileKeys = [
            'file_logo_frontend'  => 'logo_frontend',
            'file_logo_backend'   => 'logo_backend',
            'file_favicon'        => 'favicon',
            'file_logo_berakhlak' => 'logo_berakhlak',
            'file_qr_code_survey' => 'qr_code_survey',
        ];

        foreach ($fileKeys as $fileInput => $settingKey) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $filename = $settingKey . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $inputs[$settingKey] = asset('uploads/settings/' . $filename);
            }
        }

        foreach ($inputs as $key => $value) {
            if ($value !== null) {
                SiteSetting::set($key, $value);
            }
        }

        ActivityLog::record('UPDATE_PENGATURAN', "Memperbarui identitas dan konfigurasi utama website DISHUB.");

        return back()->with('success', 'Pengaturan Website, Gambar Logo & Favicon berhasil disimpan!');
    }

    // --- STRUKTUR ORGANISASI CRUD ---
    public function orgChart()
    {
        $allNodes = OrgChart::with('parent')->orderBy('order_no', 'asc')->get();
        $rootNodes = OrgChart::whereNull('parent_id')->with('allChildren')->orderBy('order_no', 'asc')->get();
        return view('admin.org_chart', compact('allNodes', 'rootNodes'));
    }

    public function orgChartQuickMove(Request $request, $id)
    {
        $node = OrgChart::findOrFail($id);
        $parentId = $request->parent_id ?: null;
        if ($parentId == $id) {
            $parentId = null;
        }

        $node->update([
            'parent_id' => $parentId,
            'order_no' => $request->has('order_no') ? $request->order_no : $node->order_no,
            'line_type' => $request->has('line_type') ? $request->line_type : $node->line_type,
        ]);

        ActivityLog::record('PINDAH_STRUKTUR', "Mengubah posisi / atasan untuk \"{$node->title}\".");

        return back()->with('success', "Posisi & Atasan untuk \"{$node->title}\" berhasil diperbarui!");
    }

    public function orgChartStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ], [
            'image_file.mimes' => 'Foto pejabat HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        $imageUrl = $request->image_url ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/org_chart'), $filename);
            $imageUrl = asset('uploads/org_chart/' . $filename);
        }

        $node = OrgChart::create([
            'parent_id' => $request->parent_id ?: null,
            'title' => $request->title,
            'name' => $request->name,
            'nip' => $request->nip,
            'image_url' => $imageUrl,
            'line_type' => $request->line_type ?: 'command',
            'order_no' => $request->order_no ?? 0,
        ]);

        ActivityLog::record('TAMBAH_STRUKTUR', "Menambahkan jabatan \"{$node->title}\" ({$node->name}) ke Struktur Organisasi.");

        return back()->with('success', 'Jabatan / Pejabat berhasil ditambahkan ke Struktur Organisasi!');
    }

    public function orgChartUpdate(Request $request, $id)
    {
        $node = OrgChart::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ], [
            'image_file.mimes' => 'Foto pejabat HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }

        // Mencegah self-reference parent
        $parentId = $request->parent_id ?: null;
        if ($parentId == $id) {
            $parentId = null;
        }

        $imageUrl = $node->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/org_chart'), $filename);
            $imageUrl = asset('uploads/org_chart/' . $filename);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
        }

        $node->update([
            'parent_id' => $parentId,
            'title' => $request->title,
            'name' => $request->name,
            'nip' => $request->nip,
            'image_url' => $imageUrl,
            'line_type' => $request->line_type ?: 'command',
            'order_no' => $request->order_no ?? 0,
        ]);

        ActivityLog::record('EDIT_STRUKTUR', "Memperbarui data struktur \"{$node->title}\".");

        return back()->with('success', 'Data Struktur Organisasi berhasil diperbarui!');
    }

    public function orgChartDestroy($id)
    {
        $node = OrgChart::findOrFail($id);
        $title = $node->title;
        $node->delete();

        ActivityLog::record('HAPUS_STRUKTUR', "Menghapus \"{$title}\" dari Struktur Organisasi.");

        return back()->with('success', 'Jabatan/Pejabat berhasil dihapus!');
    }

    // --- 13. LAYANAN PUBLIK CRUD ---
    public function services()
    {
        $this->syncServicesToNavigationMenu();
        $services = Service::with('creator')->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.services', compact('services'));
    }

    private function syncServicesToNavigationMenu()
    {
        $parent = NavigationMenu::where('title', 'LAYANAN')->first();
        if (!$parent) {
            $parent = NavigationMenu::create([
                'title'     => 'LAYANAN',
                'url'       => '#',
                'order'     => 3,
                'is_active' => true,
            ]);
        }

        // Hapus submenu lama di bawah parent LAYANAN agar tidak terjadi duplikasi
        NavigationMenu::where('parent_id', $parent->id)->delete();

        // Tambahkan submenu utama "Semua Layanan Publik DISHUB"
        NavigationMenu::create([
            'title'     => 'Semua Layanan Publik DISHUB',
            'url'       => '/layanan',
            'parent_id' => $parent->id,
            'order'     => 1,
            'is_active' => true,
        ]);

        // Tambahkan tiap layanan aktif secara otomatis
        $activeServices = Service::where('is_active', true)->orderBy('order', 'asc')->get();
        $order = 2;
        foreach ($activeServices as $svc) {
            NavigationMenu::create([
                'title'     => $svc->title,
                'url'       => '/layanan/' . $svc->slug,
                'parent_id' => $parent->id,
                'order'     => $order++,
                'is_active' => true,
            ]);
        }
    }

    public function serviceStore(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'content'     => 'nullable|string',
            'image_url'   => 'nullable|string',
            'image_file'  => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'pdf_file'    => 'nullable|file|mimes:pdf|max:25600',
            'pdf_url'     => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'category'    => 'nullable|string|max:100',
        ], [
            'image_file.mimes' => 'Gambar layanan HANYA boleh berformat JPG, JPEG, atau PNG.',
            'pdf_file.mimes'   => 'Dokumen layanan HANYA boleh berformat PDF.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }
        if ($request->hasFile('pdf_file')) {
            $this->validateStrictFile($request->file('pdf_file'), 'pdf', 'pdf_file');
        }

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'svc_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $imageUrl = asset('uploads/services/' . $filename);
        }

        $pdfUrl = $request->pdf_url;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'svc_pdf_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services/pdf'), $filename);
            $pdfUrl = asset('uploads/services/pdf/' . $filename);
        }

        $minOrder = Service::min('order') ?? 1;
        $defaultOrder = max(0, $minOrder - 1);

        $service = Service::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'content'     => $request->content,
            'image_url'   => $imageUrl,
            'pdf_url'     => $pdfUrl,
            'icon'        => $request->icon ?? 'fas fa-cogs',
            'category'    => $request->category ?? 'Umum',
            'order'       => $request->filled('order') ? (int)$request->order : $defaultOrder,
            'is_active'   => $request->has('is_active'),
            'created_by'  => auth()->id(),
        ]);

        $this->syncServicesToNavigationMenu();

        ActivityLog::record('TAMBAH_LAYANAN', "Menambahkan layanan publik baru \"{$service->title}\".");

        return back()->with('success', 'Layanan berhasil ditambahkan di posisi paling atas!');
    }

    public function serviceReorder(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $direction = $request->direction;

        $allServices = Service::orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();
        $currentIndex = $allServices->search(function($item) use ($id) {
            return $item->id == $id;
        });

        if ($currentIndex === false) {
            return back();
        }

        if ($direction === 'up' && $currentIndex > 0) {
            $prevItem = $allServices[$currentIndex - 1];
            $temp = $service->order;
            $service->order = $prevItem->order;
            $prevItem->order = $temp;
            if ($service->order == $prevItem->order) {
                $service->order = max(0, $prevItem->order - 1);
            }
            $service->save();
            $prevItem->save();
        } elseif ($direction === 'down' && $currentIndex < $allServices->count() - 1) {
            $nextItem = $allServices[$currentIndex + 1];
            $temp = $service->order;
            $service->order = $nextItem->order;
            $nextItem->order = $temp;
            if ($service->order == $nextItem->order) {
                $service->order = $nextItem->order + 1;
            }
            $service->save();
            $nextItem->save();
        } elseif ($request->filled('position')) {
            $targetPos = (int)$request->position;
            $allServices = $allServices->reject(fn($i) => $i->id == $id)->values();
            $allServices->splice(max(0, $targetPos - 1), 0, [$service]);

            foreach ($allServices as $idx => $item) {
                $item->update(['order' => $idx]);
            }
        }

        $this->syncServicesToNavigationMenu();
        ActivityLog::record('URUTAN_LAYANAN', "Mengubah posisi urutan layanan \"{$service->title}\".");

        return back()->with('success', 'Posisi urutan layanan berhasil diperbarui!');
    }

    public function serviceUpdate(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $request->validate([
            'title'      => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'pdf_file'   => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'image_file.mimes' => 'Gambar layanan HANYA boleh berformat JPG, JPEG, atau PNG.',
            'pdf_file.mimes'   => 'Dokumen layanan HANYA boleh berformat PDF.',
        ]);

        if ($request->hasFile('image_file')) {
            $this->validateStrictFile($request->file('image_file'), 'image', 'image_file');
        }
        if ($request->hasFile('pdf_file')) {
            $this->validateStrictFile($request->file('pdf_file'), 'pdf', 'pdf_file');
        }

        $imageUrl = $request->filled('image_url') ? $request->image_url : $service->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'svc_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $imageUrl = asset('uploads/services/' . $filename);
        }

        $pdfUrl = $request->filled('pdf_url') ? $request->pdf_url : $service->pdf_url;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'svc_pdf_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services/pdf'), $filename);
            $pdfUrl = asset('uploads/services/pdf/' . $filename);
        }

        $service->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'content'     => $request->content,
            'image_url'   => $imageUrl,
            'pdf_url'     => $pdfUrl,
            'icon'        => $request->icon ?? $service->icon,
            'category'    => $request->category ?? $service->category,
            'order'       => $request->order ?? 0,
            'is_active'   => $request->has('is_active'),
            'created_by'  => $service->created_by ?: auth()->id(),
        ]);

        $this->syncServicesToNavigationMenu();

        ActivityLog::record('EDIT_LAYANAN', "Memperbarui layanan publik \"{$service->title}\".");

        return back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function serviceDestroy($id)
    {
        $service = Service::findOrFail($id);
        $title = $service->title;
        $service->delete();

        $this->syncServicesToNavigationMenu();

        ActivityLog::record('HAPUS_LAYANAN', "Menghapus layanan publik \"{$title}\".");

        return back()->with('success', 'Layanan berhasil dihapus!');
    }

    // --- 14. TAB INFORMASI CRUD ---
    public function informasiTabs()
    {
        $tabs = InformasiTab::orderBy('order', 'asc')->get();
        return view('admin.informasi_tabs', compact('tabs'));
    }

    public function informasiTabStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
        ]);

        $tab = InformasiTab::create([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name) . '-' . time(),
            'icon'         => $request->icon ?? 'fas fa-newspaper',
            'order'        => $request->order ?? 0,
            'is_active'    => $request->has('is_active'),
            'filter_type'  => $request->filter_type ?? 'all',
            'filter_value' => $request->filter_value,
        ]);

        ActivityLog::record('TAMBAH_TAB_INFORMASI', "Menambahkan tab informasi baru \"{$tab->name}\".");

        return back()->with('success', 'Tab Informasi berhasil ditambahkan!');
    }

    public function informasiTabUpdate(Request $request, $id)
    {
        $tab = InformasiTab::findOrFail($id);
        $request->validate(['name' => 'required|string|max:100']);

        $tab->update([
            'name'         => $request->name,
            'icon'         => $request->icon ?? $tab->icon,
            'order'        => $request->order ?? 0,
            'is_active'    => $request->has('is_active'),
            'filter_type'  => $request->filter_type ?? 'all',
            'filter_value' => $request->filter_value,
        ]);

        ActivityLog::record('EDIT_TAB_INFORMASI', "Memperbarui tab informasi \"{$tab->name}\".");

        return back()->with('success', 'Tab Informasi berhasil diperbarui!');
    }

    public function informasiTabDestroy($id)
    {
        $tab = InformasiTab::findOrFail($id);
        $name = $tab->name;
        $tab->delete();

        ActivityLog::record('HAPUS_TAB_INFORMASI', "Menghapus tab informasi \"{$name}\".");

        return back()->with('success', 'Tab Informasi berhasil dihapus!');
    }

    // --- 15. VIDEO DOKUMENTASI CRUD ---
    public function videos(Request $request)
    {
        $query = VideoItem::with('creator')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        $videos = $query->paginate(12);
        return view('admin.videos', compact('videos'));
    }

    public function videoStore(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'video_url'      => 'required|string|max:500',
            'thumbnail_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'thumbnail_url'  => 'nullable|string|max:500',
            'description'    => 'nullable|string',
            'published_at'   => 'nullable|date',
        ], [
            'thumbnail_file.mimes' => 'Thumbnail video HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $this->validateStrictFile($request->file('thumbnail_file'), 'image', 'thumbnail_file');
        }

        $thumbnailUrl = $request->thumbnail_url;
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = 'video_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $filename);
            $thumbnailUrl = url('uploads/videos/' . $filename);
        }

        // Jika thumbnail kosong, coba generate otomatis dari YouTube ID
        if (!$thumbnailUrl) {
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $request->video_url, $matches)) {
                $thumbnailUrl = 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
            }
        }

        $video = VideoItem::create([
            'title'         => $request->title,
            'slug'          => Str::slug($request->title) . '-' . time(),
            'video_url'     => $request->video_url,
            'thumbnail_url' => $thumbnailUrl,
            'description'   => $request->description,
            'published_at'  => $request->published_at ?? now(),
            'created_by'    => auth()->id(),
        ]);

        ActivityLog::record('TAMBAH_VIDEO', "Menambahkan video dokumentasi baru \"{$video->title}\".");

        return back()->with('success', 'Video Dokumentasi berhasil ditambahkan!');
    }

    public function videoUpdate(Request $request, $id)
    {
        $video = VideoItem::findOrFail($id);

        $request->validate([
            'title'          => 'required|string|max:255',
            'video_url'      => 'required|string|max:500',
            'thumbnail_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'thumbnail_url'  => 'nullable|string|max:500',
            'description'    => 'nullable|string',
            'published_at'   => 'nullable|date',
        ], [
            'thumbnail_file.mimes' => 'Thumbnail video HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $this->validateStrictFile($request->file('thumbnail_file'), 'image', 'thumbnail_file');
        }

        $thumbnailUrl = $request->thumbnail_url ?? $video->thumbnail_url;
        if ($request->hasFile('thumbnail_file')) {
            // Hapus file thumbnail lama jika lokal
            if ($video->thumbnail_url && str_contains($video->thumbnail_url, 'uploads/videos/')) {
                $parsed = parse_url($video->thumbnail_url, PHP_URL_PATH);
                $oldPath = public_path(ltrim($parsed, '/'));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $file = $request->file('thumbnail_file');
            $filename = 'video_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $filename);
            $thumbnailUrl = url('uploads/videos/' . $filename);
        }

        if ($request->has('remove_thumbnail') && $request->remove_thumbnail == '1') {
            if ($video->thumbnail_url && str_contains($video->thumbnail_url, 'uploads/videos/')) {
                $parsed = parse_url($video->thumbnail_url, PHP_URL_PATH);
                $oldPath = public_path(ltrim($parsed, '/'));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $thumbnailUrl = null;
            // Fallback ke YouTube jika URL masih YouTube
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $request->video_url, $matches)) {
                $thumbnailUrl = 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
            }
        }

        $video->update([
            'title'         => $request->title,
            'video_url'     => $request->video_url,
            'thumbnail_url' => $thumbnailUrl,
            'description'   => $request->description,
            'published_at'  => $request->published_at ? $request->published_at : $video->published_at,
        ]);

        ActivityLog::record('EDIT_VIDEO', "Memperbarui video dokumentasi \"{$video->title}\".");

        return back()->with('success', 'Video Dokumentasi berhasil diperbarui!');
    }

    public function videoDestroy($id)
    {
        $video = VideoItem::findOrFail($id);
        $title = $video->title;

        // Hapus thumbnail lokal jika ada
        if ($video->thumbnail_url && str_contains($video->thumbnail_url, 'uploads/videos/')) {
            $parsed = parse_url($video->thumbnail_url, PHP_URL_PATH);
            $oldPath = public_path(ltrim($parsed, '/'));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $video->delete();

        ActivityLog::record('HAPUS_VIDEO', "Menghapus video dokumentasi \"{$title}\".");

        return back()->with('success', 'Video Dokumentasi berhasil dihapus!');
    }

    // --- 16. ALBUM GALERI FOTO KEGIATAN CRUD ---
    public function gallery(Request $request)
    {
        $query = GalleryAlbum::with('creator')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        $albums = $query->paginate(12);
        return view('admin.gallery', compact('albums'));
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'cover_file'     => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'cover_image'    => 'nullable|string|max:500',
            'photo_files.*'  => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'photo_urls'     => 'nullable|string',
        ], [
            'cover_file.mimes'    => 'Cover album HANYA boleh berformat JPG, JPEG, atau PNG.',
            'photo_files.*.mimes' => 'Foto galeri HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('cover_file')) {
            $this->validateStrictFile($request->file('cover_file'), 'image', 'cover_file');
        }
        if ($request->hasFile('photo_files')) {
            foreach ($request->file('photo_files') as $file) {
                $this->validateStrictFile($file, 'image', 'photo_files');
            }
        }

        // Handle Cover Image
        $coverUrl = $request->cover_image;
        if ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $filename = 'cover_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery/covers'), $filename);
            $coverUrl = url('uploads/gallery/covers/' . $filename);
        }

        // Handle Multiple Photos Inside Album
        $photos = [];
        if ($request->hasFile('photo_files')) {
            foreach ($request->file('photo_files') as $file) {
                $filename = 'photo_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/gallery/photos'), $filename);
                $photos[] = url('uploads/gallery/photos/' . $filename);
            }
        }

        // Handle Text / URL Lines of Photos
        if ($request->filled('photo_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $request->photo_urls)));
            foreach ($urls as $u) {
                if (filter_var($u, FILTER_VALIDATE_URL)) {
                    $photos[] = $u;
                }
            }
        }

        // Fallback Cover: jika cover kosong tapi ada foto, pakai foto pertama
        if (!$coverUrl && count($photos) > 0) {
            $coverUrl = $photos[0];
        } elseif (!$coverUrl) {
            $coverUrl = 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600';
        }

        // Jika photos kosong, set cover sebagai foto pertama
        if (empty($photos)) {
            $photos = [$coverUrl];
        }

        $album = GalleryAlbum::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'cover_image' => $coverUrl,
            'photos'      => array_values(array_unique($photos)),
            'created_by'  => auth()->id(),
        ]);

        ActivityLog::record('TAMBAH_ALBUM_GALERI', "Menambahkan album galeri foto baru \"{$album->title}\" dengan " . count($photos) . " foto.");

        return back()->with('success', 'Album foto kegiatan berhasil ditambahkan!');
    }

    public function galleryUpdate(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);

        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'cover_file'     => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'cover_image'    => 'nullable|string|max:500',
            'photo_files.*'  => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'photo_urls'     => 'nullable|string',
        ], [
            'cover_file.mimes'    => 'Cover album HANYA boleh berformat JPG, JPEG, atau PNG.',
            'photo_files.*.mimes' => 'Foto galeri HANYA boleh berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('cover_file')) {
            $this->validateStrictFile($request->file('cover_file'), 'image', 'cover_file');
        }
        if ($request->hasFile('photo_files')) {
            foreach ($request->file('photo_files') as $file) {
                $this->validateStrictFile($file, 'image', 'photo_files');
            }
        }

        // Handle Cover Image
        $coverUrl = $request->cover_image ?? $album->cover_image;
        if ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $filename = 'cover_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery/covers'), $filename);
            $coverUrl = url('uploads/gallery/covers/' . $filename);
        }

        // Current Photos
        $photos = is_array($album->photos) ? $album->photos : [];

        // Remove selected photos if requested
        if ($request->has('removed_photos') && is_array($request->removed_photos)) {
            $removed = $request->removed_photos;
            $photos = array_values(array_filter($photos, function($p) use ($removed) {
                return !in_array($p, $removed);
            }));
        }

        // Add newly uploaded photo files
        if ($request->hasFile('photo_files')) {
            foreach ($request->file('photo_files') as $file) {
                $filename = 'photo_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/gallery/photos'), $filename);
                $photos[] = url('uploads/gallery/photos/' . $filename);
            }
        }

        // Add photo URLs
        if ($request->filled('photo_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $request->photo_urls)));
            foreach ($urls as $u) {
                if (filter_var($u, FILTER_VALIDATE_URL) && !in_array($u, $photos)) {
                    $photos[] = $u;
                }
            }
        }

        if (empty($photos)) {
            $photos = [$coverUrl];
        }

        $album->update([
            'title'       => $request->title,
            'description' => $request->description,
            'cover_image' => $coverUrl,
            'photos'      => array_values(array_unique($photos)),
        ]);

        ActivityLog::record('EDIT_ALBUM_GALERI', "Memperbarui album galeri foto \"{$album->title}\".");

        return back()->with('success', 'Album foto kegiatan berhasil diperbarui!');
    }

    public function galleryDestroy($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $title = $album->title;

        // Delete local cover image
        if ($album->cover_image && str_contains($album->cover_image, 'uploads/gallery/')) {
            $parsed = parse_url($album->cover_image, PHP_URL_PATH);
            $oldPath = public_path(ltrim($parsed, '/'));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Delete local photo files inside album
        if (is_array($album->photos)) {
            foreach ($album->photos as $p) {
                if (str_contains($p, 'uploads/gallery/')) {
                    $parsed = parse_url($p, PHP_URL_PATH);
                    $oldPath = public_path(ltrim($parsed, '/'));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }
        }

        $album->delete();

        ActivityLog::record('HAPUS_ALBUM_GALERI', "Menghapus album galeri foto \"{$title}\".");

        return back()->with('success', 'Album foto kegiatan berhasil dihapus!');
    }

    public function surveiResponses()
    {
        $surveys = SurveyResponse::orderBy('created_at', 'desc')->paginate(7);
        $avgScore = SurveyResponse::avg('score') ?? 4.0;
        $totalSurveys = SurveyResponse::count();
        $questions = SurveyQuestion::orderBy('step_number')->get();
        return view('admin.survei_responses', compact('surveys', 'avgScore', 'totalSurveys', 'questions'));
    }

    public function surveiFeedbackUpdate(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'nullable|string',
            'score'    => 'required|numeric|min:1|max:4'
        ]);

        $survey = SurveyResponse::findOrFail($id);
        $survey->feedback = $request->feedback;
        $survey->score = $request->score;
        $survey->save();

        ActivityLog::record('UPDATE_SARAN_SURVEI', "Memperbarui saran/masukan dan skor dari responden {$survey->name}.");

        return back()->with('success', 'Saran dan skor responden berhasil diperbarui!');
    }

    public function surveiQuestionsUpdate(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string|max:1000',
        ]);

        foreach ($request->questions as $step => $text) {
            SurveyQuestion::updateOrCreate(
                ['step_number' => $step],
                ['question'    => $text]
            );
        }

        ActivityLog::record('UPDATE_PERTANYAAN_SURVEI', "Memperbarui daftar 10 pertanyaan SKM.");

        return back()->with('success', 'Daftar 10 Pertanyaan SKM berhasil diperbarui!');
    }

    public function surveiStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'gender'   => 'nullable|string|in:Laki-laki,Perempuan',
            'age'      => 'nullable|integer|min:17|max:100',
            'score'    => 'required|numeric|min:1|max:4',
            'feedback' => 'nullable|string',
        ]);

        SurveyResponse::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'gender'   => $request->gender,
            'age'      => $request->age,
            'score'    => $request->score,
            'feedback' => $request->feedback,
        ]);

        ActivityLog::record('TAMBAH_SURVEI', "Menambahkan tanggapan survei secara manual untuk responden {$request->name}.");

        return back()->with('success', 'Data hasil survei berhasil ditambahkan secara manual!');
    }

    public function surveiDestroy($id)
    {
        $survey = SurveyResponse::findOrFail($id);
        $name = $survey->name;
        $survey->delete();

        ActivityLog::record('HAPUS_SURVEI', "Menghapus tanggapan survei milik responden {$name}.");

        return back()->with('success', 'Tanggapan survei berhasil dihapus!');
    }
}
