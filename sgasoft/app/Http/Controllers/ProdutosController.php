<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdutosController extends Controller
{
    public function index(Request $request) {
        $query = Produto::with('fornecedor');

        if ($request->has('fornecedor_id') && $request->fornecedor_id) {
            $query->where('fornecedor_id', $request->fornecedor_id);
        }

        return Inertia::render('Produtos/Index', [
            'produtos' => $query->get(),
            'fornecedores' => Fornecedor::all(),
            'filtroFornecedor' => $request->fornecedor_id,
        ]);
    }


    public function store(Request $request) {
        $request->validate([
            'nome' => 'required',
            'preco' => 'required',
            'cor' => 'required',
            'referencia' => 'required',
            'fornecedor_id' => 'required'
        ]);

        try {

            Produto::create($request->all());

            return redirect()->back()->with('success', 'Produto criado com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Erro ao criar Produto!');
        }
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->back()->with('success', 'Produto removido com sucesso!');
    }
}
