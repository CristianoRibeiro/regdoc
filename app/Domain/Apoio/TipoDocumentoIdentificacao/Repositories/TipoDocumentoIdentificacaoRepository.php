<?php

namespace App\Domain\Apoio\TipoDocumentoIdentificacao\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Apoio\TipoDocumentoIdentificacao\Models\tipo_documento_identificacao;

use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoRepositoryInterface;

class TipoDocumentoIdentificacaoRepository implements TipoDocumentoIdentificacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return tipo_documento_identificacao::orderBy('nu_ordem')->get();
    }
}
