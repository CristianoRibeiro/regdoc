<form name="form-dados-serventia" method="post" action="">
    <fieldset>
        <legend>DADOS DA SERVENTIA</legend>
        <div class="card card-body">
            <div class="form-group">
                <div class="row">
                     <div class="col">
                        <label class="control-label">Título da serventia <small>(documentos oficiais)</small></label>
                        <input name="no_titulo" class="form-control" type="text" value="{{$pessoa_serventia->serventia->no_titulo}}" >
                        <div class="alert alert-primary mt-2 mb-0" role="alert">
                            Exemplo: 1º OFÍCIO DE NOTAS DE ...
                        </div>
                    </div>
                </div>
            </div>
            @php
                if(strlen($pessoa_serventia->nu_cpf_cnpj)>11) {
                    $in_cartorio_cnpj = 'S';
                } else {
                    $in_cartorio_cnpj = 'N';
                }
            @endphp
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-12 col-md-5">
                        <label for="no_email_pessoa" class="control-label">E-mail</label>
                        <input type="email" class="form-control text-lowercase" id="no_email_pessoa" name="no_email_pessoa" value="{{$pessoa_serventia->no_email_pessoa}}" placeholder="Digite o e-mail">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="control-label">A Serventia tem CNPJ?</label><br />
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="CartorioCNPJ_S" name="in_cartorio_cnpj" class="custom-control-input" value="S" @if($in_cartorio_cnpj=='S') checked @endif>
                            <label class="custom-control-label" for="CartorioCNPJ_S">Sim</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="CartorioCNPJ_N" name="in_cartorio_cnpj" class="custom-control-input" value="N" @if($in_cartorio_cnpj=='N') checked @endif>
                            <label class="custom-control-label" for="CartorioCNPJ_N">Não</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="control-label">CNPJ do Serventia</label>
                        <input type="text" class="form-control cnpj" name="nu_cpf_cnpj" placeholder="Digite o CNPJ do Serventia" @if($in_cartorio_cnpj == 'S') value="{{$pessoa_serventia->nu_cpf_cnpj}}" @else disabled @endif>
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-12 col-md">
                        <label class="control-label">Nome da serventia / Fantasia</label>
                        <input name="no_serventia" class="form-control" type="text" value="{{$pessoa_serventia->serventia->no_serventia}}">
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Inscrição municipal</label>
                        <input type="text" class="form-control" name="nu_inscricao_municipal" value="{{$pessoa_serventia->nu_inscricao_municipal}}" />
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-12 col-md">
                        <label class="control-label">Hora de início expediente</label>
                        <input name="hora_inicio_expediente" class="form-control"  type="text" value="{{$pessoa_serventia->serventia->hora_inicio_expediente}}">
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Hora de término expediente</label>
                        <input name="hora_termino_expediente" class="form-control" type="text" value="{{$pessoa_serventia->serventia->hora_termino_expediente}}">
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-12 col-md">
                        <label class="control-label">Hora de início do almoço</label>
                        <input name="hora_inicio_almoco" class="form-control" type="text" value="{{$pessoa_serventia->serventia->hora_inicio_almoco}}">
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Hora de término do almoço</label>
                        <input name="hora_termino_almoco" class="form-control"  type="text" value="{{$pessoa_serventia->serventia->hora_termino_almoco}}">
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Nome do oficial</label>
                        <input name="no_oficial" class="form-control"  type="text" value="{{$pessoa_serventia->serventia->no_oficial}}" disabled>
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Nome do substituto</label>
                        <input name="no_substituto" class="form-control"  type="text" value="{{$pessoa_serventia->serventia->no_substituto}}" disabled>
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-12 col-md">
                        <label class="control-label">CNS</label>
                        <input type="text" name="codigo_cns" id="codigo_cns" class="form-control" value="{{$pessoa_serventia->serventia->codigo_cns}}" />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Dígito Verificador do CNS</label>
                        <input type="text" name="dv_codigo_cns" id="dv_codigo_cns" class="form-control" value="{{$pessoa_serventia->serventia->dv_codigo_cns}}" />
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    @php
        $in_digitar_telefone = 'N';
        if(count($pessoa_serventia->telefones)>0) {
            $in_digitar_telefone = 'S';
            $telefone = $pessoa_serventia->telefones[0];
        }
    @endphp
    <fieldset class="mt-2">
        <legend>TELEFONE</legend>
        <div class="card card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-info mb-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="in_digitar_telefone" id="in_digitar_telefone_serventia" class="custom-control-input" value="S" @if($in_digitar_telefone=='S') checked @endif>
                                <label class="custom-control-label" for="in_digitar_telefone_serventia">
                                    Desejo digitar o telefone da serventia
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="campos-telefone" class="form-group mt-2" @if($in_digitar_telefone=='N') style="display:none" @endif>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <label class="control-label">Tipo de telefone</label>
                        <select class="form-control" name="id_tipo_telefone" @if($in_digitar_telefone=='N') disabled @endif>
                            <option value="">Tipo de telefone</option>
                            <option value="1" @if($in_digitar_telefone=='S') @if($telefone->id_tipo_telefone==1) selected @endif @endif>Residencial</option>
                            <option value="2" @if($in_digitar_telefone=='S') @if($telefone->id_tipo_telefone==2) selected @endif @endif>Comercial</option>
                            <option value="3" @if($in_digitar_telefone=='S') @if($telefone->id_tipo_telefone==3) selected @endif @endif>Celular</option>
                        </select>
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">DDD</label>
                        <input type="text" class="form-control ddd" name="nu_ddd" @if($in_digitar_telefone=='S') value="{{$telefone->nu_ddd}}" @else disabled @endif>
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Número do telefone</label>
                        <input type="text" class="form-control telefone" name="nu_telefone" @if($in_digitar_telefone=='S') value="{{$telefone->nu_telefone}}" @else disabled @endif>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    @php
        $in_digitar_endereco = 'N';
        if(count($pessoa_serventia->enderecos)>0) {
            $in_digitar_endereco = 'S';
            $endereco = $pessoa_serventia->enderecos[0];
        }
    @endphp
    <fieldset class="mt-2">
        <legend>ENDEREÇO</legend>
        <div class="card card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-info mb-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="in_digitar_endereco" id="in_digitar_endereco_serventia" class="custom-control-input" value="S" @if($in_digitar_endereco=='S') checked @endif>
                                <label class="custom-control-label" for="in_digitar_endereco_serventia">
                                    Desejo digitar o endereço da serventia
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="campos-endereco" class="form-group mt-2" @if($in_digitar_endereco=='N') style="display:none" @endif>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="control-label">CEP</label>
                        <input class="form-control cep" name="nu_cep" @if($in_digitar_endereco=='S') value='{{$endereco->nu_cep}}' @else disabled @endif />
                    </div>
                    <div class="col-12 col-md-7">
                        <label class="control-label">Endereço</label>
                        <input type="text" class="form-control" name="no_endereco" @if($in_digitar_endereco=='S') value='{{$endereco->no_endereco}}' @else disabled @endif />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Número</label>
                        <input type="text" class="form-control" name="nu_endereco" @if($in_digitar_endereco=='S') value='{{$endereco->nu_endereco}}' @else disabled @endif />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Bairro</label>
                        <input class="form-control" name="no_bairro" @if($in_digitar_endereco=='S') value='{{$endereco->no_bairro}}' @else disabled @endif />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Complemento</label>
                        <input type="text" class="form-control" name="no_complemento" @if($in_digitar_endereco=='S') value='{{$endereco->no_complemento}}' @else disabled @endif />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Estados</label>
                        <select class="form-control" name="id_estado" @if($in_digitar_endereco=='N') disabled @endif>
                            <option value="">Selecione o estado</option>
                            @if(count($estados)>0)
                                @foreach($estados as $estado)
                                    <option value="{{$estado->id_estado}}" data-uf="{{$estado->uf}}" @if($in_digitar_endereco=='S') @if($endereco->cidade->id_estado==$estado->id_estado) selected @endif @endif>{{$estado->no_estado}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Cidade</label>
                        <select class="form-control" name="id_cidade" @if($in_digitar_endereco=='N') disabled @endif>
                            <option value="">Selecione uma cidade</option>
                            @if(count($cidades_usuario)>0)
                                @foreach($cidades_usuario as $cidade)
                                    <option value="{{$cidade->id_cidade}}" @if($in_digitar_endereco=='S') @if($endereco->id_cidade==$cidade->id_cidade) selected @endif @endif>{{$cidade->no_cidade}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="form-group mt-3">
        <div class="row">
            <div class="col">
                <input type="reset" class="btn btn-danger float-left btn-w-100-sm mb-2 mb-md-0" value="Cancelar" />
                <input type="submit" class="btn btn-success float-right btn-w-100-sm" value="Salvar dados da serventia" />
            </div>
        </div>
    </div>
</form>
