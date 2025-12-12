<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    protected $fillable = [
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

    // RELASI
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
