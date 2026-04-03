<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use Illuminate\Http\Request;

Route::resource('/pets', PetController::class);