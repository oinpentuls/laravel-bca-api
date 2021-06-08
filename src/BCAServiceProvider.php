<?php

namespace Oinpentuls\BcaApi;

use Illuminate\Support\ServiceProvider;

class BCAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bca.php', 'bca');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/bca.php' => config_path('bca.php')
            ], 'config');
        }
    }
}
