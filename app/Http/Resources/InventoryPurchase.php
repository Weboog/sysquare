<?php

namespace App\Http\Resources;

use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryPurchase extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->generatePurchase([
                'serial' => $this->serial,
                'order_id' => $this->id,
                'created' => $this->created_at,
            ], $this->items);
    }

    private function generatePurchase(array $order_data, Collection $items): array
    {
        $arr = [];
        foreach ($items as $item) {
            if ($item->pivot->missed) continue; //Exclude missed item
            $pv = $item->pivot;
            $ct = $item->category()->first()->name;
            $sp = $item->suppliers()->where('suppliers.id', $pv->supplier_id)->first();
            $price = $pv->price;
            if (!$sp) $sp = Supplier::find($pv->supplier_id);


            $tot = round((((double) $pv->quantity)* ($price) ),2);

            if (array_key_exists($sp->code, $arr)) {
                $arr[$sp->code]['total'] += $tot;
                if (array_key_exists($ct, $arr[$sp->code]['categories'])) {
                    $arr[$sp->code]['categories'][$ct] += $tot;
                } else {
                    $arr[$sp->code]['categories'][$ct] = $tot;
                }
            } else {
                $new_ct = [];
                $new_ct[$ct] = $tot;
                $arr[$sp->code] = [
                    'supplier' => $sp->name,
                    'reference' => $order_data['serial'].'#'.$sp->id,
                    'total' => $tot,
                    'invoices' => Invoice::where(['order_id' => $order_data['order_id'], 'supplier_id' => $sp->id])->get(['reference', 'comment']),
                    'categories' => $new_ct,
                    'created' => $order_data['created']
                ];
            }
        }
        return array_values($arr);
    }

//    private function generatePurchase(array $order_data, Collection $items): array
//    {
//        $arr = [];
//        foreach ($items as $item) {
//            if ($item->pivot->missed) continue; //Exclude missed item
//            $pv = $item->pivot;
//            $ct = $item->category()->first()->name;
//            $sp = $item->suppliers()->where('suppliers.id', $pv->supplier_id)->first();
//
//            if (!$sp) {
//                $price = $pv->price;
//                $sp = Supplier::find($pv->supplier_id);
//            } else {
//                $price = $sp->pivot->price;
//            }
//
//            $tot = round((((double) $pv->quantity)* ($price) ),2);
//
//            if (array_key_exists($sp->code, $arr)) {
//                $arr[$sp->code]['total'] += $tot;
//                if (array_key_exists($ct, $arr[$sp->code]['categories'])) {
//                    $arr[$sp->code]['categories'][$ct] += $tot;
//                } else {
//                    $arr[$sp->code]['categories'][$ct] = $tot;
//                }
//            } else {
//                $new_ct = [];
//                $new_ct[$ct] = $tot;
//                $arr[$sp->code] = [
//                    'supplier' => $sp->name,
//                    'reference' => $order_data['serial'].'#'.$sp->id,
//                    'total' => $tot,
//                    'invoices' => Invoice::where(['order_id' => $order_data['order_id'], 'supplier_id' => $sp->id])->get(['reference', 'comment']),
//                    'categories' => $new_ct,
//                    'created' => $order_data['created']
//                ];
//            }
//        }
//        return array_values($arr);
//    }
}
