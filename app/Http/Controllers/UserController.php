<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Telefone;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    public function index()
{
    $user = User::with(['telefone', 'endereco'])->findOrFail(1);
    return view('users.index', compact('user'));
}

    public function create()
    {
        $users = User::all();
        return view('users.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'numero'   => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'cidade'   => 'nullable|string|max:100',
            'estado'   => 'nullable|string|max:100',
            'cep'      => 'nullable|string|max:10',
        ]);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]);

            if ($request->filled('numero')) {
                Telefone::create([
                    'id_user' => $user->id,
                    'numero'  => $request->numero,
                ]);
            }

            if ($request->filled('endereco')) {
                Endereco::create([
                    'id_user'  => $user->id,
                    'endereco' => $request->endereco,
                    'cidade'   => $request->cidade,
                    'estado'   => $request->estado,
                    'cep'      => $request->cep,
                ]);
            }

            return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Erro ao cadastrar usuário: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $user     = User::findOrFail($id);
        $telefone = Telefone::where('id_user', $id)->first();
        $endereco = Endereco::where('id_user', $id)->first();
        return view('users.edit', compact('user', 'telefone', 'endereco'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $id,
            'numero'           => 'nullable|string|max:20',
            'endereco'         => 'nullable|string|max:255',
            'cidade'           => 'nullable|string|max:100',
            'estado'           => 'nullable|string|max:100',
            'cep'              => 'nullable|string|max:10',
            'current_password' => 'nullable',
            'password'         => 'nullable|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail($id);

            // Dados pessoais
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Senha
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()
                        ->withErrors(['current_password' => 'A senha atual está incorreta.'])
                        ->withInput();
                }
                $user->update(['password' => bcrypt($request->password)]);
            }

            // Telefone
            Telefone::updateOrCreate(
                ['id_user' => $id],
                ['numero'  => $request->numero]
            );

            // Endereço
            Endereco::updateOrCreate(
                ['id_user' => $id],
                [
                    'endereco' => $request->endereco,
                    'cidade'   => $request->cidade,
                    'estado'   => $request->estado,
                    'cep'      => $request->cep,
                ]
            );

            return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Erro ao atualizar: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            Telefone::where('id_user', $id)->delete();
            Endereco::where('id_user', $id)->delete();
            $user->delete();

            return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao deletar usuário: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Erro ao deletar: ' . $e->getMessage());
        }
    }
}