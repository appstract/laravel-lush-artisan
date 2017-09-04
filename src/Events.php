<?php

namespace Appstract\LushArtisan;

use Illuminate\Support\Facades\Event;
use Appstract\LushArtisan\EventStorageFacade as EventStorage;

class Events
{
    /**
     * Lush events this app is listening for.
     *
     * @var array
     */
    public static $events = [
        'RequestEvent',
        'ResponseEvent',
        'RequestExceptionEvent',
    ];

    /**
     * Get all events.
     *
     * @return array
     */
    public static function all()
    {
        return self::$events;
    }

    /**
     * Register the event listeners.
     */
    public static function registerListeners()
    {
        foreach (self::all() as $type) {
            Event::listen("Appstract\\LushHttp\\Events\\{$type}", function ($event) use ($type) {
                EventStorage::add($type, $event);
            });
        }
    }
}
