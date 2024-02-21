<?php

namespace App\Mail;

use App\Exports\RelatorioIndicador as ExportsRelatorioIndicador;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Maatwebsite\Excel\Facades\Excel;

class RelatorioIndicadores extends Mailable
{
    use Queueable, SerializesModels;

    public $timeout = 300;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('emails');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('REGDOC - Relatório Indicadores')
            ->attach(Excel::download(new ExportsRelatorioIndicador(), 'relatorio-indicador.xlsx', null, ['no_pessoa_entidade'])->getFile(), [
                'as' => 'relatorio-indicadores_' . Carbon::now()->format('d-m-Y_H-i-s') . '.xlsx',
                'mime' => 'application/file'
            ])
            ->view('email.relatorio.relatorio-indicador'); 
    }
}