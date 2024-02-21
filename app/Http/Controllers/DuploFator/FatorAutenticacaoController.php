<?php

namespace App\Http\Controllers\DuploFator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Exception;
use DB;
use Auth;
use LogDB;
use URL;
use Mail;
use Session;
use Carbon\Carbon;
use App\Helpers\Helper;

use App\Models\usuario_2fa_email;

use App\Http\Requests\Autenticacao;

use App\Mail\Usuarios2FAMail;

class FatorAutenticacaoController extends Controller
{
    public function autenticacao_2fa(Request $request)
    {
        return view('app.usuario.seguranca.geral-2fa-email');
    }

    public function salvar_autenticacao_2fa(Request $request)
    {
        $codigo_seguranca = Helper::gera_codigo_randomico(8);

        $this->salvar_codigo_seguranca(Auth::User(), Session::getId(), $codigo_seguranca);

        $this->enviar_email(Auth::User(), $codigo_seguranca);

        return redirect('/app/usuario/autenticacao-2fa');
    }

    public function reenviar_autenticacao_2fa()
    {
        DB::beginTransaction();

        try {
            $usuario_2fa_email = new usuario_2fa_email();
            $usuario_2fa_email = $usuario_2fa_email->where('id_usuario', Auth::User()->id_usuario)
                ->where('session_id', Session::getId())
                ->where('in_validado', '=', 'N')
                ->orderBy('dt_cadastro', 'desc')
                ->first();

            $codigo_seguranca = Helper::gera_codigo_randomico(8);

            $this->salvar_codigo_seguranca(Auth::User(), Session::getId(), $codigo_seguranca, $usuario_2fa_email);

            $this->enviar_email(Auth::User(), $codigo_seguranca);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Reenvio do código de segurança.',
                'Duplo Fator de Autenticação',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Seu código foi reenviado com sucesso.'
            ];
            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function validar_autenticacao_2fa(Autenticacao $request)
    {
        DB::beginTransaction();

        try {
            $validado = usuario_2fa_email::where('id_usuario', Auth::user()->id_usuario)
                ->where('codigo_seguranca', $request->codigo_seguranca)
                ->where('session_id', '=', Session::getId())
                ->where('in_validado', '=', 'N')
                ->orderBy('dt_cadastro', 'desc')
                ->first();

            if (!$validado)
                return response()->json([
                    'status' => 'alerta',
                    'message' => 'O código de segurança informado é inválido',
                ], 400);

            $validado->in_validado = 'S';
            $validado->dt_validade = Carbon::now()->addDays(6);
            if (!$validado->save())
                throw new Exception('Erro ao salvar o código validado.');

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Validou o código de segurança.',
                'Duplo fator de autenticação',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Código de segurança validado com sucesso.',
                'redirect_url' => URL::to('/app')
            ];
            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    private function salvar_codigo_seguranca($usuario, $session_id, $codigo_seguranca, $usuario_2fa_email = null)
    {
        if (!$usuario_2fa_email) {
            $usuario_2fa_email = new usuario_2fa_email();
            $usuario_2fa_email->id_usuario = $usuario->id_usuario;
            $usuario_2fa_email->in_validado = 'N';
            $usuario_2fa_email->session_id = $session_id;
            $usuario_2fa_email->nu_endereco_ip = request()->ip();
        }

        $usuario_2fa_email->codigo_seguranca = $codigo_seguranca;
        if (!$usuario_2fa_email->save())
            throw new Exception('Erro ao salvar o codigo segurança.');

        return $usuario_2fa_email;
    }

    private function enviar_email($usuario, $codigo_seguranca)
    {
        Mail::to($usuario->email_usuario, $usuario->no_usuario)->queue(new Usuarios2FAMail($usuario, $codigo_seguranca));
    }
}
