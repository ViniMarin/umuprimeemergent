<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Controller UserController
 * 
 * Gerencia o CRUD de usuários no painel administrativo
 * Acesso restrito apenas para administradores
 */
class UserController extends Controller
{
    /**
     * Regras de validação para senhas
     * 
     * @var array<string>
     */
    private const PASSWORD_RULES = [
        'required',
        'string',
        'min:8',
        'confirmed',
        'regex:/[A-Za-z]/',
        'regex:/\d/',
    ];

    /**
     * Mensagens de validação customizadas
     * 
     * @var array<string, string>
     */
    private const VALIDATION_MESSAGES = [
        'password.confirmed' => 'As senhas não conferem.',
        'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
        'password.regex' => 'A senha precisa ter ao menos uma letra e um número.',
    ];

    /**
     * Constructor: restringe acesso apenas para admins
     */
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    /**
     * Lista todos os usuários
     * 
     * @return View
     */
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Exibe formulário de criação
     * 
     * @return View
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Armazena novo usuário
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => self::PASSWORD_RULES,
            'is_admin' => ['nullable', 'boolean'],
        ], self::VALIDATION_MESSAGES);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => (bool) ($data['is_admin'] ?? false),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Exibe formulário de edição
     * 
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualiza um usuário existente
     * 
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Validação com senha opcional
        $passwordRules = array_diff(self::PASSWORD_RULES, ['required']);
        $passwordRules = array_merge(['nullable'], $passwordRules);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => $passwordRules,
            'is_admin' => ['nullable', 'boolean'],
        ], self::VALIDATION_MESSAGES);

        $actingUserId = auth()->id();
        $turnAdminOn = (bool) ($data['is_admin'] ?? false);

        return DB::transaction(function () use ($user, $data, $actingUserId, $turnAdminOn) {
            // Proteção: não remover próprio privilégio de admin
            if ($user->id === $actingUserId && $user->is_admin && !$turnAdminOn) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Você não pode remover seu próprio acesso de administrador.');
            }

            // Proteção: não rebaixar o último admin
            if ($user->is_admin && !$turnAdminOn) {
                $adminCount = User::admins()->count();
                
                if ($adminCount <= 1) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Não é possível remover o último administrador do sistema.');
                }
            }

            // Atualiza dados do usuário
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->is_admin = $turnAdminOn;

            // Atualiza senha apenas se fornecida
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuário atualizado com sucesso.');
        });
    }

    /**
     * Remove um usuário
     * 
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $actingUserId = auth()->id();

        // Proteção: não permitir auto-exclusão
        if ($user->id === $actingUserId) {
            return redirect()
                ->back()
                ->with('error', 'Você não pode excluir a si mesmo.');
        }

        // Proteção: não excluir o último admin
        if ($user->is_admin) {
            $adminCount = User::admins()->count();
            
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

