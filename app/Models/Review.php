<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reaction;
use App\Models\Category;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'pros',
        'cons',
        'rating',
        'image_path',
        'status',
    ];

    /**
     * Отзыв принадлежит пользователю (автор).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отзыв принадлежит категории.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
