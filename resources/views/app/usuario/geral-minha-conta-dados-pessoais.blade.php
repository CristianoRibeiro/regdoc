<form name="form-dados-pessoais" method="post" action="">
    <fieldset>
        <legend>DADOS PRINCIPAIS</legend>
        <div class="card card-body">
            <div class="form-group">
                <div class="alert alert-info mb-0">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="tp_pessoa" id="tp_pessoa_f" class="custom-control-input" value="F" @if($pessoa_usuario->tp_pessoa=='F') checked @endif>
                        <label class="custom-control-label" for="tp_pessoa_f">
                            CPF
                        </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="tp_pessoa" id="tp_pessoa_j" class="custom-control-input" value="J" @if($pessoa_usuario->tp_pessoa=='J') checked @endif>
                        <label class="custom-control-label" for="tp_pessoa_j">
                            CNPJ
                        </label>
                    </div>
                </div>
            </div>
            <div class="pessoa-fisica mt-2" @if($pessoa_usuario->tp_pessoa=='J') style="display: none;" @endif>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="control-label">CPF</label>
                            <input class="form-control cpf" name="nu_cpf_cnpj_pf" @if($pessoa_usuario->tp_pessoa=='J') disabled @else value="{{$pessoa_usuario->nu_cpf_cnpj}}" @endif />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="control-label">Nome</label>
                            <input class="form-control" name="no_pessoa_pf" maxlength="80" value="{{$pessoa_usuario->no_pessoa}}" @if($pessoa_usuario->tp_pessoa=='J') disabled @endif />
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="control-label">Gênero</label>
                            <select class="form-control" name="tp_sexo" @if($pessoa_usuario->tp_pessoa=='J') disabled @endif>
                                <option value="">Selecione o gênero</option>
                                <option value="F" @if($pessoa_usuario->tp_sexo=='F') selected @endif>Feminino</option>
                                <option value="M" @if($pessoa_usuario->tp_sexo=='M') selected @endif>Masculino</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="control-label">Data de nasc.</label>
                            <input type="text" class="form-control data data_mask" name="dt_nascimento" value="{{Helper::formata_data($pessoa_usuario->dt_nascimento)}}" @if($pessoa_usuario->tp_pessoa=='J') disabled @endif>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pessoa-juridica mt-2" @if($pessoa_usuario->tp_pessoa=='F') style="display: none;" @endif>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="control-label">CNPJ</label>
                            <input class="form-control cnpj" name="nu_cpf_cnpj_pj" value="{{$pessoa_usuario->nu_cpf_cnpj}}" @if($pessoa_usuario->tp_pessoa=='F') disabled @endif />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label">Razão Social</label>
                            <input class="form-control" name="no_pessoa_pj" maxlength="80" value="{{$pessoa_usuario->no_pessoa}}" @if($pessoa_usuario->tp_pessoa=='F') disabled @endif />
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="control-label">Inscrição municipal</label>
                            <input type="text" class="form-control" name="nu_inscricao_municipal" value="{{$pessoa_usuario->nu_inscricao_municipal}}" @if($pessoa_usuario->tp_pessoa=='F') disabled @endif />
                        </div>
                        <div class="col-12 col-md">
                            <label class="control-label">Nome fantasia</label>
                            <input type="text" class="form-control" name="no_fantasia" value="{{$pessoa_usuario->no_fantasia}}" @if($pessoa_usuario->tp_pessoa=='F') disabled @endif />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    @php
        $in_digitar_telefone = 'N';
        if(count($pessoa_usuario->telefones)>0) {
            $in_digitar_telefone = 'S';
            $telefone = $pessoa_usuario->telefones[0];
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
                                <input type="checkbox" name="in_digitar_telefone" id="in_digitar_telefone_pessoais" class="custom-control-input" value="S" @if($in_digitar_telefone=='S') checked @endif>
                                <label class="custom-control-label" for="in_digitar_telefone_pessoais">
                                    Desejo digitar o meu telefone
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
        if(count($pessoa_usuario->enderecos)>0) {
            $in_digitar_endereco = 'S';
            $endereco = $pessoa_usuario->enderecos[0];
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
                                <input type="checkbox" name="in_digitar_endereco" id="in_digitar_endereco_pessoais" class="custom-control-input" value="S" @if($in_digitar_endereco=='S') checked @endif>
                                <label class="custom-control-label" for="in_digitar_endereco_pessoais">
                                    Desejo digitar o meu endereço
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
                <input type="submit" class="btn btn-success float-right btn-w-100-sm" value="Salvar dados pessoais" />
            </div>
        </div>
    </div>
</form>
