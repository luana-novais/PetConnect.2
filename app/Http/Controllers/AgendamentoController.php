<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\User;
use App\Models\Pet;
use App\Models\Servico;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function index()
    {
        $agendamentos = Agendamento::with(['cliente','pet','sitter','servico'])->get();
        return view('agendamentos.index', compact('agendamentos'));
    }

    public function create()
    {
        $clientes = User::all();
        $sitters = User::all();
        $pets = Pet::all();
        $servicos = Servico::all();

        return view('agendamentos.create', compact('clientes','sitters','pets','servicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user_cliente' => 'required|exists:users,id',
            'id_pet' => 'required|exists:pets,id',
            'id_user_sitter' => 'required|exists:users,id',
            'id_servico' => 'required|exists:servicos,id',
            'data_inicio' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'data_fim' => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
            'observacoes' => 'nullable|string|max:1000'
        ], [
            'id_user_cliente.required' => 'Selecione um cliente.',
            'id_user_cliente.exists' => 'O cliente selecionado não existe.',
            'id_pet.required' => 'Selecione um pet.',
            'id_pet.exists' => 'O pet selecionado não existe.',
            'id_user_sitter.required' => 'Selecione um sitter.',
            'id_user_sitter.exists' => 'O sitter selecionado não existe.',
            'id_servico.required' => 'Selecione um serviço.',
            'id_servico.exists' => 'O serviço selecionado não existe.',
            'data_inicio.required' => 'Informe a data e hora de início.',
            'data_inicio.date_format' => 'Formato de data/hora inválido.',
            'data_inicio.after_or_equal' => 'A data/hora de início não pode ser no passado.',
            'data_fim.required' => 'Informe a data e hora de término.',
            'data_fim.date_format' => 'Formato de data/hora inválido.',
            'data_fim.after' => 'A data/hora de término deve ser depois do início.',
        ]);

        $data = $request->all();
        $data['status'] = 'pendente';
        $data['data_agendamento'] = date('Y-m-d');
        
        Agendamento::create($data);

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento criado com sucesso!');
    }

    public function show($id)
    {
        $agendamento = Agendamento::with(['cliente','pet','sitter','servico'])->findOrFail($id);
        return view('agendamentos.show', compact('agendamento'));
    }

    public function edit($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $clientes = User::all();
        $sitters = User::all();
        $pets = Pet::all();
        $servicos = Servico::all();

        return view('agendamentos.edit', compact('agendamento','clientes','sitters','pets','servicos'));
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $request->validate([
            'id_user_cliente' => 'required|exists:users,id',
            'id_pet' => 'required|exists:pets,id',
            'id_user_sitter' => 'required|exists:users,id',
            'id_servico' => 'required|exists:servicos,id',
            'data_inicio' => 'required|date_format:Y-m-d\TH:i',
            'data_fim' => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
            'observacoes' => 'nullable|string|max:1000'
        ], [
            'id_user_cliente.required' => 'Selecione um cliente.',
            'id_user_cliente.exists' => 'O cliente selecionado não existe.',
            'id_pet.required' => 'Selecione um pet.',
            'id_pet.exists' => 'O pet selecionado não existe.',
            'id_user_sitter.required' => 'Selecione um sitter.',
            'id_user_sitter.exists' => 'O sitter selecionado não existe.',
            'id_servico.required' => 'Selecione um serviço.',
            'id_servico.exists' => 'O serviço selecionado não existe.',
            'data_inicio.required' => 'Informe a data e hora de início.',
            'data_inicio.date_format' => 'Formato de data/hora inválido.',
            'data_fim.required' => 'Informe a data e hora de término.',
            'data_fim.date_format' => 'Formato de data/hora inválido.',
            'data_fim.after' => 'A data/hora de término deve ser depois do início.',
        ]);

        $agendamento->update($request->all());

        return redirect()->route('agendamentos.show', $agendamento->id)->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento deletado com sucesso!');
    }
}