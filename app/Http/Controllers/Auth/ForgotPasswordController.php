<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\usuario_recuperar_senha;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

use Validator;
use DB;
use URL;
use Mail;
use Exception;

use App\Mail\ForgotPasswordMail;

use App\Models\usuario;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('app.auth.passwords.email');
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email_usuario' => 'required|email']);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(
            ['email_usuario' => trans($response)]
        );
    }

    public function sendResetLinkEmail(Request $request, usuario $usuario, usuario_recuperar_senha $usuario_recuperar_senha)
    {
        DB::beginTransaction();

        try {
            $usuario = $usuario->where('email_usuario', mb_strtolower($request->email_usuario, 'UTF-8'))
                ->where('in_cliente', 'N')
                ->first();

            if(!$usuario)
                return Redirect::back()->with('error', 'O usúario não foi encontrado.');

            if ($usuario->in_registro_ativo != 'S')
                return Redirect::back()->with('error', 'O usuario informado foi desativado.');

            $token = Str::random(30);

            $usuario_recuperar_senha->id_usuario = $usuario->id_usuario;
            $usuario_recuperar_senha->token = $token;
            $usuario_recuperar_senha->dt_cadastro = Carbon::now();
            if (!$usuario_recuperar_senha->save())
                return Redirect::back()->with('error', 'Erro ao recuperar senha, tente novamente mais tarde.');

            Mail::to($usuario->email_usuario, $usuario->pessoa->no_pessoa)->queue(new ForgotPasswordMail($usuario->pessoa, $token));

            DB::commit();

            return Redirect::back()->with('status', 'success')->withInput($request->all());
        } catch (Exception $e) {
            DB::rollback();

            return Redirect::back()->with('error', 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''));
        }
    }

}
