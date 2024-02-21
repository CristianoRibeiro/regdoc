<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PortalController;
use App\Http\Controllers\ARISPController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PDAVHController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\EntidadesController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\ConstrutoraController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ArquivosController;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\NotaDevolutivaController;
use App\Http\Controllers\RelatorioDocumentoController;
use App\Http\Controllers\AcompanhamentoStatusController;
use App\Http\Controllers\ServentiasController;
use App\Http\Controllers\TempFilesController;
use App\Http\Controllers\CanaisPDVController;

use App\Http\Controllers\Inicio\InicioController;
use App\Http\Controllers\Inicio\AlertasController;
use App\Http\Controllers\Inicio\RegistroOperadoresController;

use App\Http\Controllers\Protocolo\ProtocoloController as ProtocoloController;
use App\Http\Controllers\Protocolo\AcessarController as ProtocoloAcessarController;
use App\Http\Controllers\Protocolo\RegistroArquivoController as ProtocoloRegistroArquivoController;
use App\Http\Controllers\Protocolo\RegistroPagamentoController as ProtocoloRegistroPagamentoController;
use App\Http\Controllers\Protocolo\RegistroController as ProtocoloRegistroController;
use App\Http\Controllers\Protocolo\DocumentoController as ProtocoloDocumentoController;
use App\Http\Controllers\Protocolo\DocumentoArquivoController as ProtocoloDocumentoArquivoController;
use App\Http\Controllers\Protocolo\AssinaturaController as ProtocoloAssinaturaController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\CertificadoController as AuthCertificadoController;
use App\Http\Controllers\Auth\EntidadeController;

use App\Http\Controllers\DuploFator\FatorAutenticacaoController;

use App\Http\Controllers\Registros\RegistroFiduciarioController;
use App\Http\Controllers\Registros\RegistroFiduciarioCredorController;
use App\Http\Controllers\Registros\RegistroFiduciarioCustodianteController;
use App\Http\Controllers\Registros\RegistroFiduciarioTempParteController;
use App\Http\Controllers\Registros\RegistroFiduciarioTempParteProcuradorController;
use App\Http\Controllers\Registros\RegistroFiduciarioArquivoController;
use App\Http\Controllers\Registros\RegistroFiduciarioOperacaoController;
use App\Http\Controllers\Registros\RegistroFiduciarioFinanciamentoController;
use App\Http\Controllers\Registros\RegistroFiduciarioContratoController;
use App\Http\Controllers\Registros\RegistroFiduciarioCartorioController;
use App\Http\Controllers\Registros\RegistroFiduciarioCedulaController;
use App\Http\Controllers\Registros\RegistroFiduciarioPagamentoController;
use App\Http\Controllers\Registros\RegistroFiduciarioPagamentoGuiaController;
use App\Http\Controllers\Registros\RegistroFiduciarioReembolsoController;
use App\Http\Controllers\Registros\RegistroFiduciarioAssinaturaController;
use App\Http\Controllers\Registros\RegistroFiduciarioParteController;
use App\Http\Controllers\Registros\RegistroFiduciarioChecklistController;
use App\Http\Controllers\Registros\RegistroFiduciarioImovelController;
use App\Http\Controllers\Registros\RegistroFiduciarioTipoController;
use App\Http\Controllers\Registros\RegistroFiduciarioComentarioController;
use App\Http\Controllers\Registros\RegistroFiduciarioComentarioInternoController;
use App\Http\Controllers\Registros\RegistroFiduciarioComentarioArquivosController;
use App\Http\Controllers\Registros\RegistroFiduciarioNotaDevolutivaController;
use App\Http\Controllers\Registros\RegistroFiduciarioObservadorController;
use App\Http\Controllers\Registros\RegistroFiduciarioOperadorController;
use App\Http\Controllers\Registros\RegistroFiduciarioPedidoCentralController;
use App\Http\Controllers\Registros\RegistroFiduciarioPedidoCentralHistoricoController;
use App\Http\Controllers\Registros\RegistroFiduciarioProcuradorController;

use App\Http\Controllers\Documentos\DocumentoTempParteController;
use App\Http\Controllers\Documentos\DocumentoTempParteProcuradorController;
use App\Http\Controllers\Documentos\DocumentoArquivoController;
use App\Http\Controllers\Documentos\DocumentoController;
use App\Http\Controllers\Documentos\DocumentoContratoController;
use App\Http\Controllers\Documentos\DocumentoParteController;
use App\Http\Controllers\Documentos\DocumentoComentarioController;
use App\Http\Controllers\Documentos\DocumentoComentarioArquivosController;
use App\Http\Controllers\Documentos\DocumentoObservadorController;
use App\Http\Controllers\Calculadora\CalculadoraController;
use App\Http\Controllers\Biometria\BiometriaController;
use App\Http\Controllers\Biometria\BiometriaLoteController;

use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Session::has('pedido')) {
        return redirect('protocolo');
    } else {
        return view('portal.inicio');
    }
})->name('portal.inicio');

Route::get('/sobre', function () {
    return view('portal.sobre');
})->name('portal.sobre');

// Rotas de solicitação de certificado VIDaaS
Route::prefix('/certificado-vidaas')->group(function () {
    Route::get('/{link?}', [PortalController::class, 'certificado_vidaas']);
    Route::post('/{link?}', [PortalController::class, 'salvar_certificado_vidaas'])->name('certificado-vidaas');
});

// Rotas de notificação da integração ARISP
Route::prefix('arisp')->group(function () {
    Route::post('notificacao', [ARISPController::class, 'notificacao']);
    Route::get('download/{codigo_arquivo}/{no_arquivo}', [ARISPController::class, 'download_arquivo']);
});

// Rotas do sistema com o prefixo "protocolo"
Route::prefix('protocolo')->name('protocolo.')->group(function () {
    Route::post('/acessar', [ProtocoloAcessarController::class, 'acessar']);
    Route::get('/acessar/{token}', [ProtocoloAcessarController::class, 'acessar_token']);

    Route::get('/sair', [ProtocoloAcessarController::class, 'sair'])->name('sair');

    Route::get('/iniciar-assinatura-lote/{ids_parte_assinatura}/{qualificacoes}', [ProtocoloAssinaturaController::class, 'iniciar_assinatura_lote']);

    // Rotas que só podem ser acessadas se o usuário estiver autenticado como um protocolo
    Route::group(['middleware' => ['auth','auth.protocolo', 'get.configs.protocolo']], function () {

        Route::get('/', [ProtocoloController::class, 'index'])->name('index');

        Route::prefix('registro')->group(function () {
            Route::prefix('arquivos')->group(function () {
                Route::get('/', [ProtocoloRegistroArquivoController::class, 'arquivos']);
                Route::put('/', [ProtocoloRegistroArquivoController::class, 'salvar_arquivos']);
            });

            Route::prefix('pagamentos/{pagamento}')->group(function () {
                Route::get('/', [ProtocoloRegistroPagamentoController::class, 'show']);
                Route::prefix('guias/{guia}')->group(function () {
                    Route::get('/enviar-comprovante', [ProtocoloRegistroPagamentoController::class, 'enviar_comprovante']);
                    Route::post('/salvar-comprovante', [ProtocoloRegistroPagamentoController::class, 'salvar_comprovante']);
                });
            });

            Route::get('/assinaturas/{parte_assinatura}', [ProtocoloRegistroController::class, 'visualizar_assinatura']);
        });

        Route::prefix('documentos')->group(function () {
            Route::prefix('arquivos')->group(function () {
                Route::get('/', [ProtocoloDocumentoArquivoController::class, 'arquivos']);
                Route::put('/', [ProtocoloDocumentoArquivoController::class, 'salvar_arquivos']);
            });

            Route::get('/assinaturas/{parte_assinatura}', [ProtocoloDocumentoController::class, 'visualizar_assinatura']);
        });
    });

});

// Rotas de notificação da integração PDAVH
Route::prefix('pdavh')->group(function () {
    Route::post('notificacao', [PDAVHController::class, 'notificacao']);
    Route::post('notificacao-lote', [PDAVHController::class, 'notificacaoLote']);
    Route::post('notificacao-outros-arquivos', [PDAVHController::class, 'notificacaoOutrosArquivos']);
});

// Rotas do sistema com o prefixo "app"
Route::prefix('app')->group(function () {
    // Foi necessário reescrever as rotas padrões de acesso pois foi alterada a estrutura de URL e views.
    Route::get('acessar', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('acessar', [LoginController::class, 'login']);
    Route::get('acessar/lembrar-senha', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('acessar/reenviar-email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('acessar/lembrar-senha/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('acessar/lembrar-senha', [ResetPasswordController::class, 'reset'])->name('password.resetar');

    Route::get('acessar/certificado', [AuthCertificadoController::class, 'showLoginForm'])->name('login.certificado');
    Route::post('acessar/certificado', [AuthCertificadoController::class, 'login']);

    Route::get('acompanhamento/status', [AcompanhamentoStatusController::class, 'index'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/emissao', [AcompanhamentoStatusController::class, 'emissao'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/itbi', [AcompanhamentoStatusController::class, 'itbi'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/guia-prenotacao', [AcompanhamentoStatusController::class, 'guiaprenotacao'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/guia-emolumento', [AcompanhamentoStatusController::class, 'guiaemolumento'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/assinatura', [AcompanhamentoStatusController::class, 'assinatura'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/averbacao', [AcompanhamentoStatusController::class, 'averbacao'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/prenotacao', [AcompanhamentoStatusController::class, 'prenotacao'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/emolumento', [AcompanhamentoStatusController::class, 'emolumento'])->middleware('can:acompanhamento-status');
    Route::get('acompanhamento/cartorio', [AcompanhamentoStatusController::class, 'cartorio'])->middleware('can:acompanhamento-status');
});

// Rotas do sistema com o prefixo "app"
Route::prefix('app')->name('app.')->group(function () {

    Route::prefix('temp-files')->group(function () {
        Route::post('/store', [TempFilesController::class, 'store']);
        Route::post('/destroy', [TempFilesController::class, 'destroy']);
    });
    
    // Rotas para as definições de estado
    Route::prefix('estado')->group(function () {
        Route::get('/lista', [CidadeController::class, 'lista_estado']);
    });

    // Rotas para as definições da cidade
    Route::prefix('cidade')->group(function () {
        Route::post('/lista', [CidadeController::class, 'lista']);
    });

     // Rotas para as definições das tipos de nostas devolutivas
     Route::prefix('notas-devolutivas')->group(function () {
        Route::post('/lista-nota-devolutiva-causa-grupo', [NotaDevolutivaController::class, 'lista_nota_devolutiva_causa_grupo']);
        Route::post('/lista-nota-devolutiva-causa-raiz', [NotaDevolutivaController::class, 'lista_nota_devolutiva_causa_raiz']);
    });

    Route::group(['middleware' => ['auth.app']], function () {
        // Reescrita da rota de logout para que esteja de acordo com a estrutura de URL.
        Route::get('selecionar-entidade', [EntidadeController::class, 'selecionar_entidade']);
        Route::get('definir-entidade/{key}', [EntidadeController::class, 'definir_entidade'])->name('definir-entidade');
        Route::get('sair/{url?}', [LoginController::class, 'logout'])->name('logout');
    });

    // Rotas que só podem ser acessadas se o usuário estiver autenticado
    Route::group(['middleware' => ['auth', 'get.configs']], function () {
        // Altera a senha no primeiro acesso!
        Route::match(['get', 'post'], 'usuario/alterar-senha', [UsuarioController::class, 'alterar_senha']);
        Route::post('usuario/salvar-alterar-senha', [UsuarioController::class, 'salvar_dados_acesso']);

        // 2FA do Usuário
        Route::get('/usuario/salvar-autenticacao-2fa', [FatorAutenticacaoController::class, 'salvar_autenticacao_2fa']);
        Route::get('/usuario/autenticacao-2fa', [FatorAutenticacaoController::class, 'autenticacao_2fa']);
        Route::post('/usuario/reenviar-autenticacao-2fa', [FatorAutenticacaoController::class, 'reenviar_autenticacao_2fa']);
        Route::post('/usuario/validar-autenticacao-2fa', [FatorAutenticacaoController::class, 'validar_autenticacao_2fa']);

        // Rotas que só podem ser acessadas se o usuário estiver autenticado
        Route::group(['middleware' => ['auth.app', 'auth.duplo.fator', 'auth.alterar.senha']], function () {
            Route::get('/', [InicioController::class, 'index'])->name('index');

            // Rotas para as definições do usuário
            Route::prefix('usuario')->name('usuario.')->group(function () {
                Route::post('/troca-pessoa', [UsuarioController::class, 'troca_pessoa']);
                Route::get('/configuracoes', [UsuarioController::class, 'configuracoes'])->name('configuracoes');
                Route::post('/configuracoes/acesso', [UsuarioController::class, 'salvar_acesso']);

                Route::prefix('minha-conta')->group(function () {
                    Route::get('/', [UsuarioController::class, 'minha_conta'])->name('minha-conta');
                    Route::post('/salvar-dados-pessoais',[UsuarioController::class, 'salvar_dados_pessoais']);
                    Route::post('/salvar-dados-acesso',[UsuarioController::class, 'salvar_dados_acesso']);
                    Route::post('/salvar-dados-serventia',[UsuarioController::class, 'salvar_dados_serventia']);
                    Route::post('/salvar-dados-api',[UsuarioController::class, 'salvar_dados_api']);
                    Route::post('/salvar-autenticacao', [UsuarioController::class, 'salvar_autenticacao_email']);
                });
            });

            // Rotas para listagem de recursos
            Route::prefix('pessoa')->group(function () {
                Route::post('/lista', [PessoaController::class, 'lista']);
            });
            Route::prefix('usuario')->group(function () {
                Route::post('/listar', [UsuariosController::class, 'listar']);
            });
            Route::prefix('construtora')->group(function () {
                Route::post('/empreendimentos', [ConstrutoraController::class, 'empreendimentos']);
            });

            // Rotas para a gerência de usuarios
            Route::prefix('gerenciar-usuarios')->name('gerenciar-usuarios.')->middleware('can:configuracoes-usuarios')->group(function () {
                Route::match(['get', 'post'], '/', [UsuariosController::class, 'index'])->name('index');
                Route::post('/novo', [UsuariosController::class, 'novo_usuario']);
                Route::post('/inserir', [UsuariosController::class, 'inserir_usuario']);
                Route::post('/detalhes', [UsuariosController::class, 'detalhes_usuario']);
                Route::post('/gerar-nova-senha', [UsuariosController::class, 'gerar_nova_senha']);
                Route::post('/desativar-usuario', [UsuariosController::class, 'desativar_usuario']);
                Route::post('/reativar-usuario', [UsuariosController::class, 'reativar_usuario']);

                Route::prefix('vinculos')->group(function () {
                    Route::post('/novo', [UsuariosController::class, 'novo_vinculo']);
                    Route::post('/inserir', [UsuariosController::class, 'inserir_vinculo']);
                    Route::post('/remover', [UsuariosController::class, 'remover_vinculo']);
                });

                Route::post('/listar-pessoas', [UsuariosController::class, 'listar_pessoas']);
            });

            Route::resource('entidades', EntidadesController::class)->middleware('can:configuracoes-entidades')->only(['index','show']);

            // Rotas para a certificados
            Route::prefix('certificados-vidaas/{certificado}/enviar-emissao')->name('certificados-vidaas.')->middleware('can:configuracoes-certificados')->group(function () {
                Route::get('/', [CertificadoController::class, 'enviar_emissao'])->name('enviar-emissao');
                Route::post('/', [CertificadoController::class, 'salvar_enviar_emissao'])->name('salvar-enviar-emissao');
            });

            Route::prefix('certificados-vidaas/{certificado}/enviar-emissao-emitir')->name('certificados-vidaas.')->middleware('can:configuracoes-certificados')->group(function () {
                Route::get('/', [CertificadoController::class, 'enviar_emissao_emitir'])->name('enviar-emissao-emitir');
                Route::post('/', [CertificadoController::class, 'salvar_enviar_emissao_emitir'])->name('salvar-enviar-emissao-emitir');
            });

            Route::prefix('certificados-vidaas/{certificado}/cancelar-emissao')->name('certificados-vidaas.')->middleware('can:configuracoes-certificados')->group(function () {
                Route::get('/', [CertificadoController::class, 'cancelar_emissao'])->name('cancelar-emissao');
                Route::post('/', [CertificadoController::class, 'salvar_cancelar_emissao'])->name('salvar-cancelar-emissao');
            });

            Route::prefix('certificados-vidaas/{certificado}/alterar-ticket')->group(function () {
                Route::get('/', [CertificadoController::class, 'alterar_ticket'])->name('alterar-ticket');
                Route::post('/', [CertificadoController::class, 'salvar_alterar_ticket'])->name('alterar-atualizar-ticket');
            });

            Route::prefix('certificados-vidaas/{certificado}/atualizar-ticket')->group(function () {
                Route::get('/', [CertificadoController::class, 'atualizar_ticket'])->name('atualizar-ticket');
            });

            Route::resource('certificados-vidaas', CertificadoController::class)->middleware('can:certificados')->parameters([
                'certificados-vidaas' => 'certificado'
            ]);

            // Serventias
            Route::prefix('serventias')->name('serventias.')->group(function () {
                Route::match(['get', 'post'], '/', [ServentiasController::class, 'index'])->name('index');
                Route::post('/novo', [ServentiasController::class, 'nova_serventia']);
                Route::post('/salvar', [ServentiasController::class, 'salvar_serventia']);
                Route::get('/detalhes/{id_serventia}', [ServentiasController::class, 'detalhes_serventia']);
                Route::get('/editar/{id_serventia}', [ServentiasController::class, 'editar_serventia']);
                Route::post('/alterar', [ServentiasController::class, 'alterar_serventia']);
            });

            // Cadastro de parceiros - Canais PDV
            Route::prefix('canais-pdv')->name('canais-pdv.')->group(function () {
                Route::match(['get', 'post'], '/', [CanaisPDVController::class, 'index'])->name('index');
                Route::post('/novo', [CanaisPDVController::class, 'novo_canal_pdv']);
                Route::post('/salvar_parceiro', [CanaisPDVController::class, 'salvar_canal_pdv_parceiro']);
                Route::get('/detalhes/{id_canal_pdv_parceiro}', [CanaisPDVController::class, 'detalhes_canal_pdv']);
                Route::get('/editar/{id_canal_pdv_parceiro}', [CanaisPDVController::class, 'editar_canal_pdv']);
                Route::post('/alterar', [CanaisPDVController::class, 'alterar_canal_pdv']);
                Route::post('/desativar', [CanaisPDVController::class, 'desativar_canal_pdv']);
                Route::post('/registro-canal-pdv', [CanaisPDVController::class, 'registro_canal_pdv']);
            });

            // Rotas para os alertas
            Route::prefix('alertas')->name('alertas.')->group(function () {
                Route::prefix('registros')->name('registros.')->middleware('can:registros-fiduciario')->middleware('can:registros-garantias')->group(function () {
                    Route::get('/', [AlertasController::class, 'index'])->name('index');
                });
                Route::prefix('documentos')->name('documentos.')->middleware('can:documentos')->group(function () {
                    Route::get('/', [AlertasController::class, 'index_documentos'])->name('index_documentos');
                });
            });
            
            // Rotas para a lista de registros por operadores
            Route::prefix('operadores')->name('operadores.')->group(function () {
                Route::prefix('registros')->name('registros.')->middleware('can:registros-operadores')->group(function () {
                    Route::get('/', [RegistroOperadoresController::class, 'index'])->name('index');
                    Route::get('/detalhes', [RegistroOperadoresController::class, 'detalhes'])->name('detalhes');
                });
            });

            // Rotas do menu "Importação"
            /*
            Route::prefix('importacao')->name('importacao.')->group(function () {
                Route::get('/', 'InicioController@index')->name('index');

                Route::prefix('registros')->name('registros.')->group(function () {
                    Route::match(['get', 'post'], '/', 'ImportacaoRegistroFiduciarioController@index')->name('index');
                    Route::post('/novo', 'ImportacaoRegistroFiduciarioController@novo');
                    Route::post('/preimportar', 'ImportacaoRegistroFiduciarioController@preimportar');
                    Route::post('/importar', 'ImportacaoRegistroFiduciarioController@importar');
                    Route::post('/detalhes', 'ImportacaoRegistroFiduciarioController@detalhes_arquivo');
                    Route::post('/certificado', 'ImportacaoRegistroFiduciarioController@certificado_arquivo');
                });
            });
            */

            Route::prefix('relatorios')->name('relatorios.')->group(function () {
                Route::prefix('{produto}/registros')->name('registros.')->middleware('can:relatorios-registros-fiduciario')->middleware('can:relatorios-registros-garantias')->group(function () {
                    Route::match(['get', 'post'], '/', [RelatorioController::class, 'index'])->name('index');
                    Route::match(['get', 'post'], '/exportar', [RelatorioController::class, 'exportar_excel'])->name('exportar-excel');
                });

                Route::prefix('documentos')->name('documentos.')->group(function () {
                    Route::match(['get', 'post'], '/', [RelatorioDocumentoController::class, 'index'])->name('index');
                    Route::match(['get', 'post'], '/exportar', [RelatorioDocumentoController::class, 'exportar_excel'])->name('exportar-excel');
                });

                Route::prefix('logs')->name('logs.')->middleware('can:relatorios-logs')->group(function () {
                    Route::get('/', [RelatorioController::class, 'logs_view'])->name('index');
                    Route::post('/detalhes', [RelatorioController::class, 'detalhes_log']);
                });
            });

            // Rotas do menu "Produtos"
            Route::prefix('produtos')->name('produtos.')->middleware('can:registros-fiduciario')->middleware('can:registros-garantias')->group(function () {
                Route::prefix('registros')->name('registros.')->group(function () {
                    Route::resource('credores', RegistroFiduciarioCredorController::class)->parameters([
                        'credores' => 'credor'
                    ])->only(['index','show']);

                    Route::resource('custodiantes', RegistroFiduciarioCustodianteController::class)->only(['index','show']);
                    
                    Route::prefix('tipos')->group(function () {
                        Route::get('tipos-partes', [RegistroFiduciarioTipoController::class, 'tipos_partes']);
                    });

                    Route::resource('temp-partes', RegistroFiduciarioTempParteController::class)->except(['index','show']);
                    Route::resource('temp-partes.procuradores', RegistroFiduciarioTempParteProcuradorController::class)->parameters([
                         'procuradores' => 'procurador'
                    ]);

                    Route::get('procurador/{id_procurador}/{operacao}', [RegistroFiduciarioProcuradorController::class, 'detalhes_editar']);
                    Route::post('procurador/atualizar', [RegistroFiduciarioProcuradorController::class, 'salvar_atualizar']);
                    
                    Route::get('temp-partes/buscar_conjuge', [RegistroFiduciarioTempParteController::class, 'buscar_conjuge']);

                    Route::prefix('{registro}/arquivos')->group(function () {
                        Route::post('/', [RegistroFiduciarioArquivoController::class, 'salvar_arquivos_multiplos']);
                        Route::get('/', [RegistroFiduciarioArquivoController::class, 'arquivos']);
                        Route::put('/', [RegistroFiduciarioArquivoController::class, 'salvar_arquivos']);
                        Route::delete('/', [RegistroFiduciarioArquivoController::class, 'remover_arquivo']);
                    });

                    Route::post('{registro}/iniciar-proposta', [RegistroFiduciarioController::class, 'iniciar_proposta']);
                    Route::post('{registro}/iniciar-emissoes', [RegistroFiduciarioController::class, 'iniciar_emissoes']);
                    Route::post('{registro}/iniciar-documentacao', [RegistroFiduciarioController::class, 'iniciar_documentacao']);
                    Route::post('{registro}/finalizar-registro', [RegistroFiduciarioController::class, 'finalizar_registro']);
                    Route::patch('{registro}/atualizar-situacao', [RegistroFiduciarioController::class, 'atualizarPgtoItbi'])->name('registro.atualizarPgtoItbi');
                    Route::patch('{registro}/atualizar-situacao-nota-devolutiva', [RegistroFiduciarioController::class, 'atualizarNotaDevolutiva'])->name('registro.atualizarNotaDevolutiva');

                    Route::prefix('{registro}/iniciar-processamento')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'iniciar_processamento']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_iniciar_processamento']);
                    });
                    Route::prefix('{registro}/operacao')->group(function () {
                        Route::get('/editar', [RegistroFiduciarioOperacaoController::class, 'edit']);
                        Route::put('/', [RegistroFiduciarioOperacaoController::class, 'update']);
                    });
                    Route::prefix('{registro}/financiamento')->group(function () {
                        Route::get('/editar', [RegistroFiduciarioFinanciamentoController::class, 'edit']);
                        Route::put('/', [RegistroFiduciarioFinanciamentoController::class, 'update']);
                    });
                    Route::prefix('{registro}/contrato')->group(function () {
                        Route::get('/editar', [RegistroFiduciarioContratoController::class, 'edit']);
                        Route::put('/', [RegistroFiduciarioContratoController::class, 'update']);
                    });
                    Route::prefix('{registro}/cedula')->group(function () {
                        Route::get('/editar', [RegistroFiduciarioCedulaController::class, 'edit']);
                        Route::put('/', [RegistroFiduciarioCedulaController::class, 'update']);
                    });
                    Route::prefix('{registro}/cartorio')->group(function () {
                        Route::get('/editar', [RegistroFiduciarioCartorioController::class, 'edit']);
                        Route::put('/', [RegistroFiduciarioCartorioController::class, 'update']);
                    });

                    Route::prefix('{registro}/transformar-em-contrato')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'transformar_contrato']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_transformar_contrato']);
                    });

                    Route::prefix('{registro}/inserir-resultado')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'inserir_resultado']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_inserir_resultado']);
                    });

                    Route::prefix('{registro}/iniciar-assinaturas')->group(function () {
                        Route::get('/', [RegistroFiduciarioAssinaturaController::class, 'iniciar_assinaturas']);
                        Route::post('/', [RegistroFiduciarioAssinaturaController::class, 'salvar_iniciar_assinaturas']);
                        Route::post('/outros-arquivos', [RegistroFiduciarioAssinaturaController::class, 'iniciar_assinaturas_outros_arquivos']);

                        Route::prefix('/{arquivo}/configurar-partes')->group(function () {
                            Route::get('/', [RegistroFiduciarioAssinaturaController::class, 'configurar_partes']);
                            Route::post('/', [RegistroFiduciarioAssinaturaController::class, 'salvar_configurar_partes']);
                        });
                    });

                    Route::prefix('{registro}/pagamentos/{pagamento}/guias/{guia}')->group(function () {
                        Route::get('/enviar-comprovante', [RegistroFiduciarioPagamentoGuiaController::class, 'enviar_comprovante']);
                        Route::post('/salvar-comprovante', [RegistroFiduciarioPagamentoGuiaController::class, 'salvar_comprovante']);
                        Route::get('/verificar-comprovante', [RegistroFiduciarioPagamentoGuiaController::class, 'verificar_comprovante']);
                        Route::post('/salvar-validacao-comprovante', [RegistroFiduciarioPagamentoGuiaController::class, 'salvar_validacao_comprovante']);
                    });

                    Route::prefix('{registro}/iniciar-envio-registro')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'iniciar_envio_registro']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_iniciar_envio_registro']);
                        Route::post('/previa', [RegistroFiduciarioController::class, 'iniciar_envio_registro_previa']);
                    });

                    Route::prefix('{registro}/enviar-registro')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'enviar_registro']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_enviar_registro']);
                    });

                    Route::prefix('{registro}/vincular-entidade')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'vincular_entidade']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_vincular_entidade']);
                    });

                    Route::prefix('{registro}/alterar-integracao')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'alterar_integracao']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_alterar_integracao']);
                    });

                    Route::prefix('{registro}/reenviar-email')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'reenviar_email']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_reenviar_email']);
                    });

                    Route::get('{registro}/assinaturas/{assinatura}/parte/{parte_assinatura}', [RegistroFiduciarioAssinaturaController::class, 'visualizar_assinatura']);
                    Route::get('{registro}/assinaturas/{assinatura}', [RegistroFiduciarioAssinaturaController::class, 'show']);

                    Route::get('{registro}/partes/{parte}/completar', [RegistroFiduciarioParteController::class, 'completar']);
                    Route::post('{registro}/partes/{parte}/salvar-completar', [RegistroFiduciarioParteController::class, 'salvar_completar']);
                    Route::get('partes/desvincular/{id}', [RegistroFiduciarioParteController::class, 'desvincular_parte'])->name('registro.desvincular_parte');
                    Route::get('{registro}/id-tipo-pessoa/{id_tipo_pessoa}/id-tipo-parte-registro/{id_tipo_parte_registro}', [RegistroFiduciarioParteController::class, 'adicionar_parte'])->name('registro.adicionar_parte');
                    Route::post('salvar-parte', [RegistroFiduciarioParteController::class, 'salvar_parte'])->name('registro.salvar_parte');
                    Route::get('{registro}/partes/{parte}/{telefone}', [RegistroFiduciarioParteController::class, 'insere_atualiza_telefone']);

                    Route::prefix('{registro}/checklist')->group(function () {
                        Route::get('/', [RegistroFiduciarioChecklistController::class, 'checklist']);
                        Route::post('/', [RegistroFiduciarioChecklistController::class, 'salvar_checklist']);
                    });

                    Route::get('{registro}/datas', [RegistroFiduciarioController::class, 'visualizar_datas']);

                    Route::prefix('{registro}/arisp/{arisp}')->group(function () {
                        Route::get('/editar', [RegistroFiduciarioController::class, 'arisp_acesso']);
                        Route::put('/', [RegistroFiduciarioController::class, 'salvar_arisp_acesso']);
                    });

                    Route::prefix('{registro}/devolutivas/{devolutiva}/categorizar')->group(function () {
                        Route::get('/', [RegistroFiduciarioNotaDevolutivaController::class, 'categorizar']);
                        Route::post('/', [RegistroFiduciarioNotaDevolutivaController::class, 'salvar_categorizar']);
                    });

                    Route::prefix('{registro}/retroceder-situacao')->group(function () {
                        Route::get('/', [RegistroFiduciarioController::class, 'retroceder_situacao']);
                        Route::post('/', [RegistroFiduciarioController::class, 'salvar_retroceder_situacao']);
                    });
                });

                Route::prefix('{produto}')->group(function () {
                    Route::get('registros/{registro}/cancelar', [RegistroFiduciarioController::class, 'cancelar'])->name('registros.cancelar');
                    
                    Route::resource('registros', RegistroFiduciarioController::class)->middleware('can:registros-fiduciario')->middleware('can:registros-garantias');
                });
                Route::resource('registros.partes', RegistroFiduciarioParteController::class);
                Route::get('registros/{registro}/pagamentos-tab', [RegistroFiduciarioPagamentoController::class, 'show_tab_pagamentos']);
                Route::get('registros/{registro}/devolutivas-tab', [RegistroFiduciarioNotaDevolutivaController::class, 'show_tab_nota_devolutiva']);
                Route::resource('registros.pagamentos', RegistroFiduciarioPagamentoController::class)->except(['edit']);
                Route::resource('registros.pagamentos.guias', RegistroFiduciarioPagamentoGuiaController::class)->except(['edit','update']);
                Route::resource('registros.reembolsos', RegistroFiduciarioReembolsoController::class)->except(['edit','update', 'destroy']);
                Route::resource('registros.imoveis', RegistroFiduciarioImovelController::class)->parameters([
                    'imoveis' => 'imovel'
                ])->except(['index']);
                Route::resource('registros.comentarios', RegistroFiduciarioComentarioController::class)->except(['show', 'edit', 'update', 'destroy']);
                Route::resource('registros.comentarios-internos', RegistroFiduciarioComentarioInternoController::class)->except(['show', 'edit', 'update', 'destroy']);
                Route::resource('registros.comentarios.arquivos', RegistroFiduciarioComentarioArquivosController::class)->only(['index']);
                Route::resource('registros.devolutivas', RegistroFiduciarioNotaDevolutivaController::class)->except(['destroy']);
                Route::resource('registros.observadores', RegistroFiduciarioObservadorController::class)->except(['edit', 'update']);
                Route::resource('registros.operadores', RegistroFiduciarioOperadorController::class)->except(['edit', 'update']);
                Route::resource('registros.pedidos-central', RegistroFiduciarioPedidoCentralController::class)->only([
                    'edit', 'update'
                ]);
                Route::resource('registros.pedidos-central.historicos', RegistroFiduciarioPedidoCentralHistoricoController::class)->except([
                    'index', 'show', 'destroy'
                ]);

                // Documento eletrônico
                Route::prefix('documentos')->name('documentos.')->group(function () {
                    Route::resource('temp-partes', DocumentoTempParteController::class)->except(['index','show']);
                    Route::resource('temp-partes.procuradores', DocumentoTempParteProcuradorController::class)->parameters([
                         'procuradores' => 'procurador'
                    ]);

                    Route::prefix('{documento}/arquivos')->group(function () {
                        Route::get('/', [DocumentoArquivoController::class, 'arquivos']);
                        Route::put('/', [DocumentoArquivoController::class, 'salvar_arquivos']);
                        Route::delete('/', [DocumentoArquivoController::class, 'remover_arquivo']);
                    });

                    Route::post('{documento}/iniciar-proposta', [DocumentoController::class, 'iniciar_proposta']);
                    Route::post('{documento}/gerar-documentos', [DocumentoController::class, 'gerar_documentos']);
                    Route::post('{documento}/regerar-documentos', [DocumentoController::class, 'regerar_documentos']);
                    Route::post('{documento}/iniciar-assinatura', [DocumentoController::class, 'iniciar_assinatura']);

                    Route::prefix('{documento}/transformar-em-contrato')->group(function () {
                        Route::get('/', [DocumentoController::class, 'transformar_contrato']);
                        Route::post('/', [DocumentoController::class, 'salvar_transformar_contrato']);
                    });

                    Route::prefix('{documento}/reenviar-email')->group(function () {
                        Route::get('/', [DocumentoController::class, 'reenviar_email']);
                        Route::post('/', [DocumentoController::class, 'salvar_reenviar_email']);
                    });

                    Route::prefix('{documento}/vincular-entidade')->group(function () {
                        Route::get('/', [DocumentoController::class, 'vincular_entidade']);
                        Route::post('/', [DocumentoController::class, 'salvar_vincular_entidade']);
                    });

                    Route::get('{documento}/assinaturas/{parte_assinatura}', [DocumentoController::class, 'visualizar_assinatura']);

                    Route::get('{documento}/datas', [DocumentoController::class, 'visualizar_datas']);

                    Route::prefix('{documento}/contrato')->group(function () {
                        Route::get('/editar', [DocumentoContratoController::class, 'edit']);
                        Route::put('/', [DocumentoContratoController::class, 'update']);
                    });
                });

                Route::resource('documentos', DocumentoController::class)->middleware('can:documentos');
                Route::resource('documentos.partes', DocumentoParteController::class);
                Route::resource('documentos.comentarios', DocumentoComentarioController::class)->except(['show', 'edit', 'update', 'destroy']);
                Route::resource('documentos.comentarios.arquivos', DocumentoComentarioArquivosController::class)->only(['index']);
                Route::resource('documentos.observadores', DocumentoObservadorController::class)->except(['edit', 'update']);

                // Calculadora de emolumentos
                Route::prefix('calculadora')->name('calculadora.')->group(function () {
                    Route::get('/', [CalculadoraController::class, 'index'])->name('index');
                    Route::post('/', [CalculadoraController::class, 'calcular'])->name('calcular');
                    Route::get('/tipos-registro', [CalculadoraController::class, 'tipos_registro'])->name('tipos-registro');
                    Route::get('/variaveis', [CalculadoraController::class, 'variaveis'])->name('variaveis');
                });

                // Consulta Vscore
                Route::resource('biometria-lotes', BiometriaLoteController::class)->except(['edit', 'update', 'destroy']);
                Route::prefix('biometria-lotes')->name('biometria-lotes.')->group(function () {
                    Route::post('/{biometria_lote}/reprocessar', [BiometriaLoteController::class, 'reprocessar'])->name('reprocessar');
                    Route::post('/{biometria_lote}/reenviar-notificacao', [BiometriaLoteController::class, 'reenviar_notificacao'])->name('reenviar-notificacao');
                });

                Route::resource('biometrias', BiometriaController::class)->except(['edit', 'update', 'destroy']);
                Route::prefix('biometrias')->name('biometria.')->group(function () {
                    Route::post('/primeirabase', [BiometriaController::class, 'consultar_biometria']);
                    Route::post('/segundabase', [BiometriaController::class, 'consultar_biometria_segundabase']);
                    Route::post('/status', [BiometriaController::class, 'consultar_status']);


                    Route::post('/{biometria}/reprocessar', [BiometriaController::class, 'reprocessar'])->name('reprocessar');
                });
                
            });
        });

        // Rotas do serviço de upload/download de arquivos
        Route::prefix('arquivos')->group(function () {
            Route::post('/novo', [ArquivosController::class, 'novo']);
            Route::post('/inserir', [ArquivosController::class, 'inserir']);
            Route::post('/inserir_multiplos', [ArquivosController::class, 'inserir_multiplos']);
            Route::post('/remover', [ArquivosController::class, 'remover']);
            Route::post('/visualizar', [ArquivosController::class, 'visualizar_arquivo']);
            Route::get('/render/{arquivo_token}/{no_arquivo}', [ArquivosController::class, 'render_arquivo']);
            Route::get('/download/{arquivo_token}/{tipo?}', [ArquivosController::class, 'download_arquivo']);
            Route::post('/assinaturas', [ArquivosController::class, 'assinaturas_arquivo']);

            // Rotas do serviço de assinatura de arquivos
            Route::prefix('assinatura')->group(function () {
                Route::post('/iniciar-lote', [AssinaturaController::class, 'iniciar_lote']);
                Route::post('/retornar-lote', [AssinaturaController::class, 'retornar_lote']);
            });
        });
    });
});

