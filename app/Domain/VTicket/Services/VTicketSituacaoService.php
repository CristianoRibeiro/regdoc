<?php

namespace App\Domain\VTicket\Services;

use stdClass;

use App\Domain\VTicket\Contracts\VTicketSituacaoServiceInterface;
use App\Domain\VTicket\Contracts\VTicketSituacaoRepositoryInterface;

use App\Domain\VTicket\Models\valid_ticket_situacao;

class VTicketSituacaoService implements VTicketSituacaoServiceInterface
{
    /**
     * @var VTicketSituacaoRepositoryInterface
     */
    protected $VTicketSituacaoRepositoryInterface;

    /**
     * VTicketSituacaoService constructor.
     * @param VTicketSituacaoRepositoryInterface $VTicketSituacaoRepositoryInterface
     */
    public function __construct(VTicketSituacaoRepositoryInterface $VTicketSituacaoRepositoryInterface)
    {
        $this->VTicketSituacaoRepositoryInterface = $VTicketSituacaoRepositoryInterface;
    }

    /**
     * @param int $id_valid_ticket_situacao
     * @return valid_ticket_situacao|null
     */
    public function buscar(int $id_valid_ticket_situacao) : ?valid_ticket_situacao
    {
        return $this->VTicketSituacaoRepositoryInterface->buscar($id_valid_ticket_situacao);
    }

    /**
     * @param string $situacao
     * @return valid_ticket_situacao|null
     */
    public function buscar_situacao(string $situacao) : ?valid_ticket_situacao
    {
        return $this->VTicketSituacaoRepositoryInterface->buscar_situacao($situacao);
    }

    
}
