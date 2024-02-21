<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetSenha;
use App\Models\usuario_recuperar_senha;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Validator;
use DB;
use Hash;
use Exception;
use Carbon\Carbon;

use App\Models\usuario;
use App\Models\usuario_senha;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, usuario_recuperar_senha $usuario_recuperar_senha)
    {
        if (!empty($request->token)) {
            $usuario_recuperar_senha = $usuario_recuperar_senha->where('token', $request->token)
                ->where('in_utilizado', 'N')
                ->first();

            $token = $request->token;
            if ($usuario_recuperar_senha) {
                return view('app.auth.passwords.reset', compact('token'));
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function reset(ResetSenha $request, usuario $usuario, usuario_recuperar_senha $usuario_recuperar_senha)
    {
        DB::beginTransaction();

        try {
            $usuario_recuperar_senha = $usuario_recuperar_senha->where('token',$request->token)->first();
            $usuario_recuperar_senha->in_utilizado = 'S';
            if (!$usuario_recuperar_senha->save())
                return Redirect::back()->with('error', 'Erro ao desativar o token da recuperação.');

            $usuario = $usuario->find($usuario_recuperar_senha->id_usuario);

            if (!$usuario)
                return Redirect::back()->with('error', 'O usúario não foi encontrado.');

            if ($usuario->in_registro_ativo != 'S')
                return Redirect::back()->with('error', 'O usuario informado foi desativado.');

            $desativar_senha_atual = $usuario->usuario_senha()->orderBy('dt_cadastro', 'desc')->first();
            $desativar_senha_atual->dt_fim_periodo = Carbon::now();
            if (!$desativar_senha_atual->save())
                return Redirect::back()->with('error', 'Erro ao desativar a senha anterior.');

            $nova_senha = new usuario_senha();
            $nova_senha->id_usuario = $usuario->id_usuario;
            $nova_senha->dt_ini_periodo = Carbon::now();
            $nova_senha->in_alterar_senha = 'N';
            $nova_senha->senha = Hash::make($request->nova_senha);
            if(!$nova_senha->save())
                return Redirect::back()->with('error', 'Erro ao salvar a nova senha.');

            DB::commit();

            return redirect('/app');
        } catch(Exception $e) {
            DB::rollback();

            return Redirect::back()->with('error', 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''));
        }
    }
}
