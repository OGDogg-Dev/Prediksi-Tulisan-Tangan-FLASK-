<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('image', function ($app) {
            return new ImageManager(['driver' => 'gd']);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
