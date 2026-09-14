<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Service;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Memastikan public_path() mengarah ke public_html jika di-hosting di cPanel
        $this->app->bind('path.public', function() {
            $publicHtml = dirname(base_path()) . DIRECTORY_SEPARATOR . 'public_html';
            if (is_dir($publicHtml)) {
                return $publicHtml;
            }
            return base_path('public');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        // Share $settings with all views
        if (Schema::hasTable('site_settings')) {
            $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
            View::share('settings', $settings);
        }

        View::composer('public.*', function ($view) {
            if (Schema::hasTable('services')) {
                $headerServices = Service::where('is_active', true)->orderBy('order', 'asc')->get();
                $view->with('headerServices', $headerServices);
            }
        });
    }
}
