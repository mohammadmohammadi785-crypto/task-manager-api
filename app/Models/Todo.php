<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory, HasApiTokens;

    function images(){
        return $this->hasMany(Image::class, 'todo_id');
    }

    function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
