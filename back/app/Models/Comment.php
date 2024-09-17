<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['comment', 'user', 'user_id', 'event', 'rating'];

    protected $hidden = ['user_id'];
}
