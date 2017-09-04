<?php

namespace Appstract\LushArtisan\Commands;

use Illuminate\Console\Command;
use Appstract\LushHttp\LushFacade as Lush;

class Head extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lush:head
                            {url : The url to GET}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a HEAD request';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = Lush::url($this->argument('url'))->head();

        dump($request->getResult());
    }
}
