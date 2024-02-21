<?php

namespace App\Claims;

use CorBosman\Passport\AccessToken;
use Illuminate\Http\Exceptions\HttpResponseException;

use Exception;

use App\Domain\Pessoa\Models\pessoa;
use App\Domain\Usuario\Models\usuario_pessoa;

class PessoaAtivaClaim
{
    public function handle(AccessToken $token, $next)
    {
        // Obter os vínculos ativos do usuário
        $usuario_pessoas = usuario_pessoa::where('id_usuario', $token->getUserIdentifier())->pluck('id_pessoa')->toArray();

        $pessoa = pessoa::where('nu_cpf_cnpj', request()->nu_cpf_cnpj)
            ->whereIn('id_pessoa', $usuario_pessoas)
            ->first();

        if (!$pessoa)
            throw new HttpResponseException(response()->json([
                'message' => 'O usuário não possui acesso a essa empresa.'
            ], 403));

        $token->addClaim('id_pessoa_ativa', $pessoa->id_pessoa);

        return $next($token);
    }
}
