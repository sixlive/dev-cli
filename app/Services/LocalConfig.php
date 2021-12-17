<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Closure;
use Minicli\App;
use Minicli\ServiceInterface;

class LocalConfig implements ServiceInterface
{
    protected App $app;
    protected array $config = [];

    public function load(App $app)
    {
        $this->app = $app;

        $app->config->app_path;
    }

    public function __invoke()
    {
        $configFile = sprintf('%s/.config/dev-cli/config.php', getenv("HOME"));
        $config = new Config;

        if (is_readable($configFile)) {
            Closure::bind(function () use ($configFile) {
                require_once $configFile;
            }, $config)();
        }

        $this->config = $config->toArray();

        return $this->config();
    }

    public function config(): array
    {
        return $this->config;
    }
}

class Config
{
    protected $envs = [];

    public function env(string $title, Closure $actions)
    {
        $this->envs[$title] = new Env($title);
        $this->envs[$title]($actions);
    }

    public function toArray(): array
    {
        return Collection::make($this->envs)
            ->map(fn ($env) => ['actions' => $env->actions()])
            ->toArray();;
    }
}


class Env
{
    protected array $actions = [];
    protected array $tasks = [];

    public function __construct(public string $title) {
        //
    }

    public function __invoke(Closure $actions)
    {
        $actions->bindTo($this)();
    }

    public function action(string $title, Closure $tasks)
    {
        $tasks->bindTo($this)();
        $this->actions[$title] = array_merge(...$this->tasks);
        $this->tasks = [];
    }

    public function task(string $title, string $command)
    {
        array_push($this->tasks, [$title => $command]);
    }

    public function actions()
    {
        return $this->actions;
    }
}

