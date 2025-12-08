<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabCategory extends Model
{
    protected $table = 'rab_categories';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function templates()
    {
        return $this->hasMany(RabTemplate::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(RabItem::class, 'rab_category_id');
    }

    public function borongans()
    {
        return $this->hasMany(RabCategoryBorongan::class, 'rab_category_id');
    }
}
