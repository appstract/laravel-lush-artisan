<?php


namespace Appstract\LushArtisan;

use File;

class EventStorage
{

    public function add($type, $event)
    {
        $items = [];

        if ($this->get($type)) {
            $items = $this->get($type);
        }

        $items[] = $event;

        File::put(storage_path('framework/lush_'.$type), json_encode($items));
    }

    public function get($type)
    {
        if (file_exists(storage_path('framework/lush_'.$type))) {
            return json_decode(File::get(storage_path('framework/lush_'.$type)));
        }
    }

    public function clear($type)
    {
        File::put(storage_path('framework/lush_'.$type), '');
    }
}