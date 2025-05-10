<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fornecedor;

class FornecedorSeeder extends Seeder
{
    public function run(): void
    {
        Fornecedor::create([
            'nome' => 'Fornecedor Exemplo',
            'cnpj' => '12345678000190',
            'cep' => '12345-678',
            'endereco' => 'Rua Exemplo, 123',
            'ativo' => true,
        ]);
    }
}
