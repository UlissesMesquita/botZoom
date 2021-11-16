<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeraTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GeraToken:criar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando cria o token no banco de dados da aplicação';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        return "<a href='<?php redirect()->route('auth');  ?>' target='_blank'></a>";
        
    }
}
