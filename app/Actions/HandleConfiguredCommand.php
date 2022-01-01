<?php

namespace App\Actions;

use Illuminate\Foundation\Console\ClosureCommand as Command;
use TitasGailius\Terminal\Terminal;

class HandleConfiguredCommand
{
    protected Command $command;

    public function __construct()
    {
        //
    }

    public function __invoke(Command $command, array $tasks)
    {
        // Logo
        $command->line($command->getApplication()->getName());

        if ($command->option('list')) {
            $command->info($command->getName().':');
            collect($tasks)
                ->keys()
                ->each(fn ($task) => $command->line(' - '.$task));

            exit;
        }

        $this->runTasks($command, $tasks);
    }

    private function runTasks(Command $command, array $tasks): void
    {
        foreach ($tasks as $name => $task) {
            $command->task($name, function () use ($command, $task) {

                $terminal = Terminal::builder();

                /** @var Response */
                $response = $terminal->run($task);

                if ($command->getOutput()->isVerbose()) {

                    $command->newLine(2);
                    collect($response)->each(fn ($line) => $command->info($line));
                }

                return $response->ok();
            });
        }
    }
}
