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

        $response = Http::withoutVerifying()
            ->get("https://pokeapi.co/api/v2/pokemon/{$nomeOuId}");

        if (! $response->successful()) {
            return back()->with('erro', 'Pokémon não encontrado!');
        }

        $pokemon = $response->json();

        $speciesResponse = Http::withoutVerifying()->get($pokemon['species']['url']);
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
                $evoResponse = Http::withoutVerifying()->get($species['evolution_chain']['url']);
                if ($evoResponse->successful()) {
                    $evolutionChain = $this->parseEvolutionChain(
                        $evoResponse->json()['chain']
                    );
                }
            }
        }

        return view('pokemon', compact('pokemon', 'flavorText', 'evolutionChain', 'gen'));
    }

    public function quiz(Request $request)
    {
        $gen = (int) $request->input('gen', 0);

        if ($gen > 0 && array_key_exists($gen, $this->generations)) {
            [$min, $max] = $this->generations[$gen];
        } else {
            $gen = 0;
            [$min, $max] = [1, 1025];
        }

        $id = rand($min, $max);
        $response = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/{$id}");

        if (! $response->successful()) {
            return redirect()->route('quiz');
        }

        $pokemon = $response->json();
        session(['quiz_poke_id' => $pokemon['id'], 'quiz_gen' => $gen]);

        return view('quiz', $this->quizViewData($pokemon, $gen));
    }

    public function guess(Request $request)
    {
        $pokemonId = session('quiz_poke_id');
        $quizGen   = session('quiz_gen', 0);

        if (! $pokemonId) {
            return redirect()->route('quiz');
        }

        $response = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/{$pokemonId}");
        if (! $response->successful()) {
            return redirect()->route('quiz');
        }

        $pokemon   = $response->json();
        $normalize = fn (string $s) => strtolower(preg_replace('/[\s\-]/', '', trim($s)));
        $guess     = trim($request->input('guess', ''));
        $acertou   = $guess !== '' && $normalize($guess) === $normalize($pokemon['name']);

        $streak  = session('quiz_streak', 0);
        $best    = session('quiz_best', 0);
        $total   = session('quiz_total', 0) + 1;
        $correct = session('quiz_correct', 0) + ($acertou ? 1 : 0);
        $streak  = $acertou ? $streak + 1 : 0;
        $best    = max($best, $streak);

        session(['quiz_streak' => $streak, 'quiz_best' => $best, 'quiz_total' => $total, 'quiz_correct' => $correct]);
        session()->forget('quiz_poke_id');

        return view('quiz', array_merge($this->quizViewData($pokemon, $quizGen), compact('acertou')));
    }

    private function quizViewData(array $pokemon, int $quizGen): array
    {
        return [
            'pokemon' => $pokemon,
            'quizGen' => $quizGen,
            'score'   => [
                'streak'  => session('quiz_streak', 0),
                'best'    => session('quiz_best', 0),
                'total'   => session('quiz_total', 0),
                'correct' => session('quiz_correct', 0),
            ],
        ];
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
