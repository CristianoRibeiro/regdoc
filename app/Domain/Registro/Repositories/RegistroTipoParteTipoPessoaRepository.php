<?php

namespace App\Domain\Registro\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;

use App\Domain\Registro\Models\registro_tipo_parte_tipo_pessoa;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaRepositoryInterface;

class RegistroTipoParteTipoPessoaRepository implements RegistroTipoParteTipoPessoaRepositoryInterface
{
    /**
     * @param stdClass $args, stdClass|null $filtros
     * @return mixed
     */
    public function listar_partes(stdClass $args, ?stdClass $filtros)
    {
        for($i=0;$i<3;$i++) {
            $registro_tipo_parte_tipo_pessoa = registro_tipo_parte_tipo_pessoa::where('in_registro_ativo', 'S');
            if (is_null($args->id_registro_fiduciario_tipo ?? NULL)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->whereNull('registro_tipo_parte_tipo_pessoa.id_registro_fiduciario_tipo');
            } else {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('registro_tipo_parte_tipo_pessoa.id_registro_fiduciario_tipo', $args->id_registro_fiduciario_tipo);
            }
            if (is_null($args->id_pessoa ?? NULL)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->whereNull('registro_tipo_parte_tipo_pessoa.id_pessoa');
            } else {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('registro_tipo_parte_tipo_pessoa.id_pessoa', $args->id_pessoa);
            }

            if (isset($filtros->in_simples)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('in_simples', $filtros->in_simples);
            }
            if (isset($filtros->id_tipo_parte_registro_fiduciario)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('id_tipo_parte_registro_fiduciario', $filtros->id_tipo_parte_registro_fiduciario);
            }
            if (isset($filtros->in_obrigatorio_proposta)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('in_obrigatorio_proposta', $filtros->in_obrigatorio_proposta);
            }
            if (isset($filtros->in_obrigatorio_contrato)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('in_obrigatorio_contrato', $filtros->in_obrigatorio_contrato);
            }
            if (isset($filtros->in_inserir_documentos)) {
                $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->where('in_inserir_documentos', $filtros->in_inserir_documentos);
            }

            $registro_tipo_parte_tipo_pessoa = $registro_tipo_parte_tipo_pessoa->orderBy('registro_tipo_parte_tipo_pessoa.nu_ordem', 'ASC')
                ->get();
            
            if (count($registro_tipo_parte_tipo_pessoa)>0) {
                return $registro_tipo_parte_tipo_pessoa;
            } else {
                end($args);
                $ultima_key = key($args);

                if ($ultima_key) {
                    unset($args->$ultima_key);
                } else {
                    return [];
                }
            }
        }
    }

    /**
     * @param int $id_registro_tipo_parte_tipo_pessoa
     * @return registro_tipo_parte_tipo_pessoa|null
     */
    public function buscar(int $id_registro_tipo_parte_tipo_pessoa) : ?registro_tipo_parte_tipo_pessoa
    {
        return registro_tipo_parte_tipo_pessoa::find($id_registro_tipo_parte_tipo_pessoa);
    }
}
