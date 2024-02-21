<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImpostoTransmissaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_impostotransmissao;
use stdClass;
use Auth;
use Exception;

class RegistroFiduciarioImpostoTransmissaoRepository implements RegistroFiduciarioImpostoTransmissaoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_impostotransmissao
     */
    public function inserir(stdClass $args): registro_fiduciario_impostotransmissao
    {
        $registro_impostotransmissao = new registro_fiduciario_impostotransmissao();
        $registro_impostotransmissao->in_insencao = $args->in_insencao;
        $registro_impostotransmissao->nu_inscricao = $args->nu_inscricao;
        $registro_impostotransmissao->nu_guia = $args->nu_guia;
        $registro_impostotransmissao->va_pago = $args->va_pago;
        $registro_impostotransmissao->id_usuario_cad = Auth::User()->id_usuario;
        if (!$registro_impostotransmissao->save()) {
            throw new Exception('Erro ao salvar a imposto de transmissão do registro.');
        }

        return $registro_impostotransmissao;
    }

    /**
     * @param int $id_registro_fiduciario_impostotransmissao
     * @param stdClass $args
     * @return registro_fiduciario_impostotransmissao
     * @throws Exception
     */
    public function alterar(int $id_registro_fiduciario_impostotransmissao, stdClass $args): registro_fiduciario_impostotransmissao
    {
        $registro_impostotransmissao = $this->buscaRegistroFiduciarioImpostoTrasmissaoPorId($id_registro_fiduciario_impostotransmissao);

        if (!$registro_impostotransmissao) {
            throw new Exception('O id_registro_fiduciario_impostotransmissao não foi encontrado!');
        }

        $registro_impostotransmissao->in_insencao = $args->in_insencao;
        $registro_impostotransmissao->nu_inscricao = $args->nu_inscricao;
        $registro_impostotransmissao->nu_guia = $args->nu_guia;
        $registro_impostotransmissao->va_pago = $args->va_pago;
        $registro_impostotransmissao->id_usuario_cad = Auth::User()->id_usuario;

        if (!$registro_impostotransmissao->save()) {
            throw new Exception('Erro ao salvar a imposto de transmissão do registro.');
        }

        $registro_impostotransmissao->refresh();

        return $registro_impostotransmissao;
    }

    /**
     * @param int $id_registro_fiduciario_impostotransmissao
     * @return registro_fiduciario_impostotransmissao|null
     */
    public function buscaRegistroFiduciarioImpostoTrasmissaoPorId(int $id_registro_fiduciario_impostotransmissao): ?registro_fiduciario_impostotransmissao
    {
        return registro_fiduciario_impostotransmissao::where('id_registro_fiduciario_impostotransmissao', $id_registro_fiduciario_impostotransmissao)->first();
    }
}
