<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('title')->nullable()->after('category_id');
            $table->text('pros')->nullable()->after('rating');
            $table->text('cons')->nullable()->after('pros');
            $table->string('status')->default('pending')->after('cons');
            $table->unique(['user_id', 'category_id'], 'user_category_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('user_category_unique');
            $table->dropColumn(['title', 'pros', 'cons', 'status']);
        });
    }
};
