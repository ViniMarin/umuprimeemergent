<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id(); // terá sempre 1
            $table->string('hero_image_path')->nullable(); // ex: "banners/abc.webp"
        });

        // cria o registro 1
        \DB::table('site_settings')->insert([
            'id' => 1,
            'hero_image_path' => null,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
