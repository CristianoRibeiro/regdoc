<?php

namespace App\Domain\VTicket\Repositories;

use Exception;
use stdClass;
use Auth;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\VTicket\Models\valid_ticket_situacao;

use App\Domain\VTicket\Contracts\VTicketSituacaoRepositoryInterface;

class VTicketSituacaoRepository implements VTicketSituacaoRepositoryInterface
{
   /**
     * @param int $id_valid_ticket_situacao
     * @return valid_ticket_situacao|null
     */
    public function buscar(int $id_valid_ticket_situacao) : ?valid_ticket_situacao
    {
        return valid_ticket_situacao::find($id_valid_ticket_situacao);
    }

    /**
     * @param string $situacao
     * @return valid_ticket_situacao|null
     */
    public function buscar_situacao(string $situacao) : ?valid_ticket_situacao
    {
        return valid_ticket_situacao::where('no_valid_ticket_situacao', $situacao)->firstOrFail();
    }

}
