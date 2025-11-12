<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controller SiteSettingsController
 * 
 * Gerencia as configurações gerais do site
 * Acesso restrito apenas para administradores
 */
class SiteSettingsController extends Controller
{
    /**
     * Regras de validação para imagem hero
     * 
     * @var array<string, mixed>
     */
    private const HERO_IMAGE_RULES = [
        'nullable',
        'image',
        'mimes:jpg,jpeg,png,webp',
        'max:4096',
        'dimensions:min_width=1600,min_height=600',
    ];

    /**
     * Mensagens de validação customizadas
     * 
     * @var array<string, string>
     */
    private const VALIDATION_MESSAGES = [
        'hero_image.dimensions' => 'Use imagem com pelo menos 1600x600 px. Recomendado: 1920x756 px.',
        'hero_image.max' => 'Imagem muito grande (máx. 4MB).',
        'hero_image.mimes' => 'Formato inválido. Use: JPG, JPEG, PNG ou WEBP.',
    ];

    /**
     * Constructor: restringe acesso apenas para admins
     */
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    /**
     * Exibe formulário de edição das configurações
     * 
     * @return View
     */
    public function edit(): View
    {
        $settings = SiteSetting::singleton();
        
        return view('admin.settings.home', compact('settings'));
    }

    /**
     * Atualiza as configurações do site
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'hero_image' => self::HERO_IMAGE_RULES,
        ], self::VALIDATION_MESSAGES);

        $settings = SiteSetting::singleton();
        $settings->updated_by = Auth::id();

        // Upload de nova imagem hero
        if ($request->hasFile('hero_image')) {
            // Remove imagem antiga se existir
            if ($settings->hero_image && Storage::disk('public')->exists($settings->hero_image)) {
                Storage::disk('public')->delete($settings->hero_image);
            }

            // Armazena nova imagem
            $path = $request->file('hero_image')->store('banners', 'public');
            $settings->hero_image = $path;
        }

        $settings->save();

        return back()->with('success', 'Banner da Home atualizado com sucesso!');
    }
}
