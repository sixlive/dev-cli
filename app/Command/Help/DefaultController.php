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
        $localConfig = $this->app->{LocalConfig::class}->config();

        // Merge config defined actions into help
        $this->command_map = collect($this->command_map)
             ->map(function ($command, $key) use ($localConfig) {
                if (array_key_exists($key, $localConfig)) {
                    $command = array_keys($localConfig[$key]['actions']);
                }

                 return $command;
            })->toArray();

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
