<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Agendamento;
use App\Models\User;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * Criar avaliação (F_F05)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_agendamento' => 'required|exists:agendamentos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentarios' => 'nullable|string|max:500'
        ]);

        // Verificar se o agendamento foi concluído
        $agendamento = Agendamento::findOrFail($request->id_agendamento);
        
        if ($agendamento->status !== 'concluido') {
            return redirect()->back()->with('error', 'Só é possível avaliar agendamentos concluídos.');
        }

        // Verificar se já existe avaliação para este agendamento pelo usuário
        $avaliacaoExistente = Avaliacao::where('id_agendamento', $request->id_agendamento)
            ->where('id_user', auth()->id()) // O usuário logado faz a avaliação
            ->first();

        if ($avaliacaoExistente) {
            return redirect()->back()->with('error', 'Você já avaliou este agendamento.');
        }

        // Criar avaliação
        $avaliacao = Avaliacao::create([
            'id_user' => $request->id_user,
            'id_agendamento' => $request->id_agendamento,
            'nota' => $request->nota,
            'comentarios' => $request->comentarios,
        ]);

        // Calcular média de avaliações do usuário (Caso de Uso F_F05)
        $user = User::findOrFail($request->id_user);
        $avaliacoes = Avaliacao::where('id_user', $user->id)->get();
        
        if ($avaliacoes->count() > 0) {
            $media = $avaliacoes->avg('nota');
            // Você pode armazenar a média em um campo da tabela users se desejar
            // $user->update(['media_avaliacoes' => $media]);
        }

        return redirect()->route('agendamentos.show', $request->id_agendamento)
            ->with('success', "Avaliação registrada com sucesso! Nota: {$request->nota}/5 ⭐");
    }
}
