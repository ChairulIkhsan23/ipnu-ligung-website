<?php

namespace App\Enums;

enum Visibility: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';

    public function label(): string
    {
        return match ($this) {
            self::PUBLIC => 'Publik',
            self::PRIVATE => 'Privat',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PUBLIC => 'success',
            self::PRIVATE => 'danger',
        };
    }
}
