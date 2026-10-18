<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\User;
use App\Models\Pet;
use App\Models\Servico;
use App\Models\Disponibilidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\FotoPet;

class AgendamentoController extends Controller
{
    public function index(Request $request)
    {
        $this->concluirAgendamentosExpirados();

        $filters = $request->validate([
            'status' => 'nullable|in:pendente,confirmado,cancelado,concluido',
            'data' => 'nullable|date_format:Y-m-d',
        ]);

        $query = Agendamento::with(['cliente','pet','sitter','servico','pagamento']);

        if (auth()->user()->role === 'ADM') {
            // Administradores podem consultar todos os agendamentos.
        } elseif (auth()->user()->role === 'CLI') {
            $query->where('id_user_cliente', auth()->id());
        } else {
            $query->where('id_user_sitter', auth()->id());
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['data'])) {
            $query->whereDate('data_inicio', $filters['data']);
        }

        $agendamentos = $query->get();

        return view('agendamentos.index', compact('agendamentos'));
    }

    public function confirm($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if (auth()->user()->role !== 'PRO' || $agendamento->id_user_sitter !== auth()->id()) {
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            return redirect()->back()->with('error', 'Apenas agendamentos pendentes podem ser confirmados.');
        }

        $agendamento->update(['status' => 'confirmado']);

        return redirect()->back()->with('success', 'Agendamento confirmado com sucesso!');
    }

    public function complete($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if (auth()->user()->role !== 'PRO' || $agendamento->id_user_sitter !== auth()->id()) {
            abort(403);
        }

        if ($agendamento->status !== Agendamento::STATUS_CONFIRMADO) {
            return redirect()->back()->with('error', 'Apenas agendamentos confirmados podem ser concluídos.');
        }

        if (!$agendamento->data_fim || Carbon::parse($agendamento->data_fim)->isFuture()) {
            return redirect()->back()->with('error', 'O atendimento ainda não terminou.');
        }

        $agendamento->update(['status' => Agendamento::STATUS_CONCLUIDO]);

        return redirect()->back()->with('success', 'Atendimento concluído com sucesso!');
    }

    public function create(Request $request)
    {
        $clientes = Auth::user() ? [Auth::user()] : [];
        $pets = Auth::user() ? Auth::user()->pets : [];
        $selectedSitter = $request->filled('sitter_id')
            ? User::where('role', 'PRO')->find($request->sitter_id)
            : null;

        $servicosQuery = Servico::whereNotNull('id_user')
            ->whereHas('user', fn ($query) => $query->where('role', 'PRO'));

        if ($selectedSitter) {
            $servicosQuery->where('id_user', $selectedSitter->id);
        }

        $servicos = $servicosQuery->orderBy('nome')->get()->unique('nome')->values();
        $oldServiceId = $request->session()->getOldInput('id_servico');
        $selectedServiceName = $oldServiceId
            ? Servico::whereKey($oldServiceId)->value('nome')
            : null;

        return view('agendamentos.create', compact('clientes', 'pets', 'servicos', 'selectedSitter', 'selectedServiceName'));
    }

    public function searchSittersByService(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:servicos,id',
            'sitter_id' => 'nullable|exists:users,id',
        ]);

        $service = Servico::findOrFail($data['service_id']);
        $offerings = Servico::with('user')
            ->where('nome', $service->nome)
            ->whereNotNull('id_user')
            ->whereHas('user', fn ($query) => $query->where('role', 'PRO'))
            ->when($data['sitter_id'] ?? null, fn ($query, $sitterId) => $query->where('id_user', $sitterId))
            ->orderBy('valor_servico')
            ->get()
            ->unique('id_user')
            ->values()
            ->map(fn ($offering) => [
                'service_id' => $offering->id,
                'service_name' => $offering->nome,
                'sitter_id' => $offering->id_user,
                'sitter_name' => $offering->user->name,
                'price' => $offering->valor_servico,
                'duration' => $offering->duracao_minutos ?? 60,
            ]);

        return response()->json($offerings);
    }

    /**
     * Busca sitters por localização (AJAX)
     */
    public function searchSittersByLocation(Request $request)
    {
        $cidade = $request->input('cidade');
        $estado = $request->input('estado');

        $sitters = User::where('role', 'PRO')
            ->whereHas('endereco', function ($query) use ($cidade, $estado) {
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
                    'id'       => $sitter->id,
                    'name'     => $sitter->name,
                    'cidade'   => $sitter->endereco->cidade ?? 'N/A',
                    'estado'   => $sitter->endereco->estado ?? 'N/A',
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
                    'terca'   => 'Terça-feira',
                    'quarta'  => 'Quarta-feira',
                    'quinta'  => 'Quinta-feira',
                    'sexta'   => 'Sexta-feira',
                    'sabado'  => 'Sábado',
                    'domingo' => 'Domingo',
                ];
                return [
                    'dia_semana'  => $daysMap[$disp->dia_semana] ?? $disp->dia_semana,
                    'hora_inicio' => $disp->hora_inicio,
                    'hora_fim'    => $disp->hora_fim,
                ];
            });

        return response()->json([
            'disponibilidades' => $disponibilidades,
            'message'          => count($disponibilidades) > 0
                ? 'Disponibilidade carregada com sucesso'
                : 'Nenhuma disponibilidade encontrada para este sitter',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pet'          => 'required|exists:pets,id',
            'id_user_sitter'  => 'required|exists:users,id',
            'id_servico'      => 'required|exists:servicos,id',
            'data_inicio'     => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'data_fim'        => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
            'observacoes'     => 'nullable|string|max:1000',
        ], [
            'id_pet.required'          => 'Selecione um pet.',
            'id_pet.exists'           => 'O pet selecionado não existe.',
            'id_user_sitter.required' => 'Selecione um sitter.',
            'id_user_sitter.exists'   => 'O sitter selecionado não existe.',
            'id_servico.required'     => 'Selecione um serviço.',
            'id_servico.exists'       => 'O serviço selecionado não existe.',
            'data_inicio.required'    => 'Informe a data e hora de início.',
            'data_inicio.date_format' => 'Formato de data/hora inválido.',
            'data_inicio.after_or_equal' => 'A data/hora de início não pode ser no passado.',
            'data_fim.required'       => 'Informe a data e hora de término.',
            'data_fim.date_format'    => 'Formato de data/hora inválido.',
            'data_fim.after'          => 'A data/hora de término deve ser depois do início.',
        ]);

        $sitter = User::findOrFail($request->id_user_sitter);
        if ($sitter->role !== 'PRO') {
            return redirect()->back()->withInput()->with('error', 'O usuário selecionado não é um pet sitter válido.');
        }

        $servico = Servico::findOrFail($request->id_servico);
        if ((int) $servico->id_user !== (int) $sitter->id) {
            return redirect()->back()->withInput()->with('error', 'O serviço selecionado não é oferecido pelo pet sitter escolhido.');
        }

        $inicio = Carbon::createFromFormat('Y-m-d\TH:i', $request->data_inicio);
        $fim    = Carbon::createFromFormat('Y-m-d\TH:i', $request->data_fim);

        if (!$this->sitterHasAvailability($request->id_user_sitter, $inicio, $fim)) {
            return redirect()->back()->withInput()->with('error', 'O pet sitter não possui disponibilidade para o dia e horário selecionados.');
        }

        if ($this->sitterHasConflict($request->id_user_sitter, $inicio, $fim)) {
            return redirect()->back()->withInput()->with('error', 'Já existe outro agendamento neste horário para o pet sitter selecionado.');
        }

        $data = $request->only([
            'id_pet', 'id_user_sitter', 'id_servico',
            'data_inicio', 'data_fim', 'observacoes',
        ]);
        $data['id_user_cliente'] = Auth::id();
        $data['status']          = 'pendente';

        Agendamento::create($data);

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento criado com sucesso!');
    }

    public function show($id)
    {
        $this->concluirAgendamentosExpirados();
        $agendamento = Agendamento::with(['cliente', 'pet', 'sitter', 'servico', 'avaliacoes.user', 'fotos'])->findOrFail($id);

        $user = auth()->user();
        if (
            $user->role !== 'ADM' &&
            $agendamento->id_user_cliente !== $user->id &&
            $agendamento->id_user_sitter  !== $user->id
        ) {
            abort(403);
        }

        return view('agendamentos.show', compact('agendamento'));
    }

    public function uploadFotos(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if (auth()->user()->role !== 'PRO' || $agendamento->id_user_sitter !== auth()->id()) {
            abort(403);
        }

        if (!in_array($agendamento->status, [Agendamento::STATUS_CONFIRMADO, Agendamento::STATUS_CONCLUIDO], true)) {
            return back()->with('error', 'As fotos só podem ser adicionadas durante um atendimento confirmado ou concluído.');
        }

        $request->validate([
            'fotos' => 'required|array|max:10',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'fotos.required' => 'Selecione pelo menos uma foto.',
            'fotos.max' => 'Você pode enviar no máximo 10 fotos por vez.',
            'fotos.*.image' => 'Todos os arquivos devem ser imagens.',
            'fotos.*.mimes' => 'As fotos devem estar nos formatos JPG ou PNG.',
            'fotos.*.max' => 'Cada foto pode ter no máximo 5 MB.',
        ]);

        foreach ($request->file('fotos', []) as $arquivo) {
            FotoPet::create([
                'id_user' => auth()->id(),
                'id_pet' => $agendamento->id_pet,
                'id_agendamento' => $agendamento->id,
                'nome_arquivo' => $arquivo->getClientOriginalName(),
                'url_foto' => $arquivo->store('fotos/atendimentos', 'public'),
            ]);
        }

        return back()->with('success', 'Fotos do atendimento enviadas com sucesso!');
    }

    public function edit($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->status === Agendamento::STATUS_CONCLUIDO) {
            return redirect()->route('agendamentos.show', $agendamento->id)
                ->with('error', 'Agendamentos finalizados não podem ser alterados.');
        }

        $user = auth()->user();
        if (
            $user->role !== 'ADM' &&
            $agendamento->id_user_cliente !== $user->id &&
            $agendamento->id_user_sitter  !== $user->id
        ) {
            abort(403);
        }

        // ADM vê todos; outros veem apenas o que é relevante para eles
        if ($user->role === 'ADM') {
            $clientes = User::where('role', 'CLI')->get();
            $sitters  = User::where('role', 'PRO')->get();
            $pets     = Pet::all();
        } else {
            $clientes = [$user];
            $sitters  = User::where('role', 'PRO')->get();
            $pets     = $user->pets;
        }

        $servicos = Servico::all();

        return view('agendamentos.edit', compact('agendamento', 'clientes', 'sitters', 'pets', 'servicos'));
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->status === Agendamento::STATUS_CONCLUIDO) {
            return redirect()->route('agendamentos.show', $agendamento->id)
                ->with('error', 'Agendamentos finalizados não podem ser alterados.');
        }

        $user = auth()->user();
        if (
            $user->role !== 'ADM' &&
            $agendamento->id_user_cliente !== $user->id &&
            $agendamento->id_user_sitter  !== $user->id
        ) {
            abort(403);
        }

        $request->validate([
            'id_user_cliente' => 'required|exists:users,id',
            'id_pet'          => 'required|exists:pets,id',
            'id_user_sitter'  => 'required|exists:users,id',
            'id_servico'      => 'required|exists:servicos,id',
            'data_inicio'     => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'data_fim'        => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
            'observacoes'     => 'nullable|string|max:1000',
        ], [
            'id_user_cliente.required' => 'Selecione um cliente.',
            'id_user_cliente.exists'   => 'O cliente selecionado não existe.',
            'id_pet.required'          => 'Selecione um pet.',
            'id_pet.exists'           => 'O pet selecionado não existe.',
            'id_user_sitter.required' => 'Selecione um sitter.',
            'id_user_sitter.exists'   => 'O sitter selecionado não existe.',
            'id_servico.required'     => 'Selecione um serviço.',
            'id_servico.exists'       => 'O serviço selecionado não existe.',
            'data_inicio.required'    => 'Informe a data e hora de início.',
            'data_inicio.date_format' => 'Formato de data/hora inválido.',
            'data_inicio.after_or_equal' => 'A data/hora de início não pode ser no passado.',
            'data_fim.required'       => 'Informe a data e hora de término.',
            'data_fim.date_format'    => 'Formato de data/hora inválido.',
            'data_fim.after'          => 'A data/hora de término deve ser depois do início.',
        ]);

        $sitter = User::findOrFail($request->id_user_sitter);
        if ($sitter->role !== 'PRO') {
            return redirect()->back()->withInput()->with('error', 'O usuário selecionado não é um pet sitter válido.');
        }

        $inicio = Carbon::createFromFormat('Y-m-d\TH:i', $request->data_inicio);
        $fim    = Carbon::createFromFormat('Y-m-d\TH:i', $request->data_fim);

        if (!$this->sitterHasAvailability($request->id_user_sitter, $inicio, $fim)) {
            return redirect()->back()->withInput()->with('error', 'O pet sitter não possui disponibilidade para o dia e horário selecionados.');
        }

        if ($this->sitterHasConflict($request->id_user_sitter, $inicio, $fim, $agendamento->id)) {
            return redirect()->back()->withInput()->with('error', 'Já existe outro agendamento neste horário para o pet sitter selecionado.');
        }

        $dados = $request->only([
            'id_user_cliente', 'id_pet', 'id_user_sitter',
            'id_servico', 'data_inicio', 'data_fim', 'observacoes',
        ]);

        $agendamento->update($dados);

        return redirect()->route('agendamentos.show', $agendamento->id)->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if (auth()->user()->role !== 'ADM') {
            abort(403);
        }

        $agendamento->delete();

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento deletado com sucesso!');
    }

    /**
     * Cancelar agendamento (F_F04)
     * O cliente dono, o sitter responsável ou ADM pode cancelar.
     */
    public function cancel($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $user = auth()->user();
        $isClienteDono = $user->role === 'CLI' && $agendamento->id_user_cliente === $user->id;
        $isSitterResponsavel = $user->role === 'PRO' && $agendamento->id_user_sitter === $user->id;

        if ($user->role !== 'ADM' && !$isClienteDono && !$isSitterResponsavel) {
            abort(403);
        }

        if ($agendamento->status === 'cancelado' || $agendamento->status === 'concluido') {
            return redirect()->back()->with('error', 'Este agendamento não pode ser cancelado.');
        }

        $agendamento->update(['status' => 'cancelado']);

        return redirect()->route('agendamentos.show', $agendamento->id)
            ->with('success', 'Agendamento cancelado com sucesso! A data e horário foram liberados para o pet sitter.');
    }

    // -------------------------------------------------------------------------
    // Métodos privados de apoio
    // -------------------------------------------------------------------------

    /**
     * Verifica se o sitter tem disponibilidade cadastrada para o período.
     * Checa dia da semana + sobreposição de horário.
     */
    private function sitterHasAvailability(int $sitterId, Carbon $inicio, Carbon $fim): bool
    {
        $diaDaSemana = $this->carbonDayToPtBr($inicio->dayOfWeek);

        return Disponibilidade::where('id_user', $sitterId)
            ->where('dia_semana', $diaDaSemana)
            ->where('hora_inicio', '<=', $inicio->format('H:i'))
            ->where('hora_fim', '>=', $fim->format('H:i'))
            ->exists();
    }

    private function concluirAgendamentosExpirados(): void
    {
        Agendamento::whereNotIn('status', [Agendamento::STATUS_CANCELADO, Agendamento::STATUS_CONCLUIDO])
            ->whereNotNull('data_fim')
            ->where('data_fim', '<=', now())
            ->update(['status' => Agendamento::STATUS_CONCLUIDO]);
    }

    /**
     * Verifica se já existe agendamento ativo que conflite com o período.
     * O parâmetro $excludeId permite ignorar o próprio agendamento ao editar.
     */
    private function sitterHasConflict(int $sitterId, Carbon $inicio, Carbon $fim, ?int $excludeId = null): bool
    {
        return Agendamento::where('id_user_sitter', $sitterId)
            ->whereNotIn('status', ['cancelado', 'concluido'])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($inicio, $fim) {
                $query->whereBetween('data_inicio', [$inicio, $fim])
                      ->orWhereBetween('data_fim', [$inicio, $fim])
                      ->orWhere(function ($q) use ($inicio, $fim) {
                          $q->where('data_inicio', '<=', $inicio)
                            ->where('data_fim', '>=', $fim);
                      });
            })
            ->exists();
    }

    /**
     * Converte o dayOfWeek do Carbon (0=Dom … 6=Sáb) para o slug em PT-BR.
     */
    private function carbonDayToPtBr(int $dayOfWeek): string
    {
        return match ($dayOfWeek) {
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terca',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sabado',
            default => '',
        };
    }
}
