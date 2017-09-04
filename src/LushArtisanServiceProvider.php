<?php

namespace Appstract\LushArtisan;

use Illuminate\Support\ServiceProvider;
use Appstract\LushArtisan\Events as LushEvents;

class LushArtisanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Get::class,
                Commands\Head::class,
                Commands\Watch::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        LushEvents::registerListeners();

        // config
//        $this->mergeConfigFrom(__DIR__.'/../config/opcache.php', 'opcache');
    }
}
