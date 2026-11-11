<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\User;
use App\Models\FotoPet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use RuntimeException;
use Throwable;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'nome' => 'nullable|string|max:255',
            'id_especie' => 'nullable|integer|exists:especie,id',
            'id_raca' => 'nullable|integer|exists:raca,id',
            'sexo' => 'nullable|in:MACHO,FEMEA',
        ]);

        $user = auth()->user();
        $query = Pet::with(['especie', 'raca', 'user', 'fotos', 'foto']);

        if ($user->role !== 'ADM') {
            // CLI vê apenas os próprios pets
            $query->where('id_user', $user->id);
        }

        $query
            ->when($filters['nome'] ?? null, fn ($query, $nome) => $query->where('nome', 'like', '%' . trim($nome) . '%'))
            ->when($filters['id_especie'] ?? null, fn ($query, $especieId) => $query->where('id_especie', $especieId))
            ->when($filters['id_raca'] ?? null, fn ($query, $racaId) => $query->where('id_raca', $racaId))
            ->when($filters['sexo'] ?? null, fn ($query, $sexo) => $query->where('sexo', $sexo));

        $pets = $query->paginate(10)->withQueryString();
        $especies = Especie::all();
        $selectedEspecie = $filters['id_especie'] ?? null;
        $racas = $selectedEspecie
            ? Raca::where('id_especie', $selectedEspecie)->orderBy('nome')->get()
            : collect();

        return view('pets.index', compact('pets', 'especies', 'racas', 'selectedEspecie'));
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
        ], [
            'fotos.*.mimes'   => 'As imagens devem estar nos formatos JPG ou PNG.',
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

    public function storePhotos(Request $request, Pet $pet)
    {
        $this->authorizePetAccess($pet);

        $request->validate([
            'fotos' => ['required', 'array', 'min:1', 'max:10'],
            'fotos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'fotos.required' => 'Selecione ao menos uma foto.',
            'fotos.min' => 'Selecione ao menos uma foto.',
            'fotos.max' => 'É possível enviar no máximo 10 fotos por vez.',
            'fotos.*.image' => 'Cada arquivo deve ser uma imagem válida.',
            'fotos.*.mimes' => 'As imagens devem estar nos formatos JPG, PNG ou WebP.',
            'fotos.*.max' => 'Cada imagem deve ter no máximo 5 MB.',
        ]);

        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $pet, &$storedPaths): void {
                foreach ($request->file('fotos', []) as $arquivo) {
                    $path = $arquivo->store('fotos/pets', 'public');

                    if ($path === false) {
                        throw new RuntimeException('Não foi possível salvar uma das fotos enviadas.');
                    }

                    $storedPaths[] = $path;

                    FotoPet::create([
                        'id_pet' => $pet->id,
                        'nome_arquivo' => $arquivo->getClientOriginalName(),
                        'url_foto' => $path,
                    ]);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);
            throw $exception;
        }

        return redirect()->route('pets.show', $pet)->with('success', 'Fotos adicionadas ao pet com sucesso.');
    }

    public function destroyPhoto(Pet $pet, FotoPet $foto)
    {
        $this->authorizePetAccess($pet);

        $belongsToPet = $foto->id_agendamento === null
            && ((int) $foto->id_pet === (int) $pet->id || (int) $pet->id_foto === (int) $foto->id);

        if (!$belongsToPet) {
            abort(404);
        }

        $path = $foto->url_foto;

        if ((int) $pet->id_foto === (int) $foto->id) {
            $pet->foto()->dissociate();
            $pet->save();
        }

        $foto->delete();
        Storage::disk('public')->delete($path);

        return redirect()->route('pets.show', $pet)->with('success', 'Foto excluída com sucesso.');
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
        ], [
            'fotos.*.mimes'   => 'As imagens devem estar nos formatos JPG ou PNG.',
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

    private function authorizePetAccess(Pet $pet): void
    {
        $user = auth()->user();

        if ($user->role !== 'ADM' && (int) $pet->id_user !== (int) $user->id) {
            abort(403);
        }
    }
}