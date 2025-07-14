<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->string('tipo')->default('text');
            $table->string('grupo')->nullable();
            $table->text('descricao')->nullable();
            $table->unsignedInteger('ordem')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'grupo', 'descricao', 'ordem']);
        });
    }
};
