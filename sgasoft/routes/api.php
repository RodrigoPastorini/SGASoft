<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedidoController;

Route::prefix('v1')->group(function () {
    Route::post('autenticacao', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('fornecedor/{cnpj}/pedidos', [PedidoController::class, 'pedidosPorFornecedor']);
        Route::get('pedidos/{id}', [PedidoController::class, 'show']);
        Route::post('pedidos', [PedidoController::class, 'store']);
        Route::put('pedidos', [PedidoController::class, 'updateApi']);
        Route::delete('pedidos', [PedidoController::class, 'destroy']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
