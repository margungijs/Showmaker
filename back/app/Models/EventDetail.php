<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    use HasFactory;

    protected $fillable = ['location', 'event', 'date'];

    protected $hidden = ['event', 'created_at', 'updated_at'];

    public function event_main(){
        return $this->hasMany(Event::class, 'id', 'event');
    }

    public function tickets(){
        return $this->hasMany(EventTicket::class, 'event_detail', 'event');
    }

    public function images(){
        return $this->hasMany(EventImage::class, 'event_id', 'event');
    }

    public function comment(){
        return $this->hasMany(Comment::class, 'event', 'id');
    }

}
