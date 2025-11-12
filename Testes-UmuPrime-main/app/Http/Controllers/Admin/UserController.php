<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        // Cinturão de segurança extra (além do can:admin nas rotas)
        $this->middleware('can:admin');
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required','string','max:255'],
            'email'                 => ['required','string','email','max:255','unique:users,email'],
            'password'              => ['required','string','min:8','confirmed', 'regex:/[A-Za-z]/', 'regex:/\d/'],
            'is_admin'              => ['nullable','boolean'],
        ], [
            'password.confirmed'    => 'As senhas não conferem.',
            'password.min'          => 'A senha deve ter no mínimo 8 caracteres.',
            'password.regex'        => 'A senha precisa ter ao menos uma letra e um número.',
        ]);

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->is_admin = (bool) ($data['is_admin'] ?? false);
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'                  => ['required','string','max:255'],
            'email'                 => ['required','string','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password'              => ['nullable','string','min:8','confirmed', 'regex:/[A-Za-z]/', 'regex:/\d/'],
            'is_admin'              => ['nullable','boolean'],
        ], [
            'password.confirmed'    => 'As senhas não conferem.',
            'password.min'          => 'A senha deve ter no mínimo 8 caracteres.',
            'password.regex'        => 'A senha precisa ter ao menos uma letra e um número.',
        ]);

        $actingUserId = auth()->id();
        $turnAdminOn  = (bool) ($data['is_admin'] ?? false);

        return DB::transaction(function () use ($user, $data, $actingUserId, $turnAdminOn) {

            // Não permitir remover o próprio privilégio de admin
            if ($user->id === $actingUserId && $user->is_admin && !$turnAdminOn) {
                return redirect()
                    ->back()
                    ->with('error', 'Você não pode remover seu próprio acesso de administrador.');
            }

            // Não permitir que o último admin seja rebaixado
            if ($user->is_admin && !$turnAdminOn) {
                $adminCount = User::where('is_admin', true)->count();
                if ($adminCount <= 1) {
                    return redirect()
                        ->back()
                        ->with('error', 'Não é possível remover o último administrador do sistema.');
                }
            }

            $user->name  = $data['name'];
            $user->email = $data['email'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->is_admin = $turnAdminOn;
            $user->save();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuário atualizado com sucesso.');
        });
    }

    public function destroy(User $user)
    {
        $actingUserId = auth()->id();

        if ($user->id === $actingUserId) {
            return redirect()
                ->back()
                ->with('error', 'Você não pode excluir a si mesmo.');
        }

        // Não permitir excluir o último admin
        if ($user->is_admin) {
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->back()
                    ->with('error', 'Não é possível excluir o último administrador do sistema.');
            }
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }
}
