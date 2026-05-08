<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pokemon->name }} — Custom Pokédex</title>
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

        .info-panel { background: #16213e; border: 2px solid #1e2e4a; border-radius: 10px; overflow: hidden; }
        .panel-header { font-size: 6px; color: #4a6a2a; letter-spacing: 2px; text-transform: uppercase; padding: 10px 16px; border-bottom: 1px solid #1e2e4a; display: flex; justify-content: space-between; align-items: center; }
        .panel-body { padding: 14px 16px; }

        .stat-bar-bg { background: #0a0a1a; border-radius: 6px; overflow: hidden; height: 10px; }
        .stat-bar    { height: 100%; border-radius: 6px; transition: width 0.8s cubic-bezier(.22,.61,.36,1); }

        .type-badge { font-size: 6px; text-transform: uppercase; letter-spacing: 1px; }
        .pixel-border { border: 2px solid #000; box-shadow: 2px 2px 0 #000; }

        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
            text-decoration: none; display: inline-block; cursor: pointer; border: none;
        }
        .action-btn:active { transform: translateY(2px); }
        .action-btn.blue { background: linear-gradient(145deg, #2266cc, #1144aa); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #112277; }
        .action-btn.yellow { background: linear-gradient(145deg, #cc9900, #aa7700); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #775500; }

        .info-row { border-bottom: 1px solid #1e2e4a; padding-bottom: 12px; margin-bottom: 12px; }
        .info-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

        .action-btn.green { background: linear-gradient(145deg, #1a7a2a, #0f5a1a); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #083a0a; }

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
        @keyframes led-pulse {
            0%, 100% { box-shadow: 0 0 8px #00ff00; }
            50%       { box-shadow: 0 0 20px #00ff00, 0 0 40px #00ff00; }
        }
        .crying .screen-inner { animation: screen-flash 0.6s ease-out; }
        .crying #pokemon-sprite { animation: sprite-bounce 0.5s ease-out; }
        .crying #led-green      { animation: led-pulse 0.4s ease-in-out 3; }

        .speaker-hole { width: 4px; height: 4px; border-radius: 50%; background: #8b0000; box-shadow: inset 1px 1px 0 rgba(0,0,0,0.5); }

        #cry-canvas { display: none; border-radius: 2px; }
        #cry-canvas.active { display: block; }
        #cry-label { transition: opacity 0.3s; }
    </style>
</head>
<body class="min-h-screen">

@include('partials.nav')

<main class="max-w-6xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('custom-pokemons.index') }}" class="action-btn px-4 py-2 text-xs rounded">← CUSTOM DEX</a>
        <span style="color: #4a5568; font-size: 6px;">/ {{ strtoupper($pokemon->name) }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT: Pokédex device --}}
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
                        <div id="led-green" class="w-3 h-3 rounded-full pixel-border" style="background: #44ff44; box-shadow: 0 0 8px #00ff00;"></div>
                    </div>
                    <div class="ml-auto text-right">
                        <p style="font-size: 5px; color: rgba(255,214,214,0.6);">CUSTOM</p>
                    </div>
                </div>

                {{-- Screen --}}
                <div class="screen-outer p-2 mb-4">
                    <div class="screen-inner scan-line p-4 flex flex-col items-center" style="min-height: 260px;">
                        <div class="w-full flex justify-between mb-2">
                            <span style="font-size: 6px; color: #3a5a00;">CUSTOM DEX</span>
                            <span style="font-size: 6px; color: #3a5a00;">#{{ str_pad($pokemon->dex_number, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="flex-1 flex items-center justify-center w-full relative">
                            <img id="pokemon-sprite"
                                 src="{{ $pokemon->sprite_path ? asset($pokemon->sprite_path) : 'https://placehold.co/128x128/9bbc0f/1a1a2e?text=' . urlencode(strtoupper($pokemon->name[0])) }}"
                                 alt="{{ $pokemon->name }}"
                                 class="w-36 h-36 object-contain"
                                 style="image-rendering: pixelated; filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.4));"
                                 onerror="this.src='https://placehold.co/128x128/9bbc0f/1a1a2e?text=?'">
                            <canvas id="cry-canvas" width="160" height="40"
                                    style="position: absolute; bottom: -8px; left: 0; right: 0; margin: auto; opacity: 0.7;"></canvas>
                        </div>

                        <p id="cry-label" class="mt-2 text-center" style="font-size: 7px; color: #1a3a00; text-transform: uppercase;">
                            {{ strtoupper($pokemon->name) }}
                        </p>

                        <div class="flex gap-1 mt-2 flex-wrap justify-center">
                            <span class="type-badge type-{{ $pokemon->type_primary }} text-white px-2 py-1 rounded pixel-border">
                                {{ strtoupper($pokemon->type_primary) }}
                            </span>
                            @if($pokemon->type_secondary)
                                <span class="type-badge type-{{ $pokemon->type_secondary }} px-2 py-1 rounded pixel-border font-bold">
                                    {{ strtoupper($pokemon->type_secondary) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Speaker holes --}}
                <div class="flex justify-end mb-3">
                    <div class="flex gap-1 flex-wrap" style="max-width: 60px;">
                        @for($i = 0; $i < 12; $i++)
                            <div class="speaker-hole"></div>
                        @endfor
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 flex-wrap justify-center">
                    <button onclick="playCry()" class="action-btn green px-4 py-2 rounded text-xs">♪ CRY</button>
                    <a href="{{ route('custom-pokemons.quiz') }}" class="action-btn yellow px-4 py-2 rounded text-xs">? QUIZ</a>
                    <a href="{{ route('custom-pokemons.create') }}" class="action-btn blue px-4 py-2 rounded text-xs">+ CRIAR</a>
                </div>

                {{-- Edit --}}
                <a href="{{ route('custom-pokemons.edit', $pokemon->id) }}"
                   class="action-btn blue mt-3 px-4 py-2 rounded text-xs w-full text-center"
                   style="display: block;">
                    ✎ EDITAR POKÉMON
                </a>

                {{-- Delete --}}
                <form method="POST" action="{{ route('custom-pokemons.destroy', $pokemon->id) }}"
                      class="mt-2 text-center"
                      onsubmit="return confirm('Deletar {{ strtoupper($pokemon->name) }}? Esta ação não pode ser desfeita.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn px-4 py-2 rounded text-xs w-full">
                        ✕ DELETAR POKÉMON
                    </button>
                </form>
            </div>
        </div>

        {{-- RIGHT: Info panels --}}
        <div class="lg:col-span-3 flex flex-col gap-4">

            {{-- Stats --}}
            <div class="info-panel">
                <div class="panel-header">
                    <span>ESTATÍSTICAS BASE</span>
                    <span style="color: #9bbc0f;">TOTAL: {{ $pokemon->hp + $pokemon->attack + $pokemon->defense + $pokemon->speed }}</span>
                </div>
                <div class="panel-body">
                    @php
                        $statColors = ['hp' => '#FF5959', 'attack' => '#F5AC78', 'defense' => '#FAE078', 'speed' => '#FA92B2'];
                        $statLabels = ['hp' => 'HP', 'attack' => 'ATAQUE', 'defense' => 'DEFESA', 'speed' => 'VELOCIDADE'];
                    @endphp
                    @foreach($statColors as $key => $color)
                        <div class="flex items-center gap-3 mb-3 last:mb-0">
                            <span class="text-gray-500 flex-shrink-0 text-right" style="font-size: 6px; width: 68px;">{{ $statLabels[$key] }}</span>
                            <span class="text-gray-300 flex-shrink-0 text-right" style="font-size: 7px; width: 26px;">{{ $pokemon->$key }}</span>
                            <div class="flex-1 stat-bar-bg">
                                <div class="stat-bar" style="width: {{ min(100, ($pokemon->$key / 255) * 100) }}%; background: {{ $color }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Lore --}}
            <div class="info-panel">
                <div class="panel-header">INFORMAÇÕES</div>
                <div class="panel-body">
                    <div class="info-row">
                        <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 6px;">ANIMAL BASE</p>
                        <p style="font-size: 8px; color: #9bbc0f;">{{ $pokemon->base_animal }}</p>
                    </div>
                    <div class="info-row">
                        <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 6px;">INSPIRAÇÃO</p>
                        <p style="font-size: 8px; color: #9bbc0f;">{{ $pokemon->inspiration }}</p>
                    </div>
                    <div>
                        <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 6px;">NÚMERO DEX</p>
                        <p style="font-size: 8px; color: #9bbc0f;">#{{ $pokemon->dex_number }}</p>
                    </div>
                </div>
            </div>

            {{-- Tipos detalhados --}}
            <div class="info-panel">
                <div class="panel-header">TIPAGEM</div>
                <div class="panel-body">
                    <div class="flex gap-3 flex-wrap">
                        <div class="flex flex-col items-center gap-2">
                            <p style="font-size: 5px; color: #4a6a2a;">PRIMÁRIO</p>
                            <span class="type-badge type-{{ $pokemon->type_primary }} text-white px-4 py-2 rounded pixel-border">
                                {{ strtoupper($pokemon->type_primary) }}
                            </span>
                        </div>
                        @if($pokemon->type_secondary)
                        <div class="flex flex-col items-center gap-2">
                            <p style="font-size: 5px; color: #4a6a2a;">SECUNDÁRIO</p>
                            <span class="type-badge type-{{ $pokemon->type_secondary }} px-4 py-2 rounded pixel-border font-bold">
                                {{ strtoupper($pokemon->type_secondary) }}
                            </span>
                        </div>
                        @else
                        <div class="flex flex-col items-center gap-2">
                            <p style="font-size: 5px; color: #4a6a2a;">SECUNDÁRIO</p>
                            <span style="font-size: 6px; color: #4a5568;">NENHUM</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Ataques --}}
            @if($pokemon->attacks && count($pokemon->attacks) > 0)
            <div class="info-panel">
                <div class="panel-header">
                    <span>MOVIMENTOS</span>
                    <span style="color: #9bbc0f;">{{ count($pokemon->attacks) }}/4</span>
                </div>
                <div class="panel-body space-y-3">
                    @foreach($pokemon->attacks as $i => $atk)
                    <div style="background: #0d0d1a; border: 1px solid #1e2e4a; border-radius: 8px; padding: 12px 14px; border-left: 3px solid {{ ['#FF5959','#F5AC78','#9DB7F5','#A7DB8D'][$i % 4] }};">
                        <div class="flex items-center gap-3 mb-2">
                            <span style="font-size: 5px; color: #4a5568; background: #0a0a1a; border: 1px solid #1e2e4a; padding: 2px 6px; border-radius: 4px;">{{ $i + 1 }}</span>
                            <p style="font-size: 8px; color: #9bbc0f;">{{ $atk['name'] }}</p>
                        </div>
                        <p style="font-size: 6px; color: #4a5568; line-height: 2;">{{ $atk['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Metadados --}}
            <div class="info-panel">
                <div class="panel-header">METADADOS</div>
                <div class="panel-body">
                    <div class="info-row">
                        <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 5px;">ID NO SISTEMA</p>
                        <p style="font-size: 7px; color: #9bbc0f;">#{{ $pokemon->id }}</p>
                    </div>
                    <div>
                        <p style="font-size: 5px; color: #4a6a2a; margin-bottom: 5px;">REGISTRADO EM</p>
                        <p style="font-size: 7px; color: #9bbc0f;">{{ $pokemon->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    const CRY_DATA = {
        dexNumber:   {{ $pokemon->dex_number }},
        type:        '{{ $pokemon->type_primary }}',
        hp:          {{ $pokemon->hp }},
        attack:      {{ $pokemon->attack }},
        defense:     {{ $pokemon->defense }},
        speed:       {{ $pokemon->speed }},
    };

    const TYPE_WAVES = {
        fire: 'sawtooth', fighting: 'sawtooth', dragon: 'sawtooth', dark: 'sawtooth', rock: 'sawtooth',
        water: 'sine',    ice: 'sine',          psychic: 'sine',    fairy: 'sine',
        electric: 'square', steel: 'square',    bug: 'square',
        grass: 'triangle',  ground: 'triangle', flying: 'triangle', normal: 'triangle',
        ghost: 'triangle',  poison: 'triangle',
    };

    let audioCtx = null;
    let animFrame = null;
    let analyser  = null;

    function playCry() {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();

        const { dexNumber, type, hp, attack, defense, speed } = CRY_DATA;

        const waveType  = TYPE_WAVES[type] || 'triangle';
        const baseFreq  = 150 + (dexNumber * 7) % 350;           // 150–500 Hz, unique per dex
        const duration  = 0.35 + (hp    / 255) * 0.55;           // 0.35–0.9 s
        const vibratoHz = 5  + (speed   / 255) * 10;             // 5–15 Hz
        const vibratoAm = 5  + (hp      / 255) * 20;             // 5–25 Hz depth
        const goesUp    = speed > attack;
        const endFreq   = goesUp ? baseFreq * 1.6 : baseFreq * 0.55;

        const now = audioCtx.currentTime;

        // Main oscillator
        const osc  = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        analyser   = audioCtx.createAnalyser();
        analyser.fftSize = 256;

        // Vibrato LFO
        const lfo     = audioCtx.createOscillator();
        const lfoGain = audioCtx.createGain();
        lfo.frequency.value = vibratoHz;
        lfoGain.gain.value  = vibratoAm;
        lfo.connect(lfoGain);
        lfoGain.connect(osc.frequency);

        osc.type = waveType;
        osc.frequency.setValueAtTime(baseFreq, now);
        osc.frequency.exponentialRampToValueAtTime(endFreq, now + duration * 0.65);
        osc.frequency.exponentialRampToValueAtTime(baseFreq * 0.4, now + duration);

        // Envelope
        gain.gain.setValueAtTime(0, now);
        gain.gain.linearRampToValueAtTime(0.25, now + 0.03);
        gain.gain.setValueAtTime(0.25, now + duration * 0.55);
        gain.gain.exponentialRampToValueAtTime(0.001, now + duration);

        osc.connect(gain);
        gain.connect(analyser);
        analyser.connect(audioCtx.destination);

        lfo.start(now);
        osc.start(now);
        osc.stop(now + duration + 0.05);
        lfo.stop(now + duration + 0.05);

        // Visual effects
        animateCry(duration * 1000);
    }

    function animateCry(durationMs) {
        const device  = document.querySelector('.pokedex-body');
        const label   = document.getElementById('cry-label');
        const canvas  = document.getElementById('cry-canvas');
        const ctx     = canvas.getContext('2d');

        device.classList.add('crying');
        label.textContent = '♪ CRY!';
        canvas.classList.add('active');

        cancelAnimationFrame(animFrame);

        function drawWave() {
            if (!analyser) return;
            const buf = new Uint8Array(analyser.frequencyBinCount);
            analyser.getByteTimeDomainData(buf);

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
            animFrame = requestAnimationFrame(drawWave);
        }
        drawWave();

        setTimeout(() => {
            device.classList.remove('crying');
            label.textContent = '{{ strtoupper($pokemon->name) }}';
            canvas.classList.remove('active');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            cancelAnimationFrame(animFrame);
        }, durationMs + 200);
    }
</script>

</body>
</html>
