<?php

namespace App\Http\Controllers;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class ServicoController extends Controller
{
    /**
     * Lista todos os servicos com filtros e paginação.
     * Rota: GET /servicos
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Servico::query();

        if ($user->role === 'PRO') {
            $query->where('id_user', $user->id);
        }

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        $servicos = $query->with('user')->latest()->paginate(10)->withQueryString();
        return view('servicos.index',compact('servicos'));
    }

    /**
     * Exibe o formulário de criação de pet.
     * Rota: GET /servicos/create
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->role !== 'PRO') {
            abort(403, 'Somente pet sitters podem cadastrar serviços.');
        }

        $servicos = Servico::all();
        return view('servicos.create', compact('servicos'));
    }

    /**
     * Salva um novo pet no banco de dados.
     * Rota: POST /servicos
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'PRO') {
            abort(403, 'Somente pet sitters podem cadastrar serviços.');
        }

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'valor_servico' => 'required|numeric|min:0',
            'duracao_minutos' => 'required|integer|min:15|max:720',
        ]);

        try {
            // Um pet sitter só pode cadastrar serviço em nome dele mesmo;
            // apenas o administrador pode escolher outro dono.
            $data['id_user'] = $user->id;
            Servico::create($data);
        }catch(Exception $e){
            Log::error('Erro ao inserir serviço: '. $e->getMessage(),
            [ 'stack' => $e->getTraceAsString()]);
        }
        return redirect()->route('servicos.index');
    }

    /**
     * Exibe os detalhes de um servico específico.
     * Rota: GET /servicos/
     */
    public function show($id)
    {
        $servicos = Servico::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'ADM' && $servicos->id_user !== $user->id) {
            abort(403, 'Você só pode consultar os próprios serviços.');
        }

        return view('servicos.show',compact('servicos'));
    }

    /**
     * Exibe o formulário de edição de pet ou processa a atualização.
     * Rota: GET/POST /servicos/{id}/edit
     */
    public function edit($id)
    {
        $servicos = Servico::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'PRO' || $servicos->id_user !== $user->id) {
            abort(403, 'Você só pode editar os próprios serviços.');
        }

        return view('servicos.edit', compact('servicos'));
    }

    public function update(Request $request, $id)
        {
            $servicos = Servico::findOrFail($id);
            $user = auth()->user();

            if ($user->role !== 'PRO' || $servicos->id_user !== $user->id) {
                abort(403, 'Você só pode editar os próprios serviços.');
            }

            $data = $request->validate([
                'nome' => 'required|string|max:255',
                'valor_servico' => 'required|numeric|min:0',
                'duracao_minutos' => 'required|integer|min:15|max:720',
            ]);

            try {
                $servicos->update($data);
            } catch(Exception $e){
                Log::error('Erro ao alterar o cadastro: '. $e->getMessage(), [
                    'stack' => $e->getTraceAsString()
                ]);
            }
            return redirect()->route('servicos.index');
        }
    /**
     * Remove um pet do banco de dados.
     * Rota: DELETE /servicos/{pet}
     */
    public function destroy($id)
    {
        $servicos = Servico::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'PRO' || $servicos->id_user !== $user->id) {
            abort(403, 'Você só pode excluir os próprios serviços.');
        }

        $servicos->delete();

        return redirect()
            ->route('servicos.index')
            ->with('success', "Serviço \"{$servicos->nome}\" excluído com sucesso.");
    }
}