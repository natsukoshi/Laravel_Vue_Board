<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Observers\ImageObserver;
use App\Image;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Image::observe(ImageObserver::class);
    }
}
