<?php

namespace App\Enums;

enum SemesterSeason: int
{
    case Winter = 0;
    case Summer = 1;

    public function getName(): string
    {
        return match ($this) {
            self::Winter => 'Zimski',
            self::Summer => 'Letnji',
        };
    }
}
