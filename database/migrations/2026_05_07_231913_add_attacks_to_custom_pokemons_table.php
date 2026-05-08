<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_pokemons', function (Blueprint $table) {
            $table->json('attacks')->nullable()->after('sprite_path');
        });
    }

    public function down(): void
    {
        Schema::table('custom_pokemons', function (Blueprint $table) {
            $table->dropColumn('attacks');
        });
    }
};
