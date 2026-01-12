<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angkutan extends Model
{
    protected $table = 'angkatans';

    protected $fillable = [
        'tanggal',
        'kode',
        'lokasi',
        'angkutan',
        'jumlah',
        'pangkalan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer',
    ];
}
