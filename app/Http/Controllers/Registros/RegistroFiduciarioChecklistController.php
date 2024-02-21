<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use LogDB;
use stdClass;

use App\Http\Requests\RegistroFiduciario\Checklists\StoreChecklistRegistroFiduciario;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistServiceInterface;

class RegistroFiduciarioChecklistController extends Controller
{
     /**
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioChecklistServiceInterface
     */
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioChecklistServiceInterface;

    /**
     * RegistroFiduciarioChecklistController constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioChecklistServiceInterface $RegistroFiduciarioChecklistServiceInterface
     */
    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioChecklistServiceInterface $RegistroFiduciarioChecklistServiceInterface)
    {
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioChecklistServiceInterface = $RegistroFiduciarioChecklistServiceInterface;
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function checklist(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        if ($registro_fiduciario) {
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
            ];
            return view('app.produtos.registro-fiduciario.detalhes.checklists.geral-registro-checklists-detalhes', $compact_args);
        }
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvar_checklist(StoreChecklistRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                foreach ($registro_fiduciario->registro_fiduciario_checklists as $registro_fiduciario_checklist) {
                    $args_checklist = new stdClass();
                    $args_checklist->in_marcado = array_key_exists($registro_fiduciario_checklist->id_registro_fiduciario_checklist, ($request->id_registro_fiduciario_checklist ?? [])) ? 'S' : 'N';

                    $this->RegistroFiduciarioChecklistServiceInterface->alterar($registro_fiduciario_checklist, $args_checklist);
                }

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O checklist do registro foi salvo com sucesso.',
                    'Registro - Checklist',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O checklist foi inserido com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao salvar o checklist do registro.',
                    'Registro - Checklist',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json, 500);
            }
        }
    }
}
