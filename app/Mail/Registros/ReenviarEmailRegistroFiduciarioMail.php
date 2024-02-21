<?php

namespace App\Mail\Registros;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class ReenviarEmailRegistroFiduciarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registro_fiduciario;
    public $args_email;
    public $assunto;

    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct(registro_fiduciario $registro_fiduciario,
                                array $args_email,
                                string $assunto)
    {
        $this->registro_fiduciario = $registro_fiduciario;
        $this->args_email = $args_email;
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
        if (View::exists('email.produtos.registros.pessoas.'.$id_pessoa_origem.'.acesso-registro')) {
            $view = 'email.produtos.registros.pessoas.'.$id_pessoa_origem.'.acesso-registro';
        } else {
            $view = 'email.produtos.registros.acesso-registro';
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
