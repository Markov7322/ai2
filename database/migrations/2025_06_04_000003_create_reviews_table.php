<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Ссылка на пользователя, который оставил отзыв
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Ссылка на объект, к которому пишут отзыв
            $table->foreignId('review_object_id')
                  ->constrained('review_objects')
                  ->onDelete('cascade');

            // Текст отзыва (обязательное поле)
            $table->text('content');

            // Рейтинг: допустим, целое число от 1 до 5
            $table->tinyInteger('rating')->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
