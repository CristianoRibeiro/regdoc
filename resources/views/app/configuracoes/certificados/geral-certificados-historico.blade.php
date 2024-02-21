<table class="table table-striped table-bordered mb-1">
    <thead>
        <tr>
            <th width="25%">Nome</th>
            <th width="13%">CPF</th>
            <th width="12%">Ticket</th>
            <th width="15%">Data de cadastro</th>
            <th width="20%">Situação</th>
            <th width="15%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if (count($parte_emissao_certificados)>0)
            @foreach ($parte_emissao_certificados as $parte_emissao_certificado)
                <tr>
                    <td>
                        {{$parte_emissao_certificado->no_parte}}<br />
                        <span class="badge badge-primary badge-sm">
                            @if($parte_emissao_certificado->id_parte_emissao_certificado_tipo==config('constants.PARTE_EMISSAO_CERTIFICADO.TIPO.INTERNO'))
                                {{$parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_cliente ?? $parte_emissao_certificado->portal_certificado_vidaas->cliente}}
                            @else
                                REGDOC
                            @endif
                        </span>
                    </td>
                    <td>
                        {{Helper::pontuacao_cpf_cnpj($parte_emissao_certificado->nu_cpf_cnpj)}}
                        {!!($parte_emissao_certificado->in_cnh == 'S' ? '<span class="badge badge-success badge-sm">Tem CNH</span>' : '<span class="badge badge-warning badge-sm">Não tem CNH</span>')!!}
                    </td>
                    <td>
                       {{$parte_emissao_certificado->nu_ticket_vidaas}}
                    </td>
                    <td>{{Carbon\Carbon::parse($parte_emissao_certificado->dt_cadastro)->format('d/m/Y H:i:s')}}</td>
                    <td>
                        <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                    </td>
                    <td class="opcoes">
                        <div class="btn-group" role="group">
                            @if (Gate::allows('certificados-detalhes'))
							    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#certificados" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}" data-operacao="detalhes">Detalhes</button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu">
                                        @if (Gate::allows('certificados-alterar-situacao', $parte_emissao_certificado))
                                            <a class="dropdown-item" type="button" href="javascript:void(0);" data-target="#certificados" data-toggle="modal" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}" data-operacao="editar">
                                                Alterar situação
                                            </a>
                                        @endif
                                        @if (Gate::allows('certificados-enviar-emissao', $parte_emissao_certificado))
                                            <a class="dropdown-item" type="button" href="javascript:void(0);" data-target="#certificados-enviar" data-toggle="modal" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}">
                                                Gerar ticket
                                            </a>
                                        @endif
                                        @if (Gate::allows('certificados-enviar-emissao', $parte_emissao_certificado))
                                            <a class="dropdown-item" type="button" href="javascript:void(0);" data-target="#certificados-enviar-emitir" data-toggle="modal" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}">
                                                Gerar ticket e emitir
                                            </a>
                                        @endif
                                        @if (Gate::allows('certificados-alterar-ticket', $parte_emissao_certificado))
                                            <a class="dropdown-item" type="button" href="javascript:void(0);" data-target="#certificados-alterar-ticket" data-toggle="modal" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}">
                                                Alterar o número do ticket
                                            </a>
                                        @endif
                                        @if (Gate::allows('certificados-atualizar-ticket', $parte_emissao_certificado))
                                            <a class="dropdown-item atualizar-ticket" type="button" href="javascript:void(0);" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}">
                                                Atualizar com a VALID
                                            </a>
                                        @endif
                                        @if (Gate::allows('certificados-cancelar', $parte_emissao_certificado))
                                            <a class="dropdown-item" type="button" href="javascript:void(0);" data-target="#certificados-cancelar" data-toggle="modal" data-idparteemissao="{{$parte_emissao_certificado->id_parte_emissao_certificado}}">
                                                Cancelar emissão
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
						</div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">
                    <div class="single alert alert-danger mb-0">
                        <i class="glyphicon glyphicon-remove"></i>
                        <div class="mensagem">
                            Nenhum Certificado foi encontrado.
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
<div class="container">
    <div class="row mt-2">
        <div class="col-6">
            Exibindo <b>{{count($parte_emissao_certificados)}}</b> de <b>{{$parte_emissao_certificados->total()}}</b> {{($parte_emissao_certificados->total()>1?'certificados':'certificado')}}.
        </div>
        <div class="col text-right">
            {{$parte_emissao_certificados->fragment('certificados')->render()}}
        </div>
    </div>
</div>
