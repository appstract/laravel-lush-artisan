<?php

namespace Appstract\LushArtisan\Commands;

use File;
use Illuminate\Console\Command;
use Appstract\LushArtisan\EventStorageFacade as EventStorage;

class Watch extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lush:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch for Lush requests';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Listening for Lush requests in your app...');
        $this->line('Press ctrl+c to exit'.PHP_EOL);

        EventStorage::clear('request');
        EventStorage::clear('response');

        while (true) {
            $requestEvent = EventStorage::get('request');

            if ($requestEvent) {
               $this->renderRequestEvents($requestEvent);
            }

            $responseEvent = EventStorage::get('response');

            if ($responseEvent) {
                $this->renderResponseEvents($responseEvent);
            }

            sleep(1);
        }

    }

    protected function renderRequestEvents($events)
    {
        foreach ($events as $event) {
            $this->info('New Lush request for: '.$event->request->payload->url);
            dump($event->request);
            $this->line(PHP_EOL);

            EventStorage::clear('request');
        }
    }

    protected function renderResponseEvents($events)
    {
        foreach ($events as $event) {
            $this->info('New Lush response for: '.$event->response->request->payload->url);
            dump($event->response->object);
//            dump($event->response);
            $this->line(PHP_EOL);

            EventStorage::clear('response');
        }
    }
}
