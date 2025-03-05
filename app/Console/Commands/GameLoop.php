<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Events\GameStateEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GameLoop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:game-loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para criar a sala do jogo, inciar a votação, encerrar a votação, enviar o resultado e reiniciar o ciclo.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $state = [
            'option1' => [
                'name' => 'flamengo',
                'votes' => '123',
                'flag' => 'flamengo.png',
            ],
            'option2' => [
                'name' => 'vasco',
                'votes' => '321',
                'flag' => 'vasco.png',
            ],
            'playerCount' => 0,
            'status' => 'waiting', // waiting, voting, ended
            'createdAt' => now(),
            'id' => Str::ulid(),
        ];

        Cache::put('game_state', $state);

        broadcast(new GameStateEvent($state));

        $this->info('Sala criada com sucesso!');

        sleep(3);

        $state['status'] = 'voting';
        Cache::put('game_state', $state);
        broadcast(new GameStateEvent($state));

        $this->info('Votação iniciada!');

        sleep(10);

        $state['status'] = 'ended';
        Cache::put('game_state', $state);
        broadcast(new GameStateEvent($state));

        sleep(5);

        $this->info('Votação encerrada!');

        $this->call('app:game-loop');
    }
}
