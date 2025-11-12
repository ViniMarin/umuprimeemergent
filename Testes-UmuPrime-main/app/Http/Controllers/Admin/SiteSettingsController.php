<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    public function __construct()
    {
        // Cinturão de segurança: mesmo que a rota falhe, só admin entra
        $this->middleware('can:admin');
    }

    public function edit()
    {
        $settings = SiteSetting::singleton();
        return view('admin.settings.home', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096','dimensions:min_width=1600,min_height=600'],
        ], [
            'hero_image.dimensions' => 'Use imagem com pelo menos 1600x600 px. Recomendado: 1920x756 px.',
            'hero_image.max'        => 'Imagem muito grande (máx. 4MB).',
        ]);

        $settings = SiteSetting::singleton();
        $settings->updated_by = Auth::id();

        if ($request->hasFile('hero_image')) {
            if ($settings->hero_image && Storage::disk('public')->exists($settings->hero_image)) {
                Storage::disk('public')->delete($settings->hero_image);
            }
            $path = $request->file('hero_image')->store('banners', 'public'); // storage/app/public/banners/xxx.ext
            $settings->hero_image = $path;
        }

        $settings->save();

        return back()->with('success', 'Banner da Home atualizado com sucesso!');
    }
}
