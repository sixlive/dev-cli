<?php

namespace App\Actions;

use Closure;

class LoadLocalConfig
{
    public function __construct()
    {
        $this->configPath = sprintf(
            '%s/.config/dev-cli/config.php',
            getenv("HOME")
        );
    }

    public function __invoke()
    {
        $config = new Config;

        if (is_readable($this->configPath)) {
            Closure::bind(function (){
                require_once $this->configPath;
            }, $config)();
        }

        dd($config);

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
