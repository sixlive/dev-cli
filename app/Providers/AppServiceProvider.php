<?php

namespace App\Providers;

use App\Actions\HandleconfiguredCommand;
use App\Enums\ProtectedTasks;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Application as App;
use Illuminate\Support\Collection;

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
                    (string) ProtectedTasks::Description() => 'Start default development env',
                ],
                'stop' => [
                    'Valet' => 'valet stop',
                ],
            ],
            'minimart' => [
                'start' => [
                    'Valet' => 'valet start',
                ],
                'stop' => [
                    'Valet' => 'valet stop',
                ],
            ],
        ];

        $commands = collect();

        foreach ($config as $command => $actions){
            foreach ($actions as $action => $tasks) {
                $commands["{$command}:{$action}"] = collect($tasks);
            }
        }

        $commands->each(function (Collection $tasks, $commandName) {
            $command = Artisan::command("{$commandName}", function () use ($tasks) {
                app(HandleconfiguredCommand::class)(
                    $this,
                    (array) $tasks->except(ProtectedTasks::toArray()),
                );
            });

            $command->setDescription(
                $tasks->get((string) ProtectedTasks::Description(), '')
            );
        });
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
