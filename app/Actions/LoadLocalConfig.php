<?php

namespace App\Actions;

use Illuminate\Support\Collection;
use App\Config\Config;
use Closure;

class LoadLocalConfig
{
    protected Collection $configPaths;

    public function __construct()
    {
        $this->configPaths = collect();

        $this->configPaths->push(sprintf(
            '%s/.config/dev-cli/*.php',
            getenv("HOME")
        ));

        if ($this->hasConfigInPath()) {
            $this->configPaths->push(sprintf('%s/.dev-cli.php', getenv('PWD')));
        }
    }

    private function hasConfigInPath(): bool
    {
        return is_file(sprintf('%s/.dev-cli.php', getenv('PWD')));
    }

    public function __invoke(): Collection
    {
        $config = new Config;

        $this->configPaths->each(fn ($path) => $this->loadConfigForPath($config, $path));

        return $this->formatConfigForCommands($config);
    }

    private function loadConfigForPath(Config $config, string $path): void
    {
        foreach (glob($path) as $configPath) {
            if (is_readable($configPath)) {
                Closure::bind(function (string $configPath) {
                    require_once $configPath;
                }, $config)($configPath);
            }
        }
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
