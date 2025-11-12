<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * Provider AuthServiceProvider
 * 
 * Gerencia políticas e gates de autorização
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento de políticas
     * 
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Registra gates de autorização
     * 
     * @return void
     */
    public function boot(): void
    {
        // Gate: verifica se o usuário é administrador
        Gate::define('admin', function (User $user): bool {
            return $user->isAdmin();
        });

        // Você pode adicionar mais gates aqui conforme necessário
        // Exemplo:
        // Gate::define('edit-imovel', function (User $user, Imovel $imovel) {
        //     return $user->id === $imovel->user_id || $user->isAdmin();
        // });
    }
}
