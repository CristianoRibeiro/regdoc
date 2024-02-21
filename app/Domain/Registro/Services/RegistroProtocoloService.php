<?php

namespace App\Domain\Registro\Services;

use App\Domain\Pedido\Models\pedido_usuario;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\Registro\Contracts\RegistroProtocoloServiceInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte;
use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura;
use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\Pessoa\Models\pessoa;
use App\Helpers\Helper;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

use stdClass;

class RegistroProtocoloService implements RegistroProtocoloServiceInterface
{
  public function __construct(private RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
															private Session $session,
															private ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
  {}

  public function render_pedido(pedido_usuario $pedido_usuario)
  {
		Auth::login($pedido_usuario->usuario);
		Auth::user()->pedido_ativo = $pedido_usuario->pedido->id_pedido;
		Auth::user()->pessoa_ativa = $pedido_usuario->usuario->usuario_pessoa[0]->pessoa;
		$this->session->put('pedido_usuario_id', $pedido_usuario->id_pedido_usuario);
		$this->load_config($pedido_usuario->usuario->usuario_pessoa[0]->pessoa);
		
    switch ($pedido_usuario->pedido->id_produto) {
      case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
      case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
        return $this->index_registro($pedido_usuario);
        break;
      case config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO'):
        return $this->index_documentos($pedido_usuario);
        break;
      default:
        throw new \Exception('Tipo de produto não suportado');
        break;
    }
  }

  private function index_registro(pedido_usuario $pedido_usuario)
	{
		$registro_fiduciario = $pedido_usuario->pedido->registro_fiduciario_pedido->registro_fiduciario;

		// Variáveis da aba inicial
		if ($pedido_usuario->registro_fiduciario_parte) {
			$registro_fiduciario_parte = $pedido_usuario->registro_fiduciario_parte;
		} elseif ($pedido_usuario->registro_fiduciario_procurador) {
			$registro_fiduciario_procurador = $pedido_usuario->registro_fiduciario_procurador;
			$registro_fiduciario_parte = $registro_fiduciario_procurador->registro_fiduciario_parte;
		}
		$total_arquivos_docto_partes_enviados = $registro_fiduciario_parte->arquivos_grupo->count();
		$total_pagamentos_pendentes = $registro_fiduciario->registro_fiduciario_pagamentos
			->where('id_registro_fiduciario_pagamento_situacao', config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE'))
			->count();

		// Variáveis da aba Arquivos
		$total_arquivos_resultado = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_RESULTADO'))->count();
		$total_arquivos_contrato = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))->count();
		$total_arquivos_instrumento_particular = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'))->count();
		$total_arquivos_docto_partes = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'))->count();
		$total_arquivos_imovel = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'))->count();
		$total_arquivos_outros = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_OUTROS'))->count();
		$total_arquivos_formulario = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_FORMULARIO'))->count();

		// Partes que podem inserir documentos
		$args_tipos_partes = new stdClass();
		$args_tipos_partes->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
		$args_tipos_partes->id_pessoa = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem;
		
		$filtros_tipos_partes = new stdClass();
		$filtros_tipos_partes->in_inserir_documentos = 'S';

		$lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

		$tipos_partes_documentos = [];
		foreach ($lista_tipos_partes as $tipo_parte) {
			$tipos_partes_documentos[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
		}

		$partes_exigencia_documentos = $registro_fiduciario->registro_fiduciario_parte
			->whereIn('id_tipo_parte_registro_fiduciario', $tipos_partes_documentos);

		//Montar todos os arquivos a serem assinados desse usuarios
		$cpf_cnpj = Helper::somente_numeros($pedido_usuario->usuario->usuario_pessoa[0]->pessoa->nu_cpf_cnpj);

		$partes_assinaturas = registro_fiduciario_parte_assinatura::select('registro_fiduciario_parte_assinatura.*')
			->join('registro_fiduciario_parte', 'registro_fiduciario_parte.id_registro_fiduciario_parte', '=', 'registro_fiduciario_parte_assinatura.id_registro_fiduciario_parte')
			->where('registro_fiduciario_parte.nu_cpf_cnpj', '=', $cpf_cnpj)
			->whereIn('registro_fiduciario_parte.id_tipo_parte_registro_fiduciario', [15, 16])
			->join('registro_fiduciario_pedido', 'registro_fiduciario_pedido.id_registro_fiduciario', '=', 'registro_fiduciario_parte.id_registro_fiduciario')
			->join('pedido', 'pedido.id_pedido', '=', 'registro_fiduciario_pedido.id_pedido')
			->whereNotIn('pedido.id_situacao_pedido_grupo_produto', [config('constants.SITUACAO.11.ID_FINALIZADO'), config('constants.SITUACAO.11.ID_CANCELADO'), config('constants.SITUACAO.11.ID_DEVOLVIDO'), config('constants.SITUACAO.11.ID_EXCLUIDO')])
			->join('registro_fiduciario_parte_assinatura_arquivo', 'registro_fiduciario_parte_assinatura_arquivo.id_registro_fiduciario_parte_assinatura', '=', 'registro_fiduciario_parte_assinatura.id_registro_fiduciario_parte_assinatura')
			->whereNull('registro_fiduciario_parte_assinatura_arquivo.id_arquivo_grupo_produto_assinatura')
			->groupBy('registro_fiduciario_parte_assinatura.id_registro_fiduciario_parte_assinatura')
			->orderBy('registro_fiduciario_parte_assinatura.dt_cadastro', 'DESC')
			->get();

		$dados_registros_arquivos = [];
		foreach($partes_assinaturas as $parte_assinatura)
		{
			$registro_fiduciario_local = $parte_assinatura->registro_fiduciario_parte->registro_fiduciario;
			$dados_registros_arquivos[] = [
				'protocolo_pedido' => $registro_fiduciario_local->registro_fiduciario_pedido->pedido->protocolo_pedido,
				'credor' => $registro_fiduciario_local->registro_fiduciario_operacao->registro_fiduciario_credor->no_credor ?? "",
				'qualificacao' => $parte_assinatura->registro_fiduciario_parte->id_tipo_parte_registro_fiduciario === 15 ? "Testemunha" : "Credor",
				'tipo' => $parte_assinatura->registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo,
				'no_process_url' => $parte_assinatura->no_process_url,
				'id_registro_fiduciario_parte_assinatura' => $parte_assinatura->id_registro_fiduciario_parte_assinatura
			];
		}

		// Argumentos para o retorno da view
		$compact_args = [
			'pedido' => $pedido_usuario->pedido,
			'registro_fiduciario' => $registro_fiduciario,
			'dados_registros_arquivos' => $dados_registros_arquivos,

			// Variáveis da aba inicial
			'registro_fiduciario_parte' => $registro_fiduciario_parte,
			'registro_fiduciario_procurador' => $registro_fiduciario_procurador ?? NULL,
			'total_arquivos_docto_partes_enviados' => $total_arquivos_docto_partes_enviados,
			'total_pagamentos_pendentes' => $total_pagamentos_pendentes,

			// Variáveis da aba Arquivos
			'partes_exigencia_documentos' => $partes_exigencia_documentos,
			'total_arquivos_resultado' => $total_arquivos_resultado,
			'total_arquivos_contrato' => $total_arquivos_contrato,
			'total_arquivos_instrumento_particular' => $total_arquivos_instrumento_particular,
			'total_arquivos_docto_partes' => $total_arquivos_docto_partes,
			'total_arquivos_imovel' => $total_arquivos_imovel,
			'total_arquivos_outros' => $total_arquivos_outros,
			'total_arquivos_formulario' => $total_arquivos_formulario,
		];

		return view('protocolo.produtos.registro-fiduciario.geral-registro', $compact_args);
	}

	private function index_documentos(pedido_usuario $pedido_usuario)
	{
		$documento = $pedido_usuario->pedido->documento;

		// Variáveis da aba inicial
		if ($pedido_usuario->documento_parte) {
			$documento_parte = $pedido_usuario->documento_parte;
		} elseif ($pedido_usuario->documento_procurador) {
			$documento_procurador = $pedido_usuario->documento_procurador;
			$documento_parte = $documento_procurador->documento_parte;
		}

		// Variáveis da aba Arquivos
		$total_arquivos_contrato = $documento->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO'))->count();
		$total_arquivos_procuracao = $documento->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO'))->count();
		$total_arquivos_assessor_legal = $documento->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.DOCUMENTO.ARQUIVOS.ID_ASSESSOR_LEGAL'))->count();

		// Argumentos para o retorno da view
		$compact_args = [
			'pedido' => $pedido_usuario->pedido,
			'documento' => $documento,

			// Variáveis da aba inicial
			'documento_parte' => $documento_parte,
			'documento_procurador' => $documento_procurador ?? NULL,

			// Variáveis da aba Arquivos
			'total_arquivos_contrato' => $total_arquivos_contrato,
			'total_arquivos_procuracao' => $total_arquivos_procuracao,
			'total_arquivos_assessor_legal' => $total_arquivos_assessor_legal

		];

		return view('protocolo.produtos.documentos.geral-documentos', $compact_args);
	}

	private function load_config(pessoa $pessoa) {	

		$configuracao_pessoa = $this->ConfiguracaoPessoaServiceInterface->listar_array($pessoa->id_pessoa);
		
		foreach ($configuracao_pessoa as $slug => $valor) {

			config([
				'protocolo.'.$slug => $valor,
			]);
		}

		//dd(config('protocolo.protocolo-img-logo'));
	}
}