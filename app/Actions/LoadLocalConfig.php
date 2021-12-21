<?php

namespace App\Actions;

use Illuminate\Support\Collection;
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

    public function __invoke(): Collection
    {
        $config = new Config;

        if (is_readable($this->configPath)) {
            Closure::bind(function ($configPath) {
                require_once $configPath;
            }, $config)($this->configPath);
        }

        return $this->formatConfigForCommands($config);
    }

    private function formatConfigForCommands(Config $config): Collection
    {
        $commands = collect();

        foreach ($config->toArray() as $command => $actions){
            foreach ($actions as $action => $tasks) {
                $name = strtolower($command) === 'default'
                    ? $action
                    : "{$command}:{$action}";

                $commands["$name"] = collect($tasks);
            }
        }

        return $commands;
    }
}
