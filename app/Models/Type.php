<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['nama'];

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_types');
    }
}
