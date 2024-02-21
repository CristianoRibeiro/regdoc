<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

// Classes necessárias para assinatura digital via LACUNA
use Lacuna\RestPki\RestPkiClient;
use Lacuna\RestPki\StandardSignaturePolicies;
use Lacuna\RestPki\StandardSecurityContexts;

use Auth;
use LogDB;
use Session;

use App\Models\pessoa;

class CertificadoController extends Controller
{
    public function showLoginForm()
    {
        $RestPki = new RestPkiClient(config('lacuna.REST_PKI.URL'), config('lacuna.REST_PKI.TOKEN'));

        $auth = $RestPki->getAuthentication();
        if (config('app.env') === 'production') {
            $security_context = StandardSecurityContexts::PKI_BRAZIL;
        } else {
            $security_context = '803517ad-3bbc-4169-b085-60053a8f6dbf';
        }

        // Argumentos para o retorno da view
        $compact_args = [
            'token' => $auth->startWithWebPki($security_context)
        ];

        return view('app.auth.acessar-certificado',$compact_args);
    }

    public function login(Request $request)
    {
        $RestPki = new RestPkiClient(config('lacuna.REST_PKI.URL'), config('lacuna.REST_PKI.TOKEN'));

        $auth = $RestPki->getAuthentication();
        $vr = $auth->completeWithWebPki($request->token);

        if ($vr->isValid()) {
            $certificado = $auth->getCertificate();

            if (!empty($certificado->pkiBrazil->cpf)) {
                $nu_cpf_cnpj = $certificado->pkiBrazil->cpf;
            } elseif (!empty($certificado->pkiBrazil->cnpj)) {
                $nu_cpf_cnpj = $certificado->pkiBrazil->cnpj;
            } else {
                LogDB::insere(
                    1,
                    1,
                    'Erro no acesso com o token: '.$request->token,
                    'Acesso certificado',
                    'N',
                    request()->ip()
                );
                return $this->sendFailedLoginResponse($request,'auth.failed');
            }

            $pessoa = new pessoa();
            $pessoa = $pessoa->where('nu_cpf_cnpj', $nu_cpf_cnpj)
                             ->first();

            if ($pessoa) {
                if ($pessoa->usuario) {
                    if ($pessoa->usuario->in_registro_ativo=='N') {
                        LogDB::insere(
                            $pessoa->usuario->id_usuario,
                            1,
                            'Tentativa de acesso do usuário: usuário desativado',
                            'Acesso certificado',
                            'N',
                            request()->ip()
                        );
                        return $this->sendFailedLoginResponse($request,'auth.disabled');
                    } elseif ($pessoa->usuario->in_confirmado=='N') {
                        LogDB::insere(
                            $pessoa->usuario->id_usuario,
                            1,
                            'Tentativa de acesso do usuário: usuário não confirmado',
                            'Acesso certificado',
                            'N',
                            request()->ip()
                        );
                        return $this->sendFailedLoginResponse($request,'auth.unconfirmed');
                    } elseif ($pessoa->usuario->in_aprovado=='N') {
                        LogDB::insere(
                            $pessoa->usuario->id_usuario,
                            1,
                            'Tentativa de acesso do usuário: usuário não aprovado',
                            'Acesso certificado',
                            'N',
                            request()->ip()
                        );
                        return $this->sendFailedLoginResponse($request,'auth.inanalysis');
                    }

                    if ($pessoa->usuario->usuario_pessoa) {
                        LogDB::insere(
                            $pessoa->usuario->id_usuario,
                            1,
                            'Usuário logado com sucesso',
                            'Acesso certificado',
                            'N',
                            request()->ip()
                        );

                        Auth::loginUsingId($pessoa->usuario->id_usuario);

                        Auth::User()->pessoa_ativa = $pessoa->usuario->usuario_pessoa[0]->pessoa;
                        Auth::User()->pessoa_ativa_in_usuario_master = $pessoa->usuario->usuario_pessoa[0]->in_usuario_master;
                        Auth::User()->certificado = $certificado;
                    } else {
                        LogDB::insere(
                            $pessoa->usuario->id_usuario,
                            1,
                            'Usuário logado com sucesso, entretanto não possui pessoa vinculada',
                            'Acesso certificado',
                            'N',
                            request()->ip()
                        );

                        return redirect()->route('logout');
                    }

                    return redirect()->intended('/');
                } else {
                    return $this->sendFailedLoginResponse($request,'auth.unknown');
                }
            } else {
                return $this->sendFailedLoginResponse($request,'auth.failed');
            }
        } else {
            return $this->sendFailedLoginResponse($request,'auth.failed');
        }
    }

    protected function sendFailedLoginResponse(Request $request, $trans)
    {
        throw ValidationException::withMessages([
            'certificateSelect' => [trans($trans)],
        ]);
    }
}
