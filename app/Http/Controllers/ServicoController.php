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
        $servicos = Servico::paginate(10);
        return view('servicos.index',compact('servicos'));
    }

    /**
     * Exibe o formulário de criação de pet.
     * Rota: GET /servicos/create
     */
    public function create()
    {
        $servicos = Servico::all();
        return view('servicos.create', compact('servicos'));
    }

    /**
     * Salva um novo pet no banco de dados.
     * Rota: POST /servicos
     */
    public function store(Request $request)
    {
        try{
            Servico::create($request->all());
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
        return view('servicos.show',compact('servicos'));
    }

    /**
     * Exibe o formulário de edição de pet ou processa a atualização.
     * Rota: GET/POST /servicos/{id}/edit
     */
    public function edit($id)
    {
        $servicos = Servico::findOrFail($id);
        return view('servicos.edit', compact('servicos'));
    }

    public function update(Request $request, $id)
        {
            try{
                $servicos = Servico::findOrFail($id);
                $servicos->update($request->all());
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
        $servicos->delete();

        return redirect()
            ->route('servicos.index')
            ->with('success', "Serviço \"{$servicos->nome}\" excluído com sucesso.");
    }
}