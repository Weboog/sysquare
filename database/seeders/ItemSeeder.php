<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
       $supplierId = 17;
        $items = [
        	  0 => [
    'title' => 'Aspirateur à usage domistique',
    'price' => '2,200.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  1 => [
    'title' => 'Monobrosse c43 ergonomique avec accessoires',
    'price' => '11,500.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  2 => [
    'title' => 'Karcher',
    'price' => '15,900.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  3 => [
    'title' => 'Mouilleur vitre blanc 35',
    'price' => '60.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  4 => [
    'title' => 'Raclette vitre en inox fixe 35',
    'price' => '95.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  5 => [
    'title' => 'Perche telescopique 2 pièces 600 cm',
    'price' => '350.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  6 => [
    'title' => 'Raclette sol traditionnel',
    'price' => '180.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  7 => [
    'title' => 'Manche en bois',
    'price' => '50.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  8 => [
    'title' => 'Bac de rangement',
    'price' => '240.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  9 => [
    'title' => 'Plumeau poussière',
    'price' => '55.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  10 => [
    'title' => 'Tête de loup',
    'price' => '65.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  11 => [
    'title' => 'Balai en nylon',
    'price' => '55.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  12 => [
    'title' => 'Manche à balai en bois',
    'price' => '45.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  13 => [
    'title' => 'support en aluminum avec velcro 40cm',
    'price' => '250.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  14 => [
    'title' => 'Manche en aluminum 147 cm',
    'price' => '140.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  15 => [
    'title' => 'Sau carré',
    'price' => '85.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
  16 => [
    'title' => 'Pelle et balais domestique',
    'price' => '230.00',
    'category_id' => '2',
    'type_id' => '1',
    'brand_id' => '1',
    'condition' => 'Unité',
  ],
        ];
        
        foreach ($items as $item) {
           $itm = Item::where('title', $item['title'])->whereHas('suppliers', function ($q) use ($supplierId) { return $q->where('suppliers.id', $supplierId); })->first();
           if ($itm) {
               $itm->suppliers->isEmpty()
               ? $itm->suppliers()->attach($supplierId, ['price' => $item['price']])
               : $itm->suppliers()->updateExistingPivot($supplierId , [ 'price' => $item['price'] ]);
           }
           else {
               //create new item
               DB::transaction(function () use ($item, $supplierId) {
                   $newItem = Item::create($item);
                   $newItem->refresh();
                   $newItem->suppliers()->attach($supplierId, ['price' => $item['price']]);
               });
           }
        }

//        $item = Item::where('title', $title)->first();
//
//        if ($item) {
//            // Update the price of the existing item
//            $item->price = $price;
//            $item->save();
//        } else {
//            // Create a new item
//            Item::create([
//                'title' => $title,
//                'price' => $price
//            ]);
//        }
    }
}
