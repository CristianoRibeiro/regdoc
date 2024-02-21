<fieldset>
    <legend>DADOS PRINCIPAIS</legend>
    <div class="card card-body">
        <div class="pessoa-juridica">
            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="control-label">Documento identificador (CNPJ)</label>
                    <input class="form-control cnpj" name="nu_cnpj" />
                </div>
                <div class="col-12 col-md-6">
                    <label class="control-label">Razão Social</label>
                    <input class="form-control" name="no_pessoa" maxlength="80"  />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Inscrição municipal</label>
                    <input type="text" class="form-control" name="nu_inscricao_municipal" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Nome fantasia</label>
                    <input type="text" class="form-control" name="no_fantasia" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">E-mail</label>
                    <input type="text" class="form-control text-lowercase" name="no_email_pessoa" />
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
                        <option value="1">Residencial</option>
                        <option value="2">Comercial</option>
                        <option value="3">Celular</option>
                    </select>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">DDD</label>
                    <input type="text" class="form-control ddd" name="nu_ddd" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Número do telefone</label>
                    <input type="text" class="form-control telefone" name="nu_telefone" />
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
                    <input class="form-control cep" name="nu_cep" />
                </div>
                <div class="col-12 col-md-7">
                    <label class="control-label">Endereço</label>
                    <input type="text" class="form-control" name="no_endereco" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Número</label>
                    <input type="text" class="form-control" name="nu_endereco" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Bairro</label>
                    <input class="form-control" name="no_bairro" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Complemento</label>
                    <input type="text" class="form-control" name="no_complemento" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Estados</label>
                    <select class="form-control" name="id_estado">
                        <option value="">Selecione o estado</option>
                        @if(count($estados)>0)
                            @foreach($estados as $estado)
                                <option value="{{$estado->id_estado}}" data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">Cidade</label>
                    <select class="form-control" name="id_cidade" disabled>
                        <option value="">Selecione uma cidade</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend class="text-uppercase">Credor Fiduciário</legend>
    <div class="card card-body">
        <div class="form-group">
            <div class="row">
                <div class="col-12 col-md">
                    <div class="alert alert-info mb-0">
                        <p class="mb-1"><b>Desejo cadastrar o banco como credor fiduciário?</b></p>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="in_credor_fiduciario" id="in_credor_fiduciario_S" class="custom-control-input" value="S">
                            <label class="custom-control-label" for="in_credor_fiduciario_S">
                                Sim
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="in_credor_fiduciario" id="in_credor_fiduciario_N" class="custom-control-input" value="N">
                            <label class="custom-control-label" for="in_credor_fiduciario_N">
                                Não
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="novo-credor form-group mt-2" style="display:none">
            <fieldset>
                <legend>DADOS PRINCIPAIS</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md">
                            <label for="id_banco" class="control-label">Banco</label>
                            <select name="id_banco" id="id_banco" class="form-control selectpicker" data-live-search="true">
                                <option value="">Selecione um banco</option>
                                @foreach ($bancos as $banco)
                                    <option value="{{ $banco->id_banco }}">{{ $banco->codigo_banco .' - '. $banco->no_banco }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md">
                            <label class="control-label">Agência</label>
                            <input type="text" name="codigo_agencia" class="form-control" maxlength="4" />
                        </div>
                        <div class="col-12 col-md">
                            <label class="control-label">Nome do Credor</label>
                            <input type="text" name="no_agencia" class="form-control" />
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend class="text-uppercase">Primeiro Usuário</legend>
    <div class="card card-body">
        <div class="form-group">
            <div class="row">
                <div class="col-12 col-md">
                    <div class="alert alert-info mb-0">
                        <p class="mb-1"><b>O banco já possui usuário cadastrado?</b></p>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="in_usuario_existente" id="in_usuario_existente_S" class="custom-control-input" value="S">
                            <label class="custom-control-label" for="in_usuario_existente_S">
                                Sim
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="in_usuario_existente" id="in_usuario_existente_N" class="custom-control-input" value="N">
                            <label class="custom-control-label" for="in_usuario_existente_N">
                                Não
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="usuario-existente form-group mt-2" style="display:none">
            <div class="form-group">
                <div class="row">
                    <div class="col-12">
                        <label class="control-label">Documento identificador (CPF)</label>
                        <input class="form-control cpf" name="nu_cpf_usuario_existente" disabled />
                    </div>
                </div>
            </div>
        </div>
        <div class="novo-usuario form-group mt-2" style="display:none">
            <fieldset>
                <legend>DADOS PRINCIPAIS</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label class="control-label">Documento identificador (CPF)</label>
                            <input class="form-control cpf" name="nu_cpf_usuario" disabled />
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="control-label">Nome</label>
                            <input class="form-control" name="no_pessoa_usuario" maxlength="80" disabled />
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="control-label">Gênero</label>
                            <select class="form-control" name="tp_sexo_usuario" disabled>
                                <option value="">Selecione o gênero</option>
                                <option value="F">Feminino</option>
                                <option value="M">Masculino</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="control-label">Data de nasc.</label>
                            <input type="text" class="form-control data data_mask" name="dt_nascimento_usuario" disabled>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="mt-2">
                <legend>DADOS DE ACESSO</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="control-label">E-mail</label>
                            <input class="form-control text-lowercase" name="email_usuario" disabled>
                        </div>
                        <div class="col-12 col-md">
                            <label class="control-label">Senha</label>
                            <input class="form-control" type='text' name='senha_usuario' disabled value="Gerada automaticamente e enviada por e-mail.">
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</fieldset>
