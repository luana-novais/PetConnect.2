<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AgendamentoController;
use Illuminate\Http\Request;

Route::resource('/pets',PetController::class);
Route::resource('/agendamentos',AgendamentoController::class);