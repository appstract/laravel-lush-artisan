<?php

namespace Appstract\LushArtisan;

use File;
use Appstract\LushArtisan\Events as LushEvents;

class EventStorage
{

    protected $baseDir = 'lush';

    /**
     * EventStorage constructor.
     */
    public function __construct()
    {
        if (!is_dir(storage_path($this->baseDir))) {
            mkdir(storage_path($this->baseDir), 0755, true);
        }
    }

    /**
     * Add an event entry
     *
     * @param $type
     * @param $event
     */
    public function add($type, $event)
    {
        $items = [];

        if ($this->get($type)) {
            $items = $this->get($type);
        }

        $items[] = $event;

        // limit the amount of events to log
        if (count($items) > 5) {
            unset($items[0]); // remove the oldest
            $items = array_values($items);
        }

        File::put($this->getPath($type), json_encode($items));
    }

    /**
     * Get event entries
     *
     * @param $type
     *
     * @return mixed
     */
    public function get($type)
    {
        if (file_exists($this->getPath($type))) {
            return json_decode(File::get($this->getPath($type)));
        }
    }

    /**
     * Clear event entries of given type
     *
     * @param $type
     */
    public function clear($type)
    {
        File::put($this->getPath($type), '');
    }

    /**
     * Clear event entries of all types
     */
    public function clearAll()
    {
        foreach (LushEvents::all() as $type) {
            $this->clear($type);
        }
    }

    /**
     * Get the storage path of the given type
     *
     * @param $type
     *
     * @return string
     */
    protected function getPath($type)
    {
        return storage_path("{$this->baseDir}/Lush{$type}");
    }
}