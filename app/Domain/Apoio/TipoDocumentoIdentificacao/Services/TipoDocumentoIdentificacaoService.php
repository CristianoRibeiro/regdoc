<?php

namespace App\Domain\Apoio\TipoDocumentoIdentificacao\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoRepositoryInterface;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoServiceInterface;

class TipoDocumentoIdentificacaoService implements TipoDocumentoIdentificacaoServiceInterface
{

    /**
     * @var TipoDocumentoIdentificacaoRepositoryInterface
     */
    protected $TipoDocumentoIdentificacaoRepositoryInterface;

    /**
     * TipoDocumentoIdentificacaoService constructor.
     * @param TipoDocumentoIdentificacaoRepositoryInterface $TipoDocumentoIdentificacaoRepositoryInterface
     */
    public function __construct(TipoDocumentoIdentificacaoRepositoryInterface $TipoDocumentoIdentificacaoRepositoryInterface)
    {
        $this->TipoDocumentoIdentificacaoRepositoryInterface = $TipoDocumentoIdentificacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->TipoDocumentoIdentificacaoRepositoryInterface->listar();
    }
}
