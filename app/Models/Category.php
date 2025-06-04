<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'status',
        'parent_id',
    ];

    /**
     * Одна категория может иметь много объектов (review_objects).
     */
    public function objects()
    {
        return $this->hasMany(ReviewObject::class);
    }

    /**
     * Parent category relation.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Children categories relation.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Determine if category has no children.
     */
    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }
}
