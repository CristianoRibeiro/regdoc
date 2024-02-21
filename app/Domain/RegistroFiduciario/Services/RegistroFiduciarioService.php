<?php

namespace App\Domain\RegistroFiduciario\Services;

use DB;
use LogDB;
use stdClass;
use Crypt;
use Upload;
use Helper;
use Auth;
use PDAVH;
use Exception;
use App\Exceptions\RegdocException;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioServiceInterface;

use App\Jobs\RegistroSituacaoNotificacao;
use App\Traits\EmailRegistro;

class RegistroFiduciarioService implements RegistroFiduciarioServiceInterface
{
    use EmailRegistro;

    /**
     * @var RegistroFiduciarioRepositoryInterface
     * @var RegistroFiduciarioProcuradorServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var RegistroFiduciarioAssinaturaServiceInterface
     * @var ParteEmissaoCertificadoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var PedidoServiceInterface
     * @var PedidoUsuarioServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     * @var UsuarioServiceInterface
     * @var PessoaServiceInterface
     * @var RegistroTipoParteTipoPessoaServiceInterface
     * @var IntegracaoRegistroFiduciarioServiceInterface
     */
    protected $RegistroFiduciarioRepositoryInterface;
    protected $RegistroFiduciarioProcuradorServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroFiduciarioAssinaturaServiceInterface;
    protected $ParteEmissaoCertificadoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $PedidoServiceInterface;
    protected $PedidoUsuarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;
    protected $UsuarioServiceInterface;
    protected $PessoaServiceInterface;
    protected $RegistroTipoParteTipoPessoaServiceInterface;
    protected $IntegracaoRegistroFiduciarioServiceInterface;

    /**
     * RegistroFiduciarioService constructor.
     * @param RegistroFiduciarioRepositoryInterface $RegistroFiduciarioRepositoryInterface
     * @param RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface
     * @param ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param PedidoUsuarioServiceInterface $PedidoUsuarioServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     * @param UsuarioServiceInterface $UsuarioServiceInterface
     * @param PessoaServiceInterface $PessoaServiceInterface
     * @param RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface
     * @param IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface
     */
    public function __construct(RegistroFiduciarioRepositoryInterface $RegistroFiduciarioRepositoryInterface,
        RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface,
        RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
        RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface,
        ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoServiceInterface,
        HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
        PedidoServiceInterface $PedidoServiceInterface,
        PedidoUsuarioServiceInterface $PedidoUsuarioServiceInterface,
        ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,
        UsuarioServiceInterface $UsuarioServiceInterface,
        PessoaServiceInterface $PessoaServiceInterface,
        RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
        IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface)
    {
        $this->RegistroFiduciarioRepositoryInterface = $RegistroFiduciarioRepositoryInterface;
        $this->RegistroFiduciarioProcuradorServiceInterface = $RegistroFiduciarioProcuradorServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroFiduciarioAssinaturaServiceInterface = $RegistroFiduciarioAssinaturaServiceInterface;
        $this->ParteEmissaoCertificadoServiceInterface = $ParteEmissaoCertificadoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->PedidoUsuarioServiceInterface = $PedidoUsuarioServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
        $this->IntegracaoRegistroFiduciarioServiceInterface = $IntegracaoRegistroFiduciarioServiceInterface;
    }

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        return $this->RegistroFiduciarioRepositoryInterface->listar($filtros);
    }

    /**
     * @param int $id_registro_fiduciario
     * @return registro_fiduciario|null
     */
    public function buscar(int $id_registro_fiduciario) : ?registro_fiduciario
    {
        return $this->RegistroFiduciarioRepositoryInterface->buscar($id_registro_fiduciario);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario
    {
        return $this->RegistroFiduciarioRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario
     */
    public function inserir(stdClass $args): registro_fiduciario
    {
        return $this->RegistroFiduciarioRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @param stdClass $args
     * @return registro_fiduciario
     * @throws Exception
     */
    public function alterar(registro_fiduciario $registro_fiduciario, stdClass $args) : registro_fiduciario
    {
        return $this->RegistroFiduciarioRepositoryInterface->alterar($registro_fiduciario, $args);
    }

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @return void
     * @throws Exception
     */
    public function iniciar_proposta(registro_fiduciario $registro_fiduciario)
    {
        if ($registro_fiduciario->registro_fiduciario_parte->count()<=0)
            throw new Exception('As partes não foram inseridas na proposta');

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        // Alterar o pedido
        $args_pedido = new stdClass();
        $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA');

        $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

        /* Criar o usuário da parte e procurador:
        *      - Criar o usuário para cada pessoa que irá receber uma senha (assim controlamos quem está
        *        acessando em cada momento);
        *      - Vincular o usuário ao pedido;
        *      - Gerar uma senha aleatória, a senha precisa ser gerada nesse momento pois é necessário
        *        salvá-la e enviar por e-mail, além disso, é necessário enviar também o protocolo que
        *        é gerado após o novo pedido;
        *      - Adicionar a parte ou procurador na lista de emissão de certificados.
        */
        $partes_envia_email = [];
        $procuradores_envia_email = [];
        foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
            if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                    $args_pedido_usuario = new stdClass();
                    $args_pedido_usuario->id_pedido = $pedido->id_pedido;
                    $args_pedido_usuario->no_contato = $registro_fiduciario_procurador->no_procurador;
                    $args_pedido_usuario->no_email_contato = $registro_fiduciario_procurador->no_email_contato;
                    $args_pedido_usuario->nu_cpf_cnpj = Helper::somente_numeros($registro_fiduciario_procurador->nu_cpf_cnpj);
                    $args_pedido_usuario->senha = strtoupper(Str::random(6));

                    $novo_pedido_usuario = $this->PedidoUsuarioServiceInterface->inserir($args_pedido_usuario);

                    $args_alterar_procurador = new stdClass();
                    $args_alterar_procurador->id_pedido_usuario = $novo_pedido_usuario->id_pedido_usuario;

                    $this->RegistroFiduciarioProcuradorServiceInterface->alterar($registro_fiduciario_procurador, $args_alterar_procurador);

                    if ($registro_fiduciario_procurador->in_emitir_certificado !== 'N') {
                        $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($registro_fiduciario_procurador->nu_cpf_cnpj);
                        if (!$busca_parte_emissao_certificado) {
                            $args_parte_emissao_certificado = new stdClass();
                            $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                            $args_parte_emissao_certificado->no_parte = $registro_fiduciario_procurador->no_procurador;
                            $args_parte_emissao_certificado->nu_cpf_cnpj = $registro_fiduciario_procurador->nu_cpf_cnpj;
                            $args_parte_emissao_certificado->nu_telefone_contato = $registro_fiduciario_procurador->nu_telefone_contato;
                            $args_parte_emissao_certificado->no_email_contato = $registro_fiduciario_procurador->no_email_contato;
                            $args_parte_emissao_certificado->in_cnh = $registro_fiduciario_procurador->in_cnh ?? 'N';
                            $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                            if($registro_fiduciario_procurador->in_cnh != 'S') {
                                $args_parte_emissao_certificado->nu_cep = $registro_fiduciario_procurador->nu_cep;
                                $args_parte_emissao_certificado->no_endereco = $registro_fiduciario_procurador->no_endereco;
                                $args_parte_emissao_certificado->nu_endereco = $registro_fiduciario_procurador->nu_endereco;
                                $args_parte_emissao_certificado->no_bairro = $registro_fiduciario_procurador->no_bairro;
                                $args_parte_emissao_certificado->id_cidade = $registro_fiduciario_procurador->id_cidade;
                            }

                            $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);

                            $procuradores_envia_email[] = $registro_fiduciario_procurador;
                        }
                    }
                }
            } else {
                $args_pedido_usuario = new stdClass();
                $args_pedido_usuario->id_pedido = $pedido->id_pedido;
                $args_pedido_usuario->no_contato = $registro_fiduciario_parte->no_parte;
                $args_pedido_usuario->no_email_contato = $registro_fiduciario_parte->no_email_contato;
                $args_pedido_usuario->nu_cpf_cnpj = Helper::somente_numeros($registro_fiduciario_parte->nu_cpf_cnpj);
                $args_pedido_usuario->senha = strtoupper(Str::random(6));

                $novo_pedido_usuario = $this->PedidoUsuarioServiceInterface->inserir($args_pedido_usuario);

                $args_alterar_parte = new stdClass();
                $args_alterar_parte->id_pedido_usuario = $novo_pedido_usuario->id_pedido_usuario;

                $this->RegistroFiduciarioParteServiceInterface->alterar($registro_fiduciario_parte, $args_alterar_parte);

                if ($registro_fiduciario_parte->in_emitir_certificado !== 'N') {
                    $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($registro_fiduciario_parte->nu_cpf_cnpj);
                    if(!$busca_parte_emissao_certificado) {
                        $args_parte_emissao_certificado = new stdClass();
                        $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                        $args_parte_emissao_certificado->no_parte = $registro_fiduciario_parte->no_parte;
                        $args_parte_emissao_certificado->nu_cpf_cnpj = $registro_fiduciario_parte->nu_cpf_cnpj;
                        $args_parte_emissao_certificado->nu_telefone_contato = $registro_fiduciario_parte->nu_telefone_contato;
                        $args_parte_emissao_certificado->no_email_contato = $registro_fiduciario_parte->no_email_contato;
                        $args_parte_emissao_certificado->in_cnh = $registro_fiduciario_parte->in_cnh ?? 'N';
                        $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                        if($registro_fiduciario_parte->in_cnh != 'S') {
                            $args_parte_emissao_certificado->nu_cep = $registro_fiduciario_parte->nu_cep;
                            $args_parte_emissao_certificado->no_endereco = $registro_fiduciario_parte->no_endereco;
                            $args_parte_emissao_certificado->nu_endereco = $registro_fiduciario_parte->nu_endereco;
                            $args_parte_emissao_certificado->no_bairro = $registro_fiduciario_parte->no_bairro;
                            $args_parte_emissao_certificado->id_cidade = $registro_fiduciario_parte->id_cidade;
                        }

                        $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);

                        $partes_envia_email[] = $registro_fiduciario_parte;
                    }
                }
            }
        }

        // Insere o histórico do pedido
        $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A proposta do Registro foi iniciada com sucesso.');

        // Atualizar data de alteração
        $args_registro_fiduciario = new stdClass();
        $args_registro_fiduciario->dt_alteracao = Carbon::now();

        $this->RegistroFiduciarioRepositoryInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

        // Enviar e-mails para as partes
        foreach ($partes_envia_email as $registro_fiduciario_parte) {
            $args_email = [
                'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                'no_contato' => $registro_fiduciario_parte->no_parte,
                'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                'token' => $registro_fiduciario_parte->pedido_usuario->token,
            ];
            $this->enviar_email_iniciar_proposta_registro($registro_fiduciario, $args_email);
        }

        // Enviar e-mails para os procuradores
        foreach ($procuradores_envia_email as $registro_fiduciario_procurador) {
            $args_email = [
                'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                'no_contato' => $registro_fiduciario_procurador->no_procurador,
                'senha' => Crypt::decryptString($registro_fiduciario_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                'token' => $registro_fiduciario_procurador->pedido_usuario->token,
            ];
            $this->enviar_email_iniciar_proposta_registro($registro_fiduciario, $args_email);
        }

        $mensagem = "A proposta / pré-contrato foi inserida na plataforma para início do processo de emissão dos certificados digitais das partes e posteriormente para assinatura do contrato.";

        $mensagemBradesco = "O contrato foi recepcionado para início do processo de registro eletrônico.<br>Nesta etapa iniciaremos a emissão dos certificados digitais de todas as partes.<br>Após conclusão, o contrato será enviado para assinaturas.";
        $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
        $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

        // Enviar Notificação
        if(!empty($pedido->url_notificacao)) {
            RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
        }

        DB::commit();

        LogDB::insere(
            Auth::User()->id_usuario,
            7,
            'A proposta do Registro ' . $pedido->protocolo_pedido . ' foi iniciada com sucesso.',
            'Registro',
            'N',
            request()->ip()
        );
    }

    /**
     * @param stdClass $args
     * @param registro_fiduciario $registro_fiduciario
     * @param bool $in_api
     * @return void
     * @throws Exception
     */
    public function transformar_contrato(stdClass $args, registro_fiduciario $registro_fiduciario, bool $in_api = false)
    {
        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        switch ($pedido->id_produto) {
            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                // Buscar pessoa do cartório de imóveis
                $pessoa_serventia = $this->PessoaServiceInterface->buscar($args->id_pessoa_cartorio_ri);
                if (!$pessoa_serventia)
                    throw new Exception('Não foi possível encontrar o cartório de registro de imóveis informado.');

                $id_serventia_ri = $pessoa_serventia->serventia->id_serventia;
                break;
            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                // Buscar pessoa do cartório de notas
                $pessoa_serventia = $this->PessoaServiceInterface->buscar($args->id_pessoa_cartorio_rtd);
                if (!$pessoa_serventia)
                    throw new Exception('Não foi possível encontrar o cartório de registro de imóveis informado.');

                $id_serventia_notas = $pessoa_serventia->serventia->id_serventia;
                break;
        }

        if ($args->in_atualizar_integracao=='S') {
            $args_integracao_registro_fiduciario = new stdClass();
            $args_integracao_registro_fiduciario->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
            $args_integracao_registro_fiduciario->id_grupo_serventia = $pessoa_serventia->serventia->id_grupo_serventia ?? NULL;
            $args_integracao_registro_fiduciario->id_serventia = $pessoa_serventia->serventia->id_serventia ?? NULL;
            $args_integracao_registro_fiduciario->id_pessoa = $pedido->id_pessoa_origem;

            $id_integracao = $this->IntegracaoRegistroFiduciarioServiceInterface->definir_integracao($args_integracao_registro_fiduciario);
        }

        // Alterar o registro
        $args_registro_fiduciario = new stdClass();
        $args_registro_fiduciario->nu_contrato = $args->nu_contrato;
        $args_registro_fiduciario->id_serventia_ri = $id_serventia_ri ?? NULL;
        $args_registro_fiduciario->id_serventia_nota = $id_serventia_notas ?? NULL;
        $args_registro_fiduciario->id_integracao = $id_integracao ?? NULL;
        $args_registro_fiduciario->in_contrato_assinado = $args->in_contrato_assinado ?? 'N';
        $args_registro_fiduciario->in_instrumento_assinado = $args->in_instrumento_assinado ?? 'N';
        $args_registro_fiduciario->dt_cadastro_contrato = Carbon::now();
        $args_registro_fiduciario->dt_alteracao = Carbon::now();

        $this->RegistroFiduciarioRepositoryInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

        // Alterar o pedido
        $args_pedido = new stdClass();
        $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO');

        $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

        if ($in_api) {
            $arquivos = $args->arquivos_api;
            $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario;

            if (count($arquivos)>0) {
                foreach ($arquivos as $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto)
                        $registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                }
            }
        } else {
            // Insere os arquivos
            $arquivos = $args->sessao_arquivos;
            $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario;

            if (count($arquivos)>0) {
                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto)
                        $registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                }
            }

            // Insere as partes do registro fiduciário
            $partes = $args->sessao_partes ?? [];

            $cpf_cnpjs = [];
            foreach ($partes as $parte) {
                $cpf_cnpjs[] = $nu_cpf_cnpj = Helper::somente_numeros($parte['nu_cpf_cnpj']);
                $telefone_parte = Helper::array_telefone($parte['nu_telefone_contato']);

                if(isset($parte['id_registro_fiduciario_parte'])) {
                    $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($parte['id_registro_fiduciario_parte']);

                    // Argumentos do registro_fiduciario_parte
                    $args_registro_fiduciario_parte = new stdClass();
                    $args_registro_fiduciario_parte->no_parte = $parte['no_parte'];
                    $args_registro_fiduciario_parte->tp_pessoa = $parte['tp_pessoa'];
                    $args_registro_fiduciario_parte->nu_cpf_cnpj = $nu_cpf_cnpj;
                    $args_registro_fiduciario_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                    $args_registro_fiduciario_parte->no_email_contato = $parte['no_email_contato'];
                    $args_registro_fiduciario_parte->id_procuracao = $parte['id_procuracao'] ?? NULL;

                    // Altera o registro_fiduciario_parte
                    $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->alterar($registro_fiduciario_parte, $args_registro_fiduciario_parte);

                    if ($registro_fiduciario_parte->pedido_usuario) {
                        $usuario = $registro_fiduciario_parte->pedido_usuario->usuario;
                        $pessoa = $usuario->pessoa;
                        
                        // Altera o usuario
                        $args_usuario = new stdClass();
                        $args_usuario->no_usuario = $parte['no_parte'];
                        $args_usuario->email_usuario = $parte['no_email_contato'];
                        $args_usuario->login = $parte['no_email_contato'];

                        $this->UsuarioServiceInterface->alterar($usuario, $args_usuario);

                        // Altera a pessoa
                        $args_pessoa = new stdClass();
                        $args_pessoa->no_pessoa = $parte['no_parte'];
                        $args_pessoa->no_email_pessoa = $parte['no_email_contato'];
                        $args_pessoa->nu_cpf_cnpj = $nu_cpf_cnpj;

                        $this->PessoaServiceInterface->alterar($pessoa, $args_pessoa);
                    }
                } else {
                    // Argumentos do registro_fiduciario_parte
                    $args_registro_fiduciario_parte = new stdClass();
                    $args_registro_fiduciario_parte->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                    $args_registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = $parte['id_tipo_parte_registro_fiduciario'];
                    $args_registro_fiduciario_parte->no_parte = $parte['no_parte'];
                    $args_registro_fiduciario_parte->tp_pessoa = $parte['tp_pessoa'];
                    $args_registro_fiduciario_parte->nu_cpf_cnpj = $nu_cpf_cnpj;
                    $args_registro_fiduciario_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                    $args_registro_fiduciario_parte->no_email_contato = $parte['no_email_contato'];
                    $args_registro_fiduciario_parte->id_procuracao = $parte['id_procuracao'] ?? NULL;
                    $args_registro_fiduciario_parte->id_registro_tipo_parte_tipo_pessoa = $parte['id_registro_tipo_parte_tipo_pessoa'];

                    // Insere o registro_fiduciario_parte
                    $this->RegistroFiduciarioParteServiceInterface->inserir($args_registro_fiduciario_parte);
                }
            }

            $args_tipos_partes = new stdClass();
            $args_tipos_partes->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
            $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

            $filtros_tipos_partes = new stdClass();
            $filtros_tipos_partes->in_simples = 'S';

            $lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

            $tipos_partes = [];
            foreach ($lista_tipos_partes as $tipo_parte) {
                $tipos_partes[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
            }

            $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_partes()
                ->whereIn('id_tipo_parte_registro_fiduciario', $tipos_partes)
                ->get();

            if ($registro_fiduciario_partes) {
                foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                    if (!in_array($registro_fiduciario_parte->nu_cpf_cnpj, $cpf_cnpjs)) {
                        $pedido_usuario = $registro_fiduciario_parte->pedido_usuario;
                        $pedido_usuario_senha = $pedido_usuario->pedido_usuario_senha;

                        $registro_fiduciario_parte->delete();
                        $pedido_usuario_senha->delete();
                        $pedido_usuario->delete();
                    }
                }
            }
        }

        // Insere o histórico do pedido
        $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A proposta do Registro foi transformada em contrato com sucesso.');

        // Realiza o commit no banco de dados
        DB::commit();

        LogDB::insere(
            Auth::User()->id_usuario,
            7,
            'A proposta de Registro ' . $pedido->protocolo_pedido . ' transformada em contrato com sucesso.',
            'Registro',
            'N',
            request()->ip()
        );
    }

    public function verifica_todas_partes_emitiram(registro_fiduciario $registro_fiduciario): bool
    {
        $situacoes_permitidas = [
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO'),
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO')
        ];
        
        foreach ($registro_fiduciario->registro_fiduciario_parte as $parte)
        {
            foreach ($parte->registro_fiduciario_procurador as $procurador)
            {
                $parte_emissao = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($procurador->nu_cpf_cnpj);
                if(!$parte_emissao || !in_array($parte_emissao->id_parte_emissao_certificado_situacao, $situacoes_permitidas)) return false;
            }

            if($parte->registro_fiduciario_procurador->count() > 0) continue;

            $parte_emissao = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($parte->nu_cpf_cnpj);
            if(!$parte_emissao || !in_array($parte_emissao->id_parte_emissao_certificado_situacao, $situacoes_permitidas)) return false;
        }

        return true;
    }

    /**
     * @param stdClass $args
     * @param registro_fiduciario $registro_fiduciario
     * @param bool $in_api
     * @return void
     * @throws Exception
     */
    public function iniciar_documentacao(registro_fiduciario $registro_fiduciario)
    {
        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        // Alterar o pedido
        $args_pedido = new stdClass();
        $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_DOCUMENTACAO');

        $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

        /* Criar o usuário da parte e procurador:
        *      - Criar o usuário para cada pessoa que irá receber uma senha (assim controlamos quem está
        *        acessando em cada momento);
        *      - Vincular o usuário ao pedido;
        *      - Gerar uma senha aleatória, a senha precisa ser gerada nesse momento pois é necessário
        *        salvá-la e enviar por e-mail, além disso, é necessário enviar também o protocolo que
        *        é gerado após o novo pedido;
        */
        foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
            if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                    $args_pedido_usuario = new stdClass();
                    $args_pedido_usuario->id_pedido = $pedido->id_pedido;
                    $args_pedido_usuario->no_contato = $registro_fiduciario_procurador->no_procurador;
                    $args_pedido_usuario->no_email_contato = $registro_fiduciario_procurador->no_email_contato;
                    $args_pedido_usuario->nu_cpf_cnpj = Helper::somente_numeros($registro_fiduciario_procurador->nu_cpf_cnpj);
                    $args_pedido_usuario->senha = strtoupper(Str::random(6));

                    $novo_pedido_usuario = $this->PedidoUsuarioServiceInterface->inserir($args_pedido_usuario);

                    $args_alterar_procurador = new stdClass();
                    $args_alterar_procurador->id_pedido_usuario = $novo_pedido_usuario->id_pedido_usuario;

                    $this->RegistroFiduciarioProcuradorServiceInterface->alterar($registro_fiduciario_procurador, $args_alterar_procurador);

                    if ($registro_fiduciario_procurador->in_emitir_certificado !== 'N') {
                        $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($registro_fiduciario_procurador->nu_cpf_cnpj);
                        if(!$busca_parte_emissao_certificado) {
                            $args_parte_emissao_certificado = new stdClass();
                            $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                            $args_parte_emissao_certificado->no_parte = $registro_fiduciario_procurador->no_procurador;
                            $args_parte_emissao_certificado->nu_cpf_cnpj = $registro_fiduciario_procurador->nu_cpf_cnpj;
                            $args_parte_emissao_certificado->nu_telefone_contato = $registro_fiduciario_procurador->nu_telefone_contato;
                            $args_parte_emissao_certificado->no_email_contato = $registro_fiduciario_procurador->no_email_contato;
                            $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                            $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);
                        }
                    }
                }
            } else {
                $args_pedido_usuario = new stdClass();
                $args_pedido_usuario->id_pedido = $pedido->id_pedido;
                $args_pedido_usuario->no_contato = $registro_fiduciario_parte->no_parte;
                $args_pedido_usuario->no_email_contato = $registro_fiduciario_parte->no_email_contato;
                $args_pedido_usuario->nu_cpf_cnpj = Helper::somente_numeros($registro_fiduciario_parte->nu_cpf_cnpj);
                $args_pedido_usuario->senha = strtoupper(Str::random(6));

                $novo_pedido_usuario = $this->PedidoUsuarioServiceInterface->inserir($args_pedido_usuario);

                $args_alterar_parte = new stdClass();
                $args_alterar_parte->id_pedido_usuario = $novo_pedido_usuario->id_pedido_usuario;

                $this->RegistroFiduciarioParteServiceInterface->alterar($registro_fiduciario_parte, $args_alterar_parte);

                if ($registro_fiduciario_parte->in_emitir_certificado !== 'N') {
                    $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($registro_fiduciario_parte->nu_cpf_cnpj);
                    if(!$busca_parte_emissao_certificado) {
                        $args_parte_emissao_certificado = new stdClass();
                        $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                        $args_parte_emissao_certificado->no_parte = $registro_fiduciario_parte->no_parte;
                        $args_parte_emissao_certificado->nu_cpf_cnpj = $registro_fiduciario_parte->nu_cpf_cnpj;
                        $args_parte_emissao_certificado->nu_telefone_contato = $registro_fiduciario_parte->nu_telefone_contato;
                        $args_parte_emissao_certificado->no_email_contato = $registro_fiduciario_parte->no_email_contato;
                        $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                        $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);
                    }
                }
            }
        }

        /* Criar as assinaturas:
        *      - Caso o registro for do tipo Garantias com Cessão Fiduciária,
        *        o sistema irá criar 2 tipos de assinaturas.
        */
        if($registro_fiduciario->id_registro_fiduciario_tipo == config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CESSAO')) {
            // Tipos de partes que só assinam o contrato
            if ($registro_fiduciario->in_contrato_assinado=='N') {
                $partes_contrato = [
                    config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_EMITENTE_CEDENTE'),
                    config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ANUENTE'),
                    config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TESTEMUNHA'),
                    config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR')
                ];
                $this->RegistroFiduciarioAssinaturaServiceInterface->inserir_assinatura(
                    $registro_fiduciario,
                    config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'),
                    config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.CONTRATO'),
                    $partes_contrato
                );
            }

            // Tipos de partes que só assinam o instrumento particular
            if ($registro_fiduciario->in_instrumento_assinado=='N') {
                $arquivos_instrumento = $registro_fiduciario->arquivos_grupo()
                    ->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'))
                    ->get();

                if (count($arquivos_instrumento)>0) {
                    $partes_instrumento_particular = [
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_EMITENTE_CEDENTE'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ANUENTE'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TESTEMUNHA'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR_FIDUCIARIO')
                    ];
                    $this->RegistroFiduciarioAssinaturaServiceInterface->inserir_assinatura(
                        $registro_fiduciario,
                        config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'),
                        config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.INSTRUMENTO_PARTICULAR'),
                        $partes_instrumento_particular
                    );
                }
            }
        } else {
            if ($registro_fiduciario->in_contrato_assinado=='N') {
                $this->RegistroFiduciarioAssinaturaServiceInterface->inserir_assinatura(
                    $registro_fiduciario,
                    config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'),
                    config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.CONTRATO')
                );
            }
        }


        // Insere o histórico do pedido
        $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A documentação do Registro foi iniciada com sucesso.');

        // Atualizar data de alteração
        $args_registro_fiduciario = new stdClass();
        $args_registro_fiduciario->dt_alteracao = Carbon::now();

        $this->RegistroFiduciarioRepositoryInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

        if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {
            // E-mails das partes que irão assinar o documento
            foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
                // Se a parte for apenas um observador, o sistema pula a parte com o "continue"
                if ($registro_fiduciario_parte->registro_tipo_parte_tipo_pessoa->in_observador=='S') {
                    continue;
                }

                if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                    foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                        if(count($registro_fiduciario_procurador->registro_fiduciario_parte_assinatura_na_ordem)>0) {
                            $args_email = [
                                'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                                'no_contato' => $registro_fiduciario_procurador->no_procurador,
                                'senha' => Crypt::decryptString($registro_fiduciario_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                'token' => $registro_fiduciario_procurador->pedido_usuario->token,
                            ];
                            $this->enviar_email_iniciar_documentacao($registro_fiduciario, $args_email);
                        }
                    }
                } else {
                    if(count($registro_fiduciario_parte->registro_fiduciario_parte_assinatura_na_ordem)>0) {
                        $args_email = [
                            'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                            'no_contato' => $registro_fiduciario_parte->no_parte,
                            'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                            'token' => $registro_fiduciario_parte->pedido_usuario->token,
                        ]; 
                        
                        //Se o pedido pertencer ao bradesco agro   
                        if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){
                            $this->enviar_email_documentacao_registro_processo_assinaturas($registro_fiduciario, $args_email);
                        }else{
                            $this->enviar_email_iniciar_documentacao($registro_fiduciario, $args_email);
                        }
                       
                    }
                }
            }

            /* E-mails para as partes que são somente observadores (sem assinatura).
             * Por regra, o sistema não deverá ter uma parte do tipo observador com procuradores.
             */
            foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
                // Se a parte não for um observador, o sistema pula a parte com o "continue"
                if ($registro_fiduciario_parte->registro_tipo_parte_tipo_pessoa->in_observador=='N') {
                    continue;
                }
                
                $args_email = [
                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                    'no_contato' => $registro_fiduciario_parte->no_parte,
                    'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                    'token' => $registro_fiduciario_parte->pedido_usuario->token,
                ];
                $this->enviar_email_iniciar_documentacao_observador($registro_fiduciario, $args_email);
            }
        }

        $mensagem = "A fase de documentação do contrato foi iniciada na plataforma para assinatura do contrato, envio de documentações e etc.";

        $mensagemBradesco = "Concluímos a emissão de todos os certificados digitais e o contrato já pode ser assinado.<br>O <i>link</i> para realizar a assinatura foi disponibilizado por e-mail.<br>Após conclusão dessa etapa, disponibilizaremos a guia do ITBI para pagamento.";
        $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
        $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

        // Enviar Notificação
        if(!empty($pedido->url_notificacao)) {
            RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
        }

        DB::commit();

        LogDB::insere(
            Auth::User()->id_usuario,
            7,
            'A documentação do Registro ' . $pedido->protocolo_pedido . ' foi iniciada com sucesso.',
            'Registro',
            'N',
            request()->ip()
        );
    }

    /**
     * @param registro_fiduciario $registro_fiduciario, string $de_motivo_cancelamento
     * @return void
     * @throws Exception
     */
    public function cancelar(registro_fiduciario $registro_fiduciario, string $de_motivo_cancelamento = '', string $finalizar = 'N', string $de_termo_admissao = NULL, string $in_finalizar_cartorio = 'N')
    {
        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        // Alterar o pedido
        $args_pedido = new stdClass();
        $args_pedido->de_motivo_cancelamento = nl2br(strip_tags($de_motivo_cancelamento));
        $args_pedido->de_termo_admissao = nl2br(strip_tags($de_termo_admissao)) ?? NULL;
        $args_pedido->in_finalizar_cartorio = $in_finalizar_cartorio; 
        $args_pedido->dt_cancelamento = Carbon::now();
        if ($finalizar=='S') {
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_FINALIZADO');
        } else {
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_CANCELADO');
        }

        $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

        if (count($registro_fiduciario->registro_fiduciario_assinaturas)>0) {
            foreach ($registro_fiduciario->registro_fiduciario_assinaturas as $registro_fiduciario_assinatura) {
                PDAVH::cancel_signature_process($registro_fiduciario_assinatura->co_process_uuid);
            }
        }

        // Insere o histórico do pedido
        if ($finalizar=='S') {
            if($in_finalizar_cartorio == 'S'){
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, config('constants.OBSERVACAO_HISTORICO_SITUACAO.FINALIZADO_CANCELADO_CARTORIO'));
            }else{
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, config('constants.OBSERVACAO_HISTORICO_SITUACAO.FINALIZADO_CANCELADO'));
            }
        } else {
            if($in_finalizar_cartorio == 'S'){
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, config('constants.OBSERVACAO_HISTORICO_SITUACAO.CANCELADO_CARTORIO'));
            }else{
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, config('constants.OBSERVACAO_HISTORICO_SITUACAO.CANCELADO'));
            }
        }

        // Enviar Notificação
        if(!empty($pedido->url_notificacao)) {
            RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
        }

        DB::commit();

        LogDB::insere(
            Auth::User()->id_usuario,
            5,
            'O Registro ' . $pedido->protocolo_pedido . ' foi cancelado' . ($finalizar=='S' ? ' e finalizado' : '') . ' com sucesso.',
            'Registro',
            'N',
            request()->ip()
        );
    }
    
    /**
     * @param registro_fiduciario $registro_fiduciario
     * @return void
     * @throws Exception
     */
    public function iniciar_emissoes(registro_fiduciario $registro_fiduciario)
    {
        if ($registro_fiduciario->registro_fiduciario_parte->count()<=0)
            throw new RegdocException('As partes não foram inseridas na registro');

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        /* Adicionar a parte ou procurador na lista de emissão de certificados.
        */
        $partes_envia_email = [];
        $procuradores_envia_email = [];
        foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
            if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                    if ($registro_fiduciario_procurador->in_emitir_certificado !== 'N') {
                        $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($registro_fiduciario_procurador->nu_cpf_cnpj);
                        if (!$busca_parte_emissao_certificado) {
                            $args_parte_emissao_certificado = new stdClass();
                            $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                            $args_parte_emissao_certificado->no_parte = $registro_fiduciario_procurador->no_procurador;
                            $args_parte_emissao_certificado->nu_cpf_cnpj = $registro_fiduciario_procurador->nu_cpf_cnpj;
                            $args_parte_emissao_certificado->nu_telefone_contato = $registro_fiduciario_procurador->nu_telefone_contato;
                            $args_parte_emissao_certificado->no_email_contato = $registro_fiduciario_procurador->no_email_contato;
                            $args_parte_emissao_certificado->in_cnh = $registro_fiduciario_procurador->in_cnh ?? 'N';
                            $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                            if($registro_fiduciario_procurador->in_cnh != 'S') {
                                $args_parte_emissao_certificado->nu_cep = $registro_fiduciario_procurador->nu_cep;
                                $args_parte_emissao_certificado->no_endereco = $registro_fiduciario_procurador->no_endereco;
                                $args_parte_emissao_certificado->nu_endereco = $registro_fiduciario_procurador->nu_endereco;
                                $args_parte_emissao_certificado->no_bairro = $registro_fiduciario_procurador->no_bairro;
                                $args_parte_emissao_certificado->id_cidade = $registro_fiduciario_procurador->id_cidade;
                            }

                            $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);

                            $procuradores_envia_email[] = $registro_fiduciario_procurador;
                        }
                    }
                }
            } else {
                if ($registro_fiduciario_parte->in_emitir_certificado !== 'N') {
                    $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($registro_fiduciario_parte->nu_cpf_cnpj);
                    if(!$busca_parte_emissao_certificado) {
                        $args_parte_emissao_certificado = new stdClass();
                        $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                        $args_parte_emissao_certificado->no_parte = $registro_fiduciario_parte->no_parte;
                        $args_parte_emissao_certificado->nu_cpf_cnpj = $registro_fiduciario_parte->nu_cpf_cnpj;
                        $args_parte_emissao_certificado->nu_telefone_contato = $registro_fiduciario_parte->nu_telefone_contato;
                        $args_parte_emissao_certificado->no_email_contato = $registro_fiduciario_parte->no_email_contato;
                        $args_parte_emissao_certificado->in_cnh = $registro_fiduciario_parte->in_cnh ?? 'N';
                        $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                        if($registro_fiduciario_parte->in_cnh != 'S') {
                            $args_parte_emissao_certificado->nu_cep = $registro_fiduciario_parte->nu_cep;
                            $args_parte_emissao_certificado->no_endereco = $registro_fiduciario_parte->no_endereco;
                            $args_parte_emissao_certificado->nu_endereco = $registro_fiduciario_parte->nu_endereco;
                            $args_parte_emissao_certificado->no_bairro = $registro_fiduciario_parte->no_bairro;
                            $args_parte_emissao_certificado->id_cidade = $registro_fiduciario_parte->id_cidade;
                        }

                        $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);

                        $partes_envia_email[] = $registro_fiduciario_parte;
                    }
                }
            }
        }

        // Insere o histórico do pedido
        $this->HistoricoPedidoServiceInterface->inserir_historico($registro_fiduciario->registro_fiduciario_pedido->pedido, 'Inicio emissões de certificado com sucesso.');

        // Atualizar data de alteração
        $args_registro_fiduciario = new stdClass();
        $args_registro_fiduciario->dt_alteracao = Carbon::now();

        $this->RegistroFiduciarioRepositoryInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

        // Enviar e-mails para as partes para Bradesco agro
        if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){

            foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
                $args_email = [
                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                    'no_contato' => $registro_fiduciario_parte->no_parte,
                    'senha' => Crypt::decryptString($registro_fiduciario_parte->registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_usuario->pedido_usuario_senha->senha_crypt),
                    'token' => $registro_fiduciario_parte->registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_usuario->token,
                    'nu_ticket_vidaas' => $registro_fiduciario_parte->parte_emissao_certificado->nu_ticket_vidaas ?? NULL,
                    'link_videoconferencia' => NULL
                ];
                $this->enviar_email_iniciar_emissao_certificado($registro_fiduciario, $args_email);
            }
        }else{

            $mensagem = "As emissões dos certificados das partes foram iniciadas.";
            $mensagemBradesco = "O contrato foi recepcionado para início do processo de registro eletrônico.<br>Nesta etapa iniciaremos a emissão dos certificados digitais de todas as partes.<br>Após conclusão, o contrato será enviado para assinaturas.";
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

        }
        
        //
        // // Enviar e-mails para os procuradores
        // foreach ($procuradores_envia_email as $registro_fiduciario_procurador) {
        //     $args_email = [
        //         'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
        //         'no_contato' => $registro_fiduciario_procurador->no_procurador,
        //         'senha' => Crypt::decryptString($registro_fiduciario_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
        //         'token' => $registro_fiduciario_procurador->pedido_usuario->token,
        //     ];
        //     $this->enviar_email_iniciar_proposta_registro($registro_fiduciario, $args_email);
        // }*/

        DB::commit();

        LogDB::insere(
            Auth::User()->id_usuario,
            7,
            'As emissões dos certificados foram iniciadas no Registro ' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido . ' foi iniciada com sucesso.',
            'Registro',
            'N',
            request()->ip()
        );

        return count($partes_envia_email) + count($procuradores_envia_email);
    }
}
