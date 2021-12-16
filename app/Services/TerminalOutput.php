<?php

namespace App\Services;

use Minicli\App;
use Minicli\ServiceInterface;
use TitasGailius\Terminal\Response;

class TerminalOutput implements ServiceInterface
{
    protected App $app;

    public function load(App $app)
    {
        $this->app = $app;
    }

    public function __invoke(Response $response): void
    {
        foreach ($response as $line) {
            $this->app
                 ->getPrinter()
                 ->{$response->ok() ? 'success' : 'error'}($line);
        }
    }
}
