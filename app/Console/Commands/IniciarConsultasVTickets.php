<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\ConsultarTicket;

use App\Domain\Parte\Models\parte_emissao_certificado;

class IniciarConsultasVTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtickets:iniciarconsultas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buscar todas os registros da tabela parte_emissao_certificado com determinadas situações e iniciar as consultas.';

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
        $parte_emissao_certificados = new parte_emissao_certificado();
        $parte_emissao_certificados = $parte_emissao_certificados->whereIn('id_parte_emissao_certificado_situacao', [
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ENVIADO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_AGENDAMENTO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.PROBLEMA'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ATENDIMENTO_PRIORITARIO')
            ])
            ->whereNotNull('nu_ticket_vidaas')
            ->where('in_atualizacao_automatica', 'S')
            ->get();

        foreach ($parte_emissao_certificados as $key => $parte_emissao_certificado) {
            ConsultarTicket::dispatch($parte_emissao_certificado);
        }
    }
}
