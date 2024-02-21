<?php

namespace App\Http\Requests\RegistroFiduciario\Arquivos;

use Illuminate\Foundation\Http\FormRequest;

use Session;

class UpdateRegistroFiduciarioArquivosMultiplos extends FormRequest
{
    /**
     * Prepare fields for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $file_count = 0;
        if (Session::has('files_'.$this->hash_files)) {
            $file_count = count(Session::get('files_'.$this->hash_files));
        }

        $this->merge([
            'file_count' => $file_count
        ]);
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
        return [
            'hash_files' => 'required',
            'file_count' => 'numeric|min:1'
        ];
    }

    /**
     * Get the validation attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'hash_files' => 'Hash dos arquivos',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file_count.min' => 'Você deve enviar ao menos 1 (um) arquivo.'
        ];
    }
}
