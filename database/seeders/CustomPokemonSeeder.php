<?php

namespace Database\Seeders;

use App\Models\CustomPokemon;
use Illuminate\Database\Seeder;

class CustomPokemonSeeder extends Seeder
{
    public function run(): void
    {
        $pokemons = [
            [
                'dex_number'     => 6388,
                'name'           => 'Javeer',
                'type_primary'   => 'electric',
                'type_secondary' => 'steel',
                'base_animal'    => 'Cervo de Nara',
                'inspiration'    => 'Java (odeia JavaScript)',
                'hp'             => 70,
                'attack'         => 85,
                'defense'        => 80,
                'speed'          => 65,
                'sprite_path'    => 'sprites/javeer.png',
                'attacks'        => [
                    [
                        'name'        => 'ByteStrike',
                        'description' => 'Choque elétrico pelos chifres que compila o inimigo no lugar — impossível se mover.',
                    ],
                    [
                        'name'        => 'GarbageCollect',
                        'description' => 'Remove todos os buffs do inimigo. Limpa a memória sem pedir permissão.',
                    ],
                    [
                        'name'        => 'NullPointer',
                        'description' => 'Ataque psíquico que deixa o inimigo confuso. Referencia algo que não existe.',
                    ],
                    [
                        'name'        => 'JVM Crash',
                        'description' => 'Golpe devastador que só funciona depois de 3 turnos carregando. Clássico Java.',
                    ],
                ],
            ],
            [
                'dex_number'     => 6389,
                'name'           => 'Artisaur',
                'type_primary'   => 'grass',
                'type_secondary' => 'psychic',
                'base_animal'    => 'Furão',
                'inspiration'    => 'PHP + Laravel Artisan',
                'hp'             => 65,
                'attack'         => 70,
                'defense'        => 55,
                'speed'          => 90,
                'sprite_path'    => 'sprites/artisaur.png',
                'attacks'        => [
                    [
                        'name'        => 'Artisan Slash',
                        'description' => 'Garra verde que executa comandos no inimigo. php artisan attack --force.',
                    ],
                    [
                        'name'        => 'Migration Wave',
                        'description' => 'Altera o campo de batalha, mudando os tipos dos inimigos. Irreversível.',
                    ],
                    [
                        'name'        => 'Blade Cut',
                        'description' => 'Corte psíquico modelado em templates Blade que atravessa qualquer defesa.',
                    ],
                    [
                        'name'        => 'Composer Install',
                        'description' => 'Cura o time inteiro — mas passa 2 turnos parado esperando. Pode ter conflito de versão.',
                    ],
                ],
            ],
            [
                'dex_number'     => 6390,
                'name'           => 'Pyranix',
                'type_primary'   => 'water',
                'type_secondary' => 'dark',
                'base_animal'    => 'Lobo',
                'inspiration'    => 'Python',
                'hp'             => 75,
                'attack'         => 90,
                'defense'        => 60,
                'speed'          => 95,
                'sprite_path'    => 'sprites/pyranix.png',
                'attacks'        => [
                    [
                        'name'        => 'Serpent Import',
                        'description' => 'Invoca uma cobra de dados que envenena o inimigo. import serpent as venom.',
                    ],
                    [
                        'name'        => 'IndentError',
                        'description' => 'Ataque que paralisa o inimigo por erro de estrutura. Um espaço errado e acabou.',
                    ],
                    [
                        'name'        => 'PipInstall',
                        'description' => 'Absorve força do inimigo e transfere pro Pyranix. pip install enemy_stats.',
                    ],
                    [
                        'name'        => 'Lambda Fang',
                        'description' => 'Mordida ultrarrápida executada como função anônima — sempre ataca primeiro.',
                    ],
                ],
            ],
        ];

        foreach ($pokemons as $data) {
            CustomPokemon::updateOrCreate(
                ['dex_number' => $data['dex_number']],
                $data
            );
        }
    }
}
