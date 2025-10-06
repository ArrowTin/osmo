<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::component('admin.layouts._datatable', 'datatable');
        Blade::component('admin.components.card-stats', 'card-stats');
        Blade::component('admin.components.chart-placeholder', 'chart-placeholder');
        Blade::component('admin.components.form-input', 'form-input');
    }
}
