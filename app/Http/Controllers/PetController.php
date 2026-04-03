<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Lista todos os pets com filtros e paginação.
     * Rota: GET /pets
     */
    public function index(Request $request)
    {
        $query = Pet::with(['especie', 'raca', 'user']);

        // Filtro por nome
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        // Filtro por espécie
        if ($request->filled('id_especie')) {
            $query->where('id_especie', $request->id_especie);
        }

        // Filtro por sexo
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        $pets    = $query->orderBy('nome')->paginate(15)->withQueryString();
        $especies = Especie::orderBy('tipo_pet')->get();

        return view('pets.index', compact('pets', 'especies'));
    }

    /**
     * Exibe o formulário de criação de pet.
     * Rota: GET /pets/create
     */
    public function create()
    {
        $users   = User::orderBy('name')->get();
        $especies = Especie::orderBy('tipo_pet')->get();
        $racas   = Raca::orderBy('nome')->get();

        return view('pets.create', compact('users', 'especies', 'racas'));
    }

    /**
     * Salva um novo pet no banco de dados.
     * Rota: POST /pets
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:100',
            'id_user'         => 'required|exists:users,id',
            'id_especie'      => 'required|exists:especies,id',
            'id_raca'         => 'required|exists:racas,id',
            'data_nascimento' => 'nullable|date|before_or_equal:today',
            'sexo'            => 'required|in:MACHO,FEMEA',
            'peso'            => 'nullable|numeric|min:0|max:999.99',
            'observacoes'     => 'nullable|string|max:1000',
        ], [
            'nome.required'       => 'O nome do pet é obrigatório.',
            'nome.max'            => 'O nome não pode ter mais de 100 caracteres.',
            'id_user.required'    => 'Selecione o dono do pet.',
            'id_user.exists'      => 'O dono selecionado não existe.',
            'id_especie.required' => 'Selecione a espécie do pet.',
            'id_especie.exists'   => 'A espécie selecionada não existe.',
            'id_raca.required'    => 'Selecione a raça do pet.',
            'id_raca.exists'      => 'A raça selecionada não existe.',
            'data_nascimento.date'             => 'Data de nascimento inválida.',
            'data_nascimento.before_or_equal'  => 'A data de nascimento não pode ser no futuro.',
            'sexo.required'       => 'Selecione o sexo do pet.',
            'sexo.in'             => 'Sexo inválido.',
            'peso.numeric'        => 'O peso deve ser um número.',
            'peso.min'            => 'O peso não pode ser negativo.',
            'peso.max'            => 'O peso não pode ultrapassar 999,99 kg.',
            'observacoes.max'     => 'As observações não podem ter mais de 1000 caracteres.',
        ]);

        Pet::create($validated);

        return redirect()
            ->route('pets.index')
            ->with('success', 'Pet cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um pet específico.
     * Rota: GET /pets/{pet}
     */
    public function show(Pet $pet)
    {
        $pet->load([
            'especie',
            'raca',
            'user',
            'fotos',
            'agendamentos.servico',
            'agendamentos.sitter',
        ]);

        return view('pets.show', compact('pet'));
    }

    /**
     * Exibe o formulário de edição de pet.
     * Rota: GET /pets/{pet}/edit
     */
    public function edit(Pet $pet)
    {
        $users   = User::orderBy('name')->get();
        $especies = Especie::orderBy('tipo_pet')->get();
        $racas   = Raca::orderBy('nome')->get();

        return view('pets.edit', compact('pet', 'users', 'especies', 'racas'));
    }

    /**
     * Atualiza os dados de um pet no banco.
     * Rota: PUT /pets/{pet}
     */
    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:100',
            'id_user'         => 'required|exists:users,id',
            'id_especie'      => 'required|exists:especies,id',
            'id_raca'         => 'required|exists:racas,id',
            'data_nascimento' => 'nullable|date|before_or_equal:today',
            'sexo'            => 'required|in:MACHO,FEMEA',
            'peso'            => 'nullable|numeric|min:0|max:999.99',
            'observacoes'     => 'nullable|string|max:1000',
        ], [
            'nome.required'       => 'O nome do pet é obrigatório.',
            'nome.max'            => 'O nome não pode ter mais de 100 caracteres.',
            'id_user.required'    => 'Selecione o dono do pet.',
            'id_user.exists'      => 'O dono selecionado não existe.',
            'id_especie.required' => 'Selecione a espécie do pet.',
            'id_especie.exists'   => 'A espécie selecionada não existe.',
            'id_raca.required'    => 'Selecione a raça do pet.',
            'id_raca.exists'      => 'A raça selecionada não existe.',
            'data_nascimento.date'             => 'Data de nascimento inválida.',
            'data_nascimento.before_or_equal'  => 'A data de nascimento não pode ser no futuro.',
            'sexo.required'       => 'Selecione o sexo do pet.',
            'sexo.in'             => 'Sexo inválido.',
            'peso.numeric'        => 'O peso deve ser um número.',
            'peso.min'            => 'O peso não pode ser negativo.',
            'peso.max'            => 'O peso não pode ultrapassar 999,99 kg.',
            'observacoes.max'     => 'As observações não podem ter mais de 1000 caracteres.',
        ]);

        $pet->update($validated);

        return redirect()
            ->route('pets.show', $pet->id)
            ->with('success', 'Pet atualizado com sucesso!');
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