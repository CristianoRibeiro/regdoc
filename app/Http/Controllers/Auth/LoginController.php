<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogDB;

use App\Http\Controllers\Controller;

use App\Models\usuario;
use App\Models\sessions;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/app';
    protected $loginPath = 'acessar';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*
     * A função original precisou ser alterada para que a view correta seja retornada.
     */
    public function showLoginForm()
    {
        return view('app.auth.acessar');
    }

    /*
     * Os campos originais da tela de acesso são diferentes.
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            'email_usuario' => 'required|string',
            'senha_usuario' => 'required|string'
        ];

        $this->validate($request, $rules);
    }

    /*
     * Foi preciso alterar pois é preciso autenticar o usuário de diversas formas.
     */
    protected function attemptLogin(Request $request)
    {
        $attempt_email = $this->guard()->attempt(
            ['email_usuario' => mb_strtolower($request->email_usuario, 'UTF-8'), 'password' => $request->senha_usuario, 'in_registro_ativo' => 'S', 'in_confirmado' => 'S', 'in_aprovado' => 'S', 'in_cliente' => 'N'], $request->filled('remember')
        );
        $attempt_login = $this->guard()->attempt(
            ['login' => mb_strtolower($request->email_usuario, 'UTF-8'), 'password' => $request->senha_usuario, 'in_registro_ativo' => 'S', 'in_confirmado' => 'S', 'in_aprovado' => 'S', 'in_cliente' => 'N'], $request->filled('remember')
        );
        return ($attempt_email or $attempt_login);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        $usuario = new usuario();
        $usuario = $usuario->where('email_usuario', mb_strtolower($request->email_usuario, 'UTF-8'))
                           ->orWhere('login', mb_strtolower($request->email_usuario, 'UTF-8'))
                           ->first();

        LogDB::insere(
            1,
            1,
            'Tentativa de acesso do usuário: '.mb_strtolower($request->email_usuario, 'UTF-8'),
            'Acesso Convencional',
            'N',
            request()->ip()
        );

        $trans_msg = 'auth.failed';
        if ($usuario) {
            if ($usuario->in_registro_ativo=='N') {
                LogDB::insere(
                    $usuario->id_usuario,
                    1,
                    'Tentativa de acesso do usuário: usuário desativado',
                    'Acesso Convencional',
                    'N',
                    request()->ip()
                );
                $trans_msg = 'auth.disabled';
            } elseif ($usuario->in_confirmado=='N') {
                LogDB::insere(
                    $usuario->id_usuario,
                    1,
                    'Tentativa de acesso do usuário: usuário não confirmado',
                    'Acesso Convencional',
                    'N',
                    request()->ip()
                );
                $trans_msg = 'auth.unconfirmed';
            } elseif ($usuario->in_aprovado=='N') {
                LogDB::insere(
                    $usuario->id_usuario,
                    1,
                    'Tentativa de acesso do usuário: usuário não aprovado',
                    'Acesso Convencional',
                    'N',
                    request()->ip()
                );
                $trans_msg = 'auth.inanalysis';
            }
        }

        return $this->sendFailedLoginResponse($request,$trans_msg);
    }

    protected function authenticated(Request $request, $user)
    {
        $usuario_pessoas = $user->usuario_pessoa->filter(fn($usuario_pessoa) => !$usuario_pessoa->pessoa->de_saml);
        if ($usuario_pessoas->count() > 0) {
            Auth::User()->pessoa_ativa = $usuario_pessoas->first()->pessoa;
            Auth::User()->pessoa_ativa_in_usuario_master = $usuario_pessoas->first()->in_usuario_master;

            if(Auth::User()->in_sessao_unica == 'S') {
                $sessions = new sessions();
                $sessions = $sessions->where('user_id', Auth::id())
                    ->get();

                foreach ($sessions as $session) {
                    $session->delete();
                }
            }

            LogDB::insere(
                $user->id_usuario,
                1,
                'Usuário logado com sucesso',
                'Acesso Convencional',
                'N',
                request()->ip()
            );

            return redirect()->intended($this->redirectPath());
        } else {
            LogDB::insere(
                $user->id_usuario,
                1,
                'Usuário logado com sucesso, entretanto não possui pessoa vinculada',
                'Acesso Convencional',
                'N',
                request()->ip()
            );

            return redirect()->route('app.logout');
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request, $trans)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans($trans)],
        ]);
    }

    /*
     * Foi preciso alterar a função para que após seja efetuado o logout, o usuário volte para a tela de login.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/'.$request->url);
    }

}
