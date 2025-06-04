<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Если полей ещё нет, добавляем их:
            if (! Schema::hasColumn('reviews', 'content')) {
                $table->text('content')->after('review_object_id');
            }
            if (! Schema::hasColumn('reviews', 'rating')) {
                $table->tinyInteger('rating')->default(5)->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('reviews', 'content')) {
                $table->dropColumn('content');
            }
        });
    }
};
