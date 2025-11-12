<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@umuprime.com.br'],
            [
                'name'              => 'Administrador UmuPrime',
                'password'          => Hash::make('admin123'), // troque depois!
                'is_admin'          => true,                   // garante perfil admin
                'email_verified_at' => now(),                  // caso verificação esteja desligada
            ]
        );
    }
}
