<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases1 extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'event', 'main', 'price'];

    protected $hidden = ['user', 'event', 'main'];

    public function events()
    {
        return $this->hasMany(EventDetail::class, 'id', 'event');
    }

    public function test(){
        return $this->hasMany(Event::class, 'id', 'main');
    }

    public function image(){
        return $this->hasMany(EventImage::class, 'event_id', 'main');
    }
}
