<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reaction;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'review_object_id',
        'content',
        'rating',
    ];

    /**
     * Отзыв принадлежит пользователю (автор).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отзыв принадлежит объекту (review_object).
     */
    public function object()
    {
        return $this->belongsTo(ReviewObject::class, 'review_object_id');
    }

    /**
     * Комментарии к этому отзыву.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Reactions (likes/dislikes) for this review.
     */
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
