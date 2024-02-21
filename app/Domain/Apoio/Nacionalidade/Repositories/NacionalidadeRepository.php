<?php

namespace App\Domain\Apoio\Nacionalidade\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Apoio\Nacionalidade\Models\nacionalidade;

use App\Domain\Apoio\Nacionalidade\Contracts\NacionalidadeRepositoryInterface;

class NacionalidadeRepository implements NacionalidadeRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return nacionalidade::orderBy('no_nacionalidade')->get();
    }
}
