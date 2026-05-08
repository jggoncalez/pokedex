<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomPokemonFactory extends Factory
{
    public function definition(): array
    {
        $types = ['fire', 'water', 'grass', 'electric', 'ice', 'fighting', 'poison',
                  'ground', 'flying', 'psychic', 'bug', 'rock', 'ghost', 'dragon',
                  'dark', 'steel', 'fairy', 'normal'];

        $suffixes = ['-ix', '-aur', '-volt', '-eon', '-nix'];
        $base = $this->faker->word();
        $name = ucfirst($base) . $this->faker->randomElement($suffixes);

        $primaryType = $this->faker->randomElement($types);
        $remainingTypes = array_filter($types, fn($t) => $t !== $primaryType);
        $secondaryType = $this->faker->optional(0.6)->randomElement(array_values($remainingTypes));

        $animals = ['leão', 'tigre', 'águia', 'lobo', 'cobra', 'urso', 'raposa',
                    'golfinho', 'elefante', 'cabra', 'cervo', 'panda', 'furão', 'falcão'];

        $languages = ['Python', 'Rust', 'Go', 'TypeScript', 'Haskell', 'Kotlin',
                      'Scala', 'Elixir', 'Clojure', 'Julia', 'Lua', 'Nim', 'Zig'];

        return [
            'dex_number'     => $this->faker->unique()->numberBetween(7001, 9999),
            'name'           => $name,
            'type_primary'   => $primaryType,
            'type_secondary' => $secondaryType,
            'base_animal'    => $this->faker->randomElement($animals),
            'inspiration'    => $this->faker->randomElement($languages),
            'hp'             => $this->faker->numberBetween(40, 120),
            'attack'         => $this->faker->numberBetween(40, 120),
            'defense'        => $this->faker->numberBetween(40, 120),
            'speed'          => $this->faker->numberBetween(40, 120),
            'sprite_path'    => null,
        ];
    }
}
