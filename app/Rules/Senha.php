<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Senha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validaSenha($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo ":attribute" deve conter no mínimo 8 caracteres, letras, números e um caracter especial.';
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    protected function validaSenha($attribute, $value)
    {
        // Regex para verifica a complexidade da senha
        $pattern =  '/(?=.*?[a-zA-Z])(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}/';

        // Verifica o tamanho da senha
        if (strlen($value) >= 8) {
            return preg_match($pattern, $value);
        }

        return false;
    }
}
