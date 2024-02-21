<table class="table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            <th width="17%">Protocolo</th>
            <th width="20%">Datas</th>
            <th width="10%">Identificação</th>
            <th width="20%">Cartório</th>
            <th width="20%">Parte(s)</th>
            <th width="13%">Situação</th>
        </tr>
    </thead>
    <tbody>
        @if ($todos_registros->count() > 0)
            @foreach ($todos_registros as $registro)
                <tr>
                    <td>{{$registro->registro_fiduciario_pedido->pedido->protocolo_pedido}}</td>
                    <td>
                        Cadastro: {{Helper::formata_data_hora($registro->dt_cadastro)}}
                        @if($registro->dt_assinatura_contrato)
                            <hr />
                            Assinatura: {{Helper::formata_data_hora($registro->dt_assinatura_contrato)}} <hr />
                        @endif
                        @if($registro->dt_entrada_registro)
                            Processamento: {{Helper::formata_data_hora($registro->dt_entrada_registro)}} <hr />
                        @endif
                        @if($registro->dt_registro)
                            Registro: {{Helper::formata_data_hora($registro->dt_registro)}}
                        @endif
                    </td>
                    <td>
                        @if($registro->empreendimento)
                            <b>Emp. / Unidade:</b> {{$registro->empreendimento->no_empreendimento}} / {{$registro->nu_unidade_empreendimento}}<br />
                        @elseif($registro->no_empreendimento)
                            <b>Emp. / Unidade:</b> {{$registro->no_empreendimento}} / {{$registro->nu_unidade_empreendimento}}<br />
                        @endif
                        @if($registro->nu_proposta)
                            <b>Proposta:</b> {{$registro->nu_proposta}}
                        @endif
                        @if($registro->nu_proposta && $registro->nu_contrato)
                            <br />
                        @endif
                        @if($registro->nu_contrato)
                            <b>Contrato:</b> {{$registro->nu_contrato}}
                        @endif
                    </td>
                    <td>
                        @switch($registro->registro_fiduciario_pedido->pedido->id_produto)
                            @case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
                                {{$registro->serventia_ri->pessoa->no_pessoa ?? 'Sem cartório'}}
                                @break
                            @case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
                                {{$registro->serventia_nota->pessoa->no_pessoa ?? 'Sem cartório'}}
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if($registro->registro_fiduciario_parte)
                            @foreach($registro->registro_fiduciario_parte as $parte)
                                <div class="parte">
                                    <span class="ellipsis" data-toggle="tooltip" title="{{$parte->no_parte}}">{{$parte->no_parte}}</span>
                                    <span class="small">
                                        <b>{{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</b> -
                                        <b>@if ($parte->tp_pessoa == 'F') CPF: @else CNPJ: @endif</b>
                                        {{Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)}}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        {{$registro->registro_fiduciario_pedido->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">
                    <div class="alert alert-danger mb-0">
                        Nenhum registro foi encontrado.
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <div class="row">
                    <div class="col">
						Exibindo <b>{{count($todos_registros)}}</b> de <b>{{$todos_registros->total()}}</b> {{($todos_registros->total()>1?'registros':'registro')}}.
					</div>
					<div class="col text-right">
						{{$todos_registros->fragment('pedidos-pendentes')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
