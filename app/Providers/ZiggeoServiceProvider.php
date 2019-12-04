<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ziggeo;

class ZiggeoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Ziggeo',
            function ($app) {
                return new Ziggeo(
                    'baa99f76cf2ca7b6407fd87ebeef1769',
                    'b0f4534c64e05758305a8449d8d45168',
                    'b7400c792298ef0b15e0f68de557a3f9'
                );
            }
        );
    }
}
