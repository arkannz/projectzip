<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabCategoryBorongan extends Model
{
    protected $table = 'rab_category_borongans';

    protected $fillable = [
        'rab_category_id',
        'type_id',
        'unit_id',
        'location_id',
        'borongan',
        'upah',
        'progress',
    ];

    protected $casts = [
        'borongan' => 'decimal:2',
        'upah' => 'decimal:2',
        'progress' => 'integer',
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
