<fieldset>
    <legend>DADOS PRINCIPAIS</legend>
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="{{$pessoa->id_pessoa}}" >
    <div class="card card-body">
        <div class="pessoa-juridica">
            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="control-label">Documento identificador (CNPJ)</label>
                    <input class="form-control cnpj" name="nu_cnpj" value="{{$pessoa->nu_cpf_cnpj}}"  />
                </div>
                <div class="col-12 col-md-6">
                    <label class="control-label">Razão Social</label>
                    <input class="form-control" name="no_pessoa" maxlength="80" value="{{$pessoa->no_pessoa}}"  />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Inscrição municipal</label>
                    <input type="text" class="form-control" name="nu_inscricao_municipal" value="{{$pessoa->nu_inscricao_municipal}}" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Nome fantasia</label>
                    <input type="text" class="form-control" name="no_fantasia" value="{{$pessoa->no_fantasia}}" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">E-mail</label>
                    <input type="text" class="form-control text-lowercase" name="no_email_pessoa" value="{{$pessoa->no_email_pessoa}}"  />
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend>TELEFONE</legend>
    <div class="card card-body">
        <div class="form-group">
            <div class="row">
            <div class="col-12 col-md-5">
                    <label class="control-label">Tipo de telefone</label>
                    <select class="form-control" name="id_tipo_telefone">
                        <option value="">Tipo de telefone</option>
                        <option @if($pessoa->telefones[0]->tipo_telefone->id_tipo_telefone=='1') selected @endif value="1">Residencial</option>
                        <option @if($pessoa->telefones[0]->tipo_telefone->id_tipo_telefone=='2') selected @endif value="2">Comercial</option>
                        <option @if($pessoa->telefones[0]->tipo_telefone->id_tipo_telefone=='3') selected @endif value="3">Celular</option>
                    </select>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">DDD</label>
                    <input type="text" class="form-control ddd" name="nu_ddd" value="{{$pessoa->telefones[0]->nu_ddd ?? NULL}}"  />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Número do telefone</label>
                    <input type="text" class="form-control telefone" name="nu_telefone" value="{{$pessoa->telefones[0]->nu_telefone ?? NULL}}" />
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend>ENDEREÇO</legend>
    <div class="card card-body">
        <div class="form-group">
            <div class="row">
                <div class="col-12 col-md-3">
                    <label class="control-label">CEP</label>
                    <input class="form-control cep" name="nu_cep" value="{{$pessoa->enderecos[0]->nu_cep ?? NULL}}"  />
                </div>
                <div class="col-12 col-md-7">
                    <label class="control-label">Endereço</label>
                    <input type="text" class="form-control" name="no_endereco" value="{{$pessoa->enderecos[0]->no_endereco ?? NULL}}"  />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Número</label>
                    <input type="text" class="form-control" name="nu_endereco" value="{{$pessoa->enderecos[0]->nu_endereco ?? NULL}}"  />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Bairro</label>
                    <input class="form-control" name="no_bairro" value="{{$pessoa->enderecos[0]->no_bairro ?? NULL}}"  />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Complemento</label>
                    <input type="text" class="form-control" name="no_complemento" value="{{$pessoa->enderecos[0]->no_complemento ?? NULL}}"  />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Estados</label>
                    <select class="form-control" name="id_estado" >
                        <option value="">Selecione o estado</option>
                        @if(count($estados)>0)
                            @foreach($estados as $estado)
                                <option value="{{$estado->id_estado}}"@if($estado->id_estado==$pessoa->enderecos[0]->cidade->id_estado) selected @endif data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Cidade</label>
                    <select class="form-control" name="id_cidade" >
                        @if(count($cidades)>0)
                            @foreach($cidades as $cidade)
                                <option value="{{$cidade->id_cidade}}"@if($cidade->id_cidade==$pessoa->enderecos[0]->cidade->id_cidade) selected @endif >{{$cidade->no_cidade}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
</fieldset>
