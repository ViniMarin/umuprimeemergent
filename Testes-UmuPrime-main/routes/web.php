<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImovelController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ImovelAdminController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\UserController; // << CRUD de usuários (somente admin)

// ----------------------------
// Rotas Públicas
// ----------------------------

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// Imóveis
Route::get('/imovel/{id}', [ImovelController::class, 'show'])->name('imovel.show');
Route::get('/imoveis-aluguel', [ImovelController::class, 'aluguel'])->name('imoveis.aluguel');
Route::get('/imoveis-venda', [ImovelController::class, 'venda'])->name('imoveis.venda');

// Páginas estáticas
Route::view('/sobre', 'sobre')->name('sobre');
Route::view('/contato', 'contato')->name('contato');

// Autenticação (sem registro público)
Auth::routes(['register' => false]);

// ----------------------------
// Rotas Administrativas
// ----------------------------
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Imóveis (qualquer usuário autenticado do painel)
        Route::resource('imoveis', ImovelAdminController::class);

        // Excluir imagem de um imóvel
        Route::delete('imoveis/{imovel}/imagens/{imagem}', [ImovelAdminController::class, 'deleteImage'])
            ->name('imoveis.deleteImage');

        // ----------------------------
        // Somente Administradores
        // ----------------------------
        Route::middleware('can:admin')->group(function () {
            // CRUD de usuários do painel
            Route::resource('users', UserController::class)->except(['show']);

            // Configurações do site: Banner da Home
            Route::get('/configuracoes/home',  [SiteSettingsController::class, 'edit'])->name('settings.home.edit');
            Route::post('/configuracoes/home', [SiteSettingsController::class, 'update'])->name('settings.home.update');
        });
    });

// ----------------------------
// Pós-login padrão do Laravel
// ----------------------------
// Mantemos a rota /home apenas para compatibilidade com o scaffold de auth,
// redirecionando para a home pública.
Route::get('/home', function () {
    return redirect()->route('home');
})->name('home.redirect');
