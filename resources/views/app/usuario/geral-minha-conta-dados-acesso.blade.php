<form name="form-dados-acesso" method="post" action="">
    <fieldset>
        <legend>DADOS DE ACESSO</legend>
        <div class="card card-body">
            <div class="form-group">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label class="control-label">E-mail</label>
                            <input type="text" name="email_usuario" class="form-control" value="{{Auth::User()->email_usuario}}" disabled />
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <label class="control-label">Senha atual</label>
                            <input type="password" name="senha_atual" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <label class="control-label">Nova senha</label>
                            <input type="password" name="nova_senha" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <label class="control-label">Confirme a nova a senha</label>
                            <input type="password" name="repetir_nova_senha" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="form-group mt-3">
        <div class="row">
            <div class="col">
                <input type="reset" class="btn btn-danger float-left btn-w-100-sm mb-2 mb-md-0" value="Cancelar" />
                <input type="submit" class="btn btn-success float-right btn-w-100-sm" value="Salvar dados de acesso" />
            </div>
        </div>
    </div>
</form>
