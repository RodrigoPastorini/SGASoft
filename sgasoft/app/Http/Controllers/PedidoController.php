<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\ItemPedido;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PedidoController extends Controller
{
    private const STATUS_PEDIDO = [
        'pendente',
        'concluido',
        'cancelado',
    ];

    public function index(Request $request)
    {
        $pedidos = Pedido::with('fornecedor')->orderByDesc('created_at')->get();
        $fornecedores = Fornecedor::all();
        $produtos = Produto::all();

        return Inertia::render('Pedidos/Index', [
            'pedidos' => $pedidos,
            'fornecedores' => $fornecedores,
            'produtos' => $produtos,
        ]);
    }
    public function pedidosPorFornecedor($cnpj, Request $request)
    {
        try {
            $fornecedor = Fornecedor::where('cnpj', $cnpj)->firstOrFail();

            $query = Pedido::where('fornecedor_id', $fornecedor->id);

            return response()->json($query->get());
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Fornecedor não encontrado',
            ], 404);
        }
    }

    public function show($id)
    {
        $pedido = Pedido::findOrFail($id);
        return response()->json($pedido);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|numeric|min:1',
            'status' => 'required|in:' . implode(',', self::STATUS_PEDIDO),
        ]);
        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'fornecedor_id' => $request['fornecedor_id'],
                'data' => $request['data'],
                'observacao' => $request['observacao'] ?? null,
                'status' => $request['status'] ?? 'pendente',
                'valor_total' => 0,
            ]);


            $produto = Produto::findOrFail($request['produto_id']);

            ItemPedido::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $produto->id,
                'quantidade' => $request['quantidade'],
                'valor_unitario' => $produto->preco,
                'subtotal' => $produto->preco * $request['quantidade'],
            ]);

            $pedido->update([
                'valor_total' => $produto->preco * $request['quantidade']
            ]);

            DB::commit();

            return response()->json($pedido->load('itens'), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao criar o pedido',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()], 500);
        }

    }

    public function updateApi(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|exists:pedidos,id',
                'observacao' => 'string',
                'status' => 'required|in:' . implode(',', self::STATUS_PEDIDO),
            ]);

            $pedido = Pedido::findOrFail($data['id']);
            $pedido->update($data);
            return response()->json($pedido);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erro ao atualizar o pedido',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()], 500);
        }
    }


    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:pedidos,id',
            ]);

            Pedido::destroy($request->id);
            return response()->json(['message' => 'Pedido deletado com sucesso']);

        } catch (ModelNotFoundException $e) {
            return response()->json([]);
        }
    }

    public function storeWeb(Request $request)
    {
        $request->validate([
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|numeric|min:1',
            'status' => 'required|in:' . implode(',', self::STATUS_PEDIDO),
        ]);
        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'fornecedor_id' => $request['fornecedor_id'],
                'data' => $request['date'],
                'observacao' => $request['observacao'] ?? null,
                'status' => $request['status'] ?? 'pendente',
                'valor_total' => 0,
            ]);


            $produto = Produto::findOrFail($request['produto_id']);

            ItemPedido::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $produto->id,
                'quantidade' => $request['quantidade'],
                'valor_unitario' => $produto->preco,
                'subtotal' => $produto->preco * $request['quantidade'],
            ]);

            $pedido->update([
                'valor_total' => $produto->preco * $request['quantidade']
            ]);

            DB::commit();

            return redirect()->route('pedidos.index')->with('success', 'Pedido criado com sucesso!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao criar o pedido',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()], 500);
        }

    }

    public function updateWeb(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:pedidos,id',
            'status' => 'required|in:' . implode(',', self::STATUS_PEDIDO),
            'observacao' => 'nullable|string',
        ]);

        $pedido = Pedido::findOrFail($data['id']);

        $pedido->update($data);

        return redirect()->route('pedidos.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroyWeb($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return redirect()->route('pedidos.index')->with('success', 'Pedido excluído com sucesso!');

    }
}
