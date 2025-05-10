<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $fillable = ['fornecedor_id', 'data', 'valor_total'];

    public function itens()
    {
        return $this->hasMany(\App\Models\ItemPedido::class);
    }
}
