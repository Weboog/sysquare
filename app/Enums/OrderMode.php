<?php

namespace App\Enums;

enum OrderMode : string {
    case MODE_DEFAULT = 'default';
    case MODE_DELIVERY_NOTE = 'delivery_note';

    public static function getAllValues(): array {
        return array_column(OrderMode::cases(), 'value');
    }
}
