<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\User;
use App\Models\Pet;
use App\Models\Servico;
use App\Models\Disponibilidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    public function index()
    {
        $agendamentos = Agendamento::with(['cliente','pet','sitter','servico'])->get();
        return view('agendamentos.index', compact('agendamentos'));
    }

    public function create()
    {
        // Obter apenas os pets do usuário autenticado
        $clientes = Auth::user() ? [Auth::user()] : [];
        $pets = Auth::user() ? Auth::user()->pets : [];
        $servicos = Servico::all();

        return view('agendamentos.create', compact('clientes', 'pets', 'servicos'));
    }

    /**
     * Busca sitters por localização (AJAX)
     */
    public function searchSittersByLocation(Request $request)
    {
        $cidade = $request->input('cidade');
        $estado = $request->input('estado');

        $sitters = User::whereHas('endereco', function ($query) use ($cidade, $estado) {
            if ($cidade) {
                $query->where('cidade', 'like', '%' . $cidade . '%');
            }
            if ($estado) {
                $query->where('estado', 'like', '%' . $estado . '%');
            }
        })
        ->with('endereco')
        ->get()
        ->map(function ($sitter) {
            return [
                'id' => $sitter->id,
                'name' => $sitter->name,
                'cidade' => $sitter->endereco->cidade ?? 'N/A',
                'estado' => $sitter->endereco->estado ?? 'N/A',
                'endereco' => $sitter->endereco->endereco ?? 'N/A',
            ];
        });

        return response()->json($sitters);
    }

    /**
     * Obtém disponibilidade do sitter (AJAX)
     */
    public function getSitterAvailability(Request $request)
    {
        $sitterId = $request->input('sitter_id');

        $disponibilidades = Disponibilidade::where('id_user', $sitterId)
            ->get()
            ->map(function ($disp) {
                $daysMap = [
                    'segunda' => 'Segunda-feira',
                    'terca' => 'Terça-feira',
                    'quarta' => 'Quarta-feira',
                    'quinta' => 'Quinta-feira',
                    'sexta' => 'Sexta-feira',
                    'sabado' => 'Sábado',
                    'domingo' => 'Domingo',
                ];
                return [
                    'dia_semana' => $daysMap[$disp->dia_semana] ?? $disp->dia_semana,
                    'hora_inicio' => $disp->hora_inicio,
                    'hora_fim' => $disp->hora_fim,
                ];
            });

        return response()->json([
            'disponibilidades' => $disponibilidades,
            'message' => count($disponibilidades) > 0 ? 'Disponibilidade carregada com sucesso' : 'Nenhuma disponibilidade encontrada para este sitter'
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'id_pet' => 'required|exists:pets,id',
        'id_user_sitter' => 'required|exists:users,id',
        'id_servico' => 'required|exists:servicos,id',
        'data_inicio' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'data_fim' => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
        'observacoes' => 'nullable|string|max:1000'
    ], [
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

    // usuário autenticado será o cliente
    $data['id_user_cliente'] = Auth::id();

    // status padrão
    $data['status'] = 'pendente';

    Agendamento::create($data);

    return redirect()
        ->route('agendamentos.index')
        ->with('success', 'Agendamento criado com sucesso!');
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

    /**
     * Cancelar agendamento (F_F04)
     */
    public function cancel($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        
        // Verificar se o agendamento já foi cancelado ou concluído
        if ($agendamento->status === 'cancelado' || $agendamento->status === 'concluido') {
            return redirect()->back()->with('error', 'Este agendamento não pode ser cancelado.');
        }

        // Cancelar o agendamento
        $agendamento->update(['status' => 'cancelado']);

        return redirect()->route('agendamentos.show', $agendamento->id)->with('success', 'Agendamento cancelado com sucesso! A data e horário foram liberados para o pet sitter.');
    }
}