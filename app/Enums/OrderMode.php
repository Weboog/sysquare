<?php

namespace App\Enums;

enum OrderMode : string {
    case MODE_DEFAULT = 'default';
    case MODE_DELIVERY_NOTE = 'purchase';

    case MODE_INVENTORY_PURCHASE = 'inventory_purchase';

    public static function getAllValues(): array {
        return array_column(OrderMode::cases(), 'value');
    }
}
