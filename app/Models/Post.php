<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $guarded = [];


    public function scopeFilter($query , array $filters){
        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            $query->where('user_id', $user_id);
        });

        $query->when($filters['title'] ?? false, function ($query, $title) {
            $query->where('title', 'like', '%' . $title . '%');
        });

    }

    public function author()  {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function images(){
        return $this->hasMany(PostImages::class);
    }
    public function likesPosts(){
        return $this->hasMany(PostLike::class,'post_id');
    }

}
