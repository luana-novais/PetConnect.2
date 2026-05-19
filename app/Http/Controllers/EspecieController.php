<?php
namespace App\Http\Controllers;

use App\Models\Especie;
use Illuminate\Http\Request;

class EspecieController extends Controller
{
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
            'tipo_pet' => 'required|in:GATO,CACHORRO,PEQUENOS MAMIFEROS,EXOTICOS',
        ]);

        Especie::create($request->only('tipo_pet'));
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
            'tipo_pet' => 'required|in:GATO,CACHORRO,PEQUENOS MAMIFEROS,EXOTICOS',
        ]);

        $especie = Especie::findOrFail($id);
        $especie->update($request->only('tipo_pet'));
        return redirect()->route('especies.index')->with('success', 'Espécie atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        Especie::findOrFail($id)->delete();
        return redirect()->route('especies.index')->with('success', 'Espécie removida com sucesso!');
    }
}