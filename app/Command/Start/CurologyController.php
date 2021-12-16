<?php

namespace App\Command\Start;

use App\Services\TerminalOutput;
use TitasGailius\Terminal\Terminal;

class CurologyController extends DefaultController
{
    public function handle()
    {
        $this->getPrinter()->display('Starting for Curology', true);
        $terminalResponse = $this->app->{TerminalOutput::class};

        // Start base services
        parent::handle();

        $this->getPrinter()->display('Starting Curology Services');
        $this->getPrinter()->info('Starting Docker services...');
        $response = Terminal::run("docker compose -f ~/docker/curology/docker-compose.yml start");
        $terminalResponse($response);
    }
}
