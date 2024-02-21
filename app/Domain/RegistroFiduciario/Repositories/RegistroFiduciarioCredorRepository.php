<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;
use Exception;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_credor;

class RegistroFiduciarioCredorRepository implements RegistroFiduciarioCredorRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_credor
     * @return registro_fiduciario_credor|null
     */
    public function buscar(int $id_registro_fiduciario_credor) : ?registro_fiduciario_credor
    {
        return registro_fiduciario_credor::find($id_registro_fiduciario_credor);
    }

    /**
     * @param string $nu_cpf_cnpj
     * @return registro_fiduciario_credor|null
     */
    public function buscar_cnpj(string $nu_cpf_cnpj) : ?registro_fiduciario_credor
    {
        return registro_fiduciario_credor::where('nu_cpf_cnpj', $nu_cpf_cnpj)->first();
    }

    /**
     * @param int $id_cidade
     * @return Collection
     */
    public function credores_disponiveis(int $id_cidade, int $id_pessoa = NULL): Collection
    {
        $credores = registro_fiduciario_credor::where('id_cidade', $id_cidade);

        if ($id_pessoa>0) {
            $credores = $credores->join('pessoa_registro_fiduciario_credor', function($join) use ($id_pessoa) {
                                     $join->on('pessoa_registro_fiduciario_credor.id_registro_fiduciario_credor', '=', 'registro_fiduciario_credor.id_registro_fiduciario_credor')
                                          ->where('pessoa_registro_fiduciario_credor.id_pessoa', $id_pessoa);
                                 });
        }

        return $credores->get();
    }

    /**
     * @param int $id_cidade
     * @param int $id_pessoa
     * @return Collection
     */
    public function credores_disponiveis_agencia(int $id_cidade, int $id_pessoa) : Collection
    {
        $credores = registro_fiduciario_credor::where('id_cidade', $id_cidade);

        if ($id_pessoa > 0) {
            $credores = $credores->join('pessoa_registro_fiduciario_credor', function($join) use ($id_pessoa) {
                                     $join->on('pessoa_registro_fiduciario_credor.id_registro_fiduciario_credor', '=', 'registro_fiduciario_credor.id_registro_fiduciario_credor')
                                          ->where('pessoa_registro_fiduciario_credor.id_pessoa', $id_pessoa);
                                 });
        }

        return $credores->orderBy('no_credor', 'asc')
                        ->with(['agencia', 'agencia.banco'])
                        ->get();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_credor
     * @throws Exception
     */
    public function insere(stdClass $args) : registro_fiduciario_credor
    {
        $args_registro_credor = new registro_fiduciario_credor();
        $args_registro_credor->uuid = Uuid::uuid4();
        $args_registro_credor->id_agencia = $args->id_agencia;
        $args_registro_credor->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $args_registro_credor->no_credor = $args->no_credor;
        $args_registro_credor->id_usuario_cad = Auth::User()->id_usuario;

        if (!$args_registro_credor->save()) {
            throw new Exception('Erro ao inserir o registro fiduciario credor.');
        }

        return $args_registro_credor;
    }
}
