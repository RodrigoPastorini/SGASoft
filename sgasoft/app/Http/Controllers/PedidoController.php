<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function pedidosPorFornecedor($cnpj, Request $request)
    {
        dd($request);
        $fornecedor = Fornecedor::where('cnpj', $cnpj)->firstOrFail();

        $query = Pedido::where('fornecedor_id', $fornecedor->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('data')) {
            $query->whereDate('created_at', $request->data);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $pedido = Pedido::findOrFail($id);
        return response()->json($pedido);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fornecedor_id' => 'required|exists:fornecedors,id',
            'descricao' => 'required|string',
            'status' => 'required|string',
        ]);

        $pedido = Pedido::create($data);
        return response()->json($pedido, 201);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:pedidos,id',
            'descricao' => 'string',
            'status' => 'string',
        ]);

        $pedido = Pedido::findOrFail($data['id']);
        $pedido->update($data);
        return response()->json($pedido);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pedidos,id',
        ]);

        Pedido::destroy($request->id);
        return response()->json(['message' => 'Pedido deletado com sucesso']);
    }
}
