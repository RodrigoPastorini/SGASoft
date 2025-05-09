<?php
namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedor::all();
        return Inertia::render('Fornecedores/Index', compact('fornecedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'cnpj' => 'required|unique:fornecedores,cnpj',
            'cep' => 'required',
            'endereco' => 'required',
        ]);

        Fornecedor::create($request->all());

        return redirect()->back()->with('success', 'Fornecedor criado com sucesso!');
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:fornecedores,email,' . $fornecedor->id,
        ]);

        $fornecedor->update($request->all());

        return redirect()->back()->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();

        return redirect()->back()->with('success', 'Fornecedor removido com sucesso!');
    }
}
