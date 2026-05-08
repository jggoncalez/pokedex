<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPokemon extends Model
{
    use HasFactory;

    protected $table = 'custom_pokemons';

    protected $fillable = [
        'dex_number',
        'name',
        'type_primary',
        'type_secondary',
        'base_animal',
        'inspiration',
        'hp',
        'attack',
        'defense',
        'speed',
        'sprite_path',
        'attacks',
    ];

    protected $casts = [
        'attacks' => 'array',
    ];
}
