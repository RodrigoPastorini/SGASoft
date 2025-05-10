<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Fornecedor;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        $fornecedor = Fornecedor::first();

        Pedido::create([
            'fornecedor_id' => $fornecedor->id,
            'data' => now()->subDays(3)->toDateString(),
            'valor_total' => 300.00,
            'observacao' => 'Primeiro pedido',
            'status' => 'pendente'
        ]);

        Pedido::create([
            'fornecedor_id' => $fornecedor->id,
            'data' => now()->subDays(2)->toDateString(),
            'valor_total' => 400.00,
            'observacao' => 'Pedido urgente',
            'status' => 'concluido'
        ]);

        Pedido::create([
            'fornecedor_id' => $fornecedor->id,
            'data' => now()->subDay()->toDateString(),
            'valor_total' => 100.00,
            'observacao' => 'Cancelado pelo cliente',
            'status' => 'cancelado'
        ]);
    }
}
