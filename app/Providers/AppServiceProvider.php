<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Observers\ImageObserver;
use App\Image;

use App\Observers\ReplyObserver;
use App\Reply;

use Illuminate\Support\Facades\Schema;

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
        Reply::observe(ReplyObserver::class);
        Image::observe(ImageObserver::class);

        Schema::defaultStringLength(191);
    }
}
