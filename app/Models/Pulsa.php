<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pulsa extends Model
{
    use HasFactory;

    protected $primaryKey = 'pulsaId';
    protected $fillable = ['nama_pulsa', 'status'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function produkPulsa()
    {
        return $this->hasMany(ProdukPulsa::class, 'pulsaId');
    }
}
