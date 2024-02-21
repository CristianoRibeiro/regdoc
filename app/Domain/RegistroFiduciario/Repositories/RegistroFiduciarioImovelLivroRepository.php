<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLivroRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_livro;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioImovelLivroRepository implements RegistroFiduciarioImovelLivroRepositoryInterface
{
    /**
     * @return Collection
     */
    public function imovel_livros() : Collection
    {
        return registro_fiduciario_imovel_livro::where('in_registro_ativo', 'S')->get();
    }
}