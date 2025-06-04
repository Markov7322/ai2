<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title'];

    /**
     * Одна категория может иметь много объектов (review_objects).
     */
    public function objects()
    {
        return $this->hasMany(ReviewObject::class);
    }
}
