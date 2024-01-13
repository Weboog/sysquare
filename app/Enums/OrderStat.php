<?php

namespace App\Enums;

enum OrderStat: string
{
    case STAT_SNAPSHOT = 'snapshot';
    case STAT_GRAPH = 'graph';
    case STAT_PARTITION_CATEGORY = 'partition_category';

    public static function getAllValues(): array
    {
        return array_column(OrderMode::cases(), 'value');
    }
}
