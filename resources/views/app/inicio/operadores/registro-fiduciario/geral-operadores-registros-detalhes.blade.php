<table id="pedidos-pendentes" class="datatable table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            <th width="13%">Entidade</th>
            <th width="12%">Produto</th>
            <th width="18%">Protocolo</th>
            <th width="25%">Identificação</th>
            <th width="20%">Situação</th>
            <th width="12%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if(count($todos_registros) > 0)
            @foreach($todos_registros as $registro)
                <?php
                switch($registro->registro_fiduciario_pedido->pedido->id_produto) {
                    case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                        $produto_texto = 'Registro fiduciário';
                        $produto_url = 'fiduciario';
                        break;
                    case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                        $produto_texto = 'Registro de garantias / contrato';
                        $produto_url = 'garantias';
                        break;
                }
                ?>
                <tr>
                    <td>
                        @if($registro->registro_fiduciario_pedido->pedido->pessoa_origem->logo_interna)
                            <img src="{{$registro->registro_fiduciario_pedido->pedido->pessoa_origem->logo_interna->no_valor}}" class="img-fluid" />
                            <span class="sr-only">{{$registro->registro_fiduciario_pedido->pedido->pessoa_origem->no_pessoa}}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{$produto_texto}}
                    </td>
                    <td>
                        {{$registro->registro_fiduciario_pedido->pedido->protocolo_pedido}}
                        <span class="badge badge-primary badge-sm">{{Helper::formata_data($registro->dt_cadastro)}}</span>
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
                        {{$registro->registro_fiduciario_pedido->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}
                        @if(Gate::allows('registros-operadores'))
                            @php
                                $total_operadores = $registro->registro_fiduciario_operadores->count();
                            @endphp
                            <button type="button" data-toggle="modal" data-target="#registro-fiduciario-operadores" class="btn btn-light-{{ $total_operadores > 0 ? 'success' : 'danger' }} btn-sm" data-idregistro="{{$registro->id_registro_fiduciario}}" data-protocolopedido="{{$registro->registro_fiduciario_pedido->pedido->protocolo_pedido}}">
                                <i class="fas fa-headset"></i>
                                {{ $total_operadores == 1 ? $total_operadores . ' operador(a)' : $total_operadores . ' operadores' }}
                            </button>
                        @endif
                    </td>
                    <td class="options">
                        <a href="{{route('app.produtos.registros.show', [$produto_url, $registro->id_registro_fiduciario])}}" class="btn btn-primary">
                            Acessar
                        </a>
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
</table>
<div class="alert alert-warning mt-2 mb-0">
    <h5><b>Atenção!</b></h5>
    Exibindo <b>{{count($todos_registros)}} de {{$total_registros}}</b> registros ordenados por data de cadastro decrescente, para visualizar todos os registros acesse uma das opções abaixo:<br /><br />
    <a href="{{route('app.produtos.registros.index', ['fiduciario'])}}?id_usuario_operador={{request()->id_usuario_operador}}" class="btn btn-primary">Acessar os registros fiduciários</a>
    <a href="{{route('app.produtos.registros.index', ['garantias'])}}?id_usuario_operador={{request()->id_usuario_operador}}" class="btn btn-info">Acessar os registros de garantias / contratos</a>
</div>