<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\Fornecedor;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $fornecedor = Fornecedor::first();

        Produto::create([
            'fornecedor_id' => $fornecedor->id,
            'referencia' => 'REF123',
            'nome' => 'Produto A',
            'cor' => 'Vermelho',
            'preco' => 150.00
        ]);

        Produto::create([
            'fornecedor_id' => $fornecedor->id,
            'referencia' => 'REF456',
            'nome' => 'Produto B',
            'cor' => 'Azul',
            'preco' => 250.00
        ]);
    }
}
