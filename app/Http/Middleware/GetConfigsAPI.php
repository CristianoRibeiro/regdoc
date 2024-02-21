<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Lcobucci\JWT\Configuration;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\pessoa;

class GetConfigsAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Obter a informação do token
        $token = $request->bearerToken();
        $jwt = Configuration::forUnsecuredSigner()->parser()->parse($token);

        // Seta a pessoa ativa na sessão do usuário
        if ($jwt->claims()->has('id_pessoa_ativa')) {
            $pessoa = new pessoa();
            $pessoa = $pessoa->find($jwt->claims()->get('id_pessoa_ativa'));

            Auth::User()->pessoa_ativa = $pessoa;

            return $next($request);
        }

        throw new HttpResponseException(response()->json([
            'message' => 'Empresa não encontrada no payload do Bearer token, entre em contato com o administrador.'
        ], 403));
    }
}
