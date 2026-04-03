<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Telefone;
use App\Models\Endereco;
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
        
    }

    /**
     * Exibe o formulário de criação de pet.
     * Rota: GET /servicos/create
     */
    public function create()
    {
        
    }

    /**
     * Salva um novo pet no banco de dados.
     * Rota: POST /servicos
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Exibe os detalhes de um servico específico.
     * Rota: GET /servicos/
     */
    public function show($id)
    {
        
    }

    /**
     * Exibe o formulário de edição de pet ou processa a atualização.
     * Rota: GET/POST /servicos/{id}/edit
     */
    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
        {
            
        }
    /**
     * Remove um pet do banco de dados.
     * Rota: DELETE /servicos/{pet}
     */
    public function destroy($id)
    {
        
    }
}