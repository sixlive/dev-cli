<?php

namespace App\Command\Help;

use App\Services\LocalConfig;
use Minicli\App;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    /** @var  array */
    protected $command_map = [];

    public function boot(App $app)
    {
        parent::boot($app);
        $this->command_map = $app->command_registry->getCommandMap();
    }

    public function handle()
    {
        $this->getPrinter()->info('Available Commands');

        $localConfig = $this->app->{LocalConfig::class};

        $localConfig = collect($localConfig())
            ->map(function ($env, $key) {
                $actions = array_keys($env['actions']);
                return array_map(fn ($action) => [$action => $key], $actions);
            })
            ->map(function ($env) {
                return array_flip($env[0]);
            })
            ->flatMap(function ($env) {
                return $env;
            })
            ->mapToGroups(function ($env, $key) {
                return [$env => $key];
            })
            ->toArray();

        $this->command_map = array_merge($this->command_map, $localConfig);

        foreach ($this->command_map as $command => $sub) {

            $this->getPrinter()->newline();
            $this->getPrinter()->out($command, 'info_alt');

            if (is_array($sub)) {
                foreach ($sub as $subcommand) {
                    if ($subcommand !== 'default') {
                        $this->getPrinter()->newline();
                        $this->getPrinter()->out(sprintf('%s%s','└──', $subcommand));
                    }
                }
            }
            $this->getPrinter()->newline();
        }

        $this->getPrinter()->newline();
        $this->getPrinter()->newline();
    }
}
