<?php

namespace App\Command\Init;

use App\Services\Task;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle()
    {
        $task = $this->app->{Task::class};
        $this->getPrinter()->info('Initializing');
        $this->getPrinter()->newline();

        $task('Create config file', fn () => $this->createConfigFile());
    }

    private function createConfigFile()
    {
        $configPath = sprintf('%s/.config/dev-cli', getenv("HOME"));

        if (!is_dir($configPath)) {
            $this->getPrinter()->info('Creating config file...');
            mkdir($configPath, 0777, true);
            copy(__DIR__.'/stubs/config.php', $configPath.'/config.php');
        }

        return true;
    }
}
