<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
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
        if (DIRECTORY_SEPARATOR === '\\') {
            $compiledPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'sistema-integracion-views';
        } elseif ($this->app->environment('testing')) {
            $compiledPath = storage_path('framework/testing/views');
        } else {
            return;
        }

        if (! File::isDirectory($compiledPath)) {
            @mkdir($compiledPath, 0755, true);
        }

        config([
            'view.compiled' => $compiledPath,
        ]);
    }
}
