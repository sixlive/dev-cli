<?php

namespace App\Services;

use Minicli\App;
use Minicli\ServiceInterface;

class Art implements ServiceInterface {
    protected App $app;

    public function header()
    {
        $this->app->getPrinter()->display(<<<EOF
        ██████  ███████ ██    ██      ██████ ██      ██
        ██   ██ ██      ██    ██     ██      ██      ██
        ██   ██ █████   ██    ██     ██      ██      ██
        ██   ██ ██       ██  ██      ██      ██      ██
        ██████  ███████   ████        ██████ ███████ ██
                                   - with love, sixlabs
        EOF, false);
    }

    public function load(App $app)
    {
        $this->app = $app;
    }
};
