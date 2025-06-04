<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->timestamps();
            $table->unique(['user_id', 'review_id'], 'user_review_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
