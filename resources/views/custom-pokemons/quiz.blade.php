<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz — Quem é este Pokémon?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        body { font-family: 'Press Start 2P', cursive; background: #1a1a2e; }

        .pokedex-body {
            background: linear-gradient(145deg, #e63946, #c1121f);
            border-radius: 16px;
            box-shadow: inset -4px -4px 0 #9b0000, inset 4px 4px 0 #ff6b6b, 0 10px 40px rgba(0,0,0,0.6);
        }
        .screen-outer { background: #1a1a1a; border-radius: 8px; box-shadow: inset 0 0 0 3px #333, inset 0 0 0 6px #555; }
        .screen-inner { background: #9bbc0f; border-radius: 4px; box-shadow: inset 0 0 20px rgba(0,0,0,0.3); position: relative; }
        .scan-line::after {
            content: ''; position: absolute; inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.05) 2px, rgba(0,0,0,0.05) 4px);
            pointer-events: none; border-radius: 4px;
        }
        .pixel-border { border: 2px solid #000; box-shadow: 2px 2px 0 #000; }

        .info-panel { background: #16213e; border: 2px solid #1e2e4a; border-radius: 10px; overflow: hidden; }
        .panel-header { font-size: 6px; color: #4a6a2a; letter-spacing: 2px; text-transform: uppercase; padding: 10px 16px; border-bottom: 1px solid #1e2e4a; }
        .panel-body { padding: 16px; }

        .pixel-input {
            background: #0a0a1a; color: #9bbc0f; border: 2px solid #1e2e1e;
            font-family: 'Press Start 2P', cursive; font-size: 9px;
            padding: 12px 14px; outline: none; width: 100%; border-radius: 4px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .pixel-input:focus { border-color: #9bbc0f; box-shadow: 0 0 10px rgba(155,188,15,0.3); }
        .pixel-input::placeholder { color: #1e2e1e; }

        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
            text-decoration: none; display: inline-block; cursor: pointer; border: none;
        }
        .action-btn:active { transform: translateY(2px); }
        .action-btn.blue   { background: linear-gradient(145deg, #2266cc, #1144aa); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277; }
        .action-btn.yellow { background: linear-gradient(145deg, #cc9900, #aa7700); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #775500; }
        .action-btn.green  { background: linear-gradient(145deg, #3a7a1a, #2a5a0a); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #1a3a05; }

        .silhouette { filter: brightness(0); image-rendering: pixelated; }
        .revealed   { image-rendering: pixelated; filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.4)); }

        @keyframes pop-in {
            0%   { transform: scale(0.5); opacity: 0; }
            70%  { transform: scale(1.1); }
            100% { transform: scale(1);   opacity: 1; }
        }
        .pop-in { animation: pop-in 0.4s ease-out forwards; }

        @keyframes confetti-fall {
            0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(120vh) rotate(720deg); opacity: 0; }
        }
        .confetti-piece {
            position: fixed; width: 8px; height: 8px;
            animation: confetti-fall linear forwards; z-index: 200; pointer-events: none;
        }

        .type-normal   { background: #A8A878; } .type-fire     { background: #F08030; }
        .type-water    { background: #6890F0; } .type-electric { background: #F8D030; color:#333; }
        .type-grass    { background: #78C850; } .type-ice      { background: #98D8D8; color:#333; }
        .type-fighting { background: #C03028; } .type-poison   { background: #A040A0; }
        .type-ground   { background: #E0C068; color:#333; } .type-flying { background: #A890F0; }
        .type-psychic  { background: #F85888; } .type-bug      { background: #A8B820; }
        .type-rock     { background: #B8A038; } .type-ghost    { background: #705898; }
        .type-dragon   { background: #7038F8; } .type-dark     { background: #705848; }
        .type-steel    { background: #B8B8D0; color:#333; } .type-fairy  { background: #EE99AC; color:#333; }
        .type-badge    { font-size: 6px; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body class="min-h-screen">

@include('partials.nav')

@php $revealed = isset($acertou); @endphp

<main class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6 flex-wrap">
        <a href="{{ route('custom-pokemons.index') }}" class="action-btn px-4 py-2 text-xs rounded">← CUSTOM DEX</a>
        <h1 class="text-green-400" style="font-size: 12px;">QUIZ MODE</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT: Pokédex com silhueta --}}
        <div class="lg:col-span-2">
            <div class="pokedex-body p-5">

                {{-- LEDs --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full pixel-border flex items-center justify-center flex-shrink-0" style="background: #ff4444; box-shadow: 0 0 8px #ff0000;">
                        <div class="w-4 h-4 rounded-full bg-white opacity-30"></div>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full pixel-border" style="background: #ff4444; box-shadow: 0 0 8px #ff0000;"></div>
                        <div class="w-3 h-3 rounded-full pixel-border" style="background: #ffcc00; box-shadow: 0 0 8px #ffaa00;"></div>
                        <div class="w-3 h-3 rounded-full pixel-border" style="background: #44ff44; box-shadow: 0 0 8px #00ff00;"></div>
                    </div>
                    <div class="ml-auto">
                        <span style="font-size: 5px; color: rgba(255,214,214,0.7);">QUIZ MODE</span>
                    </div>
                </div>

                {{-- Screen --}}
                <div class="screen-outer p-2 mb-4">
                    <div class="screen-inner scan-line p-4 flex flex-col items-center" style="min-height: 260px;">
                        <div class="w-full flex justify-between mb-2">
                            <span style="font-size: 6px; color: #3a5a00;">CUSTOM DEX</span>
                            <span style="font-size: 6px; color: #3a5a00;">{{ $revealed ? '#' . str_pad($pokemon->dex_number, 4, '0', STR_PAD_LEFT) : '#????' }}</span>
                        </div>

                        <div class="flex-1 flex items-center justify-center w-full">
                            @if($pokemon->sprite_path)
                                <img src="{{ asset($pokemon->sprite_path) }}"
                                     alt="{{ $revealed ? $pokemon->name : '???' }}"
                                     class="w-36 h-36 object-contain {{ $revealed ? 'revealed pop-in' : 'silhouette' }}"
                                     onerror="this.src='https://placehold.co/128x128/{{ $revealed ? '1a1a2e/9bbc0f' : '000000/000000' }}?text={{ $revealed ? urlencode($pokemon->name[0]) : '?' }}'">
                            @else
                                <img src="https://placehold.co/128x128/{{ $revealed ? '9bbc0f/1a1a2e' : '000000/000000' }}?text={{ $revealed ? urlencode($pokemon->name[0]) : '?' }}"
                                     alt="{{ $revealed ? $pokemon->name : '???' }}"
                                     class="w-36 h-36 object-contain {{ $revealed ? 'pop-in' : '' }}">
                            @endif
                        </div>

                        <div class="mt-2 w-full text-center" style="font-size: 7px; color: {{ $revealed ? '#1a3a00' : '#9bbc0f' }}; letter-spacing: {{ $revealed ? '0' : '4px' }}; text-transform: uppercase;">
                            @if($revealed) {{ strtoupper($pokemon->name) }} @else ??? @endif
                        </div>

                        @if($revealed)
                            <div class="flex gap-1 mt-2 flex-wrap justify-center pop-in">
                                <span class="type-badge type-{{ $pokemon->type_primary }} text-white px-2 py-1 rounded pixel-border">
                                    {{ strtoupper($pokemon->type_primary) }}
                                </span>
                                @if($pokemon->type_secondary)
                                    <span class="type-badge type-{{ $pokemon->type_secondary }} px-2 py-1 rounded pixel-border font-bold">
                                        {{ strtoupper($pokemon->type_secondary) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Hint (animal base) - sempre visível para ajudar --}}
                @if(!$revealed)
                <div class="text-center" style="font-size: 5px; color: rgba(255,214,214,0.5);">
                    DICA: Pokémon baseado em um(a) {{ $pokemon->base_animal }}
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Interação --}}
        <div class="lg:col-span-3 flex flex-col gap-4">

            @if(!$revealed)
            {{-- Formulário de palpite --}}
            <div class="info-panel">
                <div class="panel-header">QUEM É ESTE POKÉMON?</div>
                <div class="panel-body">
                    <p class="text-gray-600 mb-5" style="font-size: 6px; line-height: 2;">
                        Olhe bem na silhueta e tente adivinhar o nome do Pokémon custom!
                    </p>
                    <form method="POST" action="{{ route('custom-pokemons.guess') }}">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="guess"
                                   class="pixel-input"
                                   placeholder="Digite o nome..."
                                   autocomplete="off" autofocus>
                            @error('guess')
                                <span style="color: #ff6666; font-size: 6px; display: block; margin-top: 6px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="action-btn blue px-8 py-4 rounded text-xs w-full">
                            REVELAR RESPOSTA →
                        </button>
                    </form>
                </div>
            </div>

            {{-- Como jogar --}}
            <div class="info-panel">
                <div class="panel-header">COMO JOGAR</div>
                <div class="panel-body space-y-3" style="font-size: 6px; color: #4a5568; line-height: 2;">
                    <p>① Observe a silhueta do Pokémon na tela.</p>
                    <p>② Use a dica do animal base se precisar.</p>
                    <p>③ Digite o nome no campo acima.</p>
                    <p>④ Clique em REVELAR para ver se acertou!</p>
                </div>
            </div>

            @else
            {{-- Resultado --}}
            <div class="info-panel pop-in">
                <div class="panel-header">RESULTADO</div>
                <div class="panel-body text-center py-6">
                    @if($acertou)
                        <p style="font-size: 20px; color: #44ff44; text-shadow: 0 0 20px rgba(68,255,68,0.6); margin-bottom: 12px;">
                            ACERTOU!
                        </p>
                        <p style="font-size: 7px; color: #9bbc0f; line-height: 2; margin-bottom: 20px;">
                            Parabéns, treinador!<br>Você conhece bem os Pokémons!
                        </p>
                    @else
                        <p style="font-size: 20px; color: #ff5959; text-shadow: 0 0 20px rgba(255,89,89,0.6); margin-bottom: 12px;">
                            ERROU!
                        </p>
                        <p style="font-size: 6px; color: #9bbc0f; margin-bottom: 8px;">ERA O:</p>
                        <p style="font-size: 16px; color: white; margin-bottom: 16px;">{{ strtoupper($pokemon->name) }}</p>
                        <p style="font-size: 6px; color: #4a5568; line-height: 2; margin-bottom: 20px;">
                            Não desanime, treinador!<br>Continue tentando!
                        </p>
                    @endif

                    <div class="flex gap-3 justify-center flex-wrap">
                        <a href="{{ route('custom-pokemons.quiz') }}" class="action-btn blue px-5 py-3 rounded text-xs">
                            ⟳ JOGAR DE NOVO
                        </a>
                        <a href="{{ route('custom-pokemons.show', $pokemon->id) }}" class="action-btn px-5 py-3 rounded text-xs">
                            VER DETALHES
                        </a>
                    </div>
                </div>
            </div>

            {{-- Detalhes revelados --}}
            <div class="info-panel pop-in">
                <div class="panel-header">INFORMAÇÕES REVELADAS</div>
                <div class="panel-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 5px;">ANIMAL BASE</p>
                            <p style="font-size: 7px; color: #9bbc0f;">{{ $pokemon->base_animal }}</p>
                        </div>
                        <div>
                            <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 5px;">INSPIRAÇÃO</p>
                            <p style="font-size: 7px; color: #9bbc0f;">{{ $pokemon->inspiration }}</p>
                        </div>
                        <div>
                            <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 5px;">HP</p>
                            <p style="font-size: 7px; color: #FF5959;">{{ $pokemon->hp }}</p>
                        </div>
                        <div>
                            <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 5px;">ATK / DEF / VEL</p>
                            <p style="font-size: 7px; color: #F5AC78;">{{ $pokemon->attack }} / {{ $pokemon->defense }} / {{ $pokemon->speed }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</main>

@if($revealed && $acertou)
<script>
    (function() {
        const colors = ['#9bbc0f','#78C850','#F8D030','#F08030','#6890F0','#F85888','#EE99AC','#FA92B2'];
        for (let i = 0; i < 50; i++) {
            setTimeout(function() {
                const el = document.createElement('div');
                el.className = 'confetti-piece';
                el.style.left = Math.random() * 100 + 'vw';
                el.style.top = '-12px';
                el.style.background = colors[Math.floor(Math.random() * colors.length)];
                el.style.animationDuration = (Math.random() * 2 + 1.5) + 's';
                el.style.animationDelay = (Math.random() * 0.8) + 's';
                el.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                el.style.width = el.style.height = (Math.random() * 8 + 4) + 'px';
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 4000);
            }, i * 50);
        }
    })();
</script>
@endif

</body>
</html>
