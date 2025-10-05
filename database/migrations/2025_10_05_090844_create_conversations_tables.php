<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // ✅ conversations table
        Schema::create('conversations', function (Blueprint $t) {
            $t->id();
            $t->string('topic')->nullable(); // e.g., “Dream chat” or “Direct Message”
            $t->timestamps();
        });

        // ✅ pivot table for participants
        Schema::create('conversation_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->unique(['conversation_id', 'user_id']);
            $t->timestamps();
        });

        // ✅ messages table
        Schema::create('messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->text('body');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();

            $t->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_user');
        Schema::dropIfExists('conversations');
    }
};
