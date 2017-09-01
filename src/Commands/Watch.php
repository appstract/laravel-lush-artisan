<?php

namespace Appstract\LushArtisan\Commands;

use File;
use Illuminate\Console\Command;
use Appstract\Opcache\CreatesRequest;

class Watch extends Command
{
    use CreatesRequest;

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


        $this->truncateFile('request');
        $this->truncateFile('response');


        while(true) {

            $requestEvent = json_decode($this->readFile('request'));

            if ($requestEvent) {
                $this->line('New Lush request to: '.$requestEvent->request->payload->url);
                dump($requestEvent->request);
                $this->line(PHP_EOL);

                $this->truncateFile('request');
            }

            $responseEvent = json_decode($this->readFile('response'));


            if ($responseEvent) {
                $this->line('New Lush response:'.PHP_EOL.PHP_EOL);
                dump($responseEvent->response);
                $this->truncateFile('response');
            }

            exit;

            sleep(1);
        }

    }

    protected function readFile($file)
    {
        return File::get(storage_path('framework/lush_'.$file));
    }

    protected function truncateFile($file)
    {
//        File::put(storage_path('framework/lush_'.$file), '');
    }
}
