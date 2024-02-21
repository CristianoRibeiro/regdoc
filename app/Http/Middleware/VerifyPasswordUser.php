<?php

namespace App\Http\Middleware;

use App\Models\usuario_senha;

use Illuminate\Support\Facades\Auth;

use Closure;

class VerifyPasswordUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $usuario_alterar_senha = usuario_senha::where('id_usuario', Auth::user()->id_usuario)
                                                   ->orderBy('dt_cadastro','DESC')->first();
                                
            if ($usuario_alterar_senha->in_alterar_senha === 'S') {
                return redirect('/app/usuario/alterar-senha');
            }
        }

        return $next($request);
    }
}
