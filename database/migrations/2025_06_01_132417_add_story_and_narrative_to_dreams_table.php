<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('dreams', function (Blueprint $table) {
        $table->text('story_generation')->nullable()->after('short_interpretation');
        $table->text('long_narrative')->nullable()->after('story_generation');
    });
}

    /**
     * Reverse the migrations.
     */
    
public function down()
{
    Schema::table('dreams', function (Blueprint $table) {
        $table->dropColumn(['story_generation', 'long_narrative']);
    });
}
};
