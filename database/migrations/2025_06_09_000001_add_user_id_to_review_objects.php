<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review_objects', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->unique(['user_id', 'category_id'], 'user_category_unique');
        });
    }

    public function down(): void
    {
        Schema::table('review_objects', function (Blueprint $table) {
            $table->dropUnique('user_category_unique');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
