<?php

namespace App\Http\Controllers\Summary;

use App\Enums\OrderStat;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class SummaryController extends Controller
{

    use Helper;
    public function orders(): JsonResponse
    {

        $start = Carbon::now()->firstOfMonth();//Carbon::today()->subMonth();
        $end = Carbon::now()->addDay()->toDateString();
        $stat = OrderStat::STAT_SNAPSHOT->value;

        if ($range = request()->query('range')) {
            $split = explode(':', $range);
            $start = Carbon::parse($split[0]);
            $end = Carbon::parse($split[1])->addDay();
        }

        foreach (request()->query() as $key => $value) {
            if (!$value && $value == 'null') break;
            if ($key === 'get') {
                $stat = match ($value) {
                    OrderStat::STAT_GRAPH->value => 'graph',
                    OrderStat::STAT_PARTITION_CATEGORY->value => 'partition_category',
                    default => 'snapshot',
                };
            }
        }

        return match ($stat) {
            OrderStat::STAT_GRAPH->value => $this->orderGraph($start, $end),
            OrderStat::STAT_PARTITION_CATEGORY->value => $this->orderPartitionCategory($start, $end),
            default => $this->orderSnapshot($start, $end),
        };

    }

    //Functions/////////////////////////////////////////////////////////////////////////////
    private function orderSnapshot(Carbon $start, string $end): JsonResponse
    {
        $orders = Order::whereBetween('created_at', [$start, $end])->notRejected()->get();
        $calculation = ($orders->map(function ($order) { return array_values($this->calculateTotalAmount($order)); }))->toArray();
        return response()->json([
            'count' => $orders->count(),
            'purchases' => [
                'count' => (int) array_reduce($calculation, function ($c, $arr) {
                    return $c + $arr[0];
                }),
                'total' => round(array_reduce($calculation, function ($c, $arr) {
                    return $c + $arr[1];
                }), 2)
            ]
        ]);
    }

    private function orderGraph(Carbon $start, string $end): JsonResponse
    {
        $byMonths = [];
        $months = $start->diffInMonths($end);

        for ($i = 1; $i <= $months; $i++) {

            $startDayOfMonth = $start->copy()->addMonth($i-1)->startOfDay();
            $endDayOfMonth = $start->copy()->addMonth($i-1)->endOfMonth();

            $orders = Order::whereBetween('created_at', [$startDayOfMonth, $endDayOfMonth])->get();
            $calculation = ($orders->map(function ($order) { return array_values($this->calculateTotalAmount($order)); }))->toArray();
            $byMonths[$startDayOfMonth->month] = [
                'count' => $orders->count(),
                'purchases' => [
                    'items_count' => array_reduce($calculation, function ($c, $arr) {
                        return $c + $arr[0];
                    }),
                    'total' => round(array_reduce($calculation, function ($c, $arr) {
                        return $c + $arr[1];
                    }), 2)
                ]
            ];

        }

        return response()->json([
            'byMonth' => $byMonths
        ]);
    }

    private function orderPartitionCategory(Carbon $start, string $end): JsonResponse
    {
        return response()->json(['Message' => 'PARTITION_CATEGORY']);
    }

}
