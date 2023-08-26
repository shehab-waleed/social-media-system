<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $likes_num
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostImages> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostLike> $likesPosts
 * @property-read int|null $likes_posts_count
 *
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Post filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLikesNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'body',
        'user_id'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            $query->where('user_id', $user_id);
        });

        $query->when($filters['title'] ?? false, function ($query, $title) {
            $query->where('title', 'like', '%'.$title.'%');
        });

    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(PostImages::class);
    }

    public function likesPosts()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function likes(){
        return $this->morphMany(Like::class, 'parent');
    }
}
