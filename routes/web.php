<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// Rotas de Autenticação (sem middleware de autenticação)
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Rotas protegidas (requerem autenticação)
Route::middleware('auth')->group(function () {
    Route::resource('/pets', PetController::class);
    Route::resource('/agendamentos', AgendamentoController::class);
    Route::resource('/servicos', ServicoController::class);

    // Rotas AJAX para busca de sitters
    Route::get('/agendamentos/search/sitters', [AgendamentoController::class, 'searchSittersByLocation'])->name('agendamentos.searchSitters');
    Route::get('/agendamentos/availability/sitter', [AgendamentoController::class, 'getSitterAvailability'])->name('agendamentos.getSitterAvailability');

    // Rota para cancelar agendamento (F_F04)
    Route::put('/agendamentos/{id}/cancel', [AgendamentoController::class, 'cancel'])->name('agendamentos.cancel');

    // Rotas para avaliação (F_F05)
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');

    Route::get('/pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index');
    Route::get('/agendamentos/{id}/pagar', [PagamentoController::class, 'create'])->name('pagamentos.create');
    Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
    Route::get('/pagamentos/{id}', [PagamentoController::class, 'show'])->name('pagamentos.show');
    Route::get('/pagamentos/{id}/edit', [PagamentoController::class, 'edit'])->name('pagamentos.edit');
    Route::put('/pagamentos/{id}', [PagamentoController::class, 'update'])->name('pagamentos.update');
    Route::delete('/pagamentos/{id}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');
    Route::resource('/users', UserController::class);
    Route::resource('/users.show', UserController::class);
});

// Rota raiz - redireciona para dashboard se autenticado, senão para login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('welcome');
    }
    return redirect()->route('auth.login');
})->name('home');

// Rota de boas-vindas/dashboard
Route::get('/dashboard', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');