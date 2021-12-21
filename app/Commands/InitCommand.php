<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Initialize dev-cli';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $configPath = sprintf(
            '%s/.config/dev-cli/config.php',
            getenv("HOME")
        );

        $this->task(
            'Config Initialization',
            fn () => $this->createConfigFile($configPath)
        );
    }

    private function createConfigFile(string $configPath)
    {
        if (!is_dir($configPath)) {
            mkdir($configPath, 0777, true);
            copy(app_path('stubs/config.php'), $configPath.'/config.php');
        }

        return true;
    }
}
