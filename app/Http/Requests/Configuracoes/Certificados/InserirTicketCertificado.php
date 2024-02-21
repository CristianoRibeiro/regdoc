<?php

namespace App\Http\Requests\Configuracoes\Certificados;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class InserirTicketCertificado extends FormRequest
{
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
        return [
            'nu_ticket_vidaas' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'nu_ticket_vidaas' => 'Ticket de emissão do VIDaaS',
        ];
    }
}
