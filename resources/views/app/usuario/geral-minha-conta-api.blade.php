<?php
/*
<fieldset>
    <legend>CHAVES DE ACESSO</legend>
    <div class="card card-body">
        <form name="form-dados-api" method="POST" action="" class="clearfix">
            @if(!$usuario_key)
                <div class="alert alert-danger mb-0">
                    <i class="icon glyphicon glyphicon-remove pull-left"></i>
                    <div class="mensagem">
                        @switch(Auth::User()->pessoa_ativa->id_tipo_pessoa)
                            @case(2)
                                O seu usuário ainda não possui chaves de acesso geradas para este cartório, clique no botão "Gerar chaves de acesso".
                                @break
                            @case(8)
                                O seu usuário ainda não possui chaves de acesso geradas para esta empresa, clique no botão "Gerar chaves de acesso".
                                @break
                        @endswitch
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    @switch(Auth::User()->pessoa_ativa->id_tipo_pessoa)
                        @case(2)
                            Esta chave é válida para a serventia <b>"{{Auth::User()->pessoa_ativa->no_pessoa}}"</b> com o usuário <b>"{{Auth::User()->no_usuario}}"</b>.
                            @break
                        @case(8)
                            Esta chave é válida para a empresa <b>"{{Auth::User()->pessoa_ativa->no_pessoa}}"</b> com o usuário <b>"{{Auth::User()->no_usuario}}"</b>.
                            @break
                    @endswitch
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <label class="control-label">Client ID</label>
                            <input type="text" name="codigo_usuario" class="form-control" value="{{$usuario_key->no_codigo}}" readonly />
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <label class="control-label">Client Secret</label>
                            <input type="text" name="client_secret" class="form-control" value="{{$usuario_key->no_key}}" readonly />
                        </div>
                    </div>
                </div>
            @endif
            <input type="submit" class="btn btn-success pull-right mt-3" value="Gerar chaves de acesso" />
        </form>
    </div>
</fieldset>
*/
?>
<fieldset>
    <legend>ACESSO</legend>
    <div class="card card-body">
        <div class="form-group mt-2">
            <div class="row">
                <div class="col">
                    <label class="control-label">Usuário</label>
                    <input type="text" class="form-control" value="{{Auth::User()->email_usuario}}" readonly />
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <div class="row">
                <div class="col">
                    <label class="control-label">Senha</label>
                    <input type="text" class="form-control" value="Sua senha de acesso" readonly />
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <div class="row">
                <div class="col">
                    <label class="control-label">CNPJ <b>(Empresa ativa neste momento)</b></label>
                    <input type="text" class="form-control" value="{{Auth::User()->pessoa_ativa->nu_cpf_cnpj}}" readonly />
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend>API</legend>
    <div class="card card-body">
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label class="control-label">URL de Autenticação</label>
                    <input type="text" name="url_autentica" class="form-control" value="{{route('api.auth')}}" readonly />
                </div>
            </div>
        </div>
        <?php
        /*
        <div class="form-group mt-2">
            <div class="row">
                <div class="col">
                    <label class="control-label">URL de Comunicação</label>
                    <input type="text" name="url_comunica" class="form-control" value="{{route('api.registro-eletronico.inserir')}}" readonly />
                </div>
            </div>
        </div>
        */
        ?>
        <div class="form-group mt-2">
            <div class="row">
                <div class="col">
                    <label class="control-label d-block">Download do Manual de Integração</label>
                    <a href="https://validcertificadora.atlassian.net/l/c/kZByf7DR" target="_blank" class="btn btn-primary btn-w-100-sm" />DOCUMENTAÇÃO DA API</a>
                </div>
            </div>
        </div>
    </div>
</fieldset>
