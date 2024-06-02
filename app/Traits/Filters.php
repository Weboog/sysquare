<?php

namespace App\Traits;

use Carbon\Carbon;

trait Filters {

    public function parseQuery(string $key, $closure) {
        $value = '';
        if (request()->query($key) && request()->query($key) != 'null') {
            $value = request()->query($key);
        }
        return $closure($value);
    }

    public function extractRange(string $range, $closure) {
        $parts = explode(':', $range);
        $start = $parts[0];
        $end = $parts[1];
        //Extract start and end dates
        $startArr = [];
        $endArr = [];
        foreach ([0, 2, 4, 8, 10] as $key => $value) {
            $startArr[] = substr($start, $value, $value == 4 ? 4 : 2);
            $endArr[] = substr($end, $value, $value == 4 ? 4 : 2);
        }
        $foramttedStartDate = $startArr[2] . '-' . $startArr[1] . '-' . $startArr[0] . ' ' . $startArr[3] . ':' . $startArr[4];
        $foramttedEndDate = $endArr[2] . '-' . $endArr[1] . '-' . $endArr[0] . ' ' . $endArr[3] . ':' . $endArr[4];

        return $closure(array($foramttedStartDate, Carbon::parse($foramttedEndDate)->addDay()));
    }

}
