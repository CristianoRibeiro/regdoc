<table id="consultas" class="table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            <th width="10%">Tipo</th>
            <th width="13%">CPF</th>
            @if(Gate::allows('consultar-biometria-pessoa'))
                <th width="36%">Entidade</th>
            @else
                <th width="36%">Usuário</th>
            @endif
            <th width="12%">Data da consulta</th>
            <th width="17%">Situação / Resultado</th>
            <th width="12%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vscore_transacoes as $vscore_transacao)
            <tr>
                <td>
                    @if($vscore_transacao->id_vscore_transacao_lote)
                        Lote
                    @else
                        Interface
                    @endif
                </td>
                <td>{{Helper::pontuacao_cpf_cnpj($vscore_transacao->nu_cpf_cnpj)}}</td>
                @if(Gate::allows('consultar-biometria-pessoa'))
                    <td>
                        {{$vscore_transacao->pessoa_origem->no_pessoa}}<br />
                        <span class="small font-weight-bold">Usuário: {{$vscore_transacao->usuario_cad->no_usuario}}</span>
                    </td>
                @else
                    <td>{{$vscore_transacao->usuario_cad->no_usuario}}</td>
                @endif
                <td>{{Helper::formata_data_hora($vscore_transacao->dt_cadastro)}}</td>
                <td>
                @switch($vscore_transacao->id_vscore_transacao_situacao)
                    @case(config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO'))
                        <span class="badge badge-info">Aguardando processamento</span>
                        @break
                    @case(config('constants.VSCORE.SITUACOES.PROCESSANDO'))
                        <span class="badge badge-info">Processando</span>
                        @break
                    @case(config('constants.VSCORE.SITUACOES.ERRO'))
                        <span class="badge badge-danger">Erro</span>
                        @break
                    @case(config('constants.VSCORE.SITUACOES.FINALIZADO'))
                        @if($vscore_transacao->in_biometria_cpf==true)
                            <span class="badge badge-success">Encontrado</span>
                        @else
                            <span class="badge badge-warning">Não encontrado</span>
                        @endif
                        @break
                @endswitch
                </td>
                <td>
                    <a href="{{route('app.produtos.biometrias.show', $vscore_transacao->uuid)}}" class="btn btn-primary btn-sm">Detalhes</a>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">
                <div class="row">
                    <div class="col">
						Exibindo <b>{{count($vscore_transacoes)}}</b> de <b>{{$vscore_transacoes->total()}}</b> {{($vscore_transacoes->total()>1?'consultas':'consulta')}}.
					</div>
					<div class="col text-right">
						{{$vscore_transacoes->fragment('consultas')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
