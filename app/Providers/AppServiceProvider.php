<?php

namespace App\Providers;

use App\Actions\HandleConfiguredCommand;
use App\Actions\LoadLocalConfig;
use App\Enums\ProtectedTasks;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Application as App;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
                /** @var Command $this */
                $excludes = explode(',', $this->option('except'));

                app(HandleConfiguredCommand::class)(
                    $this,
                    $tasks
                        ->except(ProtectedTasks::toArray())
                        ->reject(fn ($_, $task) => in_array(
                            strtolower($task),
                            $excludes
                        ))
                        ->toArray(),
                );
            });

            $command->addOption('except', 'e', InputOption::VALUE_OPTIONAL, 'Tasks to exclude, comma seperated');
            $command->addOption('list', 'l', InputOption::VALUE_NONE, 'List tasks.');

            $command->setDescription(
                $tasks->get(ProtectedTasks::Description, '')
            );
        });

    }
}
