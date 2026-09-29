<?php
namespace App\Http\Controllers;

use App\Models\Especie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EspecieController extends Controller implements HasMiddleware
{
    /**
     * Somente o administrador pode incluir, alterar ou excluir espécies (F_B03).
     * A listagem (index) fica liberada para qualquer usuário autenticado,
     * pois é usada em telas de cadastro de pet por tutores e pet sitters.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('role.adm', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        $especies = Especie::all();
        return view('especies.index', compact('especies'));
    }

    public function create()
    {
        return view('especies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_pet' => 'required|string|max:255',
        ]);

        Especie::create([
            'tipo_pet' => trim($request->input('tipo_pet')),
        ]);

        return redirect()->route('especies.index')->with('success', 'Espécie cadastrada com sucesso!');
    }

    public function edit(string $id)
    {
        $especie = Especie::findOrFail($id);
        return view('especies.edit', compact('especie'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tipo_pet' => 'required|string|max:255',
        ]);

        $especie = Especie::findOrFail($id);
        $especie->update([
            'tipo_pet' => trim($request->input('tipo_pet')),
        ]);

        return redirect()->route('especies.index')->with('success', 'Espécie atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        Especie::findOrFail($id)->delete();
        return redirect()->route('especies.index')->with('success', 'Espécie removida com sucesso!');
    }
}