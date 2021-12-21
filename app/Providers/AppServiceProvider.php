<?php

namespace App\Providers;

use App\Actions\HandleConfiguredCommand;
use App\Actions\LoadLocalConfig;
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
        $this->registerCommands();
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

    private function registerCommands(): void
    {
        $commands = app(LoadLocalConfig::class)();

        $commands->each(function (Collection $tasks, $commandName) {
            $command = Artisan::command("{$commandName}", function () use ($tasks) {
                app(HandleConfiguredCommand::class)(
                    $this,
                    $tasks
                        ->except(ProtectedTasks::toArray())
                        ->toArray(),
                );
            });

            $command->setDescription(
                $tasks->get(ProtectedTasks::Description, '')
            );
        });

    }
}
