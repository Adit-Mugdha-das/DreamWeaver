<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dreams', function (Blueprint $table) {
            // Store the normalized emotion category for avatar/totem mapping
            $table->string('emotion_category')->nullable()->after('emotion_summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dreams', function (Blueprint $table) {
            $table->dropColumn('emotion_category');
        });
    }
};
