<table>
    <thead>
        <tr>
            <th>Protocolo</th>
            <th>Instituição financeira</th>
            <th>Credor fiduciário</th>
            <th>Contrato</th>
            <th>Proposta</th>
            <th>Empreendimento</th>
            <th>Número da unidade</th>
            <th>Cartório</th>
            <th>Cidade (Cartório)</th>
            <th>UF (Cartório)</th>
            <th>Imóveis</th>
            <th>Parte(s)</th>
            <th>Situação</th>
            <th>Operador</th>
            <th>Data da última atualização</th>
            <th>Data de cadastro</th>
            <th>Data de assinatura do contrato</th>
            <th>Data de processamento</th>
            <th>Data de registro</th>
            <th>Data da prenotação</th>
            <th>Data de recebimento da Nota devolutiva</th>
            <th>Data de reingresso da Nota devolutiva</th>
            <th>Tipo do Registro</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($todos_registros as $registro)
            <tr>
                <td>{{$registro->registro_fiduciario_pedido->pedido->protocolo_pedido}}</td>
                <td>{{$registro->registro_fiduciario_pedido->pedido->pessoa_origem->no_pessoa}}</td>
                <td>{{$registro->registro_fiduciario_operacao->registro_fiduciario_credor->no_credor ?? '-'}}</td>
                <td>{{$registro->nu_contrato}}</td>
                <td>{{$registro->nu_proposta}}</td>
                <td>{{$registro->empreendimento->no_empreendimento ?? NULL}}</td>
                <td>{{$registro->nu_unidade_empreendimento ?? NULL}}</td>
                <td> <!-- Nome do cartorio -->
                    @switch($registro->registro_fiduciario_pedido->pedido->id_produto)
                        @case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
                            {{$registro->serventia_ri->pessoa->no_pessoa ?? '-'}}
                            @break
                        @case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
                            {{$registro->serventia_nota->pessoa->no_pessoa ?? '-'}}
                            @break
                    @endswitch
                </td>
                <td> <!-- cidade do cartorio -->
                    {{$registro->serventia_ri->pessoa->enderecos[0]->no_cidade ?? 'Sem cartório'}}
                </td>
                <td><!-- UF do cartorio -->
                    {{$registro->serventia_ri->pessoa->enderecos[0]->co_uf ?? 'Sem cartório'}}
                </td>
                <td>
                    @if($registro->registro_fiduciario_imovel)
                        @foreach($registro->registro_fiduciario_imovel as $key => $registro_imovel)
                            {{$registro_imovel->registro_fiduciario_imovel_tipo->no_tipo ?? NULL}}<br />
                            {{$registro_imovel->nu_matricula}}
                            @if($registro_imovel->endereco->cidade)
                                <br />{{$registro_imovel->endereco->cidade->no_cidade}} / {{$registro_imovel->endereco->cidade->uf}}
                            @endif
                            @if(($key+1) < $registro->registro_fiduciario_imovel->count())
                                <br /> ---------------------------------------------- <br />
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if($registro->registro_fiduciario_parte)
                        @foreach($registro->registro_fiduciario_parte as $key => $parte)
                            {{$parte->no_parte}} ({{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}) - @if ($parte->tp_pessoa == 'F') CPF: @else CNPJ: @endif {{Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)}}
                            @if(($key+1) < $registro->registro_fiduciario_parte->count())
                                <br /> ---------------------------------------------- <br />
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    {{$registro->registro_fiduciario_pedido->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}
                </td>
                <td>{{$registro->usuario->no_usuario ?? NULL}}</td>
                <td>{{Helper::formata_data_hora($registro->dt_alteracao)}}</td>
                <td>{{Helper::formata_data_hora($registro->dt_cadastro)}}</td>
                <td>{{Helper::formata_data_hora($registro->dt_assinatura_contrato)}}</td>
                <td>{{Helper::formata_data_hora($registro->dt_entrada_registro)}}</td>
                <td>{{Helper::formata_data_hora($registro->dt_registro)}}</td>
                <td>
                    @php
                        $pedido = $registro->registro_fiduciario_pedido->pedido;
                    @endphp
                    @foreach($pedido->pedido_central as $key => $pedido_central)
                        @php
                            $historico_prenotado = $pedido_central->pedido_central_historico()->where('id_pedido_central_situacao', config('constants.PEDIDO_CENTRAL_SITUACAO.PRENOTADO'))->orderBy('id_pedido_central_situacao', 'DESC');
                        @endphp
                        Protocolo nº {{$pedido_central->nu_protocolo_central}} - {{ $historico_prenotado->count() > 0 ? Helper::formata_data_hora($historico_prenotado->first()->dt_historico) : "Não foi prenotado" }}
                        @if($key !== $pedido->pedido_central->count() - 1)
                            <br /> ---------------------------------------------- <br />
                        @endif
                    @endforeach
                    @foreach($pedido->arisp_pedido as $arisp_pedido)
                        @php
                            $historico_prenotado = $arisp_pedido->arisp_pedido_historico()->where('id_arisp_pedido_status', config('constants.PEDIDO_CENTRAL_SITUACAO.PRENOTADO'))->orderBy('id_arisp_pedido_status', 'DESC');
                        @endphp
                        Protocolo nº {{$arisp_pedido->pedido_protocolo}} - {{ $historico_prenotado->count() > 0 ? Helper::formata_data_hora($historico_prenotado->first()->dt_cadastro) : "Não foi prenotado" }}
                        @if($key !== $pedido->arisp_pedido->count() - 1)
                            <br /> ---------------------------------------------- <br />
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($registro->registro_fiduciario_nota_devolutivas as $key => $nota_devolutiva)
                        Nota Devolutiva nº {{$nota_devolutiva->id_registro_fiduciario_nota_devolutiva}} - {{Helper::formata_data_hora($nota_devolutiva->dt_cadastro)}}
                        @if($key !== $registro->registro_fiduciario_nota_devolutivas->count() - 1)
                            <br /> ---------------------------------------------- <br />
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($registro->registro_fiduciario_nota_devolutivas as $key => $nota_devolutiva)
                        @php
                            $respostas = $nota_devolutiva->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_RESPOSTA_DEVOLUTIVA'));
                        @endphp
                        @if($respostas->count() > 0)
                            Nota Devolutiva nº {{$nota_devolutiva->id_registro_fiduciario_nota_devolutiva}} - {{Helper::formata_data_hora($respostas->first()->dt_cadastro)}}
                            @if($key !== $registro->registro_fiduciario_nota_devolutivas->count() - 1)
                                <br /> ---------------------------------------------- <br />
                            @endif
                        @endif
                    @endforeach
                </td>
                <td>{{$registro->registro_fiduciario_tipo->no_registro_fiduciario_tipo}}</td>
            </tr>
        @empty
            <tr>
                <td>
                    Nenhum registro foi encontrado.
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td>
				Total de&nbsp;<b>{{$todos_registros->count()}}</b>&nbsp;registro(s).
			</td>
		</tr>
	</tfoot>
</table>
