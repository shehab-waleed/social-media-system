<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'post_id',
        'parent_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likesComments()
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }

    public function likes(){
        return $this->morphMany(Like::class , 'parent');
    }
}
