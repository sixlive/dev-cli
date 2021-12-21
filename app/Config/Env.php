<?php

namespace App\Config;

use App\Enums\ProtectedTasks;
use Closure;

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
        $this->reset();
    }

    public function task(string $title, string $command)
    {
        array_push($this->tasks, [$title => $command]);
    }

    public function actions()
    {
        return $this->actions;
    }

    public function description(string $description): void
    {
        array_push($this->tasks, [ProtectedTasks::Description => $description]);

    }

    private function reset()
    {
        $this->tasks = [];
        $this->actionDescription = '';
    }
}
