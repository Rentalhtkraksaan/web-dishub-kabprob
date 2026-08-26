<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\RecoveryController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/informasi', [PublicController::class, 'informasi'])->name('informasi');
Route::get('/informasi/{slug}', [PublicController::class, 'newsDetail'])->name('news.detail');
Route::get('/video', [PublicController::class, 'video'])->name('video');
Route::get('/galery', [PublicController::class, 'galery'])->name('galery');
Route::get('/galeri', [PublicController::class, 'galery'])->name('galeri');
Route::get('/galery/{slug}', [PublicController::class, 'galeryDetail'])->name('galery.detail');
Route::get('/galeri/{slug}', [PublicController::class, 'galeryDetail'])->name('galeri.detail');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/layanan/{slug}', [PublicController::class, 'layananDetail'])->name('layanan.detail');
Route::match(['get', 'post'], '/dokumen', [PublicController::class, 'dokumen'])->name('dokumen');
Route::match(['get', 'post'], '/dokumen/{type}', [PublicController::class, 'dokumen'])->name('dokumen.type');
Route::post('/dokumen/ajax-akuntabilitas', [PublicController::class, 'dokumen'])->name('dokumen.ajax');
Route::get('/dokumen/download/{id}', [PublicController::class, 'downloadDocument'])->name('dokumen.download');
Route::get('/dokumen/download-zip/{id}', [PublicController::class, 'downloadZip'])->name('dokumen.download_zip');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [PublicController::class, 'contactStore'])->name('kontak.store');
Route::get('/survei', [PublicController::class, 'survei'])->name('survei');
Route::post('/survei', [PublicController::class, 'surveiStore'])->name('survei.store');
Route::get('/halaman/{slug}', [PublicController::class, 'page'])->name('page');
Route::get('/csrf-token', function () {
    return response()->json([
        'token' => csrf_token(),
        'lifetime' => config('session.lifetime', 120),
    ]);
})->name('csrf.token');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordVerify'])->name('password.forgot.verify');
Route::get('/refresh_captcha', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Developer Recovery Routes
|--------------------------------------------------------------------------
*/
Route::get('/recovery', [RecoveryController::class, 'show'])->name('recovery');
Route::post('/recovery', [RecoveryController::class, 'process']);

/*
|--------------------------------------------------------------------------
| Anggota Panel Routes (Role: anggota, admin, super_admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:anggota,staf,admin,super_admin'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/dashboard', function() {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin & Control Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:anggota,staf,admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Profile (Accessible by All Roles: Anggota, Admin, Super Admin)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/profile/update', [AdminController::class, 'profileUpdate'])->name('profile.update');
    
    // 1. DOKUMEN (CRU: Lihat, Tambah, Edit untuk Anggota & Admin)
    Route::get('/documents', [AdminController::class, 'documents'])->name('documents');
    Route::post('/documents', [AdminController::class, 'documentStore'])->name('documents.store');
    Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('documents.update');

    // 2. LAYANAN PUBLIK (Lihat, Tambah, Edit, Pindah Urutan)
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/services', [AdminController::class, 'serviceStore'])->name('services.store');
    Route::post('/services/{id}/reorder', [AdminController::class, 'serviceReorder'])->name('services.reorder');
    Route::put('/services/{id}', [AdminController::class, 'serviceUpdate'])->name('services.update');

    // 2.5 Contact Messages & Survei SKM
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::put('/messages/{id}', [AdminController::class, 'messageStatus'])->name('messages.status');
    
    Route::get('/survei-responses', [AdminController::class, 'surveiResponses'])->name('survei.responses');
    Route::put('/survei-responses/{id}', [AdminController::class, 'surveiFeedbackUpdate'])->name('survei.feedback.update');

    // RESTRICTED TO ADMIN & SUPER ADMIN (View, Create, Edit):
    Route::middleware(['role:admin,super_admin'])->group(function () {
        
        // 3. BERITA & INFORMASI
        Route::get('/news', [AdminController::class, 'news'])->name('news');
        Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
        Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');

        // 4. Hero Sliders
        Route::get('/sliders', [AdminController::class, 'sliders'])->name('sliders');
        Route::post('/sliders', [AdminController::class, 'sliderStore'])->name('sliders.store');
        Route::put('/sliders/{id}', [AdminController::class, 'sliderUpdate'])->name('sliders.update');
        
        // 5. Navigation Menus
        Route::get('/menus', [AdminController::class, 'menus'])->name('menus');
        Route::post('/menus', [AdminController::class, 'menuStore'])->name('menus.store');
        Route::post('/menus/{id}/toggle-active', [AdminController::class, 'menuToggleActive'])->name('menus.toggle_active');
        Route::post('/menus/undo', [AdminController::class, 'menuUndo'])->name('menus.undo');
        Route::post('/menus/reset-default', [AdminController::class, 'menuResetDefault'])->name('menus.reset_default');
        Route::put('/menus/{id}', [AdminController::class, 'menuUpdate'])->name('menus.update');

        // 6. Custom Pages
        Route::get('/pages', [AdminController::class, 'pages'])->name('pages');
        Route::post('/pages', [AdminController::class, 'pageStore'])->name('pages.store');
        Route::post('/pages/{id}/toggle-publish', [AdminController::class, 'pageTogglePublish'])->name('pages.toggle_publish');
        Route::put('/pages/{id}', [AdminController::class, 'pageUpdate'])->name('pages.update');

        // 7. Struktur Organisasi DISHUB
        Route::get('/org-chart', [AdminController::class, 'orgChart'])->name('org_chart');
        Route::post('/org-chart', [AdminController::class, 'orgChartStore'])->name('org_chart.store');
        Route::post('/org-chart/{id}/quick-move', [AdminController::class, 'orgChartQuickMove'])->name('org_chart.quick_move');
        Route::put('/org-chart/{id}', [AdminController::class, 'orgChartUpdate'])->name('org_chart.update');

        // 8. Sidebar Widgets
        Route::get('/widgets', [AdminController::class, 'widgets'])->name('widgets');
        Route::post('/widgets', [AdminController::class, 'widgetStore'])->name('widgets.store');
        Route::put('/widgets/{id}', [AdminController::class, 'widgetUpdate'])->name('widgets.update');

        // 9. Related Links
        Route::get('/links', [AdminController::class, 'links'])->name('links');
        Route::post('/links', [AdminController::class, 'linkStore'])->name('links.store');
        Route::put('/links/{id}', [AdminController::class, 'linkUpdate'])->name('links.update');

        // 10. Survei SKM
        Route::post('/survei-responses', [AdminController::class, 'surveiStore'])->name('survei.store');
        Route::delete('/survei-responses/{id}', [AdminController::class, 'surveiDestroy'])->name('survei.destroy');
        Route::post('/survei-questions/update', [AdminController::class, 'surveiQuestionsUpdate'])->name('survei.questions.update');

        // 11. Log Aktivitas Sistem
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('logs');

        // 12. Users & Roles Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::patch('/users/{id}/toggle', [AdminController::class, 'userToggleStatus'])->name('users.toggle');

        // 13. Site Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');

        // 14. Tab Informasi
        Route::get('/informasi-tabs', [AdminController::class, 'informasiTabs'])->name('informasi_tabs');
        Route::post('/informasi-tabs', [AdminController::class, 'informasiTabStore'])->name('informasi_tabs.store');
        Route::put('/informasi-tabs/{id}', [AdminController::class, 'informasiTabUpdate'])->name('informasi_tabs.update');

        // 15. Video Kegiatan & Dokumentasi
        Route::get('/videos', [AdminController::class, 'videos'])->name('videos');
        Route::post('/videos', [AdminController::class, 'videoStore'])->name('videos.store');
        Route::put('/videos/{id}', [AdminController::class, 'videoUpdate'])->name('videos.update');

        // 16. Album Galeri Foto Kegiatan
        Route::get('/gallery', [AdminController::class, 'gallery'])->name('gallery');
        Route::post('/gallery', [AdminController::class, 'galleryStore'])->name('gallery.store');
        Route::put('/gallery/{id}', [AdminController::class, 'galleryUpdate'])->name('gallery.update');

    });

    // RESTRICTED TO SUPER ADMIN ONLY (Hak Akses Hapus Konten / Data):
    Route::middleware(['role:super_admin'])->group(function () {
        // Analytics & System
        Route::post('/visitor-tracking/toggle', [AdminController::class, 'toggleVisitorTracking'])->name('visitor.tracking.toggle');
        Route::delete('/activity-logs/clear', [AdminController::class, 'clearActivityLogs'])->name('logs.clear');
        
        Route::delete('/documents/{id}', [AdminController::class, 'documentDestroy'])->name('documents.destroy');
        Route::delete('/services/{id}', [AdminController::class, 'serviceDestroy'])->name('services.destroy');
        Route::delete('/news/{id}', [AdminController::class, 'newsDestroy'])->name('news.destroy');
        Route::delete('/sliders/{id}', [AdminController::class, 'sliderDestroy'])->name('sliders.destroy');
        Route::delete('/menus/{id}', [AdminController::class, 'menuDestroy'])->name('menus.destroy');
        Route::delete('/pages/{id}', [AdminController::class, 'pageDestroy'])->name('pages.destroy');
        Route::delete('/org-chart/{id}', [AdminController::class, 'orgChartDestroy'])->name('org_chart.destroy');
        Route::delete('/widgets/{id}', [AdminController::class, 'widgetDestroy'])->name('widgets.destroy');
        Route::delete('/links/{id}', [AdminController::class, 'linkDestroy'])->name('links.destroy');
        Route::delete('/messages/{id}', [AdminController::class, 'messageDestroy'])->name('messages.destroy');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');
        Route::delete('/informasi-tabs/{id}', [AdminController::class, 'informasiTabDestroy'])->name('informasi_tabs.destroy');
        Route::delete('/videos/{id}', [AdminController::class, 'videoDestroy'])->name('videos.destroy');
        Route::delete('/gallery/{id}', [AdminController::class, 'galleryDestroy'])->name('gallery.destroy');
    });

});
