<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
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
        Request::macro('locale', function () {
            $locale = Request::header('Accept-Language');
            $locale = strtok($locale, ',;');

            return in_array($locale, ['en', 'es']) ? $locale : config('app.fallback_locale', 'en');
        });

        App::setLocale(Request::locale());
    }
}
