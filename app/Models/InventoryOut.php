<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryOut extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'jumlah_keluar',
        'location_id',
        'type_id',
        'unit_id',
        'keterangan',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
