<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryIn extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'jumlah_masuk',
        'keterangan',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
