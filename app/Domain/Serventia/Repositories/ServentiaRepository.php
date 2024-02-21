<?php

namespace App\Domain\Serventia\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Auth;

use App\Domain\Serventia\Models\serventia;

use App\Domain\Serventia\Contracts\ServentiaRepositoryInterface;

class ServentiaRepository implements ServentiaRepositoryInterface
{
    
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        $serventia = new serventia();
        $serventia = $serventia->join('pessoa', 'pessoa.id_pessoa', '=', 'serventia.id_pessoa')
                               ->join('tipo_serventia', 'tipo_serventia.id_tipo_serventia', '=', 'serventia.id_tipo_serventia')
                               ->join('pessoa_endereco', 'pessoa_endereco.id_pessoa', '=', 'serventia.id_pessoa')
                               ->join('endereco', 'pessoa_endereco.id_endereco', '=', 'endereco.id_endereco')
                               ->join('cidade', 'endereco.id_cidade', '=', 'cidade.id_cidade')
                               ->join('estado', 'cidade.id_estado', '=', 'estado.id_estado');
                              
        if (($filtros->id_tipo_serventia ?? 0) > 0) {
            $serventia = $serventia->where('tipo_serventia.id_tipo_serventia', $filtros->id_tipo_serventia);
        }
        
        if (isset($filtros->nu_cns)) {
            $serventia = $serventia->where('serventia.codigo_cns_completo', $filtros->nu_cns);
        } 

        if (isset($filtros->no_serventia)) {
            $serventia = $serventia->where('serventia.no_serventia','ilike', '%'.$filtros->no_serventia.'%');
        }
        
        if (isset($filtros->email_serventia)) {
            $serventia = $serventia->where('pessoa.no_email_pessoa', $filtros->email_serventia);
        } 

        if (isset($filtros->no_pessoa)) {
            $serventia = $serventia->where('pessoa.no_pessoa','ilike', '%'.$filtros->no_pessoa.'%');
        }

        if (($filtros->nu_cpf_cnpj ?? 0) > 0) {
            $serventia = $serventia->where('pessoa.nu_cpf_cnpj', $filtros->nu_cpf_cnpj);
        }
        
        if (($filtros->id_estado ?? 0) > 0) {
            $serventia = $serventia->where('estado.id_estado', $filtros->id_estado);
                        
            if (($filtros->id_cidade ?? 0) > 0) {
                $serventia = $serventia->where('cidade.id_cidade', $filtros->id_cidade);
            }

        }

        $serventia = $serventia->orderBy('serventia.dt_cadastro','desc');
                       
        return $serventia;                       
    }
    
    /**
     * @param int $id_serventia
     * @return serventia|null
     */
    public function buscar(int $id_serventia) : ?serventia
    {
        return serventia::find($id_serventia);
    }

    /**
     * @param string $codigo_cns_completo
     * @return serventia|null
     */
    public function buscar_cns(string $codigo_cns_completo) : ?serventia
    {
        return serventia::where('codigo_cns_completo', $codigo_cns_completo)->first();
    }

    /**
     * @param stdClass $args
     * @return serventia
     * @throws Exception
     */
    public function inserir(stdClass $args): serventia
    {
        $novo_serventia = new serventia();
        $novo_serventia->id_tipo_serventia = $args->id_tipo_serventia;
        $novo_serventia->id_pessoa = $args->id_pessoa;
        $novo_serventia->id_unidade_gestora = 1;
        $novo_serventia->no_serventia = $args->no_serventia;
        $novo_serventia->abv_serventia = $args->abv_serventia;
        $novo_serventia->in_registro_ativo = 'S';
        $novo_serventia->id_usuario_cad = Auth::User()->id_usuario;
        $novo_serventia->dt_cadastro = Carbon::now();
        $novo_serventia->hora_inicio_expediente = '08:00';
        $novo_serventia->hora_inicio_almoco = '12:00';
        $novo_serventia->hora_termino_almoco = '13:00';
        $novo_serventia->hora_termino_expediente = '16:00';
        $novo_serventia->no_oficial = $args->no_responsavel;
        $novo_serventia->no_substituto = NULL;
        $novo_serventia->codigo_cns = $args->codigo_cns ?? NULL;
        $novo_serventia->dv_codigo_cns = $args->dv_codigo_cns ?? NULL;
        $novo_serventia->codigo_cns_completo = $args->codigo_cns_completo;
        $novo_serventia->id_cartorio_arisp = $args->id_cartorio_arisp ?? NULL;
        $novo_serventia->no_titulo = $args->no_titulo ?? NULL;
        $novo_serventia->id_grupo_serventia = 2;
        $novo_serventia->telefone_serventia = $args->telefone_serventia;
        $novo_serventia->site_serventia = $args->site_serventia;
        $novo_serventia->whatsapp_serventia = $args->whatsapp_serventia;
       
        if (!$novo_serventia->save()) {
            throw new Exception('Erro ao salvar serventia.');
        }

        return $novo_serventia;
    }

    /**
     * @param serventia $serventia
     * @param stdClass $args
     * @return serventia
     * @throws Exception
     */
    public function alterar(serventia $serventia, stdClass $args) : serventia
    {
        if (isset($args->id_tipo_serventia)) {
            $serventia->id_tipo_serventia = $args->id_tipo_serventia;
        }
        if (isset($args->no_serventia)) {
            $serventia->no_serventia = $args->no_serventia;
            $serventia->abv_serventia = $args->no_serventia;
        }
        if (isset($args->codigo_cns_completo)) {
            $serventia->codigo_cns_completo = $args->codigo_cns_completo;
        }
        if (isset($args->telefone_serventia)) {
            $serventia->telefone_serventia = $args->telefone_serventia;
        }
        if (isset($args->site_serventia)) {
            $serventia->site_serventia = $args->site_serventia;
        }
        if (isset($args->whatsapp_serventia)) {
            $serventia->whatsapp_serventia = $args->whatsapp_serventia;
        }
        if (!$serventia->save()) {
            throw new Exception('Erro ao atualizar o serventia.');
        }

        $serventia->refresh();

        return $serventia;
    }
}
