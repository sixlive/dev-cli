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
        $output->rawOutput("$title...");

        sleep(1);

        try {
            $success = $task();
        } catch (Exception) {
            $success = false;
        }

        if ($success) {
            // Go to start of line
            $output->rawOutput("\x0D");

            // Erase the line
            $output->rawOutput("\x1B[2K");
            $output->success("✔ $title");
        } else {
            $output->error("$title failed!");
        }
    }
};
