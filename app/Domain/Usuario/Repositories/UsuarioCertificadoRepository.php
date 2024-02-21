<?php

namespace App\Domain\Usuario\Repositories;

use stdClass;
use Exception;
use Auth;
use Carbon\Carbon;

use App\Domain\Usuario\Models\usuario_certificado;

use App\Domain\Usuario\Contracts\UsuarioCertificadoRepositoryInterface;

class UsuarioCertificadoRepository implements UsuarioCertificadoRepositoryInterface
{
    /**
     * @param int $id_usuario_certificado
     * @return usuario_certificado|null
     */
    public function buscar(int $id_usuario_certificado) : ?usuario_certificado
    {
        return usuario_certificado::find($id_usuario_certificado);
    }

    /**
     * @param string $nu_serial
     * @return usuario_certificado|null
     */
    public function buscar_serial(string $nu_serial) : ?usuario_certificado
    {
        return usuario_certificado::where('nu_serial', $nu_serial)->first();
    }

    /**
     * @param stdClass $args
     * @return usuario_certificado
     */
    public function inserir(stdClass $args) : usuario_certificado
    {
        $novo_usuario_certificado = new usuario_certificado();
        $novo_usuario_certificado->id_usuario = $args->id_usuario ?? NULL;
        $novo_usuario_certificado->no_comum = $args->no_comum;
        $novo_usuario_certificado->no_autoridade_raiz = $args->no_autoridade_raiz;
        $novo_usuario_certificado->no_autoridade_unidade = $args->no_autoridade_unidade;
        $novo_usuario_certificado->no_autoridade_certificadora = $args->no_autoridade_certificadora;
        $novo_usuario_certificado->nu_serial = $args->nu_serial;
        $novo_usuario_certificado->dt_validade_ini = $args->dt_validade_ini;
        $novo_usuario_certificado->dt_validade_fim = $args->dt_validade_fim;
        $novo_usuario_certificado->tp_certificado = $args->tp_certificado;
        $novo_usuario_certificado->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $novo_usuario_certificado->no_responsavel = $args->no_responsavel;
        $novo_usuario_certificado->de_campos = $args->de_campos;

        if (!$novo_usuario_certificado->save()) {
            throw new Exception('Erro ao salvar o certificado.');
        }

        return $novo_usuario_certificado;
    }
}
