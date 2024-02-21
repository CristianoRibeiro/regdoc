<?php

namespace App\Domain\RegistroFiduciario\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte;

use stdClass;
use Exception;

class RegistroFiduciarioParteService implements RegistroFiduciarioParteServiceInterface
{
    /**
     * @var RegistroFiduciarioParteRepositoryInterface
     */
    protected $RegistroFiduciarioParteRepositoryInterface;

    /**
     * RegistroFiduciarioParteService constructor.
     * @param RegistroFiduciarioParteRepositoryInterface $RegistroFiduciarioParteRepositoryInterface
     */
    public function __construct(RegistroFiduciarioParteRepositoryInterface $RegistroFiduciarioParteRepositoryInterface)
    {
        $this->RegistroFiduciarioParteRepositoryInterface = $RegistroFiduciarioParteRepositoryInterface;
    }

    /**
     * @param int|null $id_tipo_parte_registro_fiduciario
     * @param int|null $id_pessoa
     * @return Collection
     */
    public function listar_agrupado(?int $id_tipo_parte_registro_fiduciario = null, ?int $id_pessoa = null) : Collection
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->listar_agrupado($id_tipo_parte_registro_fiduciario, $id_pessoa);
    }

    /**
     * @param int $id_registro_fiduciario_parte
     * @return registro_fiduciario_parte|null
     */
    public function buscar(int $id_registro_fiduciario_parte) : ?registro_fiduciario_parte
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->buscar($id_registro_fiduciario_parte);
    }

    /**
     * @param array $ids_registro_fiduciario_parte
     */
    public function buscar_ids(array $ids_registro_fiduciario_parte) : Collection
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->buscar_ids($ids_registro_fiduciario_parte);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_parte|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_parte
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte
     */
    public function inserir(stdClass $args) : registro_fiduciario_parte
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @param stdClass $args
     * @return registro_fiduciario_parte
     */
    public function alterar(registro_fiduciario_parte $registro_fiduciario_parte, stdClass $args) : registro_fiduciario_parte
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->alterar($registro_fiduciario_parte, $args);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte
     */
    public function buscar_alterar(stdClass $args) : registro_fiduciario_parte
    {
        $registro_fiduciario_parte = $this->buscar($args->id_registro_fiduciario_parte);
        if (!$registro_fiduciario_parte)
            throw new Exception('A parte não foi encontrada');

        return $this->alterar($registro_fiduciario_parte, $args);
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @param stdClass $args
     * @return string
     */
    public function definir_qualificacao(registro_fiduciario_parte $registro_fiduciario_parte, stdClass $args) : string
    {
        $tipo_parte_registro = $registro_fiduciario_parte->tipo_parte_registro_fiduciario;
        $in_procurador = $args->in_procurador;

        for($i=0;$i<5;$i++) {
            $qualificacao = $tipo_parte_registro->tipo_parte_registro_fiduciario_qualificacao();
            if (is_null($args->id_tipo_arquivo_grupo_produto ?? NULL)) {
                $qualificacao = $qualificacao->whereNull('id_tipo_arquivo_grupo_produto');
            } else {
                $qualificacao = $qualificacao->where('id_tipo_arquivo_grupo_produto', $args->id_tipo_arquivo_grupo_produto);
            }
            if (is_null($args->in_procurador ?? NULL)) {
                $qualificacao = $qualificacao->whereNull('in_procurador');
            } else {
                $qualificacao = $qualificacao->where('in_procurador', $args->in_procurador);
            }
            if (is_null($args->id_pessoa ?? NULL)) {
                $qualificacao = $qualificacao->whereNull('id_pessoa');
            } else {
                $qualificacao = $qualificacao->where('id_pessoa', $args->id_pessoa);
            }
            if (is_null($args->id_registro_fiduciario_tipo ?? NULL)) {
                $qualificacao = $qualificacao->whereNull('id_registro_fiduciario_tipo');
            } else {
                $qualificacao = $qualificacao->where('id_registro_fiduciario_tipo', $args->id_registro_fiduciario_tipo);
            }

            $qualificacao = $qualificacao->where('in_registro_ativo', 'S')
                ->first();

            if ($qualificacao) {
                if ($in_procurador == 'S' && $qualificacao->in_procurador == 'N') {
                    return 'Procurador do ' . $qualificacao->no_qualificacao;
                } else {
                    return $qualificacao->no_qualificacao;
                }
            } else {
                end($args);
                $ultima_key = key($args);

                if ($ultima_key) {
                    unset($args->$ultima_key);
                } else {
                    return ($in_procurador == 'S'?'Procurador do ':'').$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario;
                }
            }
        }
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @return bool
     */
    public function deletar(registro_fiduciario_parte $registro_fiduciario_parte) : bool
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->deletar($registro_fiduciario_parte);
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @param stdClass $args
     * @return bool
     */
    public function verificar_cpf(stdClass $args): bool
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->verificar_cpf($args);
    }

    /**
     * @return Collection<registro_fiduciario_parte>
     */
    public function buscar_por_cpf_cnpj(string $nu_cpf_cnpj): Collection
    {
        return $this->RegistroFiduciarioParteRepositoryInterface->buscar_por_cpf_cnpj($nu_cpf_cnpj);
    }
}
