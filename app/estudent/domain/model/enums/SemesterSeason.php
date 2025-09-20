<?php

namespace App\estudent\domain\model\enums;

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
