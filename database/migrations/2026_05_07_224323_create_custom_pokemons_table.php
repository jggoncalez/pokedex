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
        Schema::create('custom_pokemons', function (Blueprint $table) {
            $table->id();
            $table->integer('dex_number')->unique();
            $table->string('name');
            $table->string('type_primary');
            $table->string('type_secondary')->nullable();
            $table->string('base_animal');
            $table->text('inspiration');
            $table->integer('hp')->default(50);
            $table->integer('attack')->default(50);
            $table->integer('defense')->default(50);
            $table->integer('speed')->default(50);
            $table->string('sprite_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_pokemons');
    }
};
