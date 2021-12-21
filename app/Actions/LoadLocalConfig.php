<?php

namespace App\Actions;

use App\Config\Config;
use Closure;

class LoadLocalConfig
{
    protected string $configPath;

    public function __construct()
    {
        $this->configPath = sprintf(
            '%s/.config/dev-cli/config.php',
            getenv("HOME")
        );
    }

    public function __invoke(): array
    {
        $config = new Config;

        if (is_readable($this->configPath)) {
            Closure::bind(function ($configPath) {
                require_once $configPath;
            }, $config)($this->configPath);
        }

        return $config->toArray();
    }
}
