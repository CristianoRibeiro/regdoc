<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;
use Exception;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCustodianteRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_custodiante;

class RegistroFiduciarioCustodianteRepository implements RegistroFiduciarioCustodianteRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_custodiante
     * @return registro_fiduciario_custodiante|null
     */
    public function buscar(int $id_registro_fiduciario_custodiante) : ?registro_fiduciario_custodiante
    {
        return registro_fiduciario_custodiante::find($id_registro_fiduciario_custodiante);
    }

    /**
     * @param int $id_cidade
     * @return Collection
     */
    public function custodiantes_disponiveis(int $id_cidade): Collection
    {
        $custodiantes = registro_fiduciario_custodiante::where('id_cidade', $id_cidade);

        return $custodiantes->get();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_custodiante
     * @throws Exception
     */
    public function insere(stdClass $args) : registro_fiduciario_custodiante
    {
        $args_registro_custodiante = new registro_fiduciario_custodiante();
        $args_registro_custodiante->uuid = Uuid::uuid4();
        $args_registro_custodiante->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $args_registro_custodiante->no_custodiante = $args->no_custodiante;
        $args_registro_custodiante->no_endereco = $args->no_endereco;
        $args_registro_custodiante->nu_endereco = $args->nu_endereco;
        $args_registro_custodiante->no_complemento = $args->no_complemento;
        $args_registro_custodiante->no_bairro = $args->no_bairro;
        $args_registro_custodiante->nu_cep = $args->nu_cep;
        $args_registro_custodiante->id_cidade = $args->id_cidade;
        $args_registro_custodiante->nu_telefone_contato = $args->nu_telefone_contato;
        $args_registro_custodiante->no_email_contato = $args->no_email_contato;
        $args_registro_custodiante->id_usuario_cad = Auth::User()->id_usuario;

        if (!$args_registro_custodiante->save()) {
            throw new Exception('Erro ao inserir o registro fiduciario custodiante.');
        }

        return $args_registro_custodiante;
    }
}
