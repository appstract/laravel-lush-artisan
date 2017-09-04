<?php

namespace Appstract\LushArtisan\Commands;

use Illuminate\Console\Command;
use Appstract\LushArtisan\Events as LushEvents;
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
        $this->line('Press ctrl+c to exit');

        EventStorage::clearAll();

        while (true) {
            foreach (LushEvents::all() as $type) {
                $event = EventStorage::get($type);

                if ($event) {
                    $this->{("render{$type}")}($event);
                }

                EventStorage::clear($type);
            }

            sleep(1);
        }

    }

    /**
     * @param $events
     */
    protected function renderRequestEvent($events)
    {
        foreach ($events as $event) {
            $this->line(PHP_EOL);
            $this->info('New Lush Request::');
            $this->table(['Request Url', 'Parameters', 'Headers', 'Options'], [$this->renderRequestTable($event->request)]);
        }
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
    protected function renderResponseEvent($events)
    {
        foreach ($events as $event) {
            $this->line(PHP_EOL);
            $this->info('New Lush Response:: ');

            $this->table(['Request Url', 'Response Headers', 'Is Json', 'Is Xml'], [$this->renderResponseTable($event->response)]);
            $this->info('Response Result:');

            if ($event->response->object) {
                dump($event->response->object);
            } else if ($event->response->content) {
                dump($event->response->content);
            } else {
                $this->warn('- empty response -');
            }
        }
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
    protected function renderRequestExceptionEvent($events)
    {
        foreach ($events as $event) {
            $this->line(PHP_EOL);
            $this->error('New Lush Exception:');
            $this->error(sprintf('[%s] %s', $event->exception->request->method, $event->exception->request->payload->url));
            $this->error($event->exception->message);
        }
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
