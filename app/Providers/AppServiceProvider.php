<?php

namespace App\Providers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\ServiceProvider;

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
        Pdf::setOptions([
            'defaultPaperSize' => 'A4',
            'dpi' => 150,
        ]);
    }
}
