<?php

namespace App\Http\Controllers;

use App\Models\CustomPokemon;
use Illuminate\Http\Request;

class CustomPokemonController extends Controller
{
    public function index()
    {
        $pokemons = CustomPokemon::orderBy('dex_number')->get();
        return view('custom-pokemons.index', compact('pokemons'));
    }

    public function show($id)
    {
        $pokemon = CustomPokemon::findOrFail($id);
        return view('custom-pokemons.show', compact('pokemon'));
    }

    public function create()
    {
        return view('custom-pokemons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'dex_number'     => 'required|integer|unique:custom_pokemons,dex_number',
            'type_primary'   => 'required|string',
            'type_secondary' => 'nullable|string|different:type_primary',
            'base_animal'    => 'required|string|max:100',
            'inspiration'    => 'required|string|max:500',
            'hp'             => 'required|integer|min:1|max:255',
            'attack'         => 'required|integer|min:1|max:255',
            'defense'        => 'required|integer|min:1|max:255',
            'speed'          => 'required|integer|min:1|max:255',
            'sprite_file'    => 'nullable|file|image|max:2048',
            'attacks'        => 'nullable|array|max:4',
            'attacks.*.name' => 'nullable|string|max:100',
            'attacks.*.description' => 'nullable|string|max:500',
        ], [
            'name.required'           => 'O nome é obrigatório.',
            'dex_number.required'     => 'O número DEX é obrigatório.',
            'dex_number.integer'      => 'O número DEX deve ser um número inteiro.',
            'dex_number.unique'       => 'Este número DEX já está em uso.',
            'type_primary.required'   => 'O tipo primário é obrigatório.',
            'type_secondary.different'=> 'O tipo secundário deve ser diferente do primário.',
            'base_animal.required'    => 'O animal base é obrigatório.',
            'inspiration.required'    => 'A inspiração é obrigatória.',
            'hp.required'             => 'O HP é obrigatório.',
            'hp.min'                  => 'O HP mínimo é 1.',
            'hp.max'                  => 'O HP máximo é 255.',
            'attack.required'         => 'O Ataque é obrigatório.',
            'attack.min'              => 'O Ataque mínimo é 1.',
            'attack.max'              => 'O Ataque máximo é 255.',
            'defense.required'        => 'A Defesa é obrigatória.',
            'defense.min'             => 'A Defesa mínima é 1.',
            'defense.max'             => 'A Defesa máxima é 255.',
            'speed.required'          => 'A Velocidade é obrigatória.',
            'speed.min'               => 'A Velocidade mínima é 1.',
            'speed.max'               => 'A Velocidade máxima é 255.',
            'sprite_file.image'       => 'O arquivo deve ser uma imagem (PNG, JPG, GIF, WEBP).',
            'sprite_file.max'         => 'A imagem não pode ter mais de 2MB.',
        ]);

        if (empty($validated['type_secondary'])) {
            $validated['type_secondary'] = null;
        }

        $validated['sprite_path'] = null;
        if ($request->hasFile('sprite_file')) {
            $path = $request->file('sprite_file')->store('sprites', 'public');
            $validated['sprite_path'] = 'storage/' . $path;
        }
        unset($validated['sprite_file']);

        $attacks = collect($request->input('attacks', []))
            ->filter(fn($a) => !empty($a['name']))
            ->values()
            ->toArray();
        $validated['attacks'] = !empty($attacks) ? $attacks : null;

        $pokemon = CustomPokemon::create($validated);

        return redirect()->route('custom-pokemons.show', $pokemon->id)
            ->with('success', 'Pokémon criado com sucesso!');
    }

    public function edit($id)
    {
        $pokemon = CustomPokemon::findOrFail($id);
        return view('custom-pokemons.edit', compact('pokemon'));
    }

    public function update(Request $request, $id)
    {
        $pokemon = CustomPokemon::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'dex_number'     => 'required|integer|unique:custom_pokemons,dex_number,' . $id,
            'type_primary'   => 'required|string',
            'type_secondary' => 'nullable|string|different:type_primary',
            'base_animal'    => 'required|string|max:100',
            'inspiration'    => 'required|string|max:500',
            'hp'             => 'required|integer|min:1|max:255',
            'attack'         => 'required|integer|min:1|max:255',
            'defense'        => 'required|integer|min:1|max:255',
            'speed'          => 'required|integer|min:1|max:255',
            'sprite_file'    => 'nullable|file|image|max:2048',
            'attacks'        => 'nullable|array|max:4',
            'attacks.*.name' => 'nullable|string|max:100',
            'attacks.*.description' => 'nullable|string|max:500',
        ], [
            'name.required'           => 'O nome é obrigatório.',
            'dex_number.required'     => 'O número DEX é obrigatório.',
            'dex_number.integer'      => 'O número DEX deve ser um número inteiro.',
            'dex_number.unique'       => 'Este número DEX já está em uso.',
            'type_primary.required'   => 'O tipo primário é obrigatório.',
            'type_secondary.different'=> 'O tipo secundário deve ser diferente do primário.',
            'base_animal.required'    => 'O animal base é obrigatório.',
            'inspiration.required'    => 'A inspiração é obrigatória.',
            'hp.required'             => 'O HP é obrigatório.',
            'hp.min'                  => 'O HP mínimo é 1.',
            'hp.max'                  => 'O HP máximo é 255.',
            'attack.required'         => 'O Ataque é obrigatório.',
            'attack.min'              => 'O Ataque mínimo é 1.',
            'attack.max'              => 'O Ataque máximo é 255.',
            'defense.required'        => 'A Defesa é obrigatória.',
            'defense.min'             => 'A Defesa mínima é 1.',
            'defense.max'             => 'A Defesa máxima é 255.',
            'speed.required'          => 'A Velocidade é obrigatória.',
            'speed.min'               => 'A Velocidade mínima é 1.',
            'speed.max'               => 'A Velocidade máxima é 255.',
            'sprite_file.image'       => 'O arquivo deve ser uma imagem (PNG, JPG, GIF, WEBP).',
            'sprite_file.max'         => 'A imagem não pode ter mais de 2MB.',
        ]);

        if (empty($validated['type_secondary'])) {
            $validated['type_secondary'] = null;
        }

        if ($request->hasFile('sprite_file')) {
            if ($pokemon->sprite_path && file_exists(public_path($pokemon->sprite_path))) {
                unlink(public_path($pokemon->sprite_path));
            }
            $path = $request->file('sprite_file')->store('sprites', 'public');
            $validated['sprite_path'] = 'storage/' . $path;
        }
        unset($validated['sprite_file']);

        $attacks = collect($request->input('attacks', []))
            ->filter(fn($a) => !empty($a['name']))
            ->values()
            ->toArray();
        $validated['attacks'] = !empty($attacks) ? $attacks : null;

        $pokemon->update($validated);

        return redirect()->route('custom-pokemons.show', $pokemon->id)
            ->with('success', strtoupper($pokemon->name) . ' foi atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $pokemon = CustomPokemon::findOrFail($id);
        $name    = strtoupper($pokemon->name);

        if ($pokemon->sprite_path && file_exists(public_path($pokemon->sprite_path))) {
            unlink(public_path($pokemon->sprite_path));
        }

        $pokemon->delete();

        return redirect()->route('custom-pokemons.index')
            ->with('success', "{$name} foi deletado com sucesso!");
    }

    public function quiz()
    {
        $pokemon = CustomPokemon::inRandomOrder()->first();

        if (!$pokemon) {
            return redirect()->route('custom-pokemons.index')
                ->with('error', 'Nenhum Pokémon custom disponível para o quiz.');
        }

        session(['quiz_pokemon_id' => $pokemon->id]);

        return view('custom-pokemons.quiz', compact('pokemon'));
    }

    public function guess(Request $request)
    {
        $pokemonId = session('quiz_pokemon_id');
        $pokemon   = CustomPokemon::findOrFail($pokemonId);

        $guess   = strtolower(trim($request->input('guess', '')));
        $correct = strtolower(trim($pokemon->name));
        $acertou = $guess !== '' && $guess === $correct;

        session()->forget('quiz_pokemon_id');

        return view('custom-pokemons.quiz', compact('pokemon', 'acertou'));
    }
}
