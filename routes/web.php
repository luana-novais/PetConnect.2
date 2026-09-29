<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisponibilidadeController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\RacaController;
use App\Http\Controllers\SitterController;

// Rotas de Autenticação (sem middleware de autenticação)
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');


// Rotas protegidas (requerem autenticação)
Route::middleware('auth')->group(function () {

    Route::resource('/pets', PetController::class);
    Route::resource('/servicos', ServicoController::class);
    Route::resource('/especies', EspecieController::class);
    Route::get('/racas/por-especie/{especieId}', [RacaController::class, 'porEspecie'])->name('racas.porEspecie');
    Route::resource('/racas', RacaController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/disponibilidades', DisponibilidadeController::class);
    Route::get('/sitters', [SitterController::class, 'index'])->name('sitters.index');

    // Rotas AJAX de agendamento — DEVEM vir antes do resource para não
    // serem interceptadas como {agendamento} pelo Laravel
    Route::get('/agendamentos/search/sitters', [AgendamentoController::class, 'searchSittersByLocation'])->name('agendamentos.searchSitters');
    Route::get('/agendamentos/availability/sitter', [AgendamentoController::class, 'getSitterAvailability'])->name('agendamentos.getSitterAvailability');

    // Ações personalizadas de agendamento
    Route::put('/agendamentos/{id}/cancel', [AgendamentoController::class, 'cancel'])->name('agendamentos.cancel');
    Route::put('/agendamentos/{id}/confirm', [AgendamentoController::class, 'confirm'])->name('agendamentos.confirm');
    Route::put('/agendamentos/{id}/complete', [AgendamentoController::class, 'complete'])->name('agendamentos.complete');
    Route::post('/agendamentos/{id}/fotos', [AgendamentoController::class, 'uploadFotos'])->name('agendamentos.fotos');

    Route::resource('/agendamentos', AgendamentoController::class);

    // Avaliações
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');

    // Pagamentos
    Route::get('/pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index');
    Route::get('/agendamentos/{id}/pagar', [PagamentoController::class, 'create'])->name('pagamentos.create');
    Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
    Route::get('/pagamentos/{id}', [PagamentoController::class, 'show'])->name('pagamentos.show');
    Route::get('/pagamentos/{id}/edit', [PagamentoController::class, 'edit'])->name('pagamentos.edit');
    Route::put('/pagamentos/{id}', [PagamentoController::class, 'update'])->name('pagamentos.update');
    Route::delete('/pagamentos/{id}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');
});

// Rota de landing page pública
Route::get('/landing', function () {
    return view('landing');
})->name('landing');

// Rota raiz — redireciona para dashboard se autenticado ou para a landing page pública
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('welcome');
    }
    return redirect()->route('landing');
})->name('home');

// Rota de boas-vindas/dashboard
Route::get('/dashboard', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');