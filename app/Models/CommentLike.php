<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
    ];

    protected $casts = [
        'comment_id' => 'integer',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
