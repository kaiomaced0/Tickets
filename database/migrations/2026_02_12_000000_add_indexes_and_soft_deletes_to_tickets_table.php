<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->softDeletes();
            $table->index('status');
            $table->index('prioridade');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
            $table->dropIndex(['prioridade']);
        });
    }
};
