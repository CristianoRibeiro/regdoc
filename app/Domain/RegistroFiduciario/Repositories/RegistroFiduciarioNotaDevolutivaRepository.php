<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class RegistroFiduciarioNotaDevolutivaRepository implements RegistroFiduciarioNotaDevolutivaRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_nota_devolutiva::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva
     * @return registro_fiduciario_nota_devolutiva|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva) : ?registro_fiduciario_nota_devolutiva
    {
        return registro_fiduciario_nota_devolutiva::find($id_registro_fiduciario_nota_devolutiva);
    }


    /**
     * @param string $uuid
     * @return registro_fiduciario_nota_devolutiva|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_nota_devolutiva
    {
        return registro_fiduciario_nota_devolutiva::where('uuid', $uuid)->firstOrFail();
    }


     /**
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_nota_devolutiva
    {
        $nova_nota_devolutiva = new registro_fiduciario_nota_devolutiva();
        $nova_nota_devolutiva->uuid = Uuid::uuid4();
        $nova_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = $args->id_registro_fiduciario_nota_devolutiva_situacao;
        $nova_nota_devolutiva->id_registro_fiduciario = $args->id_registro_fiduciario;
        $nova_nota_devolutiva->id_nota_devolutiva_cumprimento = $args->id_nota_devolutiva_cumprimento ?? NULL;
        $nova_nota_devolutiva->de_nota_devolutiva = $args->de_nota_devolutiva;
        $nova_nota_devolutiva->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        if (!$nova_nota_devolutiva->save()) {
            throw new Exception('Erro ao salvar a nota devolutiva.');
        }

        return $nova_nota_devolutiva;
    }

    /**
     * @param registro_fiduciario_nota_devolutiva $registro_fiduciario_nota_devolutiva
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva
     * @throws Exception
     */
    public function alterar(registro_fiduciario_nota_devolutiva $registro_fiduciario_nota_devolutiva, stdClass $args) : registro_fiduciario_nota_devolutiva
    {
        if (isset($args->id_registro_fiduciario_nota_devolutiva_situacao)) {
            $registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = $args->id_registro_fiduciario_nota_devolutiva_situacao;
        }
        if (isset($args->id_registro_fiduciario)) {
            $registro_fiduciario_nota_devolutiva->id_registro_fiduciario;
        }
        if (isset($args->id_nota_devolutiva_cumprimento)) {
            $registro_fiduciario_nota_devolutiva->id_nota_devolutiva_cumprimento = $args->id_nota_devolutiva_cumprimento;
        }
        if (isset($args->de_nota_devolutiva)) {
            $registro_fiduciario_nota_devolutiva->de_nota_devolutiva = $args->de_nota_devolutiva;
        }

        if (!$registro_fiduciario_nota_devolutiva->save()) {
            throw new  Exception('Erro ao atualizar a nota devolutiva.');
        }

        $registro_fiduciario_nota_devolutiva->refresh();

        return $registro_fiduciario_nota_devolutiva;
    }

    public function alterarSituacaoPorFiduciario(registro_fiduciario $registro, int $idSituacao, $id_registro_fiduciario_nota_devolutiva): bool
    {
        return registro_fiduciario_nota_devolutiva::where('id_registro_fiduciario','=',$registro->id_registro_fiduciario)
            ->where('id_registro_fiduciario_nota_devolutiva', '=', $id_registro_fiduciario_nota_devolutiva)
            ->update(['id_registro_fiduciario_nota_devolutiva_situacao' => $idSituacao])
;
    }
}
