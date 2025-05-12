<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $fillable = [
        'fornecedor_id',
        'data',
        'status',
        'valor_total',
        'observacao',
    ];

    public function itens()
    {
        return $this->hasMany(\App\Models\ItemPedido::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}
