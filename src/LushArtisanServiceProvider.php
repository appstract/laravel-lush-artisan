<?php

namespace Appstract\LushArtisan;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;


class LushArtisanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Watch::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {

        Event::listen(\Appstract\LushHttp\Events\RequestEvent::class, function ($event) {

            \File::put(storage_path('framework/lush_request'), json_encode($event));

        });

        Event::listen(\Appstract\LushHttp\Events\ResponseEvent::class, function ($event) {

            \File::put(storage_path('framework/lush_response'), json_encode($event));

        });

        // config
//        $this->mergeConfigFrom(__DIR__.'/../config/opcache.php', 'opcache');
    }
}
