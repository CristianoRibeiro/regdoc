<?php

namespace App\Domain\Documento\Assinatura\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Assinatura\Models\documento_assinatura_tipo;

use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaTipoRepositoryInterface;

class DocumentoAssinaturaTipoRepository implements DocumentoAssinaturaTipoRepositoryInterface
{
   /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return documento_assinatura_tipo::orderBy('dt_cadastro', 'DESC')->get();
    }
}
