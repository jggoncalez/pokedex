<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Pokédex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        body { font-family: 'Press Start 2P', cursive; background: #1a1a2e; }

        .type-normal   { background: #A8A878; } .type-fire     { background: #F08030; }
        .type-water    { background: #6890F0; } .type-electric { background: #F8D030; color:#333; }
        .type-grass    { background: #78C850; } .type-ice      { background: #98D8D8; color:#333; }
        .type-fighting { background: #C03028; } .type-poison   { background: #A040A0; }
        .type-ground   { background: #E0C068; color:#333; } .type-flying { background: #A890F0; }
        .type-psychic  { background: #F85888; } .type-bug      { background: #A8B820; }
        .type-rock     { background: #B8A038; } .type-ghost    { background: #705898; }
        .type-dragon   { background: #7038F8; } .type-dark     { background: #705848; }
        .type-steel    { background: #B8B8D0; color:#333; } .type-fairy  { background: #EE99AC; color:#333; }

        .pixel-card {
            background: #16213e;
            border: 2px solid #1e2e4a;
            border-radius: 10px;
            transition: transform 0.15s, border-color 0.15s, box-shadow 0.15s;
            text-decoration: none; display: block;
        }
        .pixel-card:hover {
            transform: translateY(-3px);
            border-color: #4a6a2a;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }
        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
            text-decoration: none; display: inline-block; cursor: pointer; border: none;
        }
        .action-btn:active { transform: translateY(2px); }
        .action-btn.green { background: linear-gradient(145deg, #3a7a1a, #2a5a0a); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #1a3a05; }
        .action-btn.blue  { background: linear-gradient(145deg, #2266cc, #1144aa); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277; }
        .action-btn.yellow{ background: linear-gradient(145deg, #cc9900, #aa7700); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #775500; }

        .stat-bar-bg  { background: #0a0a1a; border-radius: 4px; overflow: hidden; }
        .stat-bar-fill{ background: linear-gradient(90deg, #9bbc0f, #78C850); height: 100%; border-radius: 4px; }
        .type-badge   { font-size: 6px; text-transform: uppercase; letter-spacing: 1px; }

        .empty-state {
            background: #16213e;
            border: 2px dashed #1e2e4a;
            border-radius: 10px;
        }
    </style>
</head>
<body class="min-h-screen">

@include('partials.nav')

<main class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6 gap-4 flex-wrap">
        <div>
            <h1 class="text-xl text-green-400 mb-2">CUSTOM DEX</h1>
            <p class="text-gray-600" style="font-size: 6px;">Pokémons criados pelos treinadores</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('custom-pokemons.create') }}" class="action-btn green px-5 py-3 text-xs rounded">
                + CRIAR POKÉMON
            </a>
            <a href="{{ route('custom-pokemons.quiz') }}" class="action-btn yellow px-5 py-3 text-xs rounded">
                ? QUIZ
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-5 px-4 py-3 rounded" style="background: rgba(120,200,80,0.1); border: 2px solid #78C850; color: #9bbc0f; font-size: 7px;">
            {{ session('success') }}
        </div>
    @endif

    @if($pokemons->isEmpty())
        {{-- Empty state --}}
        <div class="empty-state p-16 text-center">
            <p class="text-gray-600 mb-6" style="font-size: 8px;">Nenhum Pokémon custom ainda.</p>
            <a href="{{ route('custom-pokemons.create') }}" class="action-btn green px-6 py-3 text-xs rounded">
                CRIAR O PRIMEIRO
            </a>
        </div>
    @else
        {{-- Stats summary --}}
        <div class="mb-5 px-4 py-3 rounded flex items-center gap-3" style="background: #0d0d1a; border: 1px solid #1e2e1e;">
            <span style="font-size: 6px; color: #4a5568;">TOTAL REGISTRADO:</span>
            <span style="font-size: 10px; color: #9bbc0f;">{{ $pokemons->count() }} POKÉMON{{ $pokemons->count() !== 1 ? 'S' : '' }}</span>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($pokemons as $pokemon)
                <div class="pixel-card" style="text-decoration: none; display: block;">
                    <a href="{{ route('custom-pokemons.show', $pokemon->id) }}" style="text-decoration: none; display: block;">
                        <div class="p-5">
                            {{-- Sprite --}}
                            <div class="flex justify-center mb-4">
                                <img src="{{ $pokemon->sprite_path ? asset($pokemon->sprite_path) : 'https://placehold.co/96x96/0a0a1a/9bbc0f?text=' . urlencode($pokemon->name[0]) }}"
                                     alt="{{ $pokemon->name }}"
                                     class="w-24 h-24 object-contain"
                                     style="image-rendering: pixelated;"
                                     onerror="this.src='https://placehold.co/96x96/0a0a1a/9bbc0f?text=?'">
                            </div>

                            {{-- Number + Name --}}
                            <div class="text-center mb-3">
                                <p class="text-gray-600 mb-1" style="font-size: 6px;">#{{ str_pad($pokemon->dex_number, 4, '0', STR_PAD_LEFT) }}</p>
                                <h2 class="text-green-400" style="font-size: 10px;">{{ strtoupper($pokemon->name) }}</h2>
                            </div>

                            {{-- Types --}}
                            <div class="flex gap-2 justify-center mb-4 flex-wrap">
                                <span class="type-badge type-{{ $pokemon->type_primary }} px-2 py-1 rounded text-white font-bold">
                                    {{ strtoupper($pokemon->type_primary) }}
                                </span>
                                @if($pokemon->type_secondary)
                                    <span class="type-badge type-{{ $pokemon->type_secondary }} px-2 py-1 rounded font-bold">
                                        {{ strtoupper($pokemon->type_secondary) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Mini stats --}}
                            <div class="space-y-2">
                                @foreach(['hp' => 'HP', 'attack' => 'ATK', 'defense' => 'DEF', 'speed' => 'VEL'] as $key => $label)
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-600 flex-shrink-0" style="font-size: 5px; width: 22px; text-align: right;">{{ $label }}</span>
                                        <div class="flex-1 stat-bar-bg" style="height: 6px;">
                                            <div class="stat-bar-fill" style="width: {{ min(100, ($pokemon->$key / 255) * 100) }}%;"></div>
                                        </div>
                                        <span class="text-gray-500 flex-shrink-0" style="font-size: 5px; width: 20px; text-align: right;">{{ $pokemon->$key }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </a>

                    {{-- Card footer --}}
                    <div class="px-5 py-2 flex items-center justify-between" style="border-top: 1px solid #1e2e4a;">
                        <span style="font-size: 5px; color: #4a5568;">VER DETALHES →</span>
                        <form method="POST" action="{{ route('custom-pokemons.destroy', $pokemon->id) }}"
                              onsubmit="return confirm('Deletar {{ strtoupper($pokemon->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn px-2 py-1 rounded" style="font-size: 5px;">✕ DEL</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</main>

</body>
</html>
