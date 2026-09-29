<?php

namespace App\Http\Controllers;

use App\Models\User;

class SitterController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'CLI') {
            abort(403);
        }

        $sitters = User::query()
            ->where('role', 'PRO')
            ->whereHas('disponibilidades')
            ->with(['fotoUsuario', 'servicos', 'disponibilidades', 'avaliacoesRecebidas'])
            ->orderBy('name')
            ->get();

        return view('sitters.index', compact('sitters'));
    }
}