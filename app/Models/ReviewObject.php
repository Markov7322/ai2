<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewObject extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'description',
        'image_path',
        'status',
        'avg_rating',
        'reviews_count'
    ];

    /**
     * Обратная связь: объект “принадлежит” категории.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Owner of the object.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь: у объекта может быть много отзывов.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'review_object_id');
    }
}
