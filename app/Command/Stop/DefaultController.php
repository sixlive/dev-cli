<?php

namespace App\Command\Stop;

use App\Services\TerminalOutput;
use Minicli\Command\CommandController;
use TitasGailius\Terminal\Terminal;

class DefaultController extends CommandController
{
    public function handle()
    {
        $this->app->setSignature('default');
        $terminalResponse = $this->app->{TerminalOutput::class};

        // Valet
        $this->getPrinter()->info('Stoping valet...');
        $response = Terminal::run('valet stop');
        $terminalResponse($response);

        // DBngin
        $this->getPrinter()->info('Opening DBngin...');
        $response = Terminal::run('open /Applications/DBngin.app');
        $terminalResponse($response);
    }
}
