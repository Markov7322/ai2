<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // если есть другие поля, добавьте их сюда
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Отзывы, которые оставил пользователь.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Комментарии, которые оставил пользователь.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
