<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('solicitante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo', 120);
            $table->text('descricao');
            $table->enum('status', ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'])->default('ABERTO');
            $table->enum('prioridade', ['BAIXA', 'MEDIA', 'ALTA'])->default('MEDIA');
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
