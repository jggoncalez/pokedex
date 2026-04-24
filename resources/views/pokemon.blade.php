<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

        body {
            font-family: 'Press Start 2P', cursive;
            background: #1a1a2e;
        }

        .pokedex-body {
            background: linear-gradient(145deg, #e63946, #c1121f);
            border-radius: 16px 16px 50% 50% / 16px 16px 30px 30px;
            box-shadow:
                inset -4px -4px 0 #9b0000,
                inset 4px 4px 0 #ff6b6b,
                0 10px 40px rgba(0,0,0,0.6);
            position: relative;
        }

        .pokedex-hinge {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.4);
        }

        .screen-outer {
            background: #1a1a1a;
            border-radius: 8px;
            box-shadow:
                inset 0 0 0 3px #333,
                inset 0 0 0 6px #555;
        }

        .screen-inner {
            background: #9bbc0f;
            border-radius: 4px;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.3);
            image-rendering: pixelated;
        }

        .screen-inner.error {
            background: #bc0f0f;
        }

        .led-red { background: #ff4444; box-shadow: 0 0 8px #ff0000; }
        .led-yellow { background: #ffcc00; box-shadow: 0 0 8px #ffaa00; }
        .led-green { background: #44ff44; box-shadow: 0 0 8px #00ff00; }

        .pokemon-sprite {
            image-rendering: pixelated;
            filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3));
        }

        .btn-dpad {
            background: linear-gradient(145deg, #333, #222);
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.1), 0 2px 4px rgba(0,0,0,0.5);
        }

        .search-input {
            background: #2a2a2a;
            color: #9bbc0f;
            border: 2px solid #444;
            font-family: 'Press Start 2P', cursive;
            font-size: 8px;
        }

        .search-input:focus {
            outline: none;
            border-color: #9bbc0f;
            box-shadow: 0 0 8px rgba(155, 188, 15, 0.4);
        }

        .search-input::placeholder {
            color: #556;
        }

        .type-badge {
            font-size: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .pixel-border {
            border: 3px solid #000;
            box-shadow: 3px 3px 0 #000;
        }

        .scan-line::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0,0,0,0.05) 2px,
                rgba(0,0,0,0.05) 4px
            );
            pointer-events: none;
            border-radius: 4px;
        }

        .type-normal   { background: #A8A878; }
        .type-fire     { background: #F08030; }
        .type-water    { background: #6890F0; }
        .type-electric { background: #F8D030; color: #333; }
        .type-grass    { background: #78C850; }
        .type-ice      { background: #98D8D8; color: #333; }
        .type-fighting { background: #C03028; }
        .type-poison   { background: #A040A0; }
        .type-ground   { background: #E0C068; color: #333; }
        .type-flying   { background: #A890F0; }
        .type-psychic  { background: #F85888; }
        .type-bug      { background: #A8B820; }
        .type-rock     { background: #B8A038; }
        .type-ghost    { background: #705898; }
        .type-dragon   { background: #7038F8; }
        .type-dark     { background: #705848; }
        .type-steel    { background: #B8B8D0; color: #333; }
        .type-fairy    { background: #EE99AC; color: #333; }

        .submit-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s;
        }
        .submit-btn:active {
            transform: translateY(2px);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #660000;
        }

        .random-btn {
            background: linear-gradient(145deg, #2266cc, #1144aa);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277;
            transition: all 0.1s;
        }
        .random-btn:active {
            transform: translateY(2px);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #112277;
        }

        .stat-bar-bg { background: #1a1a1a; }
        .stat-bar-fill { background: #9bbc0f; transition: width 0.3s; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<div class="flex flex-col items-center gap-2" style="max-width: 340px; width: 100%;">

    {{-- Pokédex superior --}}
    <div class="pokedex-body w-full p-5 pb-6">

        {{-- LEDs e câmera --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full led-red pixel-border flex items-center justify-center">
                <div class="w-4 h-4 rounded-full bg-white opacity-30"></div>
            </div>
            <div class="flex gap-2">
                <div class="w-3 h-3 rounded-full led-red pixel-border"></div>
                <div class="w-3 h-3 rounded-full led-yellow pixel-border"></div>
                <div class="w-3 h-3 rounded-full led-green pixel-border"></div>
            </div>
        </div>

        {{-- Tela principal --}}
        <div class="screen-outer p-2 mb-4 mx-2">
            <div class="screen-inner scan-line relative p-3 min-h-48 flex flex-col items-center justify-center {{ session('erro') ? 'error' : '' }}">

                @if(session('erro'))
                    <p class="text-white text-center" style="font-size: 7px; line-height: 1.8;">
                        ERRO!<br><br>Pokémon não<br>encontrado.
                    </p>
                @else
                    {{-- Número da Pokédex --}}
                    <div class="w-full text-right mb-1" style="font-size: 7px; color: #3a5a00;">
                        N° {{ str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) }}
                    </div>

                    {{-- Sprite --}}
                    <img
                        src="{{ $pokemon['sprites']['other']['official-artwork']['front_default'] ?? $pokemon['sprites']['front_default'] }}"
                        alt="{{ $pokemon['name'] }}"
                        class="pokemon-sprite w-32 h-32"
                    >

                    {{-- Nome --}}
                    <div class="w-full mt-2" style="font-size: 8px; color: #1a3a00; text-align: center; text-transform: uppercase;">
                        {{ $pokemon['name'] }}
                    </div>

                    {{-- Tipos --}}
                    <div class="flex gap-1 mt-2 flex-wrap justify-center">
                        @foreach ($pokemon['types'] as $type)
                            <span class="type-badge type-{{ $type['type']['name'] }} text-white px-2 py-1 rounded pixel-border">
                                {{ $type['type']['name'] }}
                            </span>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

        {{-- Altura e Peso --}}
        @if(!session('erro'))
        <div class="flex justify-around mx-2 mb-1">
            <span style="font-size: 6px; color: #ffd6d6;">
                ALT: {{ $pokemon['height'] / 10 }}m
            </span>
            <span style="font-size: 6px; color: #ffd6d6;">
                PES: {{ $pokemon['weight'] / 10 }}kg
            </span>
        </div>
        @endif

    </div>

    {{-- Dobradiça --}}
    <div class="pokedex-hinge w-4/5 h-4 rounded"></div>

    {{-- Pokédex inferior --}}
    <div class="pokedex-body w-full p-5 pt-6">

        {{-- Tela de input --}}
        <div class="screen-outer p-2 mb-4 mx-2">
            <div class="bg-black rounded p-2">
                <p style="font-size: 6px; color: #9bbc0f; margin-bottom: 6px;">BUSCAR POKÉMON:</p>
                <form method="GET" action="" class="flex gap-2">
                    <input
                        type="text"
                        name="pokemon"
                        value="{{ request('pokemon') }}"
                        placeholder="nome ou nº"
                        class="search-input flex-1 px-2 py-1 rounded"
                        style="font-size: 7px;"
                        autocomplete="off"
                        autocapitalize="none"
                    >
                    <button type="submit" class="submit-btn text-white px-2 py-1 rounded pixel-border" style="font-size: 6px;">
                        OK
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats (base stats) --}}
        @if(!session('erro'))
        <div class="mx-2 mb-4 bg-black bg-opacity-30 rounded p-2">
            @foreach(array_slice($pokemon['stats'], 0, 6) as $stat)
                @php
                    $statNames = [
                        'hp' => 'HP', 'attack' => 'ATK', 'defense' => 'DEF',
                        'special-attack' => 'SP.A', 'special-defense' => 'SP.D', 'speed' => 'SPD'
                    ];
                    $label = $statNames[$stat['stat']['name']] ?? strtoupper($stat['stat']['name']);
                    $val = $stat['base_stat'];
                    $pct = min(100, round($val / 255 * 100));
                @endphp
                <div class="flex items-center gap-2 mb-1">
                    <span style="font-size: 5px; color: #ffd6d6; width: 28px; flex-shrink: 0;">{{ $label }}</span>
                    <div class="flex-1 stat-bar-bg rounded" style="height: 5px;">
                        <div class="stat-bar-fill rounded" style="width: {{ $pct }}%; height: 5px;"></div>
                    </div>
                    <span style="font-size: 5px; color: #ffd6d6; width: 20px; text-align: right; flex-shrink: 0;">{{ $val }}</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Botões --}}
        <div class="flex justify-between items-center mx-2">
            <a href="?" class="random-btn text-white px-3 py-2 rounded pixel-border" style="font-size: 6px; text-decoration: none;">
                RAND
            </a>
            <div class="flex gap-1">
                <div class="btn-dpad w-6 h-6 rounded flex items-center justify-center" style="font-size: 8px; color: #555;">◀</div>
                <div class="btn-dpad w-6 h-6 rounded flex items-center justify-center" style="font-size: 8px; color: #555;">▶</div>
            </div>
            <div class="flex gap-2">
                <div class="w-6 h-6 rounded-full bg-red-700 pixel-border shadow-inner"></div>
                <div class="w-6 h-6 rounded-full bg-red-700 pixel-border shadow-inner"></div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
