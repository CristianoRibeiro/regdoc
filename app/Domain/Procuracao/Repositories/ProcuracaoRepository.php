<?php

namespace App\Domain\Procuracao\Repositories;

use Auth;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Procuracao\Models\procuracao;
use App\Domain\Procuracao\Models\pessoa_procuracao;

use App\Domain\Procuracao\Contracts\ProcuracaoRepositoryInterface;

class ProcuracaoRepository implements ProcuracaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        //Vincular procurações com a pessoa_ativa
        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:
                return procuracao::join('pessoa_procuracao', 'pessoa_procuracao.id_procuracao', '=', 'procuracao.id_procuracao')
                                   ->where("pessoa_procuracao.id_pessoa", Auth::User()->pessoa_ativa->id_pessoa)
                                   ->where('in_registro_ativo', 'S')
                                   ->get();
                    
                break;
            default:
                return procuracao::where('in_registro_ativo', 'S')->get();
                break;
        }


    }

    /**
     * @param string $uuid
     * @return procuracao|null
     */
    public function buscar_uuid(string $uuid) : ?procuracao
    {
        return procuracao::where('uuid', $uuid)->first();
    }
}
