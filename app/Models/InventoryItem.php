<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'nama',
        'satuan',
        'harga',
        'keterangan',
        'stok_awal',
    ];

    // Relasi masuk
    public function inventoryIns()
    {
        return $this->hasMany(InventoryIn::class, 'inventory_item_id');
    }

    // Relasi keluar
    public function inventoryOuts()
    {
        return $this->hasMany(InventoryOut::class, 'inventory_item_id');
    }

    // Alias untuk relasi (backward compatibility)
    public function masuk()
    {
        return $this->inventoryIns();
    }

    public function keluar()
    {
        return $this->inventoryOuts();
    }

    // Hitung stok otomatis (stok dari transaksi saja)
    public function getStokAttribute()
    {
        $masuk  = $this->inventoryIns()->sum('jumlah_masuk');
        $keluar = $this->inventoryOuts()->sum('jumlah_keluar');

        return $masuk - $keluar;
    }

    // Hitung stok akhir (stok_awal + masuk - keluar)
    public function getStokAkhirAttribute()
    {
        $masuk  = $this->inventoryIns()->sum('jumlah_masuk');
        $keluar = $this->inventoryOuts()->sum('jumlah_keluar');

        return $this->stok_awal + $masuk - $keluar;
    }

    // Method alternatif (tetap dipertahankan untuk compatibility)
    public function hitungStokAkhir()
    {
        return $this->stok_akhir;
    }
}
