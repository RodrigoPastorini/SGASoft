<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'preco',
        'cor',
        'referencia',
        'fornecedor_id',
    ];

    public function itens()
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

}
