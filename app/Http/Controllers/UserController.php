<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(10);
        return view('users.index', compact('usuarios'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UsuarioRequest $request)
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $usuario)
    {
        $user = $usuario->load('obrigacoes');
        return view('users.show', compact('user'));
    }

    public function edit(User $usuario)
    {
        return view('users.edit', compact('usuario'));
    }

    public function update(UsuarioRequest $request, User $usuario)
    {
        $usuario->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário removido com sucesso!');
    }
}
