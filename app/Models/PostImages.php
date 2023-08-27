<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PostImages
 *
 * @property int $id
 * @property int $post_id
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post|null $post
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImages whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PostImages extends Model
{
    use HasFactory;

    public $fillable = [
        'post_id',
        'image',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
