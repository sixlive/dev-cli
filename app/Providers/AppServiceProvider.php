<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use TitasGailius\Terminal\Response;
use TitasGailius\Terminal\Terminal;
use Illuminate\Console\Application as App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $config = [
            'default' => [
                'start' => [
                    'Valet' => 'valet start',
                    'options' => [
                        'flags' => ['verbose'],
                    ],
                ],
                'stop' => [
                    'Valet' => 'valet stop',
                ],
            ],
            'minimart' => [
                'start' => [
                    'Valet' => 'valet start',
                    'options' => [
                        'verbose'
                    ],
                ],
                'stop' => [
                    'Valet' => 'valet stop',
                ],
            ],
        ];
        $commands = [];

        foreach ($config as $command => $actions){
            foreach ($actions as $action => $tasks) {
                $commands["{$command}:{$action}"] = $tasks;
            }
        }

        foreach ($commands as $command => $tasks) {
            Artisan::command("{$command}", function () use ($tasks) {
                /** @var \LaravelZero\Framework\Commands\Command $this */

                $this->line($this->getApplication()->getName());
                $options = $tasks['options'] ?? [];
                if ($options) {
                   unset($tasks['options']);
                }

                foreach ($tasks as $name => $task) {
                    $this->task($name, function () use ($task) {
                        /** @var \LaravelZero\Framework\Commands\Command $this */
                        $terminal = Terminal::builder();

                        /** @var Response */
                        $response = $terminal->run($task);

                        if ($this->getOutput()->isVerbose()) {

                            $this->newLine(2);
                            collect($response)->each(fn ($line) => $this->info($line));
                        }

                        return $response->ok();
                    });
                }
            })
                ->setDescription('Development command');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       App::starting(
           function ($artisan) {
               $artisan->setName(<<<EOF

                ██████  ███████ ██    ██      ██████ ██      ██
                ██   ██ ██      ██    ██     ██      ██      ██
                ██   ██ █████   ██    ██     ██      ██      ██
                ██   ██ ██       ██  ██      ██      ██      ██
                ██████  ███████   ████        ██████ ███████ ██
                                           - with love, sixlabs

               EOF);
           }
       );
    }
}
