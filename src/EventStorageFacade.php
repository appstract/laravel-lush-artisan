<?php

namespace Appstract\LushArtisan;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Appstract\LushHttp\Lush
 */
class EventStorageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return EventStorage::class;
    }
}
