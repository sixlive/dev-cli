<?php

namespace App\Config;

use Illuminate\Support\Collection;
use Closure;

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
            ->map(fn ($env) => $env->actions())
            ->toArray();;
    }
}
