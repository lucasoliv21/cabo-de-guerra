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
        // Supondo que as opções válidas sejam 'opcaoA' e 'opcaoB'
        if (!in_array($option, ['opcaoA', 'opcaoB'])) {
            return response()->json(['error' => 'Opção inválida.'], 400);
        }

        // Acho que nao vai ter necessidade de autenticação, mas se tiver, o código abaixo é para pegar o id do usuário
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
        broadcast(new VoteReceived($option, $voteCounts))->toOthers();

        return response()->json(['message' => 'Voto computado.', 'voteCounts' => $voteCounts]);
    }
}
