<?php

use App\Http\Controllers\API\CertificadoraController;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegistroController;
use App\Http\Controllers\API\RegistroArquivoController;
use App\Http\Controllers\API\RegistroPagamentoController;
use App\Http\Controllers\API\RegistroParteController;
use App\Http\Controllers\API\RegistroParteArquivoController;
use App\Http\Controllers\API\RegistroNotaDevolutivaController;
use App\Http\Controllers\API\RegistroCertificadosController;
use App\Http\Controllers\API\RegistroObservadoresController;
use App\Http\Controllers\API\RegistroComentariosController;
use App\Http\Controllers\API\ProcuracaoController;
use App\Http\Controllers\API\CartorioController;
use App\Http\Controllers\API\CredorController;
use App\Http\Controllers\API\BiometriaLoteController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth', [AuthController::class, 'auth']);

Route::group(['middleware' => ['force.json', 'auth:api', 'get.configs.api']], function () {
    // Rotas do produto "Registros"
    Route::prefix('registros/{registro}')->group(function () {
        Route::get('/historico', [RegistroController::class, 'historico']);
        Route::get('/historico-central', [RegistroController::class, 'historico_central']);
        Route::get('/assinaturas', [RegistroController::class, 'assinaturas']);
        Route::get('/itbi-pgto', [RegistroController::class, 'verificarPgtoItbi']);
        Route::put('/itbi-pgto', [RegistroController::class, 'atualizarPgtoItbi']);
                

        Route::post('/iniciar-proposta', [RegistroController::class, 'iniciar_proposta']);
        Route::post('/transformar-contrato', [RegistroController::class, 'transformar_contrato']);
        Route::post('/iniciar-documentacao', [RegistroController::class, 'iniciar_documentacao']);
        Route::post('/iniciar-emissao', [RegistroController::class, 'iniciar_emissao']);

        Route::prefix('pagamentos/{pagamento}')->group(function () {
            Route::prefix('guias/{guia}')->group(function () {
                Route::post('/comprovante', [RegistroPagamentoController::class, 'salvar_comprovante']);
            });
        });
    });

    Route::prefix('nota-devolutiva/{uuid}')->group(function () {
        Route::put('/resposta', [RegistroController::class, 'update_nota_devolutiva']);
    });

    Route::resource('registros', RegistroController::class)->only('store', 'show', 'destroy');
    Route::resource('registros.arquivos', RegistroArquivoController::class)->only('index', 'store', 'show', 'destroy');
    Route::resource('registros.pagamentos', RegistroPagamentoController::class)->only('index', 'show', 'store');
    Route::resource('registros.partes', RegistroParteController::class)->only('index', 'show');
    Route::resource('registros.partes.arquivos', RegistroParteArquivoController::class)->only('index', 'store', 'destroy');
    Route::resource('registros.notas-devolutivas', RegistroNotaDevolutivaController::class)->only('index');
    Route::resource('registros.certificados', RegistroCertificadosController::class)->only('index');
    Route::resource('registros.observadores', RegistroObservadoresController::class)->only('index','store');
    Route::resource('registros.comentarios', RegistroComentariosController::class)->only('index', 'store', 'show');

    Route::resource('procuracoes', ProcuracaoController::class)->only('index');
    Route::resource('cartorios', CartorioController::class)->only('index');
    Route::resource('credores', CredorController::class)->only('index');
    Route::resource('biometria-lotes', BiometriaLoteController::class)->only('index', 'store', 'show');
    
    Route::patch('/certificadora', [CertificadoraController::class, 'update']);
});

Route::post('sistemasul', [CertificadoraController::class, 'sistemasul']); // TODO: Tirar essa linha e o metodo quando a Sistemasul trocar para o novo