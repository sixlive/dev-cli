<?php

namespace App\Services;

use Exception;
use Minicli\App;
use Minicli\ServiceInterface;

class Task implements ServiceInterface {
    protected App $app;

    public function load(App $app)
    {
        $this->app = $app;
    }

    public function __invoke(string $title, callable $task)
    {
        $output = $this->app->getPrinter();
        $output->out("$title...", 'info');

        sleep(1);

        try {
            $success = $task();
        } catch (Exception) {
            $success = false;
        }

        // Go to start of line
        $output->rawOutput("\x0D");
        // Erase the line
        $output->rawOutput("\x1B[2K");

        if ($success) {
            $output->out("✔ $title", 'success');
        } else {
            $output->out("ⅹ $title", 'error');
        }

        $output->newline();

        return $success;
    }
};
