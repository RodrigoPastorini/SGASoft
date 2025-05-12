<?php

namespace Database\Seeders;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pedido = Pedido::first();
        $produtos = Produto::all();

        $valorTotal = 0;

        foreach ($produtos as $produto) {
            $quantidade = rand(1, 3);
            $subtotal = $produto->preco * $quantidade;

            ItemPedido::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $produto->id,
                'valor_unitario' => $produto->preco,
                'quantidade' => $quantidade,
                'subtotal' => $subtotal,
            ]);

            $valorTotal += $subtotal;
        }

        $pedido->update(['valor_total' => $valorTotal]);
    }
}
