<?php

namespace App\Domain\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Parte\Models\parte_emissao_certificado;
use App\Domain\Pedido\Models\pedido;

interface ParteEmissaoCertificadoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listar(stdClass $args) : \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * @param int $id_parte_emissao_certificado
     * @return parte_emissao_certificado
     */
    public function buscar(int $id_parte_emissao_certificado) : ?parte_emissao_certificado;

    /**
     * @param string $nu_cpf_cnpj
     * @return parte_emissao_certificado
     */
    public function buscar_cpf_cnpj(string $nu_cpf_cnpj) : ?parte_emissao_certificado;

    /**
     * @param stdClass $args
     * @return parte_emissao_certificado
     */
    public function inserir(stdClass $args) : parte_emissao_certificado;

    /**
     * @param parte_emissao_certificado $parte_emissao_certificado
     * @param stdClass $args
     * @return parte_emissao_certificado
     */
    public function alterar(parte_emissao_certificado $parte_emissao_certificado, stdClass $args) : parte_emissao_certificado;

    /**
     * @return Collection<parte_emissao_certificado>
     */
    public function busca_todas_emissoes_pedido(pedido $pedido): Collection;
}
