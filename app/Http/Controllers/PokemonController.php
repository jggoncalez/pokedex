<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    private array $generations = [
        1 => [1, 151],   2 => [152, 251],  3 => [252, 386],
        4 => [387, 493], 5 => [494, 649],  6 => [650, 721],
        7 => [722, 809], 8 => [810, 905],  9 => [906, 1025],
    ];

    public function index(Request $request)
    {
        $gen = (int) $request->input('gen', 1);
        $gen = array_key_exists($gen, $this->generations) ? $gen : 1;
        [$min, $max] = $this->generations[$gen];

        $busca = $request->input('pokemon') ?? rand($min, $max);
        $nomeOuId = strtolower(trim((string) $busca));

        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$nomeOuId}");

        if (! $response->successful()) {
            return back()->with('erro', 'Pokémon não encontrado!');
        }

        $pokemon = $response->json();

        $speciesResponse = Http::get($pokemon['species']['url']);
        $species = $speciesResponse->successful() ? $speciesResponse->json() : null;

        $flavorText = null;
        $evolutionChain = [];

        if ($species) {
            foreach ($species['flavor_text_entries'] as $entry) {
                if ($entry['language']['name'] === 'en') {
                    $flavorText = preg_replace('/\s+/', ' ', $entry['flavor_text']);
                    break;
                }
            }

            if (isset($species['evolution_chain']['url'])) {
                $evoResponse = Http::get($species['evolution_chain']['url']);
                if ($evoResponse->successful()) {
                    $evolutionChain = $this->parseEvolutionChain(
                        $evoResponse->json()['chain']
                    );
                }
            }
        }

        return view('pokemon', compact('pokemon', 'flavorText', 'evolutionChain', 'gen'));
    }

    public function addToTeam(Request $request)
    {
        $team = session('team', []);

        $slot = [
            'id'     => $request->id,
            'name'   => $request->name,
            'sprite' => $request->sprite,
        ];

        if (count($team) < 6 && ! collect($team)->contains('id', $slot['id'])) {
            $team[] = $slot;
            session(['team' => $team]);
        }

        return back();
    }

    public function removeFromTeam(Request $request)
    {
        $team = collect(session('team', []))
            ->reject(fn($p) => $p['id'] == $request->id)
            ->values()
            ->all();

        session(['team' => $team]);

        return back();
    }

    private function parseEvolutionChain(array $chain): array
    {
        $result = [];
        $current = $chain;

        while ($current) {
            $name = $current['species']['name'];
            $url  = $current['species']['url'];
            $id   = basename(rtrim($url, '/'));

            $result[] = [
                'name'   => $name,
                'id'     => $id,
                'sprite' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png",
            ];

            $current = $current['evolves_to'][0] ?? null;
        }

        return $result;
    }
}
