<?php

namespace App\Command\Start;

use App\Services\TerminalOutput;
use Minicli\Command\CommandController;
use TitasGailius\Terminal\Terminal;

class DefaultController extends CommandController
{
    public function handle()
    {
        $terminalResponse = $this->app->{TerminalOutput::class};

        // Valet
        $this->getPrinter()->info('Starting valet...');
        $response = Terminal::run('valet start');
        $terminalResponse($response);

        // DBngin
        $this->getPrinter()->info('Opening DBngin...');
        $response = Terminal::run('open /Applications/DBngin.app');
        $terminalResponse($response);
    }
}
