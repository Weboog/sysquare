<?php

namespace App\Enums;
enum OrderStatus: string {
    case REGISTERED = 'registered';
    case VALIDATING= 'validating';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';
    case UNDELIVERED = 'undelivered';
}
