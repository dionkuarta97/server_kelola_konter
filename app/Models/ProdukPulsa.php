<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukPulsa extends Model
{
    use HasFactory;
    protected $primaryKey = 'produkPulsaId';
    protected $fillable = ['nama_produk', 'status', 'pulsaId', 'harga'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function pulsa()
    {
        return $this->belongsTo(Pulsa::class, 'pulsaId');
    }
}
