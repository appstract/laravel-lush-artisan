<?php

namespace Appstract\LushArtisan;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Appstract\LushArtisan\EventStorageFacade as EventStorage;

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
            EventStorage::add('request', $event);
        });

        Event::listen(\Appstract\LushHttp\Events\ResponseEvent::class, function ($event) {
            EventStorage::add('response', $event);
        });

        // config
//        $this->mergeConfigFrom(__DIR__.'/../config/opcache.php', 'opcache');
    }
}
