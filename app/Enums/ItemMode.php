<?php

namespace App\Enums;

enum ItemMode: string
{
    case DEFAULT = 'default';
    case COMPARISON = 'comparison';

    public static function getAllValues(): array {
        return array_column(ItemMode::cases(), 'value');
    }
}
