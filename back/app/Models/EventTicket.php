<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'ticket_count', 'event_detail'];

    protected $hidden = ['created_at', 'updated_at', 'id', 'laravel_through_key'];
}
