@php $navTeam = count(session('team', [])); @endphp
<nav style="background: #0d0d1a; border-bottom: 2px solid #2a3a0a;" class="sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 flex items-center justify-between gap-4" style="height: 52px;">

        {{-- Logo --}}
        <a href="/" style="font-size: 10px; color: #9bbc0f; text-decoration: none; letter-spacing: 1px; flex-shrink: 0;">
            ⚡ POKÉDEX
        </a>

        {{-- Links --}}
        <div class="flex items-center overflow-x-auto" style="gap: 2px;">
            <a href="/"
               style="font-size: 6px; text-decoration: none; padding: 6px 10px; border-radius: 4px; white-space: nowrap; transition: color 0.15s, background 0.15s;"
               class="{{ request()->routeIs('pokedex') ? 'text-green-400' : 'text-gray-500 hover:text-green-400' }}"
               @if(request()->routeIs('pokedex')) style="font-size: 6px; text-decoration: none; padding: 6px 10px; border-radius: 4px; white-space: nowrap; background: rgba(155,188,15,0.12); color: #9bbc0f;" @endif>
                POKÉDEX
            </a>
            <a href="{{ route('custom-pokemons.index') }}"
               style="font-size: 6px; text-decoration: none; padding: 6px 10px; border-radius: 4px; white-space: nowrap; {{ request()->routeIs('custom-pokemons.index') || request()->routeIs('custom-pokemons.show') ? 'background: rgba(155,188,15,0.12); color: #9bbc0f;' : 'color: #6b7280;' }}">
                CUSTOM DEX
            </a>
            <a href="{{ route('custom-pokemons.create') }}"
               style="font-size: 6px; text-decoration: none; padding: 6px 10px; border-radius: 4px; white-space: nowrap; {{ request()->routeIs('custom-pokemons.create') ? 'background: rgba(96,165,250,0.12); color: #60a5fa;' : 'color: #6b7280;' }}">
                + CRIAR
            </a>
            <a href="{{ route('quiz') }}"
               style="font-size: 6px; text-decoration: none; padding: 6px 10px; border-radius: 4px; white-space: nowrap; {{ request()->routeIs('quiz') || request()->routeIs('quiz.guess') ? 'background: rgba(248,208,48,0.12); color: #F8D030;' : 'color: #6b7280;' }}">
                QUIZ
            </a>
        </div>

        {{-- Team count --}}
        <div style="font-size: 6px; flex-shrink: 0; text-align: right;">
            <span style="color: #4a5568;">TIME </span>
            <span style="color: {{ $navTeam > 0 ? '#9bbc0f' : '#4a5568' }};">{{ $navTeam }}/6</span>
        </div>
    </div>
</nav>
