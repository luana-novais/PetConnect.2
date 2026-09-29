<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Agendamento;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'ADM') {
            $pagamentos = Pagamento::with(['agendamento.servico', 'agendamento.cliente', 'agendamento.pet'])->get();
        } elseif ($user->role === 'CLI') {
            $pagamentos = Pagamento::with(['agendamento.servico', 'agendamento.cliente', 'agendamento.pet'])
                ->whereHas('agendamento', fn ($q) => $q->where('id_user_cliente', $user->id))
                ->get();
        } else {
            // PRO vê pagamentos dos seus agendamentos
            $pagamentos = Pagamento::with(['agendamento.servico', 'agendamento.cliente', 'agendamento.pet'])
                ->whereHas('agendamento', fn ($q) => $q->where('id_user_sitter', $user->id))
                ->get();
        }

        return view('pagamentos.index', compact('pagamentos'));
    }

    public function create($id = null)
    {
        if (!$id) {
            return redirect()->route('agendamentos.index')
                ->with('warning', 'Selecione um agendamento para criar um pagamento.');
        }

        $agendamento = Agendamento::with('servico')->findOrFail($id);
        $user        = auth()->user();

        if ($user->role !== 'ADM' && $agendamento->id_user_cliente !== $user->id) {
            abort(403);
        }

        return view('pagamentos.create', compact('agendamento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_agendamento'   => 'required|exists:agendamentos,id',
            'metodo_pagamento' => 'required|in:pix,cartao,dinheiro',
        ]);

        $agendamento = Agendamento::with('servico')->findOrFail($request->id_agendamento);
        $user        = auth()->user();

        if ($user->role !== 'ADM' && $agendamento->id_user_cliente !== $user->id) {
            abort(403);
        }

        $pagamento = Pagamento::create([
            'id_agendamento'   => $agendamento->id,
            'total_servico'    => $agendamento->servico->valor_servico,
            'metodo_pagamento' => $request->metodo_pagamento,
            'status_pagamento' => 'pago',
            'data_pagamento'   => now(),
        ]);

        return redirect()->route('pagamentos.show', $pagamento->id)
            ->with('success', 'Pagamento registrado com sucesso!');
    }

    public function show($id)
    {
        $pagamento = Pagamento::with(['agendamento'])->findOrFail($id);
        $user      = auth()->user();

        if (
            $user->role !== 'ADM' &&
            $pagamento->agendamento->id_user_cliente !== $user->id &&
            $pagamento->agendamento->id_user_sitter  !== $user->id
        ) {
            abort(403);
        }

        return view('pagamentos.show', compact('pagamento'));
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'ADM') {
            abort(403);
        }

        $pagamento    = Pagamento::findOrFail($id);
        $agendamentos = Agendamento::all();

        return view('pagamentos.edit', compact('pagamento', 'agendamentos'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'ADM') {
            abort(403);
        }

        $pagamento = Pagamento::findOrFail($id);

        $request->validate([
            'id_agendamento'   => 'required|exists:agendamentos,id',
            'total_servico'    => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|in:pix,cartao,dinheiro',
            'status_pagamento' => 'required|in:pendente,pago,estornado',
            'data_pagamento'   => 'nullable|date',
        ]);

        $pagamento->update($request->only([
            'id_agendamento', 'total_servico',
            'metodo_pagamento', 'status_pagamento', 'data_pagamento',
        ]));

        return redirect()->route('pagamentos.show', $pagamento->id)
            ->with('success', 'Pagamento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'ADM') {
            abort(403);
        }

        $pagamento = Pagamento::findOrFail($id);
        $pagamento->delete();

        return redirect()->route('pagamentos.index')
            ->with('success', 'Pagamento deletado com sucesso!');
    }
}