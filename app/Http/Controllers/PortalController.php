<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Helper;
use Mail;
use Carbon\Carbon;
use Exception;
use stdClass;

use App\Http\Requests\CertificadoVIDaaSSalvar;

use App\Domain\Portal\Contracts\CertificadoVidaasServiceInterface;
use App\Domain\Portal\Contracts\CertificadoVidaasClienteServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;

use App\Mail\PortalCertificadoVIDaaSMail;

class PortalController extends Controller
{
    /**
     * @var CertificadoVidaasServiceInterface
     * @var CertificadoVidaasClienteServiceInterface
     * @var EstadoServiceInterface
     * @var ParteEmissaoCertificadoServiceInterface
     */
    protected $CertificadoVidaasServiceInterface;
    protected $CertificadoVidaasClienteServiceInterface;
    protected $EstadoServiceInterface;
    protected $ParteEmissaoCertificadoServiceInterface;

    public function __construct(CertificadoVidaasServiceInterface $CertificadoVidaasServiceInterface,
        CertificadoVidaasClienteServiceInterface $CertificadoVidaasClienteServiceInterface,
        EstadoServiceInterface $EstadoServiceInterface,
        ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoServiceInterface)
    {
        $this->CertificadoVidaasServiceInterface = $CertificadoVidaasServiceInterface;
        $this->CertificadoVidaasClienteServiceInterface = $CertificadoVidaasClienteServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->ParteEmissaoCertificadoServiceInterface = $ParteEmissaoCertificadoServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function certificado_vidaas(Request $request)
    {
        $estados = $this->EstadoServiceInterface->estados_disponiveis();

        if ($request->link) {
            $portal_certificado_vidaas_cliente = $this->CertificadoVidaasClienteServiceInterface->buscar_link($request->link);
        }

        $compact_args = [
            'portal_certificado_vidaas_cliente' => $portal_certificado_vidaas_cliente ?? NULL,
            'estados' => $estados
        ];

		return view('portal.certificado-vidaas', $compact_args);
	}

    /**
     * @param CertificadoVIDaaSSalvar $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function salvar_certificado_vidaas(CertificadoVIDaaSSalvar $request)
    {
        if ($request->link) {
            $portal_certificado_vidaas_cliente = $this->CertificadoVidaasClienteServiceInterface->buscar_link($request->link);
        }

        DB::beginTransaction();

        try {
            $args_nova_solicitacao = new stdClass();
            $args_nova_solicitacao->id_portal_certificado_vidaas_cliente = $portal_certificado_vidaas_cliente->id_portal_certificado_vidaas_cliente ?? NULL;
            $args_nova_solicitacao->nome = $request->nome;
            $args_nova_solicitacao->cpf = Helper::somente_numeros($request->cpf);
            $args_nova_solicitacao->email = $request->email;
            $args_nova_solicitacao->telefone = Helper::somente_numeros($request->telefone);
            $args_nova_solicitacao->data_nascimento = Carbon::createFromFormat('d/m/Y', $request->data_nascimento);
            $args_nova_solicitacao->cep = Helper::somente_numeros($request->cep);
            $args_nova_solicitacao->endereco = $request->endereco;
            $args_nova_solicitacao->numero = $request->numero;
            $args_nova_solicitacao->bairro = $request->bairro;
            $args_nova_solicitacao->id_cidade = $request->id_cidade;
            $args_nova_solicitacao->observacoes = $request->observacoes;
            $args_nova_solicitacao->in_delivery = $request->in_delivery ?? 'N';
            $args_nova_solicitacao->in_cnh = $request->in_cnh ?? 'N';

            // Salva o cadastro do cliente
            $nova_solicitacao = $this->CertificadoVidaasServiceInterface->inserir($args_nova_solicitacao);

            $args_nova_emissao_certificado = new stdClass();
            $args_nova_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
            $args_nova_emissao_certificado->no_parte = $request->nome;
            $args_nova_emissao_certificado->nu_cpf_cnpj = Helper::somente_numeros($request->cpf);
            $args_nova_emissao_certificado->nu_telefone_contato = Helper::somente_numeros($request->telefone);
            $args_nova_emissao_certificado->no_email_contato = $request->email;
            $args_nova_emissao_certificado->id_portal_certificado_vidaas = $nova_solicitacao->id_portal_certificado_vidaas;
            $args_nova_emissao_certificado->id_parte_emissao_certificado_tipo = config('constants.PARTE_EMISSAO_CERTIFICADO.TIPO.INTERNO');
            $args_nova_emissao_certificado->in_cnh = $request->in_cnh ?? 'N';
            $args_nova_emissao_certificado->dt_nascimento = Carbon::createFromFormat('d/m/Y', $request->data_nascimento);
            $args_nova_emissao_certificado->nu_cep = Helper::somente_numeros($request->cep);
            $args_nova_emissao_certificado->no_endereco = $request->endereco;
            $args_nova_emissao_certificado->nu_endereco = $request->numero;
            $args_nova_emissao_certificado->no_bairro = $request->bairro;
            $args_nova_emissao_certificado->id_cidade = $request->id_cidade;

            // Salva a emissão do certificado
            $this->ParteEmissaoCertificadoServiceInterface->inserir($args_nova_emissao_certificado);

            if ($portal_certificado_vidaas_cliente->de_emails_enviar ?? NULL) {
                $partes = explode(';', $portal_certificado_vidaas_cliente->de_emails_enviar);
            } elseif (config('app.portal_vidaas_emails')) {
                $partes = explode(';', config('app.portal_vidaas_emails'));
            }

            if (isset($partes)) {
                foreach ($partes as $parte) {
                    $email = explode(',', $parte);

                    Mail::to($email[0], $email[1] ?? NULL)
                        ->queue(new PortalCertificadoVIDaaSMail($nova_solicitacao));
                }
            }

            DB::commit();

            return redirect()->back()->with('status', 'sucesso');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'status' => 'erro',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():'')
            ])->withInput($request->all());
        }
    }
}
