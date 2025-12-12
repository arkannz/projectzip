<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabTemplate extends Model
{
    protected $table = 'rab_templates';

    protected $fillable = [
        'category_id',
        'uraian',
        'item_name',
        'inventory_item_id',
        'default_bahan_baku',
    ];

    public function category()
    {
        return $this->belongsTo(RabCategory::class, 'category_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    // Relasi ke nilai bahan_baku per type
    public function typeValues()
    {
        return $this->hasMany(RabTypeValue::class, 'rab_template_id');
    }

    // Helper: ambil bahan_baku untuk type tertentu
    public function getBahanBakuForType($typeId)
    {
        $typeValue = $this->typeValues()->where('type_id', $typeId)->first();
        return $typeValue ? $typeValue->bahan_baku : ($this->default_bahan_baku ?? 0);
    }
}
