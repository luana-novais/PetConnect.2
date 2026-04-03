<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Agendamento;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamento::with(['agendamento'])->get();
        return view('pagamentos.index', compact('pagamentos'));
    }

    public function create($id = null)
    {
        if ($id) {
            $agendamento = Agendamento::with('servico')->findOrFail($id);
            return view('pagamentos.create', compact('agendamento'));
        }
        // Se não tiver agendamento, redireciona para agendamentos
        return redirect()->route('agendamentos.index')->with('warning', 'Selecione um agendamento para criar um pagamento.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_agendamento' => 'required|exists:agendamentos,id',
            'metodo_pagamento' => 'required|in:pix,cartao,dinheiro',
        ]);

        $agendamento = Agendamento::with('servico')->findOrFail($request->id_agendamento);

        $pagamento = Pagamento::create([
            'id_agendamento'   => $agendamento->id,
            'total_servico'    => $agendamento->servico->valor_servico,
            'metodo_pagamento' => $request->metodo_pagamento,
            'status_pagamento' => 'pago',
            'data_pagamento'   => now(),
        ]);

        return redirect()->route('pagamentos.show', $pagamento->id)->with('success', 'Pagamento registrado com sucesso!');
    }

    public function show($id)
    {
        $pagamento = Pagamento::with(['agendamento'])->findOrFail($id);
        return view('pagamentos.show', compact('pagamento'));
    }

    public function edit($id)
    {
        $pagamento = Pagamento::findOrFail($id);
        $agendamentos = Agendamento::all();

        return view('pagamentos.edit', compact('pagamento', 'agendamentos'));
    }

    public function update(Request $request, $id)
    {
        $pagamento = Pagamento::findOrFail($id);

        $request->validate([
            'id_agendamento' => 'required|exists:agendamentos,id',
            'total_servico' => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|in:pix,cartao,dinheiro',
            'status_pagamento' => 'required|in:pendente,pago,estornado',
            'data_pagamento' => 'nullable|date',
        ]);

        $pagamento->update($request->all());

        return redirect()->route('pagamentos.show', $pagamento->id)->with('success', 'Pagamento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $pagamento = Pagamento::findOrFail($id);
        $pagamento->delete();

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento deletado com sucesso!');
    }
}