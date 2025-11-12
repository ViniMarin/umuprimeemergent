<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [ /* ... */ ];

    public function boot(): void
    {
        // Só acessa quem for admin
        Gate::define('admin', fn (User $user) => (bool) $user->is_admin);
    }
}
