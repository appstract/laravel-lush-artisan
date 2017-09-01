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
        $this->line('Listening for Lush requests...');
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

            $exceptionEvent = EventStorage::get('exception');

            if ($exceptionEvent) {
                $this->renderExceptionEvents($exceptionEvent);
            }

            sleep(1);
        }

    }

    /**
     * @param $events
     */
    protected function renderRequestEvents($events)
    {
        foreach ($events as $event) {
            $this->info('New Lush Request::');
            $this->table(['Request Url', 'Parameters', 'Headers', 'Options'], [$this->renderRequestTable($event->request)]);
            $this->line(PHP_EOL);
        }

        EventStorage::clear('request');
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected function renderRequestTable($data)
    {
        return [
            '['.$data->method.'] '.$data->payload->url,
            $this->stringifyArray($data->payload->parameters),
            $this->stringifyArray($data->payload->headers),
            $this->stringifyArray($data->payload->options),
        ];
    }

    /**
     * @param $events
     */
    protected function renderResponseEvents($events)
    {
        foreach ($events as $event) {
            if ($event->response->content) {
                $this->info('New Lush Response:: ');
                $this->table(['Request Url', 'Response Headers', 'Is Json', 'Is Xml'], [$this->renderResponseTable($event->response)]);
                $this->info('Response Result:');

                if ($event->response->object) {
                    dump($event->response->object);
                } else {
                    dump($event->response->content);
                }
            } else {
                $this->warn('Empty response on: '.$event->response->request->payload->url);
            }

            $this->line(PHP_EOL);
        }

        EventStorage::clear('response');
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected function renderResponseTable($data)
    {
        return [
            '['.$data->request->method.'] '.$data->request->payload->url,
            $this->stringifyArray($data->headers),
            $data->is_json,
            $data->is_xml,
        ];
    }

    /**
     * @param $events
     */
    protected function renderExceptionEvents($events)
    {
        foreach ($events as $event) {
            $this->error('Exception on url: '.$event->exception->request->payload->url);
            $this->error($event->exception->message);
            $this->line(PHP_EOL);
        }

        EventStorage::clear('exception');
    }

    /**
     * @param $array
     *
     * @return string
     */
    protected function stringifyArray($array)
    {
        $string = '';

        array_walk_recursive($array, function ($value, $key) use (&$string) {
                $string .= $key . ' => ' . $value . PHP_EOL;
            }
        );

        return $string;
    }
}
