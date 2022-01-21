<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class User extends Model
{
    use HasFactory;
    protected $primaryKey = 'userId';
    protected $fillable = ['nama', 'username', 'password', 'role', 'konterId', 'status'];
    protected $hidden = ['password', 'konterId'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function konter()
    {
        return $this->belongsTo(Konter::class, 'konterId');
    }
}
