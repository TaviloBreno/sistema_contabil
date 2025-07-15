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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id'); // Chat ao qual a mensagem pertence
            $table->unsignedBigInteger('user_id'); // Usuário que enviou a mensagem
            $table->text('message'); // Conteúdo da mensagem
            $table->enum('type', ['text', 'file', 'image'])->default('text'); // Tipo da mensagem
            $table->string('file_path')->nullable(); // Caminho do arquivo (se for file/image)
            $table->string('file_name')->nullable(); // Nome original do arquivo
            $table->boolean('is_read')->default(false); // Mensagem foi lida
            $table->timestamp('read_at')->nullable(); // Data/hora da leitura
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['chat_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
