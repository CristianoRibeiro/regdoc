<x-certificados.detalhes-identificacao :parteemissaocertificado="$parte_emissao_certificado"/>
<fieldset>
    <legend>DADOS PRINCIPAIS</legend>
    <div class="card card-body">
        <div class="pessoa-juridica">
            <div class="row">
                <div class="col-12 col-md">
                    <label class="control-label">Nome</label>
                    <input class="form-control" value="{{$parte_emissao_certificado->no_parte}}" disabled />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label" for="cpf_cnpj">CPF/CNPJ</label>
				    <input type="text" class="form-control cpf_cnpj" value="{{$parte_emissao_certificado->nu_cpf_cnpj}}" disabled/>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label">Telefone</label>
                    <input type="text" class="form-control telefone_celular" value="{{$parte_emissao_certificado->nu_telefone_contato}}" disabled/>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">E-mail</label>
                    <input type="text" class="form-control" value="{{$parte_emissao_certificado->no_email_contato}}" disabled />
                </div>
            </div>
            @if($parte_emissao_certificado->dt_nascimento || $parte_emissao_certificado->in_cnh)
                <div class="row mt-1">
                    @if($parte_emissao_certificado->dt_nascimento)
                        <div class="col-12 col-md">
                            <label class="control-label">Data de nascimento</label>
                            <input type="text" class="form-control" value="{{Helper::formata_data($parte_emissao_certificado->dt_nascimento)}}" disabled/>
                        </div>
                    @endif
                    @if($parte_emissao_certificado->in_cnh)
                        <div class="col-12 col-md">
                            <label class="control-label">Possui CNH</label>
                            <input type="text" class="form-control" value="{{$parte_emissao_certificado->in_cnh == 'S' ? 'SIM' : 'NÃO'}}" disabled />
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</fieldset>
@if($parte_emissao_certificado->nu_cep)
    <fieldset class="mt-2">
        <legend>ENDEREÇO</legend>
        <div class="card card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="control-label">CEP</label>
                        <input class="form-control cep" value="{{$parte_emissao_certificado->nu_cep}}" disabled />
                    </div>
                    <div class="col-12 col-md-7">
                        <label class="control-label">Endereço</label>
                        <input type="text" class="form-control" value="{{$parte_emissao_certificado->no_endereco}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Número</label>
                        <input type="text" class="form-control" value="{{$parte_emissao_certificado->nu_endereco}}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <label class="control-label">Bairro</label>
                        <input class="form-control" name="no_bairro" value="{{$parte_emissao_certificado->no_bairro}}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Estado</label>
                        <input type="text" class="form-control" value="{{$parte_emissao_certificado->cidade->estado->no_estado ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Cidade</label>
                        <input type="text" class="form-control" value="{{$parte_emissao_certificado->cidade->no_cidade ?? NULL}}" disabled />
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
@endif
@if(isset($parte_emissao_certificado->portal_certificado_vidaas->observacoes))
    <fieldset class="mt-2">
        <legend>OBSERVAÇÕES DO CLIENTE</legend>
        <textarea class="form-control" disabled>{{$parte_emissao_certificado->portal_certificado_vidaas->observacoes}}</textarea>
    </fieldset>
@endif
@if(isset($parte_emissao_certificado->de_observacoes_envio))
    <fieldset class="mt-2">
        <legend>OBSERVAÇÕES DA EMISSÃO</legend>
        <textarea class="form-control" disabled>{{$parte_emissao_certificado->de_observacoes_envio}}</textarea>
    </fieldset>
@endif
<hr />
<fieldset class="mt-2">
    <legend>SITUAÇÃO ATUAL</legend>
    <div class="card card-body">
        <div class="row">
            <div class="col-12 col-md">
                <label class="control-label">Situação REGDOC</label>
                <input class="form-control" value="{{$parte_emissao_certificado->parte_emissao_certificado_situacao->no_situacao}}" disabled />
            </div>
        </div>
        @if($parte_emissao_certificado->de_situacao_ticket)
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label">Situação da V/Cert</label>
                    <input class="form-control" value="{{$parte_emissao_certificado->de_situacao_ticket}}" disabled />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label">Observação situação da V/Cert</label>
                    <input class="form-control" value="{{$parte_emissao_certificado->de_observacao_situacao}}" disabled />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label">Data da situação da V/Cert</label>
                    <input class="form-control" value="{{Helper::formata_data_hora($parte_emissao_certificado->dt_situacao)}}" disabled />
                </div>
            </div>
        @endif
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend>HISTÓRICO DA EMISSÃO</legend>
    <table class="table table-striped table-bordered table-fixed">
        <thead>
            <tr>
                <th width="20%">Situação</th>
                <th width="20%">Situação do ticket</th>
                <th width="20%">Observação do ticket</th>
                <th width="20%">Usuário</th>
                <th width="20%">Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parte_emissao_certificado->parte_emissao_certificado_historico as $parte_emissao_certificado_historico)
                <tr>
                    <td>
                        {{$parte_emissao_certificado_historico->parte_emissao_certificado_situacao->no_situacao}}
                    </td>
                    <td>{{$parte_emissao_certificado_historico->de_situacao_ticket}}</td>
                    <td>{{$parte_emissao_certificado_historico->de_observacao_situacao}}</td>
                    <td>{{ $parte_emissao_certificado_historico->usuario->no_usuario }}</td>
                    <td>{{Helper::formata_data_hora($parte_emissao_certificado_historico->dt_cadastro)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="alert alert-danger mb-0">
                            Nenhum histórico foi encontrado.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</fieldset>