<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->date('data');
            $table->decimal('valor_total', 10, 2);
            $table->text('observacao')->nullable();
            $table->enum('status', ['pendente', 'concluido', 'cancelado'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
