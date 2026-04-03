<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class PetController extends Controller
{
    /**
     * Lista todos os pets com filtros e paginação.
     * Rota: GET /pets
     */
    public function index(Request $request)
    {
        $pets = Pet::paginate(10);
        $especies = Especie::all();
        return view('pets.index', compact('pets', 'especies'));
    }

    /**
     * Exibe o formulário de criação de pet.
     * Rota: GET /pets/create
     */
    public function create()
    {
        $racas = Raca::all();
        $especies = Especie::all();
        $users = User::all();
        return view('pets.create', compact('racas', 'especies', 'users'));
    }

    /**
     * Salva um novo pet no banco de dados.
     * Rota: POST /pets
     */
    public function store(Request $request)
    {
        try{
            Pet::create($request->all());
        }catch(Exception $e){
            Log::error('Erro ao inserir o pet: '. $e->getMessage(),
            [ 'stack' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Erro ao cadastrar o pet.');
        }

        return redirect()
            ->route('pets.index')
            ->with('success', 'Pet cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um pet específico.
     * Rota: GET /pets/{pet}
     */
    public function show($id)
    {
        $pet = Pet::findOrFail($id);
        return view('pets.show',compact('pet'));

    }

    /**
     * Exibe o formulário de edição de pet ou processa a atualização.
     * Rota: GET/POST /pets/{id}/edit
     */
    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $users = User::all();
        $especies = Especie::all();
        $racas = Raca::all();

        return view('pets.edit', compact('pet', 'users', 'especies', 'racas'));
    }

    public function update(Request $request, $id)
        {
            try{
                $pet = Pet::findOrFail($id);
                $pet->update($request->all());
            } catch(Exception $e){
                Log::error('Erro ao alterar o cadastro: '. $e->getMessage(), [
                    'stack' => $e->getTraceAsString()
                ]);
            }
            return redirect()->route('pets.index');
        }

    /**
     * Remove um pet do banco de dados.
     * Rota: DELETE /pets/{pet}
     */
    public function destroy(Pet $pet)
    {
        // Remove fotos associadas do storage, se houver
        if ($pet->fotos->isNotEmpty()) {
            foreach ($pet->fotos as $foto) {
                Storage::disk('public')->delete($foto->url_foto);
            }
        }

        $nomePet = $pet->nome;
        $pet->delete();

        return redirect()
            ->route('pets.index')
            ->with('success', "Pet \"{$nomePet}\" excluído com sucesso.");
    }
}