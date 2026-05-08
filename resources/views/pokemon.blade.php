<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        * { box-sizing: border-box; }
        body { font-family: 'Press Start 2P', cursive; background: #1a1a2e; }

        /* ── Pokédex device (left card) ── */
        .pokedex-body {
            background: linear-gradient(145deg, #e63946, #c1121f);
            border-radius: 16px;
            box-shadow: inset -4px -4px 0 #9b0000, inset 4px 4px 0 #ff6b6b, 0 10px 40px rgba(0,0,0,0.6);
        }
        .screen-outer {
            background: #1a1a1a;
            border-radius: 8px;
            box-shadow: inset 0 0 0 3px #333, inset 0 0 0 6px #555;
        }
        .screen-inner {
            background: #9bbc0f;
            border-radius: 4px;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.3);
            position: relative;
        }
        .screen-inner.error-screen { background: #bc0f0f; }
        .scan-line::after {
            content: ''; position: absolute; inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.05) 2px, rgba(0,0,0,0.05) 4px);
            pointer-events: none; border-radius: 4px;
        }
        .led-red    { background: #ff4444; box-shadow: 0 0 8px #ff0000; }
        .led-yellow { background: #ffcc00; box-shadow: 0 0 8px #ffaa00; }
        .led-green  { background: #44ff44; box-shadow: 0 0 8px #00ff00; }
        .pixel-border { border: 2px solid #000; box-shadow: 2px 2px 0 #000; }

        /* ── Type badges ── */
        .type-badge { font-size: 6px; text-transform: uppercase; letter-spacing: 1px; }
        .type-normal   { background: #A8A878; } .type-fire     { background: #F08030; }
        .type-water    { background: #6890F0; } .type-electric { background: #F8D030; color:#333; }
        .type-grass    { background: #78C850; } .type-ice      { background: #98D8D8; color:#333; }
        .type-fighting { background: #C03028; } .type-poison   { background: #A040A0; }
        .type-ground   { background: #E0C068; color:#333; } .type-flying { background: #A890F0; }
        .type-psychic  { background: #F85888; } .type-bug      { background: #A8B820; }
        .type-rock     { background: #B8A038; } .type-ghost    { background: #705898; }
        .type-dragon   { background: #7038F8; } .type-dark     { background: #705848; }
        .type-steel    { background: #B8B8D0; color:#333; } .type-fairy  { background: #EE99AC; color:#333; }

        /* ── Search & gen buttons ── */
        .search-input {
            background: #0d0d1a; color: #9bbc0f; border: 2px solid #2a3a0a;
            font-family: 'Press Start 2P', cursive; font-size: 8px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-input:focus { outline: none; border-color: #9bbc0f; box-shadow: 0 0 10px rgba(155,188,15,0.3); }
        .search-input::placeholder { color: #2a3a1a; }

        .gen-btn {
            background: #0d0d1a; color: #4a5568; border: 2px solid #1e2e1e;
            font-family: 'Press Start 2P', cursive; font-size: 5px;
            padding: 5px 9px; border-radius: 4px; cursor: pointer;
            text-decoration: none; display: inline-block; transition: all 0.1s; white-space: nowrap;
        }
        .gen-btn.active { background: #9bbc0f; color: #000; border-color: #9bbc0f; }
        .gen-btn:hover:not(.active) { border-color: #4a6a2a; color: #9bbc0f; }

        /* ── Action buttons ── */
        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
            text-decoration: none; display: inline-block; cursor: pointer; border: none;
        }
        .action-btn:active { transform: translateY(2px); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #660000; }
        .action-btn.blue { background: linear-gradient(145deg, #2266cc, #1144aa); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277; }
        .action-btn.blue:active { box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #112277; }
        .action-btn.yellow { background: linear-gradient(145deg, #cc9900, #aa7700); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #775500; }
        .action-btn.yellow:active { box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #775500; }
        .action-btn.green { background: linear-gradient(145deg, #229922, #117711); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #115511; }
        .action-btn.green:active { box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #115511; }
        .action-btn.dark { background: linear-gradient(145deg, #2a2a2a, #1a1a1a); box-shadow: inset 0 2px 0 rgba(255,255,255,0.1), 0 3px 0 #000; color: #aaa; }
        .action-btn.dark:active { box-shadow: inset 0 2px 0 rgba(255,255,255,0.1), 0 1px 0 #000; }

        /* ── Info panels (right column) ── */
        .info-panel {
            background: #16213e;
            border: 2px solid #1e2e4a;
            border-radius: 10px;
            overflow: hidden;
        }
        .panel-header {
            font-size: 6px; color: #4a6a2a; letter-spacing: 2px; text-transform: uppercase;
            padding: 10px 16px; border-bottom: 1px solid #1e2e4a;
            display: flex; justify-content: space-between; align-items: center;
        }
        .panel-body { padding: 14px 16px; }

        /* ── Stat bars ── */
        .stat-bar-bg { background: #0a0a1a; border-radius: 6px; overflow: hidden; height: 10px; }
        .stat-bar { height: 100%; border-radius: 6px; transition: width 0.8s cubic-bezier(.22,.61,.36,1); }

        /* ── Pokémon sprite ── */
        .pokemon-sprite { image-rendering: pixelated; filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); transition: filter 0.3s; }
        .pokemon-sprite.shiny    { filter: drop-shadow(0 0 14px #ffd700) brightness(1.08); }
        .pokemon-sprite.silhouette { filter: brightness(0); }
        .quiz-reveal { cursor: pointer; }
        .quiz-label { font-size: 6px; color: #1a3a00; text-align: center; text-transform: uppercase; }
        .quiz-label.hidden-name { color: #9bbc0f; letter-spacing: 4px; }
        .flavor-text { font-size: 6px; color: #1a3a00; line-height: 2; text-align: center; }

        /* ── Team slots ── */
        .team-slot {
            width: 48px; height: 48px; border: 2px solid #1e2e1e;
            border-radius: 8px; background: #0a0a1a;
            display: flex; align-items: center; justify-content: center;
            transition: border-color 0.2s, transform 0.1s;
        }
        .team-slot:hover { border-color: #9bbc0f; transform: translateY(-2px); }
        .team-slot img { width: 38px; height: 38px; image-rendering: pixelated; }
        .team-slot.empty { border-style: dashed; border-color: #1e2e1e; }

        /* ── Loader ── */
        @keyframes pokeball-spin { to { transform: rotate(360deg); } }
        .pokeball-loader {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75);
            align-items: center; justify-content: center; z-index: 999;
        }
        .pokeball-loader.visible { display: flex; }
        .pokeball-spin { animation: pokeball-spin 0.6s linear infinite; font-size: 56px; }

        /* ── Cry animations ── */
        @keyframes screen-flash {
            0%   { background: #9bbc0f; }
            20%  { background: #c8e830; }
            50%  { background: #ffffff; }
            70%  { background: #c8e830; }
            100% { background: #9bbc0f; }
        }
        @keyframes sprite-bounce {
            0%, 100% { transform: translateY(0); }
            30%       { transform: translateY(-8px); }
            60%       { transform: translateY(-3px); }
        }
        @keyframes led-cry-pulse {
            0%, 100% { box-shadow: 0 0 8px #00ff00; }
            50%       { box-shadow: 0 0 20px #00ff00, 0 0 40px #00ff00; }
        }
        .crying .screen-inner     { animation: screen-flash 0.6s ease-out; }
        .crying #pokemon-sprite   { animation: sprite-bounce 0.5s ease-out; }
        .crying #led-green        { animation: led-cry-pulse 0.4s ease-in-out 3; }

        #cry-canvas { display: none; border-radius: 2px; }
        #cry-canvas.active { display: block; }
    </style>
</head>
<body class="min-h-screen">

{{-- Loader --}}
<div class="pokeball-loader" id="loader">
    <div class="pokeball-spin">⚪</div>
</div>

{{-- Nav --}}
@include('partials.nav')

@php
    $typeChart = [
        'normal'   => ['weak'=>['fighting'],                             'immune'=>['ghost']],
        'fire'     => ['weak'=>['water','ground','rock'],                'immune'=>[]],
        'water'    => ['weak'=>['electric','grass'],                     'immune'=>[]],
        'electric' => ['weak'=>['ground'],                               'immune'=>[]],
        'grass'    => ['weak'=>['fire','ice','poison','flying','bug'],   'immune'=>[]],
        'ice'      => ['weak'=>['fire','fighting','rock','steel'],       'immune'=>[]],
        'fighting' => ['weak'=>['flying','psychic','fairy'],             'immune'=>[]],
        'poison'   => ['weak'=>['ground','psychic'],                     'immune'=>[]],
        'ground'   => ['weak'=>['water','grass','ice'],                  'immune'=>['electric']],
        'flying'   => ['weak'=>['electric','ice','rock'],                'immune'=>['ground']],
        'psychic'  => ['weak'=>['bug','ghost','dark'],                   'immune'=>[]],
        'bug'      => ['weak'=>['fire','flying','rock'],                 'immune'=>[]],
        'rock'     => ['weak'=>['water','grass','fighting','ground','steel'], 'immune'=>[]],
        'ghost'    => ['weak'=>['ghost','dark'],                         'immune'=>['normal','fighting']],
        'dragon'   => ['weak'=>['ice','dragon','fairy'],                 'immune'=>[]],
        'dark'     => ['weak'=>['fighting','bug','fairy'],               'immune'=>['psychic']],
        'steel'    => ['weak'=>['fire','fighting','ground'],             'immune'=>['poison']],
        'fairy'    => ['weak'=>['poison','steel'],                       'immune'=>['dragon']],
    ];

    $weaknesses = [];
    $immunities = [];

    if (isset($pokemon)) {
        foreach ($pokemon['types'] as $t) {
            $typeName = $t['type']['name'];
            if (isset($typeChart[$typeName])) {
                foreach ($typeChart[$typeName]['weak'] as $w) {
                    $weaknesses[$w] = ($weaknesses[$w] ?? 0) + 1;
                }
                foreach ($typeChart[$typeName]['immune'] as $i) {
                    $immunities[] = $i;
                }
            }
        }
        foreach ($immunities as $immune) {
            unset($weaknesses[$immune]);
        }
        $weaknesses = array_keys($weaknesses);
    }

    $currentId   = $pokemon['id'] ?? 0;
    $prevId      = max(1, $currentId - 1);
    $nextId      = $currentId + 1;
    $currentGen  = $gen ?? 1;
    $team        = session('team', []);
    $inTeam      = collect($team)->contains('id', $currentId);
    $normalSprite = $pokemon['sprites']['other']['official-artwork']['front_default'] ?? $pokemon['sprites']['front_default'] ?? '';
    $shinySprite  = $pokemon['sprites']['other']['official-artwork']['front_shiny']   ?? $pokemon['sprites']['front_shiny'] ?? $normalSprite;
    $cry = $pokemon['cries']['latest'] ?? null;

    $statColors = [
        'hp'              => '#FF5959',
        'attack'          => '#F5AC78',
        'defense'         => '#FAE078',
        'special-attack'  => '#9DB7F5',
        'special-defense' => '#A7DB8D',
        'speed'           => '#FA92B2',
    ];
    $statLabels = [
        'hp'=>'HP', 'attack'=>'ATK', 'defense'=>'DEF',
        'special-attack'=>'SP.A', 'special-defense'=>'SP.D', 'speed'=>'VEL',
    ];
@endphp

<main class="max-w-6xl mx-auto px-4 pt-5 pb-10">

    {{-- ── SEARCH + GENERATIONS ── --}}
    <div class="mb-6">
        <form method="GET" action="/" class="flex gap-2 mb-3" onsubmit="showLoader()">
            <input type="hidden" name="gen" value="{{ $currentGen }}">
            <input type="text" name="pokemon"
                   value="{{ request('pokemon') }}"
                   placeholder="Nome ou número do Pokémon..."
                   class="search-input flex-1 px-4 py-3 rounded"
                   autocomplete="off" autocapitalize="none">
            <button type="submit" class="action-btn px-5 py-3 rounded text-xs">BUSCAR</button>
        </form>
        <div class="flex flex-wrap gap-1">
            @for($g = 1; $g <= 9; $g++)
                <a href="/?gen={{ $g }}" onclick="showLoader()" class="gen-btn {{ $g == $currentGen ? 'active' : '' }}">
                    GEN&nbsp;{{ $g }}
                </a>
            @endfor
        </div>
    </div>

    @if(session('erro'))
    {{-- ── ERROR STATE ── --}}
    <div class="flex justify-center py-16">
        <div class="info-panel max-w-sm w-full text-center">
            <div class="panel-header justify-center">ERRO</div>
            <div class="panel-body py-8">
                <p class="text-red-400 text-xs mb-4">Pokémon não encontrado!</p>
                <p class="text-gray-600" style="font-size: 6px; line-height: 2;">Verifique o nome ou número<br>e tente novamente.</p>
            </div>
        </div>
    </div>

    @else
    {{-- ── MAIN GRID ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

        {{-- ── LEFT: Pokédex device (2 cols) ── --}}
        <div class="lg:col-span-2 flex flex-col gap-3">
            <div class="pokedex-body p-5">

                {{-- LEDs --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full led-red pixel-border flex items-center justify-center flex-shrink-0">
                        <div class="w-4 h-4 rounded-full bg-white opacity-30"></div>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full led-red pixel-border"></div>
                        <div class="w-3 h-3 rounded-full led-yellow pixel-border"></div>
                        <div id="led-green" class="w-3 h-3 rounded-full led-green pixel-border"></div>
                    </div>
                    <div class="ml-auto text-right">
                        <p style="font-size: 5px; color: rgba(255,214,214,0.6);">POKÉDEX</p>
                    </div>
                </div>
                @if($cry)
                    <audio id="cry-audio" src="{{ $cry }}" crossorigin="anonymous" preload="none"></audio>
                @endif

                {{-- Screen --}}
                <div class="screen-outer p-2 mb-4">
                    <div class="screen-inner scan-line p-4 flex flex-col items-center" style="min-height: 260px;">

                        {{-- Gen + Number --}}
                        <div class="w-full flex justify-between mb-2">
                            <span style="font-size: 6px; color: #3a5a00;">GEN {{ $currentGen }}</span>
                            <span style="font-size: 6px; color: #3a5a00;">Nº {{ str_pad($currentId, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        {{-- Sprite --}}
                        <div class="flex-1 flex items-center justify-center w-full relative">
                            <img id="pokemon-sprite"
                                 src="{{ $normalSprite }}"
                                 data-normal="{{ $normalSprite }}"
                                 data-shiny="{{ $shinySprite }}"
                                 alt="{{ $pokemon['name'] }}"
                                 class="pokemon-sprite w-36 h-36">
                            <canvas id="cry-canvas" width="160" height="40"
                                    style="position: absolute; bottom: -8px; left: 0; right: 0; margin: auto; opacity: 0.7;"></canvas>
                        </div>

                        {{-- Name --}}
                        <div class="w-full mt-2 quiz-label">
                            {{ strtoupper($pokemon['name']) }}
                        </div>

                        {{-- Types --}}
                        <div class="flex gap-1 mt-2 flex-wrap justify-center">
                            @foreach($pokemon['types'] as $type)
                                <span class="type-badge type-{{ $type['type']['name'] }} text-white px-2 py-1 rounded pixel-border">
                                    {{ $type['type']['name'] }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Flavor text --}}
                        @if($flavorText)
                            <p class="flavor-text mt-3 px-1">{{ $flavorText }}</p>
                        @endif
                    </div>
                </div>

                {{-- Height / Weight --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="text-center">
                        <p style="font-size: 5px; color: rgba(255,214,214,0.6); margin-bottom: 2px;">ALTURA</p>
                        <p style="font-size: 8px; color: #ffd6d6;">{{ $pokemon['height'] / 10 }}m</p>
                    </div>
                    <div class="text-center">
                        <p style="font-size: 5px; color: rgba(255,214,214,0.6); margin-bottom: 2px;">PESO</p>
                        <p style="font-size: 8px; color: #ffd6d6;">{{ $pokemon['weight'] / 10 }}kg</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 flex-wrap justify-center">
                    @if($cry)
                        <button onclick="playCry()" class="action-btn green px-4 py-2 rounded text-xs">♪ CRY</button>
                    @endif
                    <button id="shiny-toggle" onclick="toggleShiny()"
                            class="action-btn yellow px-4 py-2 rounded text-xs">★ SHINY</button>
                    <a href="{{ route('quiz') }}" class="action-btn blue px-4 py-2 rounded text-xs">? QUIZ</a>
                </div>
            </div>

            {{-- ── NAVIGATION ── --}}
            <div class="flex flex-col gap-2">
                <a href="/?gen={{ $currentGen }}" onclick="showLoader()"
                   class="action-btn blue px-5 py-3 rounded text-xs text-center">
                    ⟳ ALEATÓRIO
                </a>
                <div class="flex gap-2">
                    <a href="/?pokemon={{ $prevId }}&gen={{ $currentGen }}" onclick="showLoader()"
                       class="action-btn dark px-5 py-3 rounded text-xs flex-1 text-center">
                        ◀ ANTERIOR
                    </a>
                    <a href="/?pokemon={{ $nextId }}&gen={{ $currentGen }}" onclick="showLoader()"
                       class="action-btn dark px-5 py-3 rounded text-xs flex-1 text-center">
                        PRÓXIMO ▶
                    </a>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Info panels (3 cols) ── --}}
        <div class="lg:col-span-3 flex flex-col gap-4">

            {{-- Stats --}}
            <div class="info-panel">
                <div class="panel-header">
                    <span>ESTATÍSTICAS BASE</span>
                    <span style="color: #9bbc0f;">TOTAL: {{ collect($pokemon['stats'])->sum('base_stat') }}</span>
                </div>
                <div class="panel-body">
                    @foreach($pokemon['stats'] as $stat)
                        @php
                            $sName  = $stat['stat']['name'];
                            $sVal   = $stat['base_stat'];
                            $sPct   = min(100, round($sVal / 255 * 100));
                            $sColor = $statColors[$sName] ?? '#9bbc0f';
                            $sLabel = $statLabels[$sName]  ?? strtoupper($sName);
                        @endphp
                        <div class="flex items-center gap-3 mb-3 last:mb-0">
                            <span class="text-gray-500 flex-shrink-0 text-right" style="font-size: 6px; width: 30px;">{{ $sLabel }}</span>
                            <span class="text-gray-300 flex-shrink-0 text-right" style="font-size: 7px; width: 26px;">{{ $sVal }}</span>
                            <div class="flex-1 stat-bar-bg">
                                <div class="stat-bar" style="width: {{ $sPct }}%; background: {{ $sColor }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Weaknesses --}}
            @if(count($weaknesses))
            <div class="info-panel">
                <div class="panel-header">FRAQUEZAS</div>
                <div class="panel-body">
                    <div class="flex flex-wrap gap-2">
                        @foreach($weaknesses as $w)
                            <span class="type-badge type-{{ $w }} text-white px-3 py-1 rounded pixel-border">{{ $w }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Evolution chain --}}
            @if(count($evolutionChain) > 1)
            <div class="info-panel">
                <div class="panel-header">CADEIA EVOLUTIVA</div>
                <div class="panel-body">
                    <div class="flex items-center justify-center gap-3 flex-wrap">
                        @foreach($evolutionChain as $evo)
                            <a href="/?pokemon={{ $evo['name'] }}&gen={{ $currentGen }}" onclick="showLoader()"
                               class="flex flex-col items-center gap-1 transition-opacity hover:opacity-100 {{ $evo['id'] == $currentId ? 'opacity-100' : 'opacity-50' }}"
                               style="text-decoration: none;">
                                <img src="{{ $evo['sprite'] }}" alt="{{ $evo['name'] }}"
                                     style="width: 52px; height: 52px; image-rendering: pixelated;">
                                <span style="font-size: 5px; color: #9bbc0f; text-transform: uppercase;">{{ $evo['name'] }}</span>
                            </a>
                            @if(!$loop->last)
                                <span style="font-size: 18px; color: #4a6a2a;">▶</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Moves --}}
            @if(!empty($pokemon['moves']))
            @php
                $allMoves    = $pokemon['moves'];
                $movesCount  = count($allMoves);
                $displayMoves = array_slice($allMoves, 0, 24);
            @endphp
            <div class="info-panel">
                <div class="panel-header">
                    <span>MOVIMENTOS</span>
                    <span style="color: #9bbc0f;">{{ $movesCount }} no total</span>
                </div>
                <div class="panel-body">
                    <div class="flex flex-wrap gap-1">
                        @foreach($displayMoves as $m)
                            <span style="font-size: 5px; background: #0d0d1a; border: 1px solid #1e2e4a; color: #9bbc0f; padding: 4px 8px; border-radius: 4px; white-space: nowrap; letter-spacing: 0.5px;">
                                {{ ucwords(str_replace('-', ' ', $m['move']['name'])) }}
                            </span>
                        @endforeach
                        @if($movesCount > 24)
                            <span style="font-size: 5px; color: #4a5568; padding: 4px 6px; align-self: center;">
                                +{{ $movesCount - 24 }} mais...
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Team --}}
            <div class="info-panel">
                <div class="panel-header">
                    <span>MEU TIME</span>
                    <span style="color: {{ count($team) >= 6 ? '#F8D030' : '#9bbc0f' }};">{{ count($team) }}/6</span>
                </div>
                <div class="panel-body">
                    <div class="flex gap-2 flex-wrap mb-4">
                        @for($s = 0; $s < 6; $s++)
                            @if(isset($team[$s]))
                                <a href="/?pokemon={{ $team[$s]['name'] }}&gen={{ $currentGen }}" onclick="showLoader()"
                                   class="team-slot" title="{{ strtoupper($team[$s]['name']) }}" style="text-decoration: none;">
                                    <img src="{{ $team[$s]['sprite'] }}" alt="{{ $team[$s]['name'] }}">
                                </a>
                            @else
                                <div class="team-slot empty"></div>
                            @endif
                        @endfor
                    </div>

                    @if(!$inTeam && count($team) < 6)
                        <form method="POST" action="{{ route('team.add') }}">
                            @csrf
                            <input type="hidden" name="id"     value="{{ $currentId }}">
                            <input type="hidden" name="name"   value="{{ $pokemon['name'] }}">
                            <input type="hidden" name="sprite" value="{{ $pokemon['sprites']['front_default'] }}">
                            <button type="submit" class="action-btn green px-4 py-2 rounded text-xs">
                                + ADICIONAR AO TIME
                            </button>
                        </form>
                    @elseif($inTeam)
                        <form method="POST" action="{{ route('team.remove') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $currentId }}">
                            <button type="submit" class="action-btn px-4 py-2 rounded text-xs">
                                − REMOVER DO TIME
                            </button>
                        </form>
                    @else
                        <p style="font-size: 6px; color: #F8D030;">⚡ Time completo! 6/6</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @endif

</main>

<script>
    let isShiny = false;

    function toggleShiny() {
        const sprite = document.getElementById('pokemon-sprite');
        const btn    = document.getElementById('shiny-toggle');
        isShiny = !isShiny;
        sprite.src = isShiny ? sprite.dataset.shiny : sprite.dataset.normal;
        sprite.classList.toggle('shiny', isShiny);
        btn.style.opacity = isShiny ? '1' : '0.6';
    }

    function showLoader() {
        document.getElementById('loader').classList.add('visible');
    }

    window.addEventListener('pageshow', () => {
        document.getElementById('loader').classList.remove('visible');
    });

    // ── Cry system ──
    let cryAudioCtx  = null;
    let cryAnalyser  = null;
    let crySource    = null;
    let cryAnimFrame = null;

    function playCry() {
        const audio = document.getElementById('cry-audio');
        if (!audio) return;

        if (!cryAudioCtx) {
            cryAudioCtx = new (window.AudioContext || window.webkitAudioContext)();
            cryAnalyser = cryAudioCtx.createAnalyser();
            cryAnalyser.fftSize = 256;
            crySource = cryAudioCtx.createMediaElementSource(audio);
            crySource.connect(cryAnalyser);
            cryAnalyser.connect(cryAudioCtx.destination);
        }

        if (cryAudioCtx.state === 'suspended') cryAudioCtx.resume();

        audio.currentTime = 0;
        audio.play();

        const startVisuals = () => {
            const duration = audio.duration > 0 ? audio.duration * 1000 : 1500;
            animateCry(duration);
        };

        audio.duration > 0 ? startVisuals() : (audio.onloadedmetadata = startVisuals);
    }

    function animateCry(durationMs) {
        const device = document.querySelector('.pokedex-body');
        const canvas = document.getElementById('cry-canvas');
        const ctx    = canvas.getContext('2d');

        device.classList.add('crying');
        canvas.classList.add('active');

        cancelAnimationFrame(cryAnimFrame);

        function drawWave() {
            if (!cryAnalyser) return;
            const buf = new Uint8Array(cryAnalyser.frequencyBinCount);
            cryAnalyser.getByteTimeDomainData(buf);

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.beginPath();
            ctx.strokeStyle = '#1a3a00';
            ctx.lineWidth   = 2;

            const sliceW = canvas.width / buf.length;
            let x = 0;
            for (let i = 0; i < buf.length; i++) {
                const v = buf[i] / 128.0;
                const y = (v * canvas.height) / 2;
                i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
                x += sliceW;
            }
            ctx.stroke();
            cryAnimFrame = requestAnimationFrame(drawWave);
        }
        drawWave();

        setTimeout(() => {
            device.classList.remove('crying');
            canvas.classList.remove('active');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            cancelAnimationFrame(cryAnimFrame);
        }, durationMs + 300);
    }
</script>

</body>
</html>
