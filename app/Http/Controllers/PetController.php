<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\User;
use App\Models\FotoPet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'ADM') {
            $pets = Pet::with(['especie', 'raca', 'user', 'fotos', 'foto'])->paginate(10);
        } else {
            // CLI vê apenas os próprios pets
            $pets = Pet::with(['especie', 'raca', 'user', 'fotos', 'foto'])
                ->where('id_user', $user->id)
                ->paginate(10);
        }

        $especies = Especie::all();

        return view('pets.index', compact('pets', 'especies'));
    }

    public function create(Request $request)
    {
        $selectedEspecie = $request->query('id_especie') ?? old('id_especie');
        $racas           = $selectedEspecie ? Raca::where('id_especie', $selectedEspecie)->get() : collect();
        $especies        = Especie::all();

        return view('pets.create', compact('racas', 'especies', 'selectedEspecie'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => 'required|max:255',
            'data_nascimento' => 'nullable|date',
            'id_especie'      => 'required|exists:especie,id',
            'id_raca'         => 'required|exists:raca,id',
            'peso'            => 'nullable|numeric|min:0',
            'sexo'            => 'required|in:MACHO,FEMEA',
            'observacoes'     => 'nullable|string|max:1000',
            'fotos'           => 'nullable|array|max:10',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $pet = Pet::create([
                'id_user'         => auth()->id(),
                'nome'            => $request->nome,
                'data_nascimento' => $request->data_nascimento,
                'id_especie'      => $request->id_especie,
                'id_raca'         => $request->id_raca,
                'peso'            => $request->peso,
                'sexo'            => $request->sexo,
                'observacoes'     => $request->observacoes,
            ]);

            foreach ($request->file('fotos', []) as $arquivo) {
                FotoPet::create([
                    'id_pet' => $pet->id,
                    'nome_arquivo' => $arquivo->getClientOriginalName(),
                    'url_foto' => $arquivo->store('fotos/pets', 'public'),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Erro ao inserir o pet: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar o pet.');
        }

        return redirect()->route('pets.index')->with('success', 'Pet cadastrado com sucesso!');
    }

    public function show($id)
    {
        $pet  = Pet::with(['especie', 'raca', 'fotos', 'foto', 'user', 'agendamentos'])->findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'ADM' && $pet->id_user !== $user->id) {
            abort(403);
        }

        $fotos = $pet->fotos;
        if ($pet->foto && !$fotos->contains('id', $pet->foto->id)) {
            $fotos->prepend($pet->foto);
        }

        return view('pets.show', compact('pet', 'fotos'));
    }

    public function edit($id)
    {
        $pet  = Pet::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'ADM' && $pet->id_user !== $user->id) {
            abort(403);
        }

        $especies = Especie::all();
        $racas    = Raca::where('id_especie', $pet->id_especie)->get();

        // ADM pode reatribuir o pet a outro usuário; CLI não vê esse campo
        $users = $user->role === 'ADM' ? User::where('role', 'CLI')->get() : collect();

        return view('pets.edit', compact('pet', 'especies', 'racas', 'users'));
    }

    public function update(Request $request, $id)
    {
        $pet  = Pet::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'ADM' && $pet->id_user !== $user->id) {
            abort(403);
        }

        $request->validate([
            'nome'            => 'required|max:255',
            'data_nascimento' => 'nullable|date',
            'id_especie'      => 'required|exists:especie,id',
            'id_raca'         => 'required|exists:raca,id',
            'peso'            => 'nullable|numeric|min:0',
            'sexo'            => 'required|in:MACHO,FEMEA',
            'observacoes'     => 'nullable|string|max:1000',
            'fotos'           => 'nullable|array|max:10',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $pet->update($request->only([
                'nome', 'data_nascimento', 'id_especie',
                'id_raca', 'peso', 'sexo', 'observacoes',
            ]));

            foreach ($request->file('fotos', []) as $arquivo) {
                FotoPet::create([
                    'id_pet' => $pet->id,
                    'nome_arquivo' => $arquivo->getClientOriginalName(),
                    'url_foto' => $arquivo->store('fotos/pets', 'public'),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Erro ao alterar o pet: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar o pet.');
        }

        return redirect()->route('pets.index')->with('success', 'Pet atualizado com sucesso!');
    }

    public function destroy(Pet $pet)
    {
        $user = auth()->user();

        if ($user->role !== 'ADM' && $pet->id_user !== $user->id) {
            abort(403);
        }

        foreach ($pet->fotos as $foto) {
            Storage::disk('public')->delete($foto->url_foto);
            $foto->delete();
        }

        if ($pet->id_foto && $pet->foto) {
            Storage::disk('public')->delete($pet->foto->url_foto);
            $pet->foto->delete();
            $pet->foto()->dissociate();
        }

        $nomePet = $pet->nome;
        $pet->delete();

        return redirect()->route('pets.index')->with('success', "Pet \"{$nomePet}\" excluído com sucesso.");
    }
}