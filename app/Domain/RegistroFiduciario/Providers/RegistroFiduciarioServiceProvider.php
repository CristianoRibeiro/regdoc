<?php

namespace App\Domain\RegistroFiduciario\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAlertaGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAlertaGrupoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioAlertaGrupoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioAlertaGrupoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioAndamentoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioAndamentoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaEspecieRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaEspecieServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCedulaEspecieRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCedulaEspecieService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaFracaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaFracaoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCedulaFracaoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCedulaFracaoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCedulaRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCedulaService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaTipoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCedulaTipoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCedulaTipoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioConjugeRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioConjugeServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioConjugeRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioConjugeService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCredorRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCredorService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCustodianteRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCustodianteServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCustodianteRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCustodianteService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioInternoRepositoryInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioComentarioInternoRepository;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioComentarioRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioComentarioService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioDajeRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioDajeServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioDajeRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioDajeService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioEnderecoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioEnderecoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioEnderecoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioEnderecoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLivroRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLivroServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioImovelLivroRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioImovelLivroService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLocalizacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLocalizacaoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioImovelLocalizacaoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioImovelLocalizacaoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioImovelRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioImovelService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelTipoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioImovelTipoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioImovelTipoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImpostoTransmissaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImpostoTransmissaoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioImpostoTransmissaoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioImpostoTransmissaoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNaturezaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNaturezaServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioNaturezaRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioNaturezaService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioOperacaoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioOperacaoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOrigemRecursosRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOrigemRecursosServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioOrigemRecursosRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioOrigemRecursosService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioParteRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioParteService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioProcuradorRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioProcuradorService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioTipoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioTipoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoImovelRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoImovelServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioVerificacaoImovelRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioVerificacaoImovelService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoParteRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoParteServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioVerificacaoParteRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioVerificacaoParteService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioPagamentoGuiaRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioPagamentoGuiaService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoHistoricoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoHistoricoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioPagamentoHistoricoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioPagamentoHistoricoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioPagamentoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioPagamentoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoTipoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioPagamentoTipoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioPagamentoTipoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaSitucacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaSitucacaoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioNotaDevolutivaSituacaoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioNotaDevolutivaSituacaoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioNotaDevolutivaRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioNotaDevolutivaService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioNotaDevolutivaArquivoGrupoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioNotaDevolutivaArquivoGrupoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioObservadorRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioObservadorService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperadorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperadorServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioOperadorRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioOperadorService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteCapacidadeCivilRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteCapacidadeCivilServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioParteCapacidadeCivilRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioParteCapacidadeCivilService;

use App\Domain\RegistroFiduciario\Contracts\SituacaoPedidoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\SituacaoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\SituacaoPedidoRepository;
use App\Domain\RegistroFiduciario\Services\SituacaoPedidoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioChecklistRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioChecklistService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioArquivoGrupoProdutoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioArquivoGrupoProdutoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioParteArquivoGrupoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioParteArquivoGrupoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoSituacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoSituacaoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioReembolsoSituacaoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioReembolsoSituacaoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPedidoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioPedidoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioPedidoService;


use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioReembolsoRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioReembolsoService;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioCanalPdvRepository;
use App\Domain\RegistroFiduciario\Services\RegistroFiduciarioCanalPdvService;

class RegistroFiduciarioServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Registro Fiduciario
         */
        $this->app->singleton(
            RegistroFiduciarioRepositoryInterface::class,
            RegistroFiduciarioRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioServiceInterface::class,
            RegistroFiduciarioService::class
        );

        /**
         * Registro Fiduciario Pedido
         */
        $this->app->singleton(
            RegistroFiduciarioPedidoRepositoryInterface::class,
            RegistroFiduciarioPedidoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioPedidoServiceInterface::class,
            RegistroFiduciarioPedidoService::class
        );

        /**
         * Registro Fiduciario Alerta Grupo
         */
        $this->app->singleton(
            RegistroFiduciarioAlertaGrupoRepositoryInterface::class,
            RegistroFiduciarioAlertaGrupoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioAlertaGrupoServiceInterface::class,
            RegistroFiduciarioAlertaGrupoService::class
        );

        /**
         * Registro Fiduciario Operacao
         */
        $this->app->singleton(
            RegistroFiduciarioOperacaoRepositoryInterface::class,
            RegistroFiduciarioOperacaoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioOperacaoServiceInterface::class,
            RegistroFiduciarioOperacaoService::class
        );

        /**
         * Registro Fiduciario Parte
         */
        $this->app->singleton(
            RegistroFiduciarioParteRepositoryInterface::class,
            RegistroFiduciarioParteRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioParteServiceInterface::class,
            RegistroFiduciarioParteService::class
        );

        /**
         * Registro Fiduciario Conjugue
         */
        $this->app->singleton(
            RegistroFiduciarioConjugeRepositoryInterface::class,
            RegistroFiduciarioConjugeRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioConjugeServiceInterface::class,
            RegistroFiduciarioConjugeService::class
        );

        /**
         * Registro Fiduciario Credor
         */
        $this->app->singleton(
            RegistroFiduciarioCredorRepositoryInterface::class,
            RegistroFiduciarioCredorRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCredorServiceInterface::class,
            RegistroFiduciarioCredorService::class
        );

        /**
         * Registro Fiduciario Custodiante
         */
        $this->app->singleton(
            RegistroFiduciarioCustodianteRepositoryInterface::class,
            RegistroFiduciarioCustodianteRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCustodianteServiceInterface::class,
            RegistroFiduciarioCustodianteService::class
        );

        /**
         * Registro Fiduciario Tipo
         */
        $this->app->singleton(
            RegistroFiduciarioTipoRepositoryInterface::class,
            RegistroFiduciarioTipoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioTipoServiceInterface::class,
            RegistroFiduciarioTipoService::class
        );

        /**
         * Registro Fiduciario Natureza
         */
        $this->app->singleton(
            RegistroFiduciarioNaturezaRepositoryInterface::class,
            RegistroFiduciarioNaturezaRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioNaturezaServiceInterface::class,
            RegistroFiduciarioNaturezaService::class
        );

        /**
         * Registro Fiduciario Imovel Livro
         */
        $this->app->singleton(
            RegistroFiduciarioImovelLivroRepositoryInterface::class,
            RegistroFiduciarioImovelLivroRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioImovelLivroServiceInterface::class,
            RegistroFiduciarioImovelLivroService::class
        );

        /**
         * Registro Fiduciario Imovel Localização
         */
        $this->app->singleton(
            RegistroFiduciarioImovelLocalizacaoRepositoryInterface::class,
            RegistroFiduciarioImovelLocalizacaoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioImovelLocalizacaoServiceInterface::class,
            RegistroFiduciarioImovelLocalizacaoService::class
        );

        /**
         * Registro Fiduciario Imovel Tipo
         */
        $this->app->singleton(
            RegistroFiduciarioImovelTipoRepositoryInterface::class,
            RegistroFiduciarioImovelTipoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioImovelTipoServiceInterface::class,
            RegistroFiduciarioImovelTipoService::class
        );

        /**
         * Registro Fiduciario Origem Recursos
         */
        $this->app->singleton(
            RegistroFiduciarioOrigemRecursosRepositoryInterface::class,
            RegistroFiduciarioOrigemRecursosRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioOrigemRecursosServiceInterface::class,
            RegistroFiduciarioOrigemRecursosService::class
        );

        /**
         * Registro Fiduciario Cedula Tipo
         */
        $this->app->singleton(
            RegistroFiduciarioCedulaTipoRepositoryInterface::class,
            RegistroFiduciarioCedulaTipoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCedulaTipoServiceInterface::class,
            RegistroFiduciarioCedulaTipoService::class
        );

        /**
         * Registro Fiduciario Cedula Frações
         */
        $this->app->singleton(
            RegistroFiduciarioCedulaFracaoRepositoryInterface::class,
            RegistroFiduciarioCedulaFracaoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCedulaFracaoServiceInterface::class,
            RegistroFiduciarioCedulaFracaoService::class
        );

        /**
         * Registro Fiduciario Cedula Especie
         */
        $this->app->singleton(
            RegistroFiduciarioCedulaEspecieRepositoryInterface::class,
            RegistroFiduciarioCedulaEspecieRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCedulaEspecieServiceInterface::class,
            RegistroFiduciarioCedulaEspecieService::class
        );

        /**
         * Registro Fiduciario Procurador
         */
        $this->app->singleton(
            RegistroFiduciarioProcuradorRepositoryInterface::class,
            RegistroFiduciarioProcuradorRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioProcuradorServiceInterface::class,
            RegistroFiduciarioProcuradorService::class
        );

        /**
         * Registro Fiduciario Endereco
         */
        $this->app->singleton(
            RegistroFiduciarioEnderecoRepositoryInterface::class,
            RegistroFiduciarioEnderecoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioEnderecoServiceInterface::class,
            RegistroFiduciarioEnderecoService::class
        );

        /**
         * Registro Fiduciario Cedula
         */
        $this->app->singleton(
            RegistroFiduciarioCedulaRepositoryInterface::class,
            RegistroFiduciarioCedulaRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCedulaServiceInterface::class,
            RegistroFiduciarioCedulaService::class
        );

        /**
         * Registro Fiduciario Imposto Transmissao
         */
        $this->app->singleton(
            RegistroFiduciarioImpostoTransmissaoRepositoryInterface::class,
            RegistroFiduciarioImpostoTransmissaoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioImpostoTransmissaoServiceInterface::class,
            RegistroFiduciarioImpostoTransmissaoService::class
        );

        /**
         * Registro Fiduciario Daje
         */
        $this->app->singleton(
            RegistroFiduciarioDajeRepositoryInterface::class,
            RegistroFiduciarioDajeRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioDajeServiceInterface::class,
            RegistroFiduciarioDajeService::class
        );

        /**
         * Registro Fiduciario Imóvel
         */
        $this->app->singleton(
            RegistroFiduciarioImovelRepositoryInterface::class,
            RegistroFiduciarioImovelRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioImovelServiceInterface::class,
            RegistroFiduciarioImovelService::class
        );

        /**
         * Registro Fiduciario Verificacao Imovel
         */
        $this->app->singleton(
            RegistroFiduciarioVerificacaoImovelRepositoryInterface::class,
            RegistroFiduciarioVerificacaoImovelRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioVerificacaoImovelServiceInterface::class,
            RegistroFiduciarioVerificacaoImovelService::class
        );

        /**
         * Registro Fiduciario Verificacao Parte
         */
        $this->app->singleton(
            RegistroFiduciarioVerificacaoParteRepositoryInterface::class,
            RegistroFiduciarioVerificacaoParteRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioVerificacaoParteServiceInterface::class,
            RegistroFiduciarioVerificacaoParteService::class
        );

        /**
         * Registro Fiduciario Andamento
         */
        $this->app->singleton(
            RegistroFiduciarioAndamentoRepositoryInterface::class,
            RegistroFiduciarioAndamentoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioAndamentoServiceInterface::class,
            RegistroFiduciarioAndamentoService::class
        );

        /**
         * Registro Fiduciario Guia Pagamento
         */
        $this->app->singleton(
            RegistroFiduciarioPagamentoGuiaRepositoryInterface::class,
            RegistroFiduciarioPagamentoGuiaRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioPagamentoGuiaServiceInterface::class,
            RegistroFiduciarioPagamentoGuiaService::class
        );

        /**
         * Registro Fiduciario Historico Pagamento
         */
        $this->app->singleton(
            RegistroFiduciarioPagamentoHistoricoRepositoryInterface::class,
            RegistroFiduciarioPagamentoHistoricoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioPagamentoHistoricoServiceInterface::class,
            RegistroFiduciarioPagamentoHistoricoService::class
        );

        /**
         * Registro Fiduciario Pagamento
         */
        $this->app->singleton(
            RegistroFiduciarioPagamentoRepositoryInterface::class,
            RegistroFiduciarioPagamentoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioPagamentoServiceInterface::class,
            RegistroFiduciarioPagamentoService::class
        );

        /**
         * Registro Fiduciario Tipo Pagamento
         */
        $this->app->singleton(
            RegistroFiduciarioPagamentoTipoRepositoryInterface::class,
            RegistroFiduciarioPagamentoTipoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioPagamentoTipoServiceInterface::class,
            RegistroFiduciarioPagamentoTipoService::class
        );

        /**
         * Registro Fiduciario Nota Devolutiva Situacao
         */
        $this->app->singleton(
            RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface::class,
            RegistroFiduciarioNotaDevolutivaSituacaoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioNotaDevolutivaSituacaoServiceInterface::class,
            RegistroFiduciarioNotaDevolutivaSituacaoService::class
        );

        /**
         * Registro Fiduciario Nota Devolutiva
         */
        $this->app->singleton(
            RegistroFiduciarioNotaDevolutivaRepositoryInterface::class,
            RegistroFiduciarioNotaDevolutivaRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioNotaDevolutivaServiceInterface::class,
            RegistroFiduciarioNotaDevolutivaService::class
        );

        /**
         * Registro Fiduciario Nota Devolutiva Arquivo Grupo
         */
        $this->app->singleton(
            RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface::class,
            RegistroFiduciarioNotaDevolutivaArquivoGrupoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface::class,
            RegistroFiduciarioNotaDevolutivaArquivoGrupoService::class
        );

        /**
         * Registro Fiduciario Parte Capacidade Civil
         */
        $this->app->singleton(
            RegistroFiduciarioParteCapacidadeCivilRepositoryInterface::class,
            RegistroFiduciarioParteCapacidadeCivilRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioParteCapacidadeCivilServiceInterface::class,
            RegistroFiduciarioParteCapacidadeCivilService::class
        );

        /**
         * Registro Fiduciario Comentário
         */

        $this->app->singleton(
            RegistroFiduciarioComentarioRepositoryInterface::class,
            RegistroFiduciarioComentarioRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioComentarioServiceInterface::class,
            RegistroFiduciarioComentarioService::class
        );

        /**
         * Registro Fiduciario Comentário Interno
         */

        $this->app->singleton(
            RegistroFiduciarioComentarioInternoRepositoryInterface::class,
            RegistroFiduciarioComentarioInternoRepository::class
        );

        /**
         * Registro Fiduciario Observador
         */

        $this->app->singleton(
            RegistroFiduciarioObservadorRepositoryInterface::class,
            RegistroFiduciarioObservadorRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioObservadorServiceInterface::class,
            RegistroFiduciarioObservadorService::class
        );

        /**
         * Registro Fiduciario Operador
         */

        $this->app->singleton(
            RegistroFiduciarioOperadorRepositoryInterface::class,
            RegistroFiduciarioOperadorRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioOperadorServiceInterface::class,
            RegistroFiduciarioOperadorService::class
        );

        /**
         * Situacao Pedido
         */
        $this->app->singleton(
            SituacaoPedidoRepositoryInterface::class,
            SituacaoPedidoRepository::class
        );

        $this->app->singleton(
            SituacaoPedidoServiceInterface::class,
            SituacaoPedidoService::class
        );

        
        /**
         * Registro Fiduciario Checklist
         */
        $this->app->singleton(
            RegistroFiduciarioChecklistRepositoryInterface::class,
            RegistroFiduciarioChecklistRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioChecklistServiceInterface::class,
            RegistroFiduciarioChecklistService::class
        );

         /**
         * Registro Fiduciario Arquivo Grupo Produto
         */
        $this->app->singleton(
            RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface::class,
            RegistroFiduciarioArquivoGrupoProdutoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioArquivoGrupoProdutoServiceInterface::class,
            RegistroFiduciarioArquivoGrupoProdutoService::class
        );

         /**
         * Registro Fiduciario Parte Arquivo Grupo
         */
        $this->app->singleton(
            RegistroFiduciarioParteArquivoGrupoRepositoryInterface::class,
            RegistroFiduciarioParteArquivoGrupoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioParteArquivoGrupoServiceInterface::class,
            RegistroFiduciarioParteArquivoGrupoService::class
        );

        /**
         * Registro Fiduciario Reembolso Situacao
         */
        $this->app->singleton(
            RegistroFiduciarioReembolsoSituacaoRepositoryInterface::class,
            RegistroFiduciarioReembolsoSituacaoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioReembolsoSituacaoServiceInterface::class,
            RegistroFiduciarioReembolsoSituacaoService::class
        );


         /**
         * Registro Fiduciario Reembolso
         */
        $this->app->singleton(
            RegistroFiduciarioReembolsoRepositoryInterface::class,
            RegistroFiduciarioReembolsoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioReembolsoServiceInterface::class,
            RegistroFiduciarioReembolsoService::class
        );


        /**
         * Registro Fiduciario Canal Pdva Parceiro
         */
        $this->app->singleton(
            RegistroFiduciarioCanalPdvRepositoryInterface::class,
            RegistroFiduciarioCanalPdvRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioCanalPdvServiceInterface::class,
            RegistroFiduciarioCanalPdvService::class
        );

    }
}
