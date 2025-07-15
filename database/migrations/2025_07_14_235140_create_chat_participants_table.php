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
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id'); // Chat
            $table->unsignedBigInteger('user_id'); // Usuário participante
            $table->timestamp('joined_at')->nullable(); // Data que entrou no chat
            $table->timestamp('left_at')->nullable(); // Data que saiu do chat
            $table->timestamp('last_read_at')->nullable(); // Data da última leitura
            $table->boolean('is_admin')->default(false); // Se é admin do grupo
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['chat_id', 'user_id']);
            $table->index(['user_id', 'chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_participants');
    }
};
