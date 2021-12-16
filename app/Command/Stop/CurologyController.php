<?php

namespace App\Command\Stop;

use App\Services\TerminalOutput;
use TitasGailius\Terminal\Terminal;

class CurologyController extends DefaultController
{
    public function handle()
    {
        $this->getPrinter()->display('Stoping for Curology', true);
        $terminalResponse = $this->app->{TerminalOutput::class};

        // Stop base services
        parent::handle();

        $this->getPrinter()->display('Stoping Curology Services');
        $this->getPrinter()->info('Stoping Docker services...');
        $response = Terminal::run("docker compose -f ~/docker/curology/docker-compose.yml stop");
        $terminalResponse($response);
    }
}
