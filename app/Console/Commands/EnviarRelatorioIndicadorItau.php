<?php

namespace App\Console\Commands;

use App\Mail\RelatorioIndicadores;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarRelatorioIndicadorItau extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'relatorio:itau';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manda o relatorio de excel do Itau via email.';

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
        Mail::to(['patricia.rigotto@valid.com', 'caio.vsantos@valid.com', 'segismar.soares@valid.com', 'cacau@rectask.com.br', 'menezes@rectask.com.br'])
            ->queue(new RelatorioIndicadores());
    }
}
