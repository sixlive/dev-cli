<?php

namespace App\Command\Init;

use App\Services\Task;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle()
    {
        $task = $this->app->{Task::class};
        $output = $this->getPrinter();

        $output->info(' Initializing ', true);
        $output->newLine();

        $task('Create config file', fn () => $this->createConfigFile());
    }

    private function createConfigFile()
    {
        $configPath = sprintf('%s/.config/dev-cli', getenv("HOME"));

        if (!is_dir($configPath)) {
            mkdir($configPath, 0777, true);
            copy(__DIR__.'/stubs/config.php', $configPath.'/config.php');
        }

        return true;
    }
}
