<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabTypeValue extends Model
{
    protected $table = 'rab_type_values';

    protected $fillable = [
        'type_id',
        'rab_template_id',
        'bahan_baku',
    ];

    protected $casts = [
        'bahan_baku' => 'decimal:2',
    ];

    public function template()
    {
        return $this->belongsTo(RabTemplate::class, 'rab_template_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
