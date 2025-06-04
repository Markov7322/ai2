<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'review_id',
        'parent_id',
        'content',
        'image_path',
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

    /**
     * Parent comment (if this is a reply).
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Replies for this comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
