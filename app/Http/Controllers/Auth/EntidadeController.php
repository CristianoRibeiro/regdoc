<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntidadeController extends Controller
{
    public function selecionar_entidade()
    {
      return view('app.auth.selecionar-entidade');
    }

    public function definir_entidade(Request $request)
		{
			if ($request->key >= 0) {
				$usuario_pessoas = Auth::User()->usuario_pessoa;
				if (!isset($usuario_pessoas[$request->key])) return redirect()->back()->withErrors(['O vínculo selecionado não existe.']);

				$pessoa = $usuario_pessoas[$request->key]->pessoa;
				
				Auth::User()->pessoa_ativa = $pessoa;
				Auth::User()->pessoa_ativa_in_usuario_master = $usuario_pessoas[$request->key]->in_usuario_master;
				
				return redirect('/app');
			} else {
				return redirect()->back()->withErrors(['A chave do vínculo não foi informada.']);
			}
		}
}
