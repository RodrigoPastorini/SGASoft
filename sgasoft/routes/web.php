<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\PedidoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return Inertia::render('Admin/Dashboard');
    }

    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
    Route::post('/fornecedores', [FornecedorController::class, 'store'])->name('fornecedores.store');
    Route::put('/fornecedores/{fornecedor}', [FornecedorController::class, 'update'])->name('fornecedores.update');
    Route::delete('/fornecedores/{fornecedor}', [FornecedorController::class, 'destroy'])->name('fornecedores.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/produtos', [ProdutosController::class, 'index'])->name('produtos.index');
    Route::post('/produtos', [ProdutosController::class, 'store'])->name('produtos.store');
    Route::delete('/produtos/{produto}', [ProdutosController::class, 'destroy'])->name('produtos.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::put('/pedidos/{pedido}', [PedidoController::class, 'updateWeb'])->name('pedidos.update');
});


require __DIR__.'/auth.php';
