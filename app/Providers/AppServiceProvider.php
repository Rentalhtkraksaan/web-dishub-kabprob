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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        View::composer('public.*', function ($view) {
            if (Schema::hasTable('services')) {
                $headerServices = Service::where('is_active', true)->orderBy('order', 'asc')->get();
                $view->with('headerServices', $headerServices);
            }
        });
    }
}
