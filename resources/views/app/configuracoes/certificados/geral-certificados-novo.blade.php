<input type="hidden" name="id_pedido" class="form-control" value="{{$campos['id_pedido'] ?? NULL}}" />

<fieldset>
    <legend>DADOS PRINCIPAIS</legend>
    <div class="card card-body">
        <div class="pessoa-juridica">
            <div class="row">
                <div class="col-12 col-md">
                    <label class="control-label">Nome</label>
                    <input name="no_parte" class="form-control" value="{{$campos['no_parte'] ?? NULL}}" {{$readonly ?? NULL}} />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label" for="cpf_cnpj">CPF/CNPJ</label>
				    <input name="nu_cpf_cnpj" type="text" class="form-control cpf_cnpj" value="{{$campos['nu_cpf_cnpj'] ?? NULL}}" {{$readonly ?? NULL}} />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label">Telefone</label>
                    <input name="nu_telefone_contato" type="text" class="form-control telefone_celular" value="{{$campos['nu_telefone_contato'] ?? NULL}}" {{$readonly ?? NULL}} />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">E-mail</label>
                    <input name="no_email_contato" type="text" class="form-control" value="{{$campos['no_email_contato'] ?? NULL}}" {{$readonly ?? NULL}} />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 col-md">
                    <div class="alert alert-info mb-0" role="alert">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="in_cnh" class="custom-control-input" id="in_cnh" value="S" @if(isset($campos['in_cnh'])) {{ ($campos['in_cnh'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif>
                            <label class="custom-control-label" for="in_cnh">A parte possui uma CNH (Carteira Nacional de Habilitação) para emissão do certificado digital.</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="endereco mt-2" @if(($campos['in_cnh'] ?? 'N') == 'S') style="display: none" @endif>
    <legend>ENDEREÇO</legend>
    <div class="card card-body">
        <div class="form-group">
            <div class="row">
                <div class="col-12 col-md-3">
                    <label class="control-label">CEP</label>
                    <input name="nu_cep" class="form-control cep" value="{{$campos['nu_cep'] ?? NULL}}" />
                </div>
                <div class="col-12 col-md-7">
                    <label class="control-label">Endereço</label>
                    <input name="no_endereco" type="text" class="form-control" value="{{$campos['no_endereco'] ?? NULL}}" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Número</label>
                    <input name="nu_endereco" type="text" class="form-control" value="{{$campos['nu_endereco'] ?? NULL}}" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <label class="control-label">Bairro</label>
                    <input name="no_bairro" class="form-control" name="no_bairro" value="{{$campos['no_bairro'] ?? NULL}}" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Estado</label>
                    <select name="id_estado" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
                        @if(count($estados_disponiveis)>0)
                            @foreach($estados_disponiveis as $estado)
                                <option value="{{$estado->id_estado}}" {{($campos['cidade']->id_estado ?? 0) == $estado->id_estado ? 'selected' : '' }} data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Cidade</label>
                        <select name="id_cidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{(count($cidades_disponiveis)<=0?'disabled':'')}} {{$disabled ?? NULL}}>
                            @if(count($cidades_disponiveis)>0)
                                @foreach($cidades_disponiveis as $cidade)
                                    <option value="{{$cidade->id_cidade}}" {{$campos['cidade']->id_cidade==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend>SITUAÇÃO</legend>
    <div class="card card-body">
        <div class="row">
            <div class="col-12 col-md">
                <label for="id_parte_emissao_certificado_situacao" class="control-label">Situação da emissão do certificado</label>
                <select name="id_parte_emissao_certificado_situacao" id="id_parte_emissao_certificado_situacao" class="form-control">
                    <option value="" selected>Selecione a situação</option>
                    @foreach ($situacoes as $situacao)
                        <option value="{{ $situacao->id_parte_emissao_certificado_situacao }}" {{$situacao->id_parte_emissao_certificado_situacao == config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO') ? 'selected' : '' }}>{{ $situacao->no_situacao }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mt-1 agendado" style="display: none">
            <div class="col-12 col-md">
                <label for="dt_agendado" class="control-label">Data do agendamento</label>
                <input type="text" name="dt_agendado" id="dt_agendado" class="form-control data" />
            </div>
            <div class="col-12 col-md">
                <label for="hr_agendado" class="control-label">Hora do agendamento</label>
                <input type="text" name="hr_agendado" id="hr_agendado" class="form-control hora" />
            </div>
        </div>
        <div class="row mt-1 emissao" style="display: none">
            <div class="col-12 col-md">
                <label for="dt_emissao" class="control-label">Data da emissão</label>
                <input type="text" name="dt_emissao" id="dt_emissao" class="form-control data_ate_hoje" />
            </div>
            <div class="col-12 col-md">
                <label for="hr_emissao" class="control-label">Hora da emissão</label>
                <input type="text" name="hr_emissao" id="hr_emissao" class="form-control hora" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md">
                <label for="de_observacao_situacao" class="control-label">Observação da situação</label>
                <textarea name="de_observacao_situacao" id="de_observacao_situacao" class="form-control"></textarea>
            </div>
        </div>
    </div>
</fieldset>