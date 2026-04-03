<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::resource('/pets', PetController::class);
Route::resource('/servicos', ServicoController::class);
Route::resource('/user', UserController::class);
Route::resource('/user/edit', UserController::class);