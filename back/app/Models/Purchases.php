<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'event'];

    public function events()
    {
        return $this->hasMany(EventDetail::class, 'id', 'event');
    }
}
