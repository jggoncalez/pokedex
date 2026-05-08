<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Pokémon — Custom Pokédex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        body { font-family: 'Press Start 2P', cursive; background: #1a1a2e; }

        .info-panel { background: #16213e; border: 2px solid #1e2e4a; border-radius: 10px; overflow: hidden; }
        .panel-header { font-size: 6px; color: #4a6a2a; letter-spacing: 2px; text-transform: uppercase; padding: 10px 16px; border-bottom: 1px solid #1e2e4a; }
        .panel-body { padding: 16px; }

        label { color: #4a6a2a; font-size: 6px; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; }

        .pixel-input, .pixel-select {
            background: #0a0a1a; color: #9bbc0f; border: 2px solid #1e2e1e;
            font-family: 'Press Start 2P', cursive; font-size: 8px;
            width: 100%; padding: 10px 12px; outline: none; border-radius: 4px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .pixel-input:focus, .pixel-select:focus { border-color: #9bbc0f; box-shadow: 0 0 10px rgba(155,188,15,0.2); }
        .pixel-input::placeholder { color: #1e2e1e; }
        .pixel-input.is-error { border-color: #ff4444; }
        .pixel-select option { background: #0a0a1a; color: #9bbc0f; }

        .error-msg { color: #ff6666; font-size: 6px; margin-top: 5px; display: block; }

        .action-btn {
            background: linear-gradient(145deg, #cc0000, #990000);
            box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #660000;
            transition: all 0.1s; color: white; font-family: 'Press Start 2P', cursive;
            text-decoration: none; display: inline-block; cursor: pointer; border: none;
        }
        .action-btn:active { transform: translateY(2px); }
        .action-btn.green { background: linear-gradient(145deg, #3a7a1a, #2a5a0a); box-shadow: inset 0 2px 0 rgba(255,255,255,0.2), 0 3px 0 #1a3a05; }

        .stat-preview { background: #0a0a1a; border-radius: 4px; overflow: hidden; height: 8px; }
        .stat-preview-fill { background: linear-gradient(90deg, #9bbc0f, #78C850); height: 100%; border-radius: 4px; transition: width 0.3s ease; }

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

<main class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6 flex-wrap">
        <a href="{{ route('custom-pokemons.index') }}" class="action-btn px-4 py-2 text-xs rounded">← CUSTOM DEX</a>
        <h1 class="text-green-400" style="font-size: 14px;">CRIAR POKÉMON</h1>
    </div>

    <form method="POST" action="{{ route('custom-pokemons.store') }}" enctype="multipart/form-data" novalidate id="create-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Preview card --}}
            <div class="lg:col-span-1">
                <div class="info-panel sticky top-20">
                    <div class="panel-header">PRÉVIA</div>
                    <div class="panel-body text-center">
                        {{-- Sprite preview --}}
                        <div class="mb-4" style="background: #9bbc0f; border-radius: 8px; padding: 16px; position: relative;">
                            <div style="position: absolute; inset: 0; background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.05) 2px, rgba(0,0,0,0.05) 4px); border-radius: 8px;"></div>
                            <img id="preview-sprite"
                                 src="https://placehold.co/96x96/9bbc0f/1a1a2e?text=?"
                                 alt="preview" class="w-24 h-24 mx-auto object-contain relative"
                                 style="image-rendering: pixelated;">
                        </div>

                        {{-- Name preview --}}
                        <p id="preview-name" class="text-green-400 mb-2" style="font-size: 10px; min-height: 18px;">???</p>
                        <p id="preview-dex" class="text-gray-600 mb-3" style="font-size: 6px;">#????</p>

                        {{-- Types preview --}}
                        <div id="preview-types" class="flex gap-2 justify-center mb-5 flex-wrap">
                            <span class="type-badge px-2 py-1 rounded" style="background: #333; color: #555; font-size: 6px;">TIPO</span>
                        </div>

                        {{-- Stats preview --}}
                        <div class="space-y-2">
                            @foreach(['hp' => 'HP', 'attack' => 'ATK', 'defense' => 'DEF', 'speed' => 'VEL'] as $key => $label)
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 flex-shrink-0 text-right" style="font-size: 5px; width: 22px;">{{ $label }}</span>
                                    <div class="flex-1 stat-preview">
                                        <div class="stat-preview-fill" id="preview-stat-{{ $key }}" style="width: {{ (50/255)*100 }}%;"></div>
                                    </div>
                                    <span class="text-gray-500 flex-shrink-0" id="preview-val-{{ $key }}" style="font-size: 5px; width: 20px; text-align: right;">50</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Form fields --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Identidade --}}
                <div class="info-panel">
                    <div class="panel-header">IDENTIDADE</div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name">NOME</label>
                                <input type="text" id="name" name="name"
                                       class="pixel-input @error('name') is-error @enderror"
                                       value="{{ old('name') }}" placeholder="Ex: Flameon"
                                       oninput="updatePreview()">
                                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="dex_number">NÚMERO DEX</label>
                                <input type="number" id="dex_number" name="dex_number"
                                       class="pixel-input @error('dex_number') is-error @enderror"
                                       value="{{ old('dex_number') }}" placeholder="Ex: 7001"
                                       oninput="updatePreview()">
                                @error('dex_number') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tipos --}}
                <div class="info-panel">
                    <div class="panel-header">TIPAGEM</div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="type_primary">TIPO PRIMÁRIO</label>
                                <select id="type_primary" name="type_primary"
                                        class="pixel-select @error('type_primary') is-error @enderror"
                                        onchange="updatePreview()">
                                    <option value="">-- Selecione --</option>
                                    @foreach(['fire','water','grass','electric','ice','fighting','poison','ground','flying','psychic','bug','rock','ghost','dragon','dark','steel','fairy','normal'] as $type)
                                        <option value="{{ $type }}" {{ old('type_primary') === $type ? 'selected' : '' }}>
                                            {{ strtoupper($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_primary') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="type_secondary">TIPO SECUNDÁRIO</label>
                                <select id="type_secondary" name="type_secondary"
                                        class="pixel-select @error('type_secondary') is-error @enderror"
                                        onchange="updatePreview()">
                                    <option value="">-- Nenhum --</option>
                                    @foreach(['fire','water','grass','electric','ice','fighting','poison','ground','flying','psychic','bug','rock','ghost','dragon','dark','steel','fairy','normal'] as $type)
                                        <option value="{{ $type }}" {{ old('type_secondary') === $type ? 'selected' : '' }}>
                                            {{ strtoupper($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_secondary') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lore --}}
                <div class="info-panel">
                    <div class="panel-header">LORE</div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="base_animal">ANIMAL BASE</label>
                                <input type="text" id="base_animal" name="base_animal"
                                       class="pixel-input @error('base_animal') is-error @enderror"
                                       value="{{ old('base_animal') }}" placeholder="Ex: Raposa">
                                @error('base_animal') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="inspiration">INSPIRAÇÃO</label>
                                <input type="text" id="inspiration" name="inspiration"
                                       class="pixel-input @error('inspiration') is-error @enderror"
                                       value="{{ old('inspiration') }}" placeholder="Ex: Rust">
                                @error('inspiration') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="info-panel">
                    <div class="panel-header">ESTATÍSTICAS (1 – 255)</div>
                    <div class="panel-body">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach(['hp' => 'HP', 'attack' => 'ATAQUE', 'defense' => 'DEFESA', 'speed' => 'VELOCIDADE'] as $field => $label)
                                <div>
                                    <label for="{{ $field }}">{{ $label }}</label>
                                    <input type="number" id="{{ $field }}" name="{{ $field }}"
                                           class="pixel-input @error($field) is-error @enderror"
                                           value="{{ old($field, 50) }}" min="1" max="255"
                                           oninput="updatePreview()">
                                    @error($field) <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Ataques --}}
                <div class="info-panel">
                    <div class="panel-header">
                        <span>MOVIMENTOS (OPCIONAL)</span>
                        <span style="color: #4a5568;">ATÉ 4 ATAQUES</span>
                    </div>
                    <div class="panel-body space-y-4">
                        @php $atkColors = ['#FF5959','#F5AC78','#9DB7F5','#A7DB8D']; @endphp
                        @for($i = 0; $i < 4; $i++)
                        <div style="background: #0d0d1a; border: 1px solid #1e2e4a; border-radius: 8px; padding: 12px 14px; border-left: 3px solid {{ $atkColors[$i] }};">
                            <p style="font-size: 5px; color: #4a5568; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px;">
                                ATAQUE {{ $i + 1 }}
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="attacks_{{ $i }}_name">NOME</label>
                                    <input type="text"
                                           id="attacks_{{ $i }}_name"
                                           name="attacks[{{ $i }}][name]"
                                           class="pixel-input rounded"
                                           value="{{ old('attacks.' . $i . '.name') }}"
                                           placeholder="Ex: Thunderbolt">
                                </div>
                                <div>
                                    <label for="attacks_{{ $i }}_desc">DESCRIÇÃO</label>
                                    <input type="text"
                                           id="attacks_{{ $i }}_desc"
                                           name="attacks[{{ $i }}][description]"
                                           class="pixel-input rounded"
                                           value="{{ old('attacks.' . $i . '.description') }}"
                                           placeholder="O que o ataque faz...">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                {{-- Sprite --}}
                <div class="info-panel">
                    <div class="panel-header">SPRITE (OPCIONAL)</div>
                    <div class="panel-body">
                        <label for="sprite_file">IMAGEM DO POKÉMON</label>
                        <div style="border: 2px dashed #1e2e4a; border-radius: 8px; padding: 20px; text-align: center; position: relative; cursor: pointer; transition: border-color 0.2s;"
                             id="drop-zone"
                             ondragover="event.preventDefault(); this.style.borderColor='#9bbc0f';"
                             ondragleave="this.style.borderColor='#1e2e4a';"
                             ondrop="handleDrop(event)">
                            <input type="file" id="sprite_file" name="sprite_file"
                                   accept="image/png,image/gif,image/jpeg,image/webp"
                                   class="@error('sprite_file') is-error @enderror"
                                   style="position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;"
                                   onchange="handleFileSelect(this)">
                            <div id="drop-hint">
                                <p style="font-size: 6px; color: #4a6a2a; margin-bottom: 8px;">CLIQUE OU ARRASTE A IMAGEM</p>
                                <p style="font-size: 5px; color: #4a5568;">PNG, GIF, JPG, WEBP — máx. 2MB</p>
                            </div>
                            <div id="file-chosen" class="hidden">
                                <p id="file-name" style="font-size: 6px; color: #9bbc0f;"></p>
                                <p style="font-size: 5px; color: #4a5568; margin-top: 4px;">Arquivo selecionado ✓</p>
                            </div>
                        </div>
                        @error('sprite_file') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit" class="action-btn green px-8 py-4 text-xs rounded">
                        SALVAR POKÉMON →
                    </button>
                </div>
            </div>
        </div>
    </form>

</main>

<script>
    const typeColors = {
        'normal':'#A8A878','fire':'#F08030','water':'#6890F0','electric':'#F8D030',
        'grass':'#78C850','ice':'#98D8D8','fighting':'#C03028','poison':'#A040A0',
        'ground':'#E0C068','flying':'#A890F0','psychic':'#F85888','bug':'#A8B820',
        'rock':'#B8A038','ghost':'#705898','dragon':'#7038F8','dark':'#705848',
        'steel':'#B8B8D0','fairy':'#EE99AC'
    };
    const typeDark = ['electric','ice','ground','steel','fairy','normal'];

    function makeBadge(type) {
        const bg = typeColors[type] || '#333';
        const color = typeDark.includes(type) ? '#333' : '#fff';
        return `<span style="font-size:6px;text-transform:uppercase;letter-spacing:1px;background:${bg};color:${color};padding:4px 8px;border-radius:4px;border:2px solid #000;box-shadow:2px 2px 0 #000;">${type.toUpperCase()}</span>`;
    }

    function updatePreview() {
        const name   = document.getElementById('name').value.trim();
        const dex    = document.getElementById('dex_number').value.trim();
        const type1  = document.getElementById('type_primary').value;
        const type2  = document.getElementById('type_secondary').value;

        document.getElementById('preview-name').textContent = name ? name.toUpperCase() : '???';
        document.getElementById('preview-dex').textContent  = dex ? '#' + dex.padStart(4,'0') : '#????';

        let typesHtml = '';
        if (type1) typesHtml += makeBadge(type1);
        if (type2) typesHtml += ' ' + makeBadge(type2);
        if (!typesHtml) typesHtml = '<span style="font-size:6px;background:#333;color:#555;padding:4px 8px;border-radius:4px;">TIPO</span>';
        document.getElementById('preview-types').innerHTML = typesHtml;

        ['hp','attack','defense','speed'].forEach(stat => {
            const val = parseInt(document.getElementById(stat).value) || 50;
            const pct = Math.min(100, (val / 255) * 100);
            document.getElementById('preview-stat-' + stat).style.width = pct + '%';
            document.getElementById('preview-val-' + stat).textContent = val;
        });
    }

    function handleFileSelect(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        document.getElementById('drop-hint').classList.add('hidden');
        document.getElementById('file-chosen').classList.remove('hidden');
        document.getElementById('file-name').textContent = file.name.toUpperCase();
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('preview-sprite').src = e.target.result; };
        reader.readAsDataURL(file);
    }

    function handleDrop(event) {
        event.preventDefault();
        document.getElementById('drop-zone').style.borderColor = '#1e2e4a';
        const file = event.dataTransfer.files[0];
        if (!file) return;
        const input = document.getElementById('sprite_file');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        handleFileSelect(input);
    }

    // Validação client-side
    document.getElementById('create-form').addEventListener('submit', function(e) {
        let ok = true;
        ['name','dex_number','type_primary','base_animal','inspiration'].forEach(f => {
            const el = document.getElementById(f);
            if (!el.value.trim()) { el.classList.add('is-error'); ok = false; }
            else el.classList.remove('is-error');
        });
        ['hp','attack','defense','speed'].forEach(f => {
            const el = document.getElementById(f);
            const v  = parseInt(el.value);
            if (!v || v < 1 || v > 255) { el.classList.add('is-error'); ok = false; }
            else el.classList.remove('is-error');
        });
        if (!ok) e.preventDefault();
    });

    // Inicializa preview com valores já preenchidos (old input)
    updatePreview();
</script>

</body>
</html>
