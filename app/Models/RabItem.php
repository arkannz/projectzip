<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabItem extends Model
{
    protected $fillable = [
        'rab_category_id',
        'type_id',
        'unit_id',
        'location_id',
        'uraian',
        'bahan_baku',
        'bahan_out',
        'harga_bahan',
        'total_harga',
        'upah',
        'borongan',
        'untung_rugi',
        'progres',
    ];

    public function category()
    {
        return $this->belongsTo(RabCategory::class, 'rab_category_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
