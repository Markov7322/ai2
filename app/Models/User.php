<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Conversation;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    /**
     * Все беседы пользователя.
     */
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }
}
