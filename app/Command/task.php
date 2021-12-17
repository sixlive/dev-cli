<?php
class ENV
{
    protected array $actions = [];
    public function __construct(public string $title)
    {
        //
    }

    public function __invoke(Closure $actions)
    {
        $actions->bindTo($this)();
    }

    public function action(string $title, Closure $tasks)
    {
        $this->actions[$title] = new Action;
        $tasks->bindTo($this->actions[$title])();
    }
}

class Action
{
    public array $tasks = [];

    public function task(string $title, string $command)
    {
        array_push($this->tasks, [$title => $command]);
    }
}

function env(string $title, Closure $actions)
{
    $env = new ENV($title);
    $env($actions);
    dump($env);
}

//function action($title, Callable $tasks)
//{
    //dd($env);
    ////$tasks();
    //return $tasks;
//}

//function task(string $title, string $task)
//{
    //return [$title, $task];
//}
