<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'event_id', 'main'];

    protected $hidden = ['event_id', 'created_at', 'updated_at', 'id'];
}
