<?php

namespace App\Http\Controllers;

use App\Events\VoteReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    // Exibe a página de lobby (manutenção)
    public function lobby()
    {
        return view('lobby');
    }

    public function vote(Request $request, $option)
    {
        // As opções válidas agora são 'fortuneRabbit' e 'fortuneTiger'
        if (!in_array($option, ['fortuneRabbit', 'fortuneTiger'])) {
            return response()->json(['error' => 'Opção inválida.'], 400);
        }

        $userId = auth()->check() ? auth()->id() : null;

        // Registra o voto na base de dados
        DB::table('votes')->insert([
            'user_id'    => $userId,
            'option'     => $option,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Calcula a contagem de votos para cada opção
        $voteCounts = DB::table('votes')
            ->select('option', DB::raw('count(*) as total'))
            ->groupBy('option')
            ->pluck('total', 'option');

        // Emite o evento via websocket para atualizar os clientes conectados
        broadcast(new VoteReceived($option, $voteCounts));

        return response()->json(['message' => 'Voto computado.', 'voteCounts' => $voteCounts]);
    }

    public function scoreboard()
    {
        $voteCounts = DB::table('votes')
            ->select('option', DB::raw('count(*) as total'))
            ->groupBy('option')
            ->pluck('total', 'option');

        return response()->json($voteCounts);
    }
}
