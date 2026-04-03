<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::resource('/pets',PetController::class);
Route::resource('/agendamentos',AgendamentoController::class);
Route::resource('/servicos',ServicoController::class);
// Route::resource('/users',UserController::class);

// Rotas de Pagamento
Route::get('/pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index');
Route::get('/agendamentos/{id}/pagar', [PagamentoController::class, 'create'])->name('pagamentos.create');
Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
Route::get('/pagamentos/{id}', [PagamentoController::class, 'show'])->name('pagamentos.show');
Route::get('/pagamentos/{id}/edit', [PagamentoController::class, 'edit'])->name('pagamentos.edit');
Route::put('/pagamentos/{id}', [PagamentoController::class, 'update'])->name('pagamentos.update');
Route::delete('/pagamentos/{id}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');
