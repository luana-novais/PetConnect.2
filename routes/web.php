<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::resource('/pets',PetController::class);
Route::resource('/agendamentos',AgendamentoController::class);
Route::resource('/servicos',ServicoController::class);
// Route::resource('/users',UserController::class);