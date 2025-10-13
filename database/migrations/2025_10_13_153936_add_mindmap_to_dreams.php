<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('dreams', function (Blueprint $t) {
        $t->longText('mindmap_md')->nullable();
    });
}
public function down(): void {
    Schema::table('dreams', function (Blueprint $t) {
        $t->dropColumn('mindmap_md');
    });
}

};
