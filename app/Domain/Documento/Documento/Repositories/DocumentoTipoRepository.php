<?php

namespace App\Domain\Documento\Documento\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Documento\Models\documento_tipo;

use App\Domain\Documento\Documento\Contracts\DocumentoTipoRepositoryInterface;

class DocumentoTipoRepository implements DocumentoTipoRepositoryInterface
{
   /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return documento_tipo::orderBy('dt_cadastro', 'DESC')->get();
    }
}
