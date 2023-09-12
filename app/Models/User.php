<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'country',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // protected function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function postsLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function commentsLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function otp()
    {
        return $this->hasOne(OTP::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function followed()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(UserFriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(UserFriendRequest::class, 'receiver_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'user_id', 'friend_id')
            ->withTimestamps();
    }
}
