<?php
namespace App\Http\Controllers;

use App\Models\Raca;
use App\Models\Especie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RacaController extends Controller implements HasMiddleware
{
    /**
     * Somente o administrador pode incluir, alterar ou excluir raças (F_B04).
     * index/show/porEspecie ficam liberados para qualquer usuário autenticado,
     * pois alimentam o combo de raças no cadastro de pet.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('role.adm', except: ['index', 'show', 'porEspecie']),
        ];
    }

    public function index()
    {
        $racas = Raca::with('especie')->get();
        return view('racas.index', compact('racas'));
    }

    public function create()
    {
        $especies = Especie::all();
        return view('racas.create', compact('especies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'       => 'required|string|max:50',
            'id_especie' => 'required|exists:especie,id',
        ]);

        Raca::create($request->only('nome', 'id_especie'));
        return redirect()->route('racas.index')->with('success', 'Raça cadastrada com sucesso!');
    }

    public function edit(string $id)
    {
        $raca    = Raca::findOrFail($id);
        $especies = Especie::all();
        return view('racas.edit', compact('raca', 'especies'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome'       => 'required|string|max:50',
            'id_especie' => 'required|exists:especie,id',
        ]);

        $raca = Raca::findOrFail($id);
        $raca->update($request->only('nome', 'id_especie'));
        return redirect()->route('racas.index')->with('success', 'Raça atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        Raca::findOrFail($id)->delete();
        return redirect()->route('racas.index')->with('success', 'Raça removida com sucesso!');
    }

    public function porEspecie($especieId)
    {
        $racas = \App\Models\Raca::where('id_especie', $especieId)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($racas);
    }
}