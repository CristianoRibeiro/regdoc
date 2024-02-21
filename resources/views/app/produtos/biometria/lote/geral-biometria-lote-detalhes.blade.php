<div class="accordion" id="biometria-lote">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#biometria-lote-dados" aria-expanded="true" aria-controls="biometria-lote-dados">
                    DADOS DO LOTE
                </button>
            </h2>
        </div>
        <div id="biometria-lote-dados" class="collapse show" data-parent="#biometria-lote">
            <div class="card-body">
				<div class="form-group">
                    <label class="control-label asterisk">UUID (Identificador único universal)</label>
                    <input class="form-control" value="{{$vscore_transacao_lote->uuid}}" disabled />
                </div>
                <div class="form-group mt-2">
                    <label class="control-label asterisk">Situação</label><br />
                    @switch($vscore_transacao_lote->in_completado)
                        @case('N')
                            <span class="badge badge-warning">Processando</span>
                            @break
                        @case('S')
                            <span class="badge badge-success">Finalizado</span>
                            @break
                    @endswitch
				</div>
                @if(Gate::allows('consultar-biometria-pessoa'))
                    <div class="form-group mt-2">
                        <label class="control-label asterisk">Entidade</label>
                        <input class="form-control" value="{{$vscore_transacao_lote->pessoa_origem->no_pessoa}}" disabled />
                    </div>
                @endif
                <div class="form-group mt-2">
                    <label class="control-label asterisk">Usuário</label>
                    <input class="form-control" value="{{$vscore_transacao_lote->usuario_cad->no_usuario}}" disabled />
                </div>
                <div class="form-group mt-2">
                    <label class="control-label asterisk">Data de cadastro</label>
                    <input class="form-control" value="{{Helper::formata_data_hora($vscore_transacao_lote->dt_cadastro)}}" disabled />
                </div>
                @if($vscore_transacao_lote->dt_finalizacao)
                    <div class="form-group mt-2">
                        <label class="control-label asterisk">Data de cadastro</label>
                        <input class="form-control" value="{{Helper::formata_data_hora($vscore_transacao_lote->dt_finalizacao)}}" disabled />
                    </div>
                @endif
			</div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#biometria-lote-consultas" aria-expanded="true" aria-controls="biometria-lote-consultas">
                    CONSULTAS DE BIOMETRIA
                </button>
            </h2>
        </div>
        <div id="biometria-lote-consultas" class="collapse" data-parent="#biometria-lote">
            <div class="card-body">
                <table id="biometria-resultados" class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="15%">CPF</th>
                            @if(Gate::allows('consultar-biometria-pessoa'))
                                <th width="35%">Entidade</th>
                            @else
                                <th width="35%">Usuário</th>
                            @endif
                            <th width="15%">Data da consulta</th>
                            <th width="20%">Situação</th>
                            <th width="15%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vscore_transacao_lote->vscore_transacoes as $vscore_transacao)
                            <tr>
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
                                            <span class="badge badge-danger">Não encontrado</span>
                                        @endif
                                        @break
                                @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{route('app.produtos.biometrias.show', $vscore_transacao->uuid)}}" class="btn btn-primary btn-sm" target="_blank">Detalhes</a>
                                        @if(Gate::any(['consultar-biometria-reprocessar'], $vscore_transacao))
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu">
                                                    @if(Gate::allows('consultar-biometria-reprocessar', $vscore_transacao))
                                                        <a class="dropdown-item reprocessar" href="javascript:void(0);" data-uuid="{{$vscore_transacao->uuid}}" data-cpf="{{Helper::pontuacao_cpf_cnpj($vscore_transacao->nu_cpf_cnpj)}}">Reprocessar</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>   
</div>