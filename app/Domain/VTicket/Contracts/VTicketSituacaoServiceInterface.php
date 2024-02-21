<?php

namespace App\Domain\VTicket\Contracts;

use stdClass;

use App\Domain\VTicket\Models\valid_ticket_situacao;

interface VTicketSituacaoServiceInterface
{
    /**
     * @param int $id_valid_ticket_situacao
     * @return valid_ticket_situacao|null
     */
    public function buscar(int $id_valid_ticket_situacao) : ?valid_ticket_situacao;

    /**
     * @param string $situacao
     * @return valid_ticket_situacao|null
     */
    public function buscar_situacao(string $situacao) : ?valid_ticket_situacao;

}
