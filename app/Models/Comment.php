<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'review_id',
        'content',
    ];

    /**
     * Комментарий принадлежит пользователю.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Комментарий принадлежит отзыву.
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
