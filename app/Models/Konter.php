<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Konter extends Model
{
    use HasFactory;
    protected $primaryKey = 'konterId';
    protected $fillable = ['nama', 'status'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'konterId');
    }
}
