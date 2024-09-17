<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'host', 'category'];

    protected $hidden = ['created_at', 'updated_at'];

    public function info(){
        return $this->hasMany(EventDetail::class, 'event', 'id');
    }

    public function image(){
        return $this->hasMany(EventImage::class, 'event_id', 'id');
    }

    public function ticket()
    {
        return $this->hasManyThrough(EventTicket::class, EventDetail::class, 'event', 'event_detail', 'id', 'id');
    }
}
