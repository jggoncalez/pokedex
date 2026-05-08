<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Pokédex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        * { box-sizing: border-box; }
        body { font-family: 'Press Start 2P', cursive; background: #1a1a2e; }

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

        /* ── Pokédex device ── */
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

        /* ── Panels ── */
        .info-panel { background: #16213e; border: 2px solid #1e2e4a; border-radius: 10px; overflow: hidden; }
        .panel-header {
            font-size: 6px; color: #4a6a2a; letter-spacing: 2px; text-transform: uppercase;
            padding: 10px 16px; border-bottom: 1px solid #1e2e4a;
            display: flex; justify-content: space-between; align-items: center;
        }
        .panel-body { padding: 14px 16px; }

        /* ── Stat bars ── */
        .stat-bar-bg { background: #0a0a1a; border-radius: 6px; overflow: hidden; height: 10px; }
        .stat-bar { height: 100%; border-radius: 6px; width: 0; transition: width 1s cubic-bezier(.22,.61,.36,1); }

        /* ── Buttons ── */
        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
            text-decoration: none; display: inline-block; cursor: pointer; border: none;
        }
        .action-btn:active { transform: translateY(2px); }
        .action-btn.blue   { background: linear-gradient(145deg, #2266cc, #1144aa); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277; }
        .action-btn.green  { background: linear-gradient(145deg, #3a7a1a, #2a5a0a); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #1a3a05; }
        .action-btn.yellow { background: linear-gradient(145deg, #cc9900, #aa7700); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #775500; }

        /* ── Difficulty buttons ── */
        .diff-btn {
            background: #0d0d1a; color: #4a5568; border: 2px solid #1e2e1e;
            font-family: 'Press Start 2P', cursive; font-size: 5px;
            padding: 5px 9px; border-radius: 4px; cursor: pointer;
            text-decoration: none; display: inline-block; transition: all 0.1s; white-space: nowrap;
        }
        .diff-btn.active { border-color: #9bbc0f; color: #9bbc0f; background: rgba(155,188,15,0.1); }
        .diff-btn:hover:not(.active) { border-color: #4a6a2a; color: #9bbc0f; }

        /* ── Input ── */
        .pixel-input {
            background: #0a0a1a; color: #9bbc0f; border: 2px solid #1e2e1e;
            font-family: 'Press Start 2P', cursive; font-size: 10px;
            padding: 14px 16px; outline: none; width: 100%; border-radius: 4px;
            transition: border-color 0.2s, box-shadow 0.2s; letter-spacing: 1px;
        }
        .pixel-input:focus { border-color: #9bbc0f; box-shadow: 0 0 12px rgba(155,188,15,0.3); }
        .pixel-input::placeholder { color: #1e2e1e; }
        .pixel-input.shake { animation: shake 0.4s ease-in-out; }

        /* ── Sprite ── */
        .silhouette { filter: brightness(0); image-rendering: pixelated; }
        .revealed   { image-rendering: pixelated; filter: drop-shadow(2px 6px 10px rgba(0,0,0,0.5)); }

        /* ── Score ── */
        .score-cell { text-align: center; padding: 10px 8px; }
        .score-value { display: block; margin-bottom: 4px; }

        /* ── Animations ── */
        @keyframes pop-in {
            0%   { transform: scale(0.3) rotate(-5deg); opacity: 0; }
            70%  { transform: scale(1.08) rotate(1deg); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .pop-in { animation: pop-in 0.45s cubic-bezier(.22,.61,.36,1) forwards; }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%,60% { transform: translateX(-8px); }
            40%,80% { transform: translateX(8px); }
        }

        @keyframes pulse-green {
            0%,100% { box-shadow: 0 0 0 0 rgba(155,188,15,0); }
            50%      { box-shadow: 0 0 0 8px rgba(155,188,15,0.2); }
        }
        .pulse-green { animation: pulse-green 1.5s ease-in-out infinite; }

        @keyframes flicker {
            0%,100% { opacity: 1; transform: scaleY(1); }
            50%      { opacity: 0.7; transform: scaleY(0.95); }
        }
        .hot-streak { animation: flicker 0.4s ease-in-out infinite; }

        @keyframes confetti-fall {
            0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(120vh) rotate(720deg); opacity: 0; }
        }
        .confetti-piece { position: fixed; animation: confetti-fall linear forwards; z-index: 200; pointer-events: none; }

        @keyframes slide-up {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }
        .slide-up { animation: slide-up 0.35s ease-out forwards; }

        /* ── Result colors ── */
        .result-win  { color: #44ff44; text-shadow: 0 0 20px rgba(68,255,68,0.6); }
        .result-lose { color: #ff5959; text-shadow: 0 0 20px rgba(255,89,89,0.6); }
    </style>
</head>
<body class="min-h-screen">

@include('partials.nav')

@php
    $revealed    = isset($acertou);
    $sprite      = $pokemon['sprites']['other']['official-artwork']['front_default']
                ?? $pokemon['sprites']['front_default'] ?? '';
    $displayName = ucwords(str_replace('-', ' ', $pokemon['name']));
    $currentId   = $pokemon['id'];
    $accuracy    = $score['total'] > 0
                    ? round(($score['correct'] / $score['total']) * 100)
                    : 0;

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
    $genNames = [
        1=>'Kanto', 2=>'Johto', 3=>'Hoenn', 4=>'Sinnoh',
        5=>'Unova', 6=>'Kalos', 7=>'Alola',  8=>'Galar', 9=>'Paldea',
    ];
    $genLabel = $quizGen > 0
        ? 'GEN ' . $quizGen . ' — ' . ($genNames[$quizGen] ?? '')
        : 'TODAS AS GERAÇÕES';

    $nextUrl = '/quiz' . ($quizGen > 0 ? '?gen=' . $quizGen : '');
@endphp

<main class="max-w-6xl mx-auto px-4 py-8">

    {{-- ── HEADER ── --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div>
            <h1 style="font-size: 14px; color: #9bbc0f;">QUIZ POKÉDEX</h1>
            <p style="font-size: 5px; color: #4a5568; margin-top: 4px;">Adivinhe o Pokémon pela silhueta!</p>
        </div>
        <a href="/" class="action-btn px-4 py-2 text-xs rounded">← POKÉDEX</a>
    </div>

    {{-- ── SCORE BAR ── --}}
    <div class="info-panel mb-5">
        <div class="panel-body" style="padding: 0;">
            <div class="grid grid-cols-4 divide-x" style="border-color: #1e2e4a;">
                {{-- Streak --}}
                <div class="score-cell">
                    @if($score['streak'] >= 3)
                        <span class="score-value hot-streak" style="font-size: 20px; color: #F8D030;">
                            🔥 {{ $score['streak'] }}
                        </span>
                    @else
                        <span class="score-value" style="font-size: 20px; color: #9bbc0f;">{{ $score['streak'] }}</span>
                    @endif
                    <span style="font-size: 5px; color: #4a5568; text-transform: uppercase; letter-spacing: 1px;">SEQUÊNCIA</span>
                </div>

                {{-- Best --}}
                <div class="score-cell">
                    <span class="score-value" style="font-size: 20px; color: #F8D030;">{{ $score['best'] }}</span>
                    <span style="font-size: 5px; color: #4a5568; text-transform: uppercase; letter-spacing: 1px;">🏆 RECORDE</span>
                </div>

                {{-- Correct/Total --}}
                <div class="score-cell">
                    <span class="score-value" style="font-size: 16px; color: #9bbc0f;">
                        {{ $score['correct'] }}<span style="font-size: 10px; color: #4a5568;">/{{ $score['total'] }}</span>
                    </span>
                    <span style="font-size: 5px; color: #4a5568; text-transform: uppercase; letter-spacing: 1px;">ACERTOS</span>
                </div>

                {{-- Accuracy --}}
                <div class="score-cell">
                    <span class="score-value" style="font-size: 16px; color: {{ $accuracy >= 70 ? '#9bbc0f' : ($accuracy >= 40 ? '#F5AC78' : '#FF5959') }};">
                        {{ $accuracy }}<span style="font-size: 10px;">%</span>
                    </span>
                    <span style="font-size: 5px; color: #4a5568; text-transform: uppercase; letter-spacing: 1px;">PRECISÃO</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── DIFFICULTY SELECTOR ── --}}
    <div class="mb-5 flex items-center gap-2 flex-wrap">
        <span style="font-size: 5px; color: #4a5568; text-transform: uppercase; white-space: nowrap;">DIFICULDADE:</span>
        <a href="/quiz" class="diff-btn {{ $quizGen === 0 ? 'active' : '' }}">TODOS</a>
        @for($g = 1; $g <= 9; $g++)
            <a href="/quiz?gen={{ $g }}" class="diff-btn {{ $quizGen === $g ? 'active' : '' }}">GEN {{ $g }}</a>
        @endfor
    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ── LEFT: Pokédex device ── --}}
        <div class="lg:col-span-2">
            <div class="pokedex-body p-5">

                {{-- LEDs --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full pixel-border flex items-center justify-center flex-shrink-0"
                         style="background: #ff4444; box-shadow: 0 0 8px #ff0000;">
                        <div class="w-4 h-4 rounded-full bg-white opacity-30"></div>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full pixel-border" style="background: #ff4444; box-shadow: 0 0 8px #ff0000;"></div>
                        <div class="w-3 h-3 rounded-full pixel-border" style="background: #ffcc00; box-shadow: 0 0 8px #ffaa00;"></div>
                        <div class="w-3 h-3 rounded-full pixel-border" style="background: #44ff44; box-shadow: 0 0 8px #00ff00;"></div>
                    </div>
                    <div class="ml-auto">
                        <span style="font-size: 5px; color: rgba(255,214,214,0.6);">QUIZ MODE</span>
                    </div>
                </div>

                {{-- Screen --}}
                <div class="screen-outer p-2 mb-4">
                    <div class="screen-inner scan-line p-4 flex flex-col items-center" style="min-height: 280px;">

                        <div class="w-full flex justify-between mb-2">
                            <span style="font-size: 5px; color: #3a5a00;">{{ $genLabel }}</span>
                            <span style="font-size: 5px; color: #3a5a00;">{{ $revealed ? 'Nº ' . str_pad($currentId, 3, '0', STR_PAD_LEFT) : '#???' }}</span>
                        </div>

                        {{-- Sprite --}}
                        <div class="flex-1 flex items-center justify-center w-full">
                            <img src="{{ $sprite }}"
                                 alt="{{ $revealed ? $displayName : '???' }}"
                                 class="w-40 h-40 object-contain {{ $revealed ? 'revealed pop-in' : 'silhouette' }}"
                                 id="quiz-sprite">
                        </div>

                        {{-- Name --}}
                        <div class="mt-2 text-center w-full" style="min-height: 20px;">
                            @if($revealed)
                                <span class="pop-in" style="font-size: 7px; color: #1a3a00; text-transform: uppercase; display: block;">
                                    {{ strtoupper($displayName) }}
                                </span>
                            @else
                                <span style="font-size: 7px; color: #9bbc0f; letter-spacing: 5px;">???</span>
                            @endif
                        </div>

                        {{-- Types --}}
                        <div class="flex gap-1 mt-2 flex-wrap justify-center" style="min-height: 26px;">
                            @if($revealed)
                                @foreach($pokemon['types'] as $type)
                                    <span class="type-badge type-{{ $type['type']['name'] }} text-white px-2 py-1 rounded pixel-border pop-in">
                                        {{ strtoupper($type['type']['name']) }}
                                    </span>
                                @endforeach
                            @else
                                <span style="background: #1a3a00; font-size: 6px; color: #3a6a00; padding: 4px 10px; border-radius: 4px;">???</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bottom info --}}
                @if($revealed)
                    <div class="flex justify-between items-end">
                        <div class="text-center">
                            <p style="font-size: 5px; color: rgba(255,214,214,0.5); margin-bottom: 2px;">ALTURA</p>
                            <p style="font-size: 8px; color: #ffd6d6;">{{ $pokemon['height'] / 10 }}m</p>
                        </div>
                        <div class="text-center">
                            <p style="font-size: 5px; color: rgba(255,214,214,0.5); margin-bottom: 2px;">PESO</p>
                            <p style="font-size: 8px; color: #ffd6d6;">{{ $pokemon['weight'] / 10 }}kg</p>
                        </div>
                        <div class="text-center">
                            <p style="font-size: 5px; color: rgba(255,214,214,0.5); margin-bottom: 2px;">Nº</p>
                            <p style="font-size: 8px; color: #ffd6d6;">{{ str_pad($currentId, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center" style="font-size: 5px; color: rgba(255,214,214,0.35); line-height: 2;">
                        Adivinhe para revelar os detalhes!
                    </div>
                @endif
            </div>
        </div>

        {{-- ── RIGHT: Interaction panels ── --}}
        <div class="lg:col-span-3 flex flex-col gap-4">

            @if(!$revealed)

            {{-- Input form --}}
            <div class="info-panel {{ $score['streak'] >= 3 ? 'pulse-green' : '' }}">
                <div class="panel-header">
                    <span>QUEM É ESTE POKÉMON?</span>
                    @if($score['streak'] >= 3)
                        <span style="color: #F8D030;">🔥 EM CHAMAS!</span>
                    @endif
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('quiz.guess') }}" id="quiz-form">
                        @csrf
                        <input type="hidden" name="gen" value="{{ $quizGen }}">
                        <div class="mb-4">
                            <input type="text" name="guess" id="guess-input"
                                   class="pixel-input"
                                   placeholder="Digite o nome..."
                                   autocomplete="off" autofocus spellcheck="false">
                            @error('guess')
                                <span style="color: #ff6666; font-size: 6px; display: block; margin-top: 6px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="action-btn blue px-6 py-4 rounded text-xs w-full" style="letter-spacing: 2px;">
                            ⟳ REVELAR RESPOSTA
                        </button>
                    </form>
                </div>
            </div>

            {{-- Dicas --}}
            <div class="info-panel">
                <div class="panel-header">DICAS</div>
                <div class="panel-body space-y-3" style="font-size: 6px; line-height: 2;">
                    <div style="background: rgba(155,188,15,0.08); border: 1px solid #2a3a0a; border-radius: 6px; padding: 10px 12px;">
                        <span style="color: #4a6a2a;">GERAÇÃO: </span>
                        <span style="color: #9bbc0f;">{{ $genLabel }}</span>
                    </div>
                    <p style="color: #4a5568;">● NÚMERO: revelado após o palpite</p>
                    <p style="color: #4a5568;">● TIPOS: revelados após o palpite</p>
                    <p style="color: #4a5568;">● STATS: revelados após o palpite</p>
                    <div style="border-top: 1px solid #1e2e4a; padding-top: 10px; color: #3a4a3a;">
                        <p>Para nomes compostos: use hífen ou espaço</p>
                        <p style="font-size: 5px; color: #2a3a2a; margin-top: 4px;">Ex: "mr-mime" ou "mr mime"</p>
                    </div>
                </div>
            </div>

            {{-- Como jogar --}}
            <div class="info-panel">
                <div class="panel-header">COMO JOGAR</div>
                <div class="panel-body space-y-2" style="font-size: 6px; color: #4a5568; line-height: 2.2;">
                    <p>① Observe a silhueta preta na tela.</p>
                    <p>② Use a dica de geração como filtro.</p>
                    <p>③ Digite o nome e clique em Revelar.</p>
                    <p>④ Acertos consecutivos = 🔥 Streak!</p>
                    <p>⑤ Aumente a dificuldade: use TODOS para os 1025 Pokémons.</p>
                </div>
            </div>

            @else

            {{-- ── RESULTADO ── --}}
            <div class="info-panel pop-in">
                <div class="panel-header">
                    <span>RESULTADO</span>
                    @if($acertou && $score['streak'] >= 3)
                        <span class="hot-streak" style="color: #F8D030;">🔥 x{{ $score['streak'] }}</span>
                    @endif
                </div>
                <div class="panel-body text-center py-6">
                    @if($acertou)
                        @if($score['streak'] >= 10)
                            <p class="result-win mb-3" style="font-size: 22px;">🏆 LENDÁRIO! 🏆</p>
                        @elseif($score['streak'] >= 5)
                            <p class="result-win mb-3" style="font-size: 22px;">🔥 INCRÍVEL! 🔥</p>
                        @else
                            <p class="result-win mb-3" style="font-size: 22px;">ACERTOU!</p>
                        @endif

                        @if($score['streak'] >= 3)
                            <p style="font-size: 8px; color: #F8D030; margin-bottom: 8px;">
                                SEQUÊNCIA DE {{ $score['streak'] }}!
                            </p>
                        @endif
                        <p style="font-size: 7px; color: #9bbc0f; line-height: 2;">
                            @if($score['streak'] >= 10) Você é um Mestre Pokémon!
                            @elseif($score['streak'] >= 5) Sequência quente! Continue assim!
                            @else Parabéns, treinador!
                            @endif
                        </p>
                    @else
                        <p class="result-lose mb-3" style="font-size: 22px;">ERROU!</p>
                        <p style="font-size: 6px; color: #9bbc0f; margin-bottom: 8px;">ERA O:</p>
                        <p style="font-size: 16px; color: white; margin-bottom: 12px; letter-spacing: 2px;">
                            {{ strtoupper($displayName) }}
                        </p>
                        <p style="font-size: 6px; color: #4a5568; line-height: 2;">Não desanime! Continue treinando.</p>
                    @endif
                </div>
            </div>

            {{-- ── STATS REVELADOS ── --}}
            <div class="info-panel slide-up" style="animation-delay: 0.1s;">
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
                            $sLabel = $statLabels[$sName] ?? strtoupper($sName);
                        @endphp
                        <div class="flex items-center gap-3 mb-3 last:mb-0">
                            <span class="flex-shrink-0 text-right text-gray-500" style="font-size: 6px; width: 30px;">{{ $sLabel }}</span>
                            <span class="flex-shrink-0 text-right text-gray-300" style="font-size: 7px; width: 26px;">{{ $sVal }}</span>
                            <div class="flex-1 stat-bar-bg">
                                <div class="stat-bar" data-width="{{ $sPct }}" style="background: {{ $sColor }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── AÇÕES ── --}}
            <div class="info-panel slide-up" style="animation-delay: 0.2s;">
                <div class="panel-body">
                    <div class="flex gap-3 flex-wrap">
                        <a href="{{ $nextUrl }}"
                           class="action-btn green px-5 py-4 rounded text-xs flex-1 text-center" style="min-width: 140px; letter-spacing: 1px;">
                            ▶ PRÓXIMO POKÉMON
                        </a>
                        <a href="/?pokemon={{ $currentId }}"
                           class="action-btn blue px-5 py-4 rounded text-xs flex-1 text-center" style="min-width: 140px; letter-spacing: 1px;">
                            VER NA POKÉDEX
                        </a>
                    </div>
                </div>
            </div>

            @endif
        </div>
    </div>

</main>

<script>
    // Animate stat bars on load (revealed state)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.stat-bar[data-width]').forEach(function (bar) {
            setTimeout(function () {
                bar.style.width = bar.dataset.width + '%';
            }, 200);
        });
    });
</script>

@if($revealed && $acertou)
<script>
    (function () {
        const colors = ['#9bbc0f','#78C850','#F8D030','#F08030','#6890F0','#F85888','#EE99AC','#FA92B2','#44ff44','#ffffff'];
        const count  = {{ $score['streak'] >= 5 ? 100 : 55 }};
        for (let i = 0; i < count; i++) {
            setTimeout(function () {
                const el   = document.createElement('div');
                const size = Math.random() * 10 + 4;
                el.className = 'confetti-piece';
                el.style.cssText = `
                    left:${Math.random() * 100}vw; top:-12px;
                    width:${size}px; height:${size}px;
                    background:${colors[Math.floor(Math.random() * colors.length)]};
                    animation-duration:${Math.random() * 2.5 + 1.5}s;
                    animation-delay:${Math.random() * 0.8}s;
                    border-radius:${Math.random() > 0.5 ? '50%' : '2px'};
                `;
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 5000);
            }, i * 35);
        }
    })();
</script>
@endif

</body>
</html>
