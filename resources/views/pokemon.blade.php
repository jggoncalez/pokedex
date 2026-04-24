<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

        body { font-family: 'Press Start 2P', cursive; background: #1a1a2e; }

        .pokedex-body {
            background: linear-gradient(145deg, #e63946, #c1121f);
            border-radius: 16px 16px 50% 50% / 16px 16px 30px 30px;
            box-shadow: inset -4px -4px 0 #9b0000, inset 4px 4px 0 #ff6b6b, 0 10px 40px rgba(0,0,0,0.6);
            position: relative;
        }
        .pokedex-hinge {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.4);
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
        }
        .screen-inner.error { background: #bc0f0f; }

        .led-red    { background: #ff4444; box-shadow: 0 0 8px #ff0000; }
        .led-yellow { background: #ffcc00; box-shadow: 0 0 8px #ffaa00; }
        .led-green  { background: #44ff44; box-shadow: 0 0 8px #00ff00; }

        .pokemon-sprite { image-rendering: pixelated; filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); transition: filter 0.3s; }
        .pokemon-sprite.shiny { filter: drop-shadow(0 0 12px #ffd700) brightness(1.05); }
        .pokemon-sprite.silhouette { filter: brightness(0); }

        .scan-line::after {
            content: ''; position: absolute; top:0; left:0; right:0; bottom:0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.05) 2px, rgba(0,0,0,0.05) 4px);
            pointer-events: none; border-radius: 4px;
        }
        .pixel-border { border: 3px solid #000; box-shadow: 3px 3px 0 #000; }

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

        .search-input {
            background: #2a2a2a; color: #9bbc0f; border: 2px solid #444;
            font-family: 'Press Start 2P', cursive; font-size: 8px;
        }
        .search-input:focus { outline: none; border-color: #9bbc0f; box-shadow: 0 0 8px rgba(155,188,15,0.4); }
        .search-input::placeholder { color: #556; }

        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
        }
        .action-btn:active { transform: translateY(2px); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #660000; }
        .action-btn.blue {
            background: linear-gradient(145deg, #2266cc, #1144aa);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277;
        }
        .action-btn.blue:active { box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 1px 0 #112277; }
        .action-btn.yellow {
            background: linear-gradient(145deg, #cc9900, #aa7700);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #775500;
        }
        .action-btn.green {
            background: linear-gradient(145deg, #229922, #117711);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #115511;
        }
        .action-btn.nav {
            background: linear-gradient(145deg, #333, #222);
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.1), 0 2px 4px rgba(0,0,0,0.5);
        }

        .stat-bar-bg { background: #1a1a1a; }
        .stat-bar-fill { background: #9bbc0f; }

        .team-slot {
            width: 36px; height: 36px; border: 2px solid #333;
            border-radius: 4px; background: #111;
            display: flex; align-items: center; justify-content: center;
        }
        .team-slot img { width: 30px; height: 30px; image-rendering: pixelated; }
        .team-slot.empty { border: 2px dashed #333; }

        .gen-btn {
            background: #222; color: #666; border: 2px solid #333;
            font-family: 'Press Start 2P', cursive; font-size: 5px;
            padding: 3px 5px; border-radius: 3px; cursor: pointer; transition: all 0.1s;
        }
        .gen-btn.active { background: #9bbc0f; color: #000; border-color: #9bbc0f; }
        .gen-btn:hover:not(.active) { border-color: #555; color: #999; }

        @keyframes pokeball-spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .pokeball-loader {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7);
            align-items: center; justify-content: center; z-index: 50;
        }
        .pokeball-loader.visible { display: flex; }
        .pokeball-spin { animation: pokeball-spin 0.6s linear infinite; font-size: 48px; }

        .quiz-reveal { cursor: pointer; }
        .quiz-label { font-size: 6px; color: #1a3a00; text-align: center; text-transform: uppercase; }
        .quiz-label.hidden-name { color: #9bbc0f; letter-spacing: 2px; }

        .flavor-text { font-size: 6px; color: #1a3a00; line-height: 1.8; text-align: center; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

{{-- Loader Pokébola --}}
<div class="pokeball-loader" id="loader">
    <div class="pokeball-spin">⚪</div>
</div>

@php
    $typeChart = [
        'normal'   => ['weak'=>['fighting'],'immune'=>['ghost']],
        'fire'     => ['weak'=>['water','ground','rock'],'immune'=>[]],
        'water'    => ['weak'=>['electric','grass'],'immune'=>[]],
        'electric' => ['weak'=>['ground'],'immune'=>[]],
        'grass'    => ['weak'=>['fire','ice','poison','flying','bug'],'immune'=>[]],
        'ice'      => ['weak'=>['fire','fighting','rock','steel'],'immune'=>[]],
        'fighting' => ['weak'=>['flying','psychic','fairy'],'immune'=>[]],
        'poison'   => ['weak'=>['ground','psychic'],'immune'=>[]],
        'ground'   => ['weak'=>['water','grass','ice'],'immune'=>['electric']],
        'flying'   => ['weak'=>['electric','ice','rock'],'immune'=>['ground']],
        'psychic'  => ['weak'=>['bug','ghost','dark'],'immune'=>[]],
        'bug'      => ['weak'=>['fire','flying','rock'],'immune'=>[]],
        'rock'     => ['weak'=>['water','grass','fighting','ground','steel'],'immune'=>[]],
        'ghost'    => ['weak'=>['ghost','dark'],'immune'=>['normal','fighting']],
        'dragon'   => ['weak'=>['ice','dragon','fairy'],'immune'=>[]],
        'dark'     => ['weak'=>['fighting','bug','fairy'],'immune'=>['psychic']],
        'steel'    => ['weak'=>['fire','fighting','ground'],'immune'=>['poison']],
        'fairy'    => ['weak'=>['poison','steel'],'immune'=>['dragon']],
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

    $currentId = $pokemon['id'] ?? 0;
    $prevId = max(1, $currentId - 1);
    $nextId = $currentId + 1;
    $currentGen = $gen ?? 1;
    $team = session('team', []);
    $inTeam = collect($team)->contains('id', $currentId);
    $isQuizMode = request()->has('quiz');
    $normalSprite = $pokemon['sprites']['other']['official-artwork']['front_default'] ?? $pokemon['sprites']['front_default'] ?? '';
    $shinySprite  = $pokemon['sprites']['other']['official-artwork']['front_shiny']   ?? $pokemon['sprites']['front_shiny'] ?? $normalSprite;
    $cry = $pokemon['cries']['latest'] ?? null;
@endphp

<div class="flex flex-col items-center gap-2" style="max-width: 360px; width: 100%;">

    {{-- ===== POKÉDEX SUPERIOR ===== --}}
    <div class="pokedex-body w-full p-5 pb-6">

        {{-- LEDs --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full led-red pixel-border flex items-center justify-center">
                <div class="w-4 h-4 rounded-full bg-white opacity-30"></div>
            </div>
            <div class="flex gap-2">
                <div class="w-3 h-3 rounded-full led-red pixel-border"></div>
                <div class="w-3 h-3 rounded-full led-yellow pixel-border"></div>
                <div class="w-3 h-3 rounded-full led-green pixel-border"></div>
            </div>
            @if(isset($pokemon) && $cry)
            <button onclick="document.getElementById('cry-audio').play()" title="Ouvir grito"
                class="ml-auto action-btn px-2 py-1 rounded pixel-border" style="font-size: 8px;">
                ♪
            </button>
            <audio id="cry-audio" src="{{ $cry }}" preload="none"></audio>
            @endif
        </div>

        {{-- Tela principal --}}
        <div class="screen-outer p-2 mb-3 mx-2">
            <div class="screen-inner scan-line relative p-3 min-h-52 flex flex-col items-center justify-center {{ session('erro') ? 'error' : '' }}">

                @if(session('erro'))
                    <p class="text-white text-center" style="font-size: 7px; line-height: 1.8;">
                        ERRO!<br><br>Pokémon não<br>encontrado.
                    </p>
                @else
                    <div class="w-full flex justify-between mb-1">
                        <span style="font-size: 6px; color: #3a5a00;">GEN {{ $currentGen }}</span>
                        <span style="font-size: 6px; color: #3a5a00;">N° {{ str_pad($currentId, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    {{-- Sprite (quiz ou normal) --}}
                    <div class="quiz-reveal" @if($isQuizMode) onclick="revealPokemon()" @endif>
                        <img
                            id="pokemon-sprite"
                            src="{{ $normalSprite }}"
                            data-normal="{{ $normalSprite }}"
                            data-shiny="{{ $shinySprite }}"
                            alt="{{ $pokemon['name'] }}"
                            class="pokemon-sprite w-32 h-32 {{ $isQuizMode ? 'silhouette' : '' }}"
                        >
                    </div>

                    {{-- Nome (oculto no quiz) --}}
                    <div id="pokemon-name" class="w-full mt-2 quiz-label {{ $isQuizMode ? 'hidden-name' : '' }}"
                         data-name="{{ strtoupper($pokemon['name']) }}">
                        @if($isQuizMode)
                            ???
                        @else
                            {{ strtoupper($pokemon['name']) }}
                        @endif
                    </div>

                    {{-- Tipos --}}
                    <div class="flex gap-1 mt-2 flex-wrap justify-center">
                        @foreach ($pokemon['types'] as $type)
                            <span class="type-badge type-{{ $type['type']['name'] }} text-white px-2 py-1 rounded pixel-border">
                                {{ $type['type']['name'] }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Flavor text --}}
                    @if($flavorText)
                    <p class="flavor-text mt-2 px-1">{{ $flavorText }}</p>
                    @endif
                @endif
            </div>
        </div>

        {{-- Altura, Peso e botões de sprite --}}
        @if(!session('erro'))
        <div class="flex items-center justify-between mx-2 mb-1">
            <span style="font-size: 6px; color: #ffd6d6;">ALT: {{ $pokemon['height'] / 10 }}m</span>
            <div class="flex gap-1">
                <button id="shiny-toggle" onclick="toggleShiny()"
                    class="action-btn yellow px-2 py-1 rounded pixel-border" style="font-size: 6px;" title="Toggle shiny">
                    ★
                </button>
                <a href="{{ request()->fullUrlWithQuery(['quiz' => $isQuizMode ? null : '1']) }}"
                   class="action-btn green px-2 py-1 rounded pixel-border" style="font-size: 6px; text-decoration:none;" title="Modo quiz">
                    ?
                </a>
            </div>
            <span style="font-size: 6px; color: #ffd6d6;">PES: {{ $pokemon['weight'] / 10 }}kg</span>
        </div>
        @endif

    </div>

    {{-- Dobradiça --}}
    <div class="pokedex-hinge w-4/5 h-4 rounded"></div>

    {{-- ===== POKÉDEX INFERIOR ===== --}}
    <div class="pokedex-body w-full p-5 pt-6">

        {{-- Tela de busca e geração --}}
        <div class="screen-outer p-2 mb-3 mx-2">
            <div class="bg-black rounded p-2">
                <p style="font-size: 6px; color: #9bbc0f; margin-bottom: 4px;">BUSCAR POKÉMON:</p>
                <form id="search-form" method="GET" action="" class="flex gap-2 mb-2" onsubmit="showLoader()">
                    <input type="hidden" name="gen" value="{{ $currentGen }}">
                    <input
                        type="text" name="pokemon"
                        value="{{ request('pokemon') }}"
                        placeholder="nome ou nº"
                        class="search-input flex-1 px-2 py-1 rounded"
                        style="font-size: 7px;"
                        autocomplete="off" autocapitalize="none"
                    >
                    <button type="submit" class="action-btn px-2 py-1 rounded pixel-border" style="font-size: 6px;">
                        OK
                    </button>
                </form>
                <p style="font-size: 5px; color: #556; margin-bottom: 4px;">GERAÇÃO:</p>
                <div class="flex flex-wrap gap-1">
                    @for($g = 1; $g <= 9; $g++)
                        <a href="?gen={{ $g }}" onclick="showLoader()"
                           class="gen-btn {{ $g == $currentGen ? 'active' : '' }}">
                            G{{ $g }}
                        </a>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Stats --}}
        @if(!session('erro'))
        <div class="mx-2 mb-3 bg-black bg-opacity-30 rounded p-2">
            @foreach(array_slice($pokemon['stats'], 0, 6) as $stat)
                @php
                    $labels = ['hp'=>'HP','attack'=>'ATK','defense'=>'DEF','special-attack'=>'SP.A','special-defense'=>'SP.D','speed'=>'SPD'];
                    $label  = $labels[$stat['stat']['name']] ?? strtoupper($stat['stat']['name']);
                    $val    = $stat['base_stat'];
                    $pct    = min(100, round($val / 255 * 100));
                    $color  = $val < 50 ? '#bc0f0f' : ($val < 90 ? '#9bbc0f' : '#44ff44');
                @endphp
                <div class="flex items-center gap-2 mb-1">
                    <span style="font-size: 5px; color: #ffd6d6; width: 28px; flex-shrink:0;">{{ $label }}</span>
                    <div class="flex-1 stat-bar-bg rounded" style="height: 5px;">
                        <div class="rounded" style="width:{{ $pct }}%; height:5px; background:{{ $color }};"></div>
                    </div>
                    <span style="font-size: 5px; color: #ffd6d6; width: 20px; text-align:right; flex-shrink:0;">{{ $val }}</span>
                </div>
            @endforeach
        </div>

        {{-- Fraquezas --}}
        @if(count($weaknesses))
        <div class="mx-2 mb-3 bg-black bg-opacity-30 rounded p-2">
            <p style="font-size: 5px; color: #ffd6d6; margin-bottom: 4px;">FRAQUEZAS:</p>
            <div class="flex flex-wrap gap-1">
                @foreach($weaknesses as $w)
                    <span class="type-badge type-{{ $w }} text-white px-2 py-1 rounded pixel-border">{{ $w }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Cadeia de Evolução --}}
        @if(count($evolutionChain) > 1)
        <div class="mx-2 mb-3 bg-black bg-opacity-30 rounded p-2">
            <p style="font-size: 5px; color: #ffd6d6; margin-bottom: 4px;">EVOLUÇÃO:</p>
            <div class="flex items-center justify-center gap-1 flex-wrap">
                @foreach($evolutionChain as $i => $evo)
                    <a href="?pokemon={{ $evo['name'] }}&gen={{ $currentGen }}" onclick="showLoader()"
                       title="{{ strtoupper($evo['name']) }}"
                       class="flex flex-col items-center {{ $evo['id'] == $currentId ? 'opacity-100' : 'opacity-60 hover:opacity-100' }}"
                       style="text-decoration:none;">
                        <img src="{{ $evo['sprite'] }}" alt="{{ $evo['name'] }}" style="width:32px;height:32px;image-rendering:pixelated;">
                        <span style="font-size:4px; color:#ffd6d6; text-transform:uppercase;">{{ $evo['name'] }}</span>
                    </a>
                    @if(!$loop->last)
                        <span style="font-size:10px; color:#ffd6d6;">▶</span>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Time do Treinador --}}
        <div class="mx-2 mb-3 bg-black bg-opacity-30 rounded p-2">
            <div class="flex justify-between items-center mb-2">
                <p style="font-size: 5px; color: #ffd6d6;">TIME:</p>
                @if(!$inTeam && count($team) < 6)
                <form method="POST" action="{{ route('team.add') }}">
                    @csrf
                    <input type="hidden" name="id"     value="{{ $currentId }}">
                    <input type="hidden" name="name"   value="{{ $pokemon['name'] }}">
                    <input type="hidden" name="sprite" value="{{ $pokemon['sprites']['front_default'] }}">
                    <button type="submit" class="action-btn green px-2 py-1 rounded pixel-border" style="font-size: 5px;">+ TIME</button>
                </form>
                @elseif($inTeam)
                <form method="POST" action="{{ route('team.remove') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $currentId }}">
                    <button type="submit" class="action-btn px-2 py-1 rounded pixel-border" style="font-size: 5px;">- SAIR</button>
                </form>
                @endif
            </div>
            <div class="flex gap-1 flex-wrap">
                @for($s = 0; $s < 6; $s++)
                    @if(isset($team[$s]))
                        <a href="?pokemon={{ $team[$s]['name'] }}&gen={{ $currentGen }}" onclick="showLoader()"
                           class="team-slot" title="{{ strtoupper($team[$s]['name']) }}" style="text-decoration:none;">
                            <img src="{{ $team[$s]['sprite'] }}" alt="{{ $team[$s]['name'] }}">
                        </a>
                    @else
                        <div class="team-slot empty"></div>
                    @endif
                @endfor
            </div>
        </div>
        @endif

        {{-- Botões de navegação e controles --}}
        <div class="flex justify-between items-center mx-2">
            <a href="?gen={{ $currentGen }}" onclick="showLoader()"
               class="action-btn blue px-3 py-2 rounded pixel-border" style="font-size: 6px; text-decoration:none;">
                RAND
            </a>
            <div class="flex gap-1">
                <a href="?pokemon={{ $prevId }}&gen={{ $currentGen }}" onclick="showLoader()"
                   class="action-btn nav w-8 h-8 rounded pixel-border flex items-center justify-center"
                   style="font-size: 10px; color: #aaa; text-decoration:none;">◀</a>
                <a href="?pokemon={{ $nextId }}&gen={{ $currentGen }}" onclick="showLoader()"
                   class="action-btn nav w-8 h-8 rounded pixel-border flex items-center justify-center"
                   style="font-size: 10px; color: #aaa; text-decoration:none;">▶</a>
            </div>
            <div class="flex gap-2">
                <div class="w-6 h-6 rounded-full bg-red-700 pixel-border shadow-inner"></div>
                <div class="w-6 h-6 rounded-full bg-red-700 pixel-border shadow-inner"></div>
            </div>
        </div>

    </div>

</div>

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

    function revealPokemon() {
        const sprite = document.getElementById('pokemon-sprite');
        const name   = document.getElementById('pokemon-name');
        sprite.classList.remove('silhouette');
        name.textContent = name.dataset.name;
        name.classList.remove('hidden-name');
    }

    function showLoader() {
        document.getElementById('loader').classList.add('visible');
    }

    window.addEventListener('pageshow', () => {
        document.getElementById('loader').classList.remove('visible');
    });
</script>

</body>
</html>
