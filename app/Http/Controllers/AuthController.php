<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Telefone;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\FotoUsuario;

class AuthController extends Controller
{
    /**
     * Mostrar tela de login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }
        return view('login');
    }

    /**
     * Realizar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('welcome'))->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors([
            'email' => 'Email ou senha incorretos.',
        ])->onlyInput('email');
    }

    /**
     * Mostrar tela de registro
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }
        return view('register');
    }

    /**
     * Realizar registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:CLI,PRO',
            'numero'   => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'cidade'   => 'nullable|string|max:100',
            'estado'   => 'nullable|string|max:100',
            'cep'      => 'nullable|string|max:10',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'name.required'      => 'O nome é obrigatório.',
            'email.required'     => 'O email é obrigatório.',
            'email.email'        => 'Digite um email válido.',
            'email.unique'       => 'Este email já está registrado.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'As senhas não correspondem.',
            'role.required'      => 'Selecione um tipo de usuário.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
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

        if ($request->hasFile('foto')) {
            $arquivo = $request->file('foto');
            FotoUsuario::create([
                'id_user' => $user->id,
                'nome_arquivo' => $arquivo->getClientOriginalName(),
                'url_foto' => $arquivo->store('fotos/usuarios', 'public'),
            ]);
        }

        Auth::login($user);

        return redirect()->route('welcome')->with('success', 'Cadastro realizado com sucesso!');
    }

    /**
     * Realizar logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}