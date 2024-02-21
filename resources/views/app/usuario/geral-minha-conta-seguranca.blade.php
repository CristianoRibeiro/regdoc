<form name="form-dados-seguranca" method="post" action="">
    <fieldset>
        <legend class="text-uppercase">Duplo Fator de Autenticação</legend>
        <div class="card card-body">
            <div class="form-row">
                <div class="col form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="in_autenticacao_email" id="in_auth_email" class="custom-control-input" value="S" @if (Auth::User()->in_autenticacao_email === 'S') checked @endif @if (Auth::User()->in_autenticacao_email_obrigatorio === 'S') disabled @endif />
                        <label class="custom-control-label" for="in_auth_email">
                            Ativar autenticação com e-mail
                        </label>
                    </div>
                    @if (Auth::User()->in_autenticacao_email_obrigatorio === 'S')
                        <div class="alert alert-warning mb-0">
                            A sua organização não permite alteração desta opção.
                        </div>                    
                    @endif
                </div>
            </div>
        </div>
    </fieldset>
    <div class="form-group mt-3">
        <div class="row">
            <div class="col">
                <input type="reset" class="btn btn-danger float-left btn-w-100-sm mb-2 mb-md-0" value="Cancelar" @if (Auth::User()->in_autenticacao_email_obrigatorio === 'S') disabled @endif />
                <input type="submit" class="btn btn-success float-right btn-w-100-sm" value="Salvar" @if (Auth::User()->in_autenticacao_email_obrigatorio === 'S') disabled @endif />
            </div>
        </div>
    </div>
</form>
