<?php

namespace App\Mail\Registros;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operador;

class NotificacaoOperadorRegistroFiduciarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registro_fiduciario;
    public $registro_fiduciario_operador;
    public $mensagem;
    public $assunto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(registro_fiduciario $registro_fiduciario,
                                registro_fiduciario_operador $registro_fiduciario_operador,
                                string $mensagem,
                                string $assunto)
    {
        $this->registro_fiduciario = $registro_fiduciario;
        $this->registro_fiduciario_operador = $registro_fiduciario_operador;
        $this->mensagem = $mensagem;
        $this->assunto = $assunto;

        $this->onQueue('emails');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id_pessoa_origem = $this->registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem;
        if (View::exists('email.produtos.registros.pessoas.'.$id_pessoa_origem.'.notificacao-operador')) {
            $view = 'email.produtos.registros.pessoas.'.$id_pessoa_origem.'.notificacao-operador';
        } else {
            $view = 'email.produtos.registros.notificacao-operador';
        }

        $from = $this->get_from($id_pessoa_origem);
        return $this->from($from["address"],$from["name"])
                    ->subject($this->assunto)
                    ->view($view);
    }

    private function get_from(int $id_pessoa_origem): array
    {
        switch ($id_pessoa_origem) {
            case 2685:
                $address = "itau@regdoc.com.br";
                $name = "Itaú";
                break;
            
            default:
                $address = config('mail.from.address');
                $name = config('mail.from.name');
                break;
        }
         return [
                "address"=> $address,
                "name"=> $name 
         ];
        
    }
}
