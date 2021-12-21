<?php

namespace App\Enums;

class ProtectedTasks
{
    const Description = 'Description';

    public static function toArray(): array
    {
        return [
            self::Description,
        ];
    }
}
