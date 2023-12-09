<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Missing extends Model
{
    use HasFactory;
//    protected $hidden = ['id', 'item_id'];

    public $timestamps = false;

    protected $fillable = ['item_id'];

    public function item() {

        return $this->belongsTo(Item::class, 'item_id');

    }
}
