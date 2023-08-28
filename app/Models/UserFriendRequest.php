<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFriendRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'friend_id', 'status'];

    protected $table = 'user_friend_requests';

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
