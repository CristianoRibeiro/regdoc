<?php

namespace App\Domain\Documento\Parte\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Parte\Models\documento_tipo_parte;

use App\Domain\Documento\Parte\Contracts\DocumentoTipoParteRepositoryInterface;

class DocumentoTipoParteRepository implements DocumentoTipoParteRepositoryInterface
{
   /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return documento_tipo_parte::orderBy('dt_cadastro', 'DESC')->get();
    }
}
