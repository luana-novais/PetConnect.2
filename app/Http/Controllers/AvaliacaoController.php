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
            'id_agendamento' => 'required|exists:agendamentos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentarios' => 'nullable|string|max:500'
        ]);

        // Verificar se o agendamento foi concluído
        $agendamento = Agendamento::findOrFail($request->id_agendamento);
        
        if ($agendamento->status !== 'concluido') {
            return redirect()->back()->with('error', 'Só é possível avaliar agendamentos concluídos.');
        }

        if (auth()->user()->role !== 'CLI' || $agendamento->id_user_cliente !== auth()->id()) {
            abort(403);
        }

        // Cada agendamento pode receber somente uma avaliação do cliente.
        $avaliacaoExistente = Avaliacao::where('id_agendamento', $agendamento->id)->first();

        if ($avaliacaoExistente) {
            return redirect()->back()->with('error', 'Você já avaliou este agendamento.');
        }

        // Criar avaliação
        $avaliacao = Avaliacao::create([
            'id_user' => auth()->id(),
            'id_agendamento' => $request->id_agendamento,
            'nota' => $request->nota,
            'comentarios' => $request->comentarios,
        ]);

        return redirect()->route('agendamentos.show', $request->id_agendamento)
            ->with('success', "Avaliação registrada com sucesso! Nota: {$request->nota}/5 ⭐");
    }
}
