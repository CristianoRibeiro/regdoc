<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use App\Helpers\Helper;
use Carbon\Carbon;

use App\Models\usuario_2fa_email;

class VerifyAuthUser
{
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::User()->in_autenticacao_email === 'S') {
            if (Session::getId()) {
                $usuario_2fa_email = new usuario_2fa_email();
                $usuario_2fa_email = $usuario_2fa_email->where('id_usuario', Auth::user()->id_usuario)
                    ->where(function($where) {
                        $where->where('session_id', Session::getId())
                            ->orWhere(function($where) {
                                $where->where('dt_validade', '>=', Carbon::now())
                                    ->where('nu_endereco_ip', '=', request()->ip());
                            });
                    })
                    ->orderBy('dt_cadastro', 'desc')
                    ->first();

                if ($usuario_2fa_email) {
                    if ($usuario_2fa_email->in_validado === 'N') {
                        return redirect('/app/usuario/autenticacao-2fa');
                    }
                } else {
                    return redirect('/app/usuario/salvar-autenticacao-2fa');
                }
            }
        }

        return $next($request);
    }
}
