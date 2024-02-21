<?php
namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use Auth;
use DB;
use URL;
use stdClass;

use App\Exceptions\ArispException;

use SoapParam;
use SoapFault;
use SoapClient;
use SoapVar;

use App\Models\arisp_token;
use App\Models\arisp_transacao;
use App\Models\arisp_arquivo;
use App\Models\arisp_pedido;

class ARISP
{
    public static function enviar_registro($registro_fiduciario, $tipo_integracao, $arquivos_envio)
    {
        $token = self::get_token_envio();

        // Cria o HASH para verificação da ARISP
        $hash = sha1(config('arisp.CHAVE').$token->token);

        // Define a URL
        $URL = self::soap_url('eprotocolo.asmx?wsdl');

        switch (config('arisp.VERSAO')) {
            case 1:
                $versao = '';
                break;
            case 2:
                $versao = '_v2';
                break;
            default:
                throw new Exception('Versão do WS da ARISP não definida');
                break;
        }

        switch ($tipo_integracao) {
            case 'XML':
                // Função de criação de XML do SOAP
                $funcao_xml = 'enviar_registro_xml' . $versao;

                // Funções de envio/retorno do WS
                $funcao = 'InsertPedidoExtratoXMLAC' . $versao;
                $objeto_retorno = 'InsertPedidoExtratoXMLAC' . $versao . 'Result';
                break;
            case 'TITULO_DIGITAL':
                // Função de criação de XML do SOAP
                $funcao_xml = 'enviar_registro_td_xml' . $versao;

                // Funções de envio/retorno do WS
                $funcao = 'InsertPedidoTituloDigitalAC' . $versao;
                $objeto_retorno = 'InsertPedidoTituloDigitalAC' . $versao . 'Result';
                break;
            default:
                throw new Exception('Tipo de integração inválida');
                break;
        }

        // Obtem o XML
        $xml = self::$funcao_xml($hash, $registro_fiduciario, $arquivos_envio);

        if (config('arisp.BYPASS') === true) {
            $retorno = new stdClass();
            $retorno->$objeto_retorno = new stdClass();
            $retorno->$objeto_retorno->RETORNO = TRUE;
            $retorno->$objeto_retorno->ProtocoloTemp = 'PROTOCOLO';
        } else {
            // Faz a chamada à função do WebService
            $retorno = self::soap_enviar($URL, $funcao, $xml);
        }

        // Verifica os resultados
        if ($retorno->$objeto_retorno->RETORNO==TRUE) {
            $response = [
                'status' => 'sucesso',
                'protocolo_temporario' => $retorno->$objeto_retorno->ProtocoloTemp
            ];

            $transacao = self::criar_transacao($token, $URL, $xml, $funcao, 'sucesso', $retorno);

            $arisp_pedido = self::criar_pedido($registro_fiduciario->registro_fiduciario_pedido->pedido, $transacao, $retorno->$objeto_retorno->ProtocoloTemp);
        } else {
            self::criar_transacao($token, $URL, $xml, $funcao, 'erro', $retorno, $retorno->$objeto_retorno->CODIGOERRO, $retorno->$objeto_retorno->ERRODESCRICAO);

            throw new ArispException($retorno->$objeto_retorno->ERRODESCRICAO);
        }

        return $arisp_pedido;
    }

    private static function enviar_registro_xml($hash, $registro_fiduciario, $arquivos_envio)
    {
        $id_cartorio = $registro_fiduciario->serventia_ri->id_cartorio_arisp;

        $adquirente = $registro_fiduciario->registro_fiduciario_parte
                                          ->where('id_tipo_parte_registro_fiduciario', config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'))
                                          ->first();

        $arquivo_xml = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', 34)->orderBy('dt_cadastro', 'DESC')->first();
        if ($arquivo_xml->arisp_arquivo) {
            //$arisp_arquivo_xml_url = 'https://homologacao.regdoc.com.br/xml_0621.0780.2925.0001.xml';
            $arisp_arquivo_xml_url = URL::to('/arisp/download/'.$arquivo_xml->arisp_arquivo->codigo_arquivo.'/'.$arquivo_xml->no_arquivo);
        } else {
            throw new Exception('O arquivo para download da ARISP não existe.');
        }

        //$url_notificacao = 'https://webhook.site/a74289b8-903a-4d56-9bbe-f75244e1de7f/notificacao';
        $url_notificacao = URL::to('/arisp/notificacao');

        // Anexos
        $anexos = self::array_anexos($registro_fiduciario, 'InsertPedidoExtratoXML_Anexo_WSReq', $arquivos_envio);

        return '<ns1:InsertPedidoExtratoXMLAC>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                        <ns1:IDCartorio>'.$id_cartorio.'</ns1:IDCartorio>
                        <ns1:IDTipoServico>3</ns1:IDTipoServico>
                        <ns1:URLArquivoXML>'.$arisp_arquivo_xml_url.'</ns1:URLArquivoXML>
                        <ns1:BoletoSacado></ns1:BoletoSacado>
                        <ns1:BoletoCPF></ns1:BoletoCPF>
                        <ns1:BoletoEndereco></ns1:BoletoEndereco>
                        <ns1:BoletoNumero></ns1:BoletoNumero>
                        <ns1:BoletoComplemento></ns1:BoletoComplemento>
                        <ns1:BoletoBairro></ns1:BoletoBairro>
                        <ns1:BoletoCidade></ns1:BoletoCidade>
                        <ns1:BoletoCEP></ns1:BoletoCEP>
                        <ns1:BoletoUF></ns1:BoletoUF>
                        <ns1:EmailNotificacao>regdoc@valid.com</ns1:EmailNotificacao>
                        <ns1:URLNotificacao>'.$url_notificacao.'</ns1:URLNotificacao>
                        <ns1:Anexos>'.implode('', $anexos).'</ns1:Anexos>
                    </ns1:oRequest>
                </ns1:InsertPedidoExtratoXMLAC>';
    }

    private static function enviar_registro_xml_v2($hash, $registro_fiduciario, $arquivos_envio)
    {
        $id_cartorio = $registro_fiduciario->serventia_ri->id_cartorio_arisp;

        $adquirente = $registro_fiduciario->registro_fiduciario_parte
                                          ->where('id_tipo_parte_registro_fiduciario', config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'))
                                          ->first();

        $arquivo_xml = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', 34)->orderBy('dt_cadastro', 'DESC')->first();
        if ($arquivo_xml->arisp_arquivo) {
            //$arisp_arquivo_xml_url = 'https://homologacao.regdoc.com.br/xml_0621.0780.2925.0001.xml';
            $arisp_arquivo_xml_url = URL::to('/arisp/download/'.$arquivo_xml->arisp_arquivo->codigo_arquivo.'/'.$arquivo_xml->no_arquivo);
        } else {
            throw new Exception('O arquivo para download da ARISP não existe para o XML.');
        }

        //$url_notificacao = 'https://webhook.site/a74289b8-903a-4d56-9bbe-f75244e1de7f/notificacao';
        $url_notificacao = URL::to('/arisp/notificacao');

        // Anexos
        $anexos = self::array_anexos($registro_fiduciario, 'InsertPedidoExtratoXML_Anexo_WSReq', $arquivos_envio);

        return '<ns1:InsertPedidoExtratoXMLAC_v2>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                        <ns1:IDCartorio>'.$id_cartorio.'</ns1:IDCartorio>
                        <ns1:IDTipoServico>3</ns1:IDTipoServico>
                        <ns1:URLArquivoXML>'.$arisp_arquivo_xml_url.'</ns1:URLArquivoXML>
                        <ns1:BoletoSacado></ns1:BoletoSacado>
                        <ns1:BoletoCPF></ns1:BoletoCPF>
                        <ns1:BoletoEndereco></ns1:BoletoEndereco>
                        <ns1:BoletoNumero></ns1:BoletoNumero>
                        <ns1:BoletoComplemento></ns1:BoletoComplemento>
                        <ns1:BoletoBairro></ns1:BoletoBairro>
                        <ns1:BoletoCidade></ns1:BoletoCidade>
                        <ns1:BoletoCEP></ns1:BoletoCEP>
                        <ns1:BoletoUF></ns1:BoletoUF>
                        <ns1:EmailNotificacao>regdoc@valid.com</ns1:EmailNotificacao>
                        <ns1:URLNotificacao>'.$url_notificacao.'</ns1:URLNotificacao>
                        <ns1:Anexos>'.implode('', $anexos).'</ns1:Anexos>
                        <ns1:ProtocolosRCDE></ns1:ProtocolosRCDE>
                        <ns1:NomeRequerente>'.config('arisp.NOME').'</ns1:NomeRequerente>
                        <ns1:EmailRequerente>'.config('arisp.EMAIL').'</ns1:EmailRequerente>
                        <ns1:CPFRequerente>'.config('arisp.CPF').'</ns1:CPFRequerente>
                    </ns1:oRequest>
                </ns1:InsertPedidoExtratoXMLAC_v2>';
    }

    private static function enviar_registro_td_xml_v2($hash, $registro_fiduciario, $arquivos_envio)
    {
        $id_cartorio = $registro_fiduciario->serventia_ri->id_cartorio_arisp;
        $apresentante = $registro_fiduciario->registro_fiduciario_apresentante;

        //$url_notificacao = 'https://webhook.site/a74289b8-903a-4d56-9bbe-f75244e1de7f/notificacao';
        $url_notificacao = URL::to('/arisp/notificacao');

        // Anexos
        $anexos = self::array_anexos($registro_fiduciario, 'InsertPedidoTituloDigital_Anexo_WSReq', $arquivos_envio);

        return '<ns1:InsertPedidoTituloDigitalAC_v2>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                        <ns1:IDCartorio>'.$id_cartorio.'</ns1:IDCartorio>
                        <ns1:IDTipoServico>4</ns1:IDTipoServico>
                        <ns1:DataTitulo></ns1:DataTitulo>
                        <ns1:Livro></ns1:Livro>
                        <ns1:Folha></ns1:Folha>
                        <ns1:Nome>'.Helper::cortar_string($apresentante->no_apresentante, 300).'</ns1:Nome>
                        <ns1:CPFCNPJ>'.Helper::somente_numeros($apresentante->nu_cpf_cnpj).'</ns1:CPFCNPJ>
                        <ns1:DDDTelefone>'.Helper::somente_numeros($apresentante->nu_ddd).'</ns1:DDDTelefone>
                        <ns1:Telefone>'.Helper::somente_numeros($apresentante->nu_telefone).'</ns1:Telefone>
                        <ns1:Email>'.Helper::cortar_string($apresentante->no_email_contato, 60).'</ns1:Email>
                        <ns1:CEP>'.Helper::somente_numeros($apresentante->nu_cep, 8).'</ns1:CEP>
                        <ns1:IDVia>4</ns1:IDVia>
                        <ns1:Logradouro>'.Helper::cortar_string($apresentante->no_endereco, 120).'</ns1:Logradouro>
                        <ns1:Numero>'.Helper::cortar_string($apresentante->nu_endereco, 10).'</ns1:Numero>
                        <ns1:Complemento></ns1:Complemento>
                        <ns1:Bairro>'.Helper::cortar_string($apresentante->no_bairro, 50).'</ns1:Bairro>
                        <ns1:UF>'.Helper::cortar_string($apresentante->cidade->estado->uf, 2).'</ns1:UF>
                        <ns1:Cidade>'.Helper::cortar_string($apresentante->cidade->no_cidade, 50).'</ns1:Cidade>
                        <ns1:BoletoSacado></ns1:BoletoSacado>
                        <ns1:BoletoCPF></ns1:BoletoCPF>
                        <ns1:BoletoEndereco></ns1:BoletoEndereco>
                        <ns1:BoletoNumero></ns1:BoletoNumero>
                        <ns1:BoletoComplemento></ns1:BoletoComplemento>
                        <ns1:BoletoBairro></ns1:BoletoBairro>
                        <ns1:BoletoCidade></ns1:BoletoCidade>
                        <ns1:BoletoCEP></ns1:BoletoCEP>
                        <ns1:BoletoUF></ns1:BoletoUF>
                        <ns1:URLNotificacao>'.$url_notificacao.'</ns1:URLNotificacao>
                        <ns1:EmailNotificacao>regdoc@valid.com</ns1:EmailNotificacao>
                        <ns1:Anexos>'.implode('', $anexos).'</ns1:Anexos>
                        <ns1:ProtocolosRCDE></ns1:ProtocolosRCDE>
                        <ns1:NomeRequerente>'.config('arisp.NOME').'</ns1:NomeRequerente>
                        <ns1:EmailRequerente>'.config('arisp.EMAIL').'</ns1:EmailRequerente>
                        <ns1:CPFRequerente>'.config('arisp.CPF').'</ns1:CPFRequerente>
                    </ns1:oRequest>
                </ns1:InsertPedidoTituloDigitalAC_v2>';
    }

    private static function array_anexos($registro_fiduciario, $tag_soap, $arquivos_envio)
    {
        // Anexos
        $anexos = [];

        $arquivos_registro = $registro_fiduciario->arquivos_grupo()
                                                 ->whereNotIn('id_tipo_arquivo_grupo_produto', [
                                                    config('constants.TIPO_ARQUIVO.11.ID_XML_CONTRATO')
                                                 ])
                                                 ->get();
        $arquivos_partes = $registro_fiduciario->arquivos_partes->pluck('arquivo_grupo_produto');

        $arquivos_pagamentos = [];
        foreach ($registro_fiduciario->registro_fiduciario_pagamentos as $registro_fiduciario_pagamento) {
            if ($registro_fiduciario_pagamento->arquivo_grupo_produto) {
                $arquivos_pagamentos[] = $registro_fiduciario_pagamento->arquivo_grupo_produto;
            }
            foreach ($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia as $registro_fiduciario_pagamento_guia) {
                $arquivos_pagamentos[] = $registro_fiduciario_pagamento_guia->arquivo_grupo_produto_guia;
                if ($registro_fiduciario_pagamento_guia->arquivo_grupo_produto_comprovante) {
                    $arquivos_pagamentos[] = $registro_fiduciario_pagamento_guia->arquivo_grupo_produto_comprovante;
                }
            }
        }

        $arquivos = $arquivos_registro->merge($arquivos_partes);
        $arquivos = $arquivos->merge($arquivos_pagamentos);

        foreach ($arquivos as $arquivo) {
            if (in_array($arquivo->id_arquivo_grupo_produto, $arquivos_envio)) {
                if (!$arquivo->arisp_arquivo)
                    throw new Exception('Erro ao obter o anexo da ARISP.');

                if (strlen($arquivo->no_descricao_arquivo)>40) {
                    $nome_arquivo = substr($arquivo->no_descricao_arquivo, 0, 40).'.'.$arquivo->no_extensao;
                } else {
                    $nome_arquivo = $arquivo->no_descricao_arquivo;
                }

                //$arisp_arquivo_url = 'https://webhook.site/a74289b8-903a-4d56-9bbe-f75244e1de7f/'.$nome_arquivo;
                $arisp_arquivo_url = URL::to('/arisp/download/'.$arquivo->arisp_arquivo->codigo_arquivo.'/'.$nome_arquivo);

                $anexos[] = '<ns1:'.$tag_soap.'>
                        <ns1:Descricao>'.$nome_arquivo.'</ns1:Descricao>
                        <ns1:URLArquivo>'.$arisp_arquivo_url.'</ns1:URLArquivo>
                </ns1:'.$tag_soap.'>';
            }
        }

        return $anexos;
    }

    public static function reenviar_pedido($id_pedido)
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('eprotocolo.asmx?wsdl');

            // Obtem o XML
            $xml = self::reenviar_pedido_xml($hash, $id_pedido);

            // Faz a chamada à função "InsertReenvioPedidoAC" do WebService
            $retorno = self::soap_enviar($URL, 'InsertReenvioPedidoAC', $xml);

            // Verifica os resultados
            if ($retorno->InsertReenvioPedidoACResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'protocolo_temporario' => $retorno->InsertReenvioPedidoACResult->ProtocoloTemp
                ];

                self::criar_transacao($token, $URL, $xml, 'InsertReenvioPedidoAC', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'InsertReenvioPedidoAC', 'erro', $retorno, $retorno->InsertReenvioPedidoACResult->CODIGOERRO, $retorno->InsertReenvioPedidoACResult->ERRODESCRICAO);

                throw new ArispException($retorno->InsertReenvioPedidoACResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }

    private static function reenviar_pedido_xml($hash, $id_pedido)
    {
        return '<ns1:InsertReenvioPedidoAC>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                        <ns1:IDPedido>'.$id_pedido.'</ns1:IDPedido>
                        <ns1:EmailNotificacao>regdoc@valid.com</ns1:EmailNotificacao>
                        <ns1:URLNotificacao>https://webhook.site/5325272b-dcd4-472f-b1be-4ca907104000</ns1:URLNotificacao>
                        <ns1:Anexos>
                            <ns1:InsertReenvioPedidoAC_Anexo_WSReq>
                                <ns1:Descricao>Teste POST HASH 2</ns1:Descricao>
                                <ns1:URLArquivo>https://webhook.site/5325272b-dcd4-472f-b1be-4ca907104000/teste2.xml</ns1:URLArquivo>
                            </ns1:InsertReenvioPedidoAC_Anexo_WSReq>
                        </ns1:Anexos>
                    </ns1:oRequest>
                </ns1:InsertReenvioPedidoAC>';
    }

    public static function consulta_pedido($id_pedido)
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('eprotocolo.asmx?wsdl');

            // Obtem o XML
            $xml = self::consulta_pedido_xml($hash, $id_pedido);

            // Faz a chamada à função "GetPedidoAC" do WebService
            $retorno = self::soap_enviar($URL, 'GetPedidoAC', $xml);

            // Verifica os resultados
            if ($retorno->GetPedidoACResult->RETORNO==TRUE || ($retorno->GetPedidoACResult->CODIGOERRO ?? 0)==42) {
                $response = [
                    'status' => 'sucesso',
                    'pedido' => [
                        'id' => $retorno->GetPedidoACResult->IDPedido,
                        'id_status' => $retorno->GetPedidoACResult->IDStatus,
                        'id_cartorio' => $retorno->GetPedidoACResult->IDCartorio,
                        'protocolo' => $retorno->GetPedidoACResult->Protocolo,
                        'data_pedido' => $retorno->GetPedidoACResult->DataPedido,
                        'id_tipo_servico' => $retorno->GetPedidoACResult->IDTipoServico,
                        'numero_prenotacao' => $retorno->GetPedidoACResult->NumeroPrenotacao,
                        'data_prenotacao' => $retorno->GetPedidoACResult->DataPrenotacao,
                        'data_vencto_prenotacao' => $retorno->GetPedidoACResult->DataVencPrenotacao,
                        'data_exame_calculo' => $retorno->GetPedidoACResult->DataExameCalculo,
                        'vl_taxa_admin' => $retorno->GetPedidoACResult->VlTaxaAdmin,
                        'vl_taxa_iss' => $retorno->GetPedidoACResult->VlTaxaISS,
                        'vl_prenotacao' => $retorno->GetPedidoACResult->VlPrenotacao,
                        'vl_exame_calculo' => $retorno->GetPedidoACResult->VLExameCalculo,
                        'vl_total' => $retorno->GetPedidoACResult->VLTotal,
                        'vl_registro' => $retorno->GetPedidoACResult->VLRegistro,
                        'data_resposta' => $retorno->GetPedidoACResult->DataResposta,
                        'resposta' => $retorno->GetPedidoACResult->Resposta,
                        'anexos' => $retorno->GetPedidoACResult->Anexos->GetPedidoAC_Anexos_WSResp,
                        'anexos_averbacao' => $retorno->GetPedidoACResult->AnexosAverbacao->GetPedidoAC_AnexosAverbacao_WSResp ?? [],
                        'anexos_exigencia' => $retorno->GetPedidoACResult->AnexosExigencia->GetPedidoAC_AnexosExigencia_WSResp ?? [],
                        'boletos' => $retorno->GetPedidoACResult->Boletos->GetPedidoAC_Boletos_WSResp ?? []
                    ]
                ];

                self::criar_transacao($token, $URL, $xml, 'GetPedidoAC', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'GetPedidoAC', 'erro', $retorno, $retorno->GetPedidoACResult->CODIGOERRO, $retorno->GetPedidoACResult->ERRODESCRICAO);

                throw new ArispException($retorno->GetPedidoACResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }

    private static function consulta_pedido_xml($hash, $id_pedido)
    {
        return '<ns1:GetPedidoAC>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                        <ns1:IDPedido>'.$id_pedido.'</ns1:IDPedido>
                    </ns1:oRequest>
                </ns1:GetPedidoAC>';
    }

    public static function listar_pedidos_status()
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('eprotocolo.asmx?wsdl');

            // Obtem o XML
            $xml = self::listar_pedidos_status_xml($hash);

            // Faz a chamada à função "ListPedidosStatusAC" do WebService
            $retorno = self::soap_enviar($URL, 'ListPedidosStatusAC', $xml);

            // Verifica os resultados
            if ($retorno->ListPedidosStatusACResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'pedidos' => $retorno->ListPedidosStatusACResult->Pedidos->ListPedidosStatusAC_Pedido_WSResp
                ];

                self::criar_transacao($token, $URL, $xml, 'GetPedidoAC', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'GetPedidoAC', 'erro', $retorno, $retorno->ListPedidosStatusACResult->CODIGOERRO, $retorno->ListPedidosStatusACResult->ERRODESCRICAO);

                throw new ArispException($retorno->ListPedidosStatusACResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }

    private static function listar_pedidos_status_xml($hash)
    {
        return '<ns1:ListPedidosStatusAC>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                    </ns1:oRequest>
                </ns1:ListPedidosStatusAC>';
    }

    public static function verifica_hash($hash)
    {
        // Define a URL
        $URL = self::soap_url('token.asmx?wsdl');

        // Obtem o XML
        $xml = self::verifica_hash_xml($hash);

        // Faz a chamada à função "GetToken" do WebService
        $retorno = self::soap_enviar($URL, 'GetToken', $xml);

        // Verifica os resultados
        if ($retorno->GetTokenResult->Retorno==TRUE) {
            $transacao = self::criar_transacao(NULL, $URL, $xml, 'GetToken', 'sucesso', $retorno);

            $response = [
                'status' => 'sucesso',
                'token' => $retorno->GetTokenResult->Token,
                'transacao' => $transacao
            ];
        } else {
            $transacao = self::criar_transacao(NULL, $URL, $xml, 'GetToken', 'erro', $retorno, $retorno->GetTokenResult->CODIGOERRO, $retorno->GetTokenResult->ERRODESCRICAO);

            throw new ArispException($retorno->GetTokenResult->ERRODESCRICAO);
        }

        return $response;
    }
    
    private static function verifica_hash_xml($hash)
    {
        return '<ns1:GetToken>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                    </ns1:oRequest>
                </ns1:GetToken>';
    }

    
    public static function listar_status()
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('eprotocolo.asmx?wsdl');

            // Obtem o XML
            $xml = self::listar_status_xml($hash);

            // Faz a chamada à função "ListStatusAC" do WebService
            $retorno = self::soap_enviar($URL, 'ListStatusAC', $xml);

            // Verifica os resultados
            if ($retorno->ListStatusACResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'status' => $retorno->ListStatusACResult->Status->ListStatusAC_Status_WSResp
                ];

                self::criar_transacao($token, $URL, $xml, 'ListStatusAC', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'ListStatusAC', 'erro', $retorno, $retorno->ListStatusACResult->CODIGOERRO, $retorno->ListStatusACResult->ERRODESCRICAO);

                throw new ArispException($retorno->ListStatusACResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }
    private static function listar_status_xml($hash)
    {
        return '<ns1:ListStatusAC>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                    </ns1:oRequest>
                </ns1:ListStatusAC>';
    }

    public static function listar_estados()
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('estados.asmx?wsdl');

            // Obtem o XML
            $xml = self::listar_estados_xml($hash);

            // Faz a chamada à função "EstadosListar" do WebService
            $retorno = self::soap_enviar($URL, 'EstadosListar', $xml);

            // Verifica os resultados
            if ($retorno->EstadosListarResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'estados' => $retorno->EstadosListarResult->Estados->Estado_WSResp
                ];

                self::criar_transacao($token, $URL, $xml, 'EstadosListar', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'EstadosListar', 'erro', $retorno, $retorno->EstadosListarResult->CODIGOERRO, $retorno->EstadosListarResult->ERRODESCRICAO);

                throw new ArispException($retorno->EstadosListarResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }

    private static function listar_estados_xml($hash)
    {
        return '<ns1:EstadosListar>
                    <ns1:oRequest>
                        <ns1:TipoServico>5</ns1:TipoServico>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                    </ns1:oRequest>
                </ns1:EstadosListar>';
    }

    public static function listar_cidades($id_estado=0)
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('cidades.asmx?wsdl');

            // Obtem o XML
            $xml = self::listar_cidades_xml($hash, $id_estado);

            // Faz a chamada à função "CidadesListar" do WebService
            $retorno = self::soap_enviar($URL, 'CidadesListar', $xml);

            // Verifica os resultados
            if ($retorno->CidadesListarResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'cidades' => $retorno->CidadesListarResult->Cidades->Cidade_WSResp
                ];

                self::criar_transacao($token, $URL, $xml, 'CidadesListar', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'CidadesListar', 'erro', $retorno, $retorno->CidadesListarResult->CODIGOERRO, $retorno->CidadesListarResult->ERRODESCRICAO);

                throw new ArispException($retorno->CidadesListarResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }
    private static function listar_cidades_xml($hash, $id_estado)
    {
        return '<ns1:CidadesListar>
                    <ns1:oRequest>
                        <ns1:TipoServico>5</ns1:TipoServico>
                        <ns1:IDEstado>'.$id_estado.'</ns1:IDEstado>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                    </ns1:oRequest>
                </ns1:CidadesListar>';
    }

    public static function listar_cartorios($id_estado=0, $id_cidade=0)
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('cartorios.asmx?wsdl');

            // Obtem o XML
            $xml = self::listar_cartorios_xml($hash, $id_estado, $id_cidade);

            // Faz a chamada à função "CartoriosListar" do WebService
            $retorno = self::soap_enviar($URL, 'CartoriosListar', $xml);

            // Verifica os resultados
            if ($retorno->CartoriosListarResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'cartorios' => $retorno->CartoriosListarResult->Cartorios->Cartorio_WSResp
                ];

                self::criar_transacao($token, $URL, $xml, 'CartoriosListar', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'CartoriosListar', 'erro', $retorno, $retorno->CartoriosListarResult->CODIGOERRO, $retorno->CartoriosListarResult->ERRODESCRICAO);

                throw new ArispException($retorno->CartoriosListarResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }
    
    private static function listar_cartorios_xml($hash, $id_estado, $id_cidade)
    {
        return '<ns1:CartoriosListar>
                    <ns1:oRequest>
                        <ns1:Hash>'.$hash.'</ns1:Hash>
                        <ns1:TipoServico>5</ns1:TipoServico>
                        <ns1:IDEstado>'.$id_estado.'</ns1:IDEstado>
                        <ns1:IDCidade>'.$id_cidade.'</ns1:IDCidade>
                    </ns1:oRequest>
                </ns1:CartoriosListar>';
    }

    public static function listar_vias()
    {
        DB::beginTransaction();

        try {
            // Obtem um Token válido para uso
            $token = self::get_token_envio();

            // Cria o HASH para verificação da ARISP
            $hash = sha1(config('arisp.CHAVE').$token->token);

            // Define a URL
            $URL = self::soap_url('vias.asmx?wsdl');

            // Faz a chamada à função "ViasListar" do WebService
            $retorno = self::soap_enviar($URL, 'ViasListar');

            // Verifica os resultados
            if ($retorno->ViasListarResult->RETORNO==TRUE) {
                $response = [
                    'status' => 'sucesso',
                    'vias' => $retorno->ViasListarResult->Vias->Vias_WSResp
                ];

                self::criar_transacao($token, $URL, $xml, 'ViasListar', 'sucesso', $retorno);
            } else {
                self::criar_transacao($token, $URL, $xml, 'ViasListar', 'erro', $retorno, $retorno->ViasListarResult->CODIGOERRO, $retorno->ViasListarResult->ERRODESCRICAO);

                throw new ArispException($retorno->ViasListarResult->ERRODESCRICAO);
            }

            DB::commit();

            return $response;
        } catch(SoapFault $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        } catch(ArispException $e) {
            DB::commit();

            return [
                'status' => 'erro',
                'message' => 'ARISP: '.$e->getMessage()
            ];
        } catch(Exception $e) {
            DB::rollback();

            return [
                'status' => 'erro',
                'message' => 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
        }
    }

    private static function login()
    {
        $URL = self::soap_url('logincliente.asmx?wsdl');

        // Obtem o XML
        $xml = self::login_xml();

        // Faz a chamada à função "LoginClienteConvenio" do WebService
        $retorno = self::soap_enviar($URL, 'LoginClienteConvenio', $xml);

        if ($retorno->LoginClienteConvenioResult->RETORNO==TRUE) {
            if ($retorno->LoginClienteConvenioResult->Ativo==true) {
                self::criar_transacao(NULL, $URL, $xml, 'LoginClienteConvenio', 'sucesso', $retorno);
                return $retorno->LoginClienteConvenioResult->Tokens->string;
            } else {
                self::criar_transacao(NULL, $URL, $xml, 'LoginClienteConvenio', 'erro', $retorno, $retorno->LoginClienteConvenioResult->CODIGOERRO ?? '', $retorno->LoginClienteConvenioResult->ERRODESCRICAO ?? '');
                throw new ArispException('O parceiro não está ativo.');
            }
        } else {
            self::criar_transacao(NULL, $URL, $xml, 'LoginClienteConvenio', 'erro', $retorno, $retorno->LoginClienteConvenioResult->CODIGOERRO ?? '', $retorno->LoginClienteConvenioResult->ERRODESCRICAO ?? '');
            throw new ArispException($retorno->LoginClienteConvenioResult->ERRODESCRICAO);
        }
    }

    private static function login_xml()
    {
        return '<ns1:LoginClienteConvenio>
                    <ns1:oRequest>
                        <ns1:Email>'.config('arisp.EMAIL').'</ns1:Email>
                        <ns1:CPF>'.config('arisp.CPF').'</ns1:CPF>
                        <ns1:IDParceiro>'.config('arisp.IDPARCEIRO').'</ns1:IDParceiro>
                    </ns1:oRequest>
                </ns1:LoginClienteConvenio>';
    }

    private static function get_token_envio()
    {
        if (config('arisp.BYPASS') === true) {
            // Criar Token falso para enviar com Bypass
            $novo_arisp_token = new arisp_token();
            $novo_arisp_token->token = 'BYPASS';
            $novo_arisp_token->dt_validade = Carbon::now()->addDays(4);
            $novo_arisp_token->in_token_utilizado = 'S';
            $novo_arisp_token->dt_token_utilizado = Carbon::now();
            $novo_arisp_token->id_usuario_cad = (Auth::check()?Auth::User()->id_usuario:1);
            $novo_arisp_token->dt_cadastro = Carbon::now();

            if (!$novo_arisp_token->save())
                throw new Exception('Erro ao salvar o token para uso posterior.');

            return $novo_arisp_token;
        } else {
            // Verifica se existem tokens disponíveis para uso
            $token_valido = new arisp_token();
            $token_valido = $token_valido->where('dt_validade', '>', Carbon::now())
                                         ->where('in_token_utilizado', 'N')
                                         ->orderBy('dt_cadastro', 'ASC')
                                         ->first();

            if ($token_valido) {
                $token_valido->in_token_utilizado = 'S';
                $token_valido->dt_token_utilizado = Carbon::now();
                if (!$token_valido->save()) {
                    throw new Exception('Erro ao salvar a utilização do Token.');
                }

                return $token_valido;
            } else {
                // Realiza um novo login no WebService da ARISP e salva para uso posteriores
                $primeiro_token = NULL;

                $login = self::login();
                if (is_array($login) || is_object($login)) {
                    $tokens = $login;
                } else {
                    $tokens = [$login];
                }
                foreach ($tokens as $key => $token) {
                    $novo_arisp_token = new arisp_token();
                    $novo_arisp_token->token = $token;
                    $novo_arisp_token->dt_validade = Carbon::now()->addDays(4);
                    if ($key==0) {
                        $novo_arisp_token->in_token_utilizado = 'S';
                        $novo_arisp_token->dt_token_utilizado = Carbon::now();
                    } else {
                        $novo_arisp_token->in_token_utilizado = 'N';
                    }
                    $novo_arisp_token->id_usuario_cad = (Auth::check()?Auth::User()->id_usuario:1);
                    $novo_arisp_token->dt_cadastro = Carbon::now();

                    if (!$novo_arisp_token->save())
                        throw new Exception('Erro ao salvar o token para uso posterior.');

                    if ($key==0)
                        $primeiro_token = $novo_arisp_token;
                }
                return $primeiro_token;
            }
        }
    }

    private static function criar_transacao($token, $URL, $xml, $funcao, $status, $retorno, $codigo_erro=NULL, $mensagem_erro=NULL)
    {
        $nova_arisp_transacao = new arisp_transacao();
        $nova_arisp_transacao->id_arisp_token = ($token?$token->id_arisp_token:NULL);
        $nova_arisp_transacao->url_envio = $URL;
        $nova_arisp_transacao->xml_enviado = $xml;
        $nova_arisp_transacao->funcao = $funcao;
        $nova_arisp_transacao->situacao_transacao = $status;
        $nova_arisp_transacao->retorno = json_encode($retorno);
        $nova_arisp_transacao->codigo_erro = $codigo_erro;
        $nova_arisp_transacao->mensagem_erro = $mensagem_erro;
        $nova_arisp_transacao->id_usuario_cad = (Auth::check()?Auth::User()->id_usuario:1);
        $nova_arisp_transacao->dt_cadastro = Carbon::now();

        if ($nova_arisp_transacao->save()) {
            return $nova_arisp_transacao;
        } else {
            throw new Exception('Erro ao salvar a transação.');
        }
    }

    private static function criar_pedido($pedido, $transacao, $protocolo_temporario)
    {
        $novo_arisp_pedido = new arisp_pedido();
        $novo_arisp_pedido->id_pedido = $pedido->id_pedido;
        $novo_arisp_pedido->id_arisp_transacao = $transacao->id_arisp_transacao;
        $novo_arisp_pedido->protocolo_temporario = $protocolo_temporario;
        $novo_arisp_pedido->id_usuario_cad = Auth::User()->id_usuario;
        $novo_arisp_pedido->dt_cadastro = Carbon::now();

        if ($novo_arisp_pedido->save()) {
            return $novo_arisp_pedido;
        } else {
            throw new Exception('Erro ao salvar a pedido da ARISP.');
        }
    }

    private static function soap_url($endpoint)
    {
        if (config('app.env')=='production') {
            return 'http://ws.arisp.com.br/'.$endpoint;
        } else {
            return 'http://cnab.arisp.com.br/'.$endpoint;
        }
    }

    private static function soap_conector($URL)
    {
        return new SoapClient($URL, [
            'soap_version'=> SOAP_1_2,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true
        ]);
    }

    private static function soap_enviar($URL, $funcao, $xml='')
    {
        $SOAP = self::soap_conector($URL);
        $soap_xml = new SoapVar($xml, XSD_ANYXML);
        return $SOAP->__soapCall($funcao, array($soap_xml));
    }
}
