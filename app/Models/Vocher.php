<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vocher extends Model
{
    use HasFactory;

    protected $primaryKey = 'vocherId';
    protected $fillable = ['nama', 'status'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function produkVocher()
    {
        return $this->hasMany(ProdukVocher::class, 'vocherId');
    }
}
