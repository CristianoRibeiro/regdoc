<?php

namespace App\Http\Requests\RegistroFiduciario\Completar;

use Illuminate\Foundation\Http\FormRequest;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

class UpdateRegistroFiduciarioCartorio extends FormRequest
{
    /**
     * @var RegistroFiduciarioServiceInterface
     */
    private $RegistroFiduciarioServiceInterface;

    /**
     * RegistroFiduciarioCartorioController constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     */
    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface)
    {
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($this->registro);

        switch ($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto) {
            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                return [
                    'id_pessoa_cartorio_ri' => 'required|exists:pessoa,id_pessoa',
                ];
                break;
            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                return [
                    'id_pessoa_cartorio_rtd' => 'required|exists:pessoa,id_pessoa',
                ];
                break;
        }
        
    }

    public function attributes()
    {
        return [
            'id_pessoa_cartorio_ri' => 'Cartorio de registro de imoveis',
            'id_pessoa_cartorio_rtd' => 'Cartorio de títulos e documentos',
        ];
    }
}
