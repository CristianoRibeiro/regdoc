<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Services;

use Helper;
use stdClass;
use Storage;
use PDAVH;
use Exception;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\TipoParteRegistroFiduciarioOrdemServiceInterface;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class RegistroFiduciarioAssinaturaService implements RegistroFiduciarioAssinaturaServiceInterface
{
    /**
     * @var RegistroFiduciarioAssinaturaRepositoryInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var RegistroFiduciarioParteAssinaturaServiceInterface
     * @var RegistroFiduciarioParteAssinaturaArquivoServiceInterface
     * @var TipoParteRegistroFiduciarioOrdemServiceInterface
     */
    protected $RegistroFiduciarioAssinaturaRepositoryInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroFiduciarioParteAssinaturaServiceInterface;
    protected $RegistroFiduciarioParteAssinaturaArquivoServiceInterface;
    protected $TipoParteRegistroFiduciarioOrdemServiceInterface;

    /**
     * RegistroFiduciarioAssinaturaService constructor.
     * @param RegistroFiduciarioAssinaturaRepositoryInterface $RegistroFiduciarioAssinaturaRepositoryInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface
     * @param RegistroFiduciarioParteAssinaturaArquivoServiceInterface $RegistroFiduciarioParteAssinaturaArquivoServiceInterface
     * @param TipoParteRegistroFiduciarioOrdemServiceInterface $TipoParteRegistroFiduciarioOrdemServiceInterface
     */
    public function __construct(RegistroFiduciarioAssinaturaRepositoryInterface $RegistroFiduciarioAssinaturaRepositoryInterface,
        RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
        RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface,
        RegistroFiduciarioParteAssinaturaArquivoServiceInterface $RegistroFiduciarioParteAssinaturaArquivoServiceInterface,
        TipoParteRegistroFiduciarioOrdemServiceInterface $TipoParteRegistroFiduciarioOrdemServiceInterface)
    {
        $this->RegistroFiduciarioAssinaturaRepositoryInterface = $RegistroFiduciarioAssinaturaRepositoryInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroFiduciarioParteAssinaturaServiceInterface = $RegistroFiduciarioParteAssinaturaServiceInterface;
        $this->RegistroFiduciarioParteAssinaturaArquivoServiceInterface = $RegistroFiduciarioParteAssinaturaArquivoServiceInterface;
        $this->TipoParteRegistroFiduciarioOrdemServiceInterface = $TipoParteRegistroFiduciarioOrdemServiceInterface;
    }

    /**
     * @param int $registro_fiduciario_assinatura
     * @return registro_fiduciario_assinatura|null
     */
    public function buscar(int $registro_fiduciario_assinatura): ?registro_fiduciario_assinatura
    {
        return $this->RegistroFiduciarioAssinaturaRepositoryInterface->buscar($registro_fiduciario_assinatura);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_assinatura|null
     */
    public function buscar_pdavh_uuid(string $uuid): ?registro_fiduciario_assinatura
    {
        return $this->RegistroFiduciarioAssinaturaRepositoryInterface->buscar_pdavh_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     */
    public function inserir(stdClass $args): registro_fiduciario_assinatura
    {
        return $this->RegistroFiduciarioAssinaturaRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_assinatura $registro_fiduciario_assinatura
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     */
    public function alterar(registro_fiduciario_assinatura $registro_fiduciario_assinatura, stdClass $args): registro_fiduciario_assinatura
    {
        return $this->RegistroFiduciarioAssinaturaRepositoryInterface->alterar($registro_fiduciario_assinatura, $args);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     */
    public function buscar_alterar(stdClass $args): registro_fiduciario_assinatura
    {
        $registro_fiduciario_assinatura = $this->buscar($args->id_registro_fiduciario_assinatura);
        if (!$registro_fiduciario_assinatura)
            throw new Exception('A assinatura não foi encontrada');

        return $this->alterar($registro_fiduciario_assinatura, $args);
    }

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @param int $id_tipo_arquivo_grupo_produto
     * @param int $id_registro_fiduciario_assinatura_tipo
     * @param array $tipos_partes
     * @param array $partes
     * @return registro_fiduciario_assinatura
     */
    public function inserir_assinatura(registro_fiduciario $registro_fiduciario, int $id_tipo_arquivo_grupo_produto, int $id_registro_fiduciario_assinatura_tipo, array $tipos_partes = [], array $partes_ids = [], array $arquivos_ids = [], array $associacao_arquivos_partes = []) : registro_fiduciario_assinatura
    {
        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $arquivos_registro = $registro_fiduciario->arquivos_grupo();
        if ($id_tipo_arquivo_grupo_produto>0) {
            $arquivos_registro = $arquivos_registro->where('id_tipo_arquivo_grupo_produto', $id_tipo_arquivo_grupo_produto);
        }
        if (count($arquivos_ids)>0) {
            $arquivos_registro = $arquivos_registro->whereIn('arquivo_grupo_produto.id_arquivo_grupo_produto', $arquivos_ids);
        }
        $arquivos_registro = $arquivos_registro->get();

        if (count($arquivos_ids)>0) {
            $arquivos_partes = $registro_fiduciario->arquivos_partes();
            if (count($arquivos_ids)>0) {
                $arquivos_partes = $arquivos_partes->whereIn('id_arquivo_grupo_produto', $arquivos_ids);
            }
            $arquivos_partes = $arquivos_partes->get()
                ->pluck('arquivo_grupo_produto');

            $arquivos = $arquivos_registro->merge($arquivos_partes);
        } else {
            $arquivos = $arquivos_registro;
        }

        if (count($arquivos)<0)
            throw new Exception('Nenhum arquivo foi encontrado para iniciar as assinaturas.');

        // Buscar pela ordem de assinaturas
        $args_ordens_assinaturas = new stdClass();
        $args_ordens_assinaturas->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
        $args_ordens_assinaturas->id_registro_fiduciario_assinatura_tipo = $id_registro_fiduciario_assinatura_tipo;
        $args_ordens_assinaturas->id_pessoa = $pedido->id_pessoa_origem;
        $ordens_assinaturas = $this->TipoParteRegistroFiduciarioOrdemServiceInterface->listar($args_ordens_assinaturas);
        if ($ordens_assinaturas) {
            $ordens_assinaturas = $ordens_assinaturas->keyBy('id_tipo_parte_registro_fiduciario')
                ->transform(function ($ordem) {
                    return $ordem->nu_ordem;
                })
                ->toArray();
        }

        // Criar um processo de assinatura
        $args_nova_assinatura = new stdClass();
        $args_nova_assinatura->id_registro_fiduciario_assinatura_tipo = $id_registro_fiduciario_assinatura_tipo;
        $args_nova_assinatura->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
        if ($ordens_assinaturas) {
            $args_nova_assinatura->in_ordem_assinatura = 'S';
            $args_nova_assinatura->nu_ordem_assinatura_atual = array_slice($ordens_assinaturas, 0, 1)[0];
        }

        $nova_assinatura = $this->RegistroFiduciarioAssinaturaRepositoryInterface->inserir($args_nova_assinatura);

        $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte();
        if (count($tipos_partes)>0) {
            $registro_fiduciario_partes = $registro_fiduciario_partes->whereIn('id_tipo_parte_registro_fiduciario', $tipos_partes);
        }
        if (count($partes_ids)>0) {
            $registro_fiduciario_partes = $registro_fiduciario_partes->whereIn('id_registro_fiduciario_parte', $partes_ids);
        }
        $registro_fiduciario_partes = $registro_fiduciario_partes->orderBy('id_tipo_parte_registro_fiduciario', 'ASC')
            ->get();

        $signers = [];
        if (count($registro_fiduciario_partes)>0) {
            foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                // Se a parte for apenas um observador, o sistema pula a parte com o "continue"
                if ($registro_fiduciario_parte->registro_tipo_parte_tipo_pessoa->in_observador=='S') {
                    continue;
                }

                $nu_ordem_assinatura = $ordens_assinaturas[$registro_fiduciario_parte->id_tipo_parte_registro_fiduciario] ?? 0;

                if (count($associacao_arquivos_partes)>0) {
                    $envia_parte = false;
                    foreach ($arquivos as $arquivo) {
                        if (isset($associacao_arquivos_partes[$arquivo->id_arquivo_grupo_produto])) {
                            if (in_array($registro_fiduciario_parte->id_registro_fiduciario_parte,
                                $associacao_arquivos_partes[$arquivo->id_arquivo_grupo_produto])) {
                                $envia_parte = true;
                            }
                        } else {
                            $envia_parte = true;
                        }
                    }

                    if (!$envia_parte) {
                        continue;
                    }
                }

                if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                    foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                        $nu_cpf_cnpj = Helper::somente_numeros($procurador->nu_cpf_cnpj);

                        $args_qualificacao = new stdClass();
                        $args_qualificacao->id_tipo_arquivo_grupo_produto = $id_tipo_arquivo_grupo_produto;
                        $args_qualificacao->in_procurador = 'S';
                        $args_qualificacao->id_pessoa = $pedido->id_pessoa_origem;
                        $args_qualificacao->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;

                        $qualificacao = $this->RegistroFiduciarioParteServiceInterface->definir_qualificacao($registro_fiduciario_parte, $args_qualificacao);

                        $find_signer = array_search($nu_cpf_cnpj, array_column($signers, 'identifier'));
                        if ($find_signer!==false) {
                            $key_signer = array_keys($signers)[$find_signer];

                            $signers[$key_signer]['qualification'] = $signers[$key_signer]['qualification'] . ' / ' . $qualificacao;

                            $signers[$key_signer]['files'] = $signers[$key_signer]['files'] +
                                $this->definir_arquivos($arquivos, $registro_fiduciario_parte, $associacao_arquivos_partes);
                        } else {
                            $nova_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->inserir_parte_assinatura($nova_assinatura, $arquivos, $associacao_arquivos_partes, $registro_fiduciario_parte->id_registro_fiduciario_parte, $procurador->id_registro_fiduciario_procurador, $nu_ordem_assinatura);

                            $index = $registro_fiduciario_parte->id_registro_fiduciario_parte.'.'.$procurador->id_registro_fiduciario_procurador;

                            $signers[$index] = [
                                "code" => $nova_parte_assinatura->id_registro_fiduciario_parte_assinatura,
                                "name" => $procurador->no_procurador,
                                "email" => $procurador->no_email_contato,
                                "identifier" => $nu_cpf_cnpj,
                                'qualification' => $qualificacao,
                                "restrict" => (config('app.env')=='production'?true:false)
                            ];

                            $signers[$index]['files'] = $this->definir_arquivos($arquivos, $registro_fiduciario_parte, $associacao_arquivos_partes);
                        }
                    }
                } else {
                    $nu_cpf_cnpj = Helper::somente_numeros($registro_fiduciario_parte->nu_cpf_cnpj);

                    $args_qualificacao = new stdClass();
                    $args_qualificacao->id_tipo_arquivo_grupo_produto = $id_tipo_arquivo_grupo_produto;
                    $args_qualificacao->in_procurador = 'N';
                    $args_qualificacao->id_pessoa = $pedido->id_pessoa_origem;
                    $args_qualificacao->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;

                    $qualificacao = $this->RegistroFiduciarioParteServiceInterface->definir_qualificacao($registro_fiduciario_parte, $args_qualificacao);

                    $find_signer = array_search($nu_cpf_cnpj, array_column($signers, 'identifier'));
                    if ($find_signer !== false) {
                        $key_signer = array_keys($signers)[$find_signer];
                        $signers[$key_signer]['qualification'] = $signers[$key_signer]['qualification'] . ' / ' . $qualificacao;

                        $signers[$key_signer]['files'] = $signers[$key_signer]['files'] +
                            $this->definir_arquivos($arquivos, $registro_fiduciario_parte, $associacao_arquivos_partes);
                    } else {
                        $nova_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->inserir_parte_assinatura($nova_assinatura, $arquivos, $associacao_arquivos_partes, $registro_fiduciario_parte->id_registro_fiduciario_parte, NULL, $nu_ordem_assinatura);

                        $index = $registro_fiduciario_parte->id_registro_fiduciario_parte;

                        $signers[$index] = [
                            "code" => $nova_parte_assinatura->id_registro_fiduciario_parte_assinatura,
                            "name" => $registro_fiduciario_parte->no_parte,
                            "email" => $registro_fiduciario_parte->no_email_contato,
                            "identifier" => $nu_cpf_cnpj,
                            'qualification' => $qualificacao,
                            "restrict" => (config('app.env')=='production'?true:false)
                        ];

                        $signers[$index]['files'] = $this->definir_arquivos($arquivos, $registro_fiduciario_parte, $associacao_arquivos_partes);
                    }
                }
            }


            $files = [];
            foreach ($arquivos as $arquivo) {
                $arquivo_path = 'public'.$arquivo->no_local_arquivo.'/'.$arquivo->no_arquivo;
                $arquivo_content = Storage::get($arquivo_path);

                $files[] = [
                    'code' => $arquivo->id_arquivo_grupo_produto,
                    'content' => base64_encode($arquivo_content),
                    'filename' => $arquivo->no_descricao_arquivo,
                    'extension' => $arquivo->no_extensao,
                    'mime' => $arquivo->no_mime_type,
                    'hash' => $arquivo->no_hash,
                    'size' => $arquivo->nu_tamanho_kb
                ];
            }


            $signature_process_title = 'Registro nº '.$pedido->protocolo_pedido.' - '.$nova_assinatura->registro_fiduciario_assinatura_tipo->no_tipo;
            $retorno_pdavh = PDAVH::init_signature_process($signature_process_title, $pedido->id_pedido, 1, $files, $signers);

            // Atualizar o processo de assinatura
            $args_atualizar_assinatura = new stdClass();
            $args_atualizar_assinatura->co_process_uuid = $retorno_pdavh->uuid;

            $this->RegistroFiduciarioAssinaturaRepositoryInterface->alterar($nova_assinatura, $args_atualizar_assinatura);

            foreach ($retorno_pdavh->signers as $signer) {
                $args_atualizar_parte_assinatura = new stdClass();
                $args_atualizar_parte_assinatura->id_registro_fiduciario_parte_assinatura = $signer->code;
                $args_atualizar_parte_assinatura->co_process_uuid = $signer->uuid;
                $args_atualizar_parte_assinatura->no_process_url = $signer->url;

                $this->RegistroFiduciarioParteAssinaturaServiceInterface->buscar_alterar($args_atualizar_parte_assinatura);
            }

        }

        return $nova_assinatura;
    }

    private function definir_arquivos($arquivos, $registro_fiduciario_parte, $associacao_arquivos_partes)
    {
        if (count($associacao_arquivos_partes)>0) {
            foreach ($arquivos as $arquivo) {
                if (isset($associacao_arquivos_partes[$arquivo->id_arquivo_grupo_produto])) {
                    if (in_array($registro_fiduciario_parte->id_registro_fiduciario_parte,
                        $associacao_arquivos_partes[$arquivo->id_arquivo_grupo_produto])) {
                        $files[] = $arquivo->id_arquivo_grupo_produto;
                    }
                } else {
                    $files[] = $arquivo->id_arquivo_grupo_produto;
                }
            }
        }

        return $files ?? [];
    }
}
