<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:191'],
            'email'                 => ['required', 'email', 'max:191', Rule::unique('users','email')],
            'password'              => [
                'required', 'confirmed',
                Password::min(8)->letters()->numbers()
            ],
            'is_admin'              => ['nullable', 'boolean'],
        ]);

        User::create([
            'name'     => $request->string('name'),
            'email'    => $request->string('email'),
            'password' => Hash::make($request->input('password')),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'     => ['required', 'string', 'max:191'],
            'email'    => ['required', 'email', 'max:191', Rule::unique('users','email')->ignore($user->id)],
            'is_admin' => ['nullable', 'boolean'],
        ];

        // senha opcional no update
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::min(8)->letters()->numbers()];
        }

        $validated = $request->validate($rules);

        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->is_admin = $request->boolean('is_admin');

        if (!empty($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        // Evita se deletar sozinho ou remover último admin
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Você não pode excluir a si mesmo.');
        }
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Não é possível excluir o último administrador.');
        }

        $user->delete();

        return back()->with('success', 'Usuário excluído com sucesso.');
    }
}
