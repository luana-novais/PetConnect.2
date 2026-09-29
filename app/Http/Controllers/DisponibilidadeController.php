<?php

namespace App\Http\Controllers;

use App\Models\Disponibilidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DisponibilidadeController extends Controller
{
    public function index(Request $request)
    {
        $query = Disponibilidade::with('user');

        if (auth()->user()->role !== 'ADM') {
            $query->where('id_user', auth()->id());
        }

        if ($request->filled('dia_semana')) {
            $query->where('dia_semana', $request->dia_semana);
        }

        $disponibilidades = $query->paginate(10)->withQueryString();

        return view('disponibilidades.index', compact('disponibilidades'));
    }

    public function create()
    {
        return view('disponibilidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
        ], [
            'dia_semana.required' => 'Selecione um dia da semana.',
            'hora_inicio.required' => 'Informe o horário de início.',
            'hora_inicio.date_format' => 'O horário de início deve estar no formato HH:MM.',
            'hora_fim.required' => 'Informe o horário de término.',
            'hora_fim.date_format' => 'O horário de término deve estar no formato HH:MM.',
            'hora_fim.after' => 'O horário de término deve ser depois do horário de início.',
        ]);

        $data = $request->only(['dia_semana', 'hora_inicio', 'hora_fim']);
        $data['id_user'] = auth()->id();

        try {
            Disponibilidade::create($data);
        } catch (Exception $e) {
            Log::error('Erro ao salvar disponibilidade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao salvar disponibilidade.')->withInput();
        }

        return redirect()->route('disponibilidades.index')->with('success', 'Disponibilidade adicionada.');
    }

    public function edit($id)
    {
        $disp = Disponibilidade::findOrFail($id);
        if (auth()->user()->role !== 'ADM' && $disp->id_user !== auth()->id()) {
            abort(403);
        }
        return view('disponibilidades.edit', compact('disp'));
    }

    public function update(Request $request, $id)
    {
        $disp = Disponibilidade::findOrFail($id);
        if (auth()->user()->role !== 'ADM' && $disp->id_user !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
        ], [
            'dia_semana.required' => 'Selecione um dia da semana.',
            'hora_inicio.required' => 'Informe o horário de início.',
            'hora_inicio.date_format' => 'O horário de início deve estar no formato HH:MM.',
            'hora_fim.required' => 'Informe o horário de término.',
            'hora_fim.date_format' => 'O horário de término deve estar no formato HH:MM.',
            'hora_fim.after' => 'O horário de término deve ser depois do horário de início.',
        ]);

        try {
            $disp->update($request->only(['dia_semana','hora_inicio','hora_fim']));
        } catch (Exception $e) {
            Log::error('Erro ao atualizar disponibilidade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar disponibilidade.')->withInput();
        }

        return redirect()->route('disponibilidades.index')->with('success', 'Disponibilidade atualizada.');
    }

    public function destroy($id)
    {
        $disp = Disponibilidade::findOrFail($id);
        if (auth()->user()->role !== 'ADM' && $disp->id_user !== auth()->id()) {
            abort(403);
        }

        $disp->delete();
        return redirect()->route('disponibilidades.index')->with('success', 'Disponibilidade removida.');
    }
}
