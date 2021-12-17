<?php

namespace App\Services;

use Minicli\App;
use Minicli\ServiceInterface;

class LocalConfig implements ServiceInterface
{
    protected App $app;

    public function load(App $app)
    {
        $this->app = $app;

        $app->config->app_path;
    }

    public function __invoke()
    {
        $configFile = sprintf('%s/.config/dev-cli/config.php', getenv("HOME"));
        $root = $this->app->config->app_path;

        if (is_readable($configFile)) {
            call_user_func(function () use ($configFile, $root) {
                require_once $root.'/task.php';
                require $configFile;
            });
        }
    }
}
