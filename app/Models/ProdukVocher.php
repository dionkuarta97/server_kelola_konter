<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukVocher extends Model
{
    use HasFactory;
    protected $primaryKey = 'produkVocherId';
    protected $fillable = ['nama', 'vocherId', 'harga', 'modal', 'stock', 'status'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function vocher()
    {
        return $this->belongsTo(Vocher::class, 'vocherId');
    }
}
