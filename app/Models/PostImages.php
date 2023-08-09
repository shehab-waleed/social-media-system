<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImages extends Model
{
    use HasFactory;

    public $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}