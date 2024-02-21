@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/usuario/jquery.funcoes.minhaconta.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <div class="container">
        <div class="card box-app" style="margin-top: 6rem;">
            <div class="card-header">
                Alterar senha
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-2">
                    Olá {{Auth::User()->no_usuario}}, para continuar o seu acesso, será necessário alterar a senha temporária enviada por e-mail.
                </div>
                <form name="form-dados-acesso-salvar" method="post" action="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label class="control-label">E-mail</label>
                                <input type="text" name="email_usuario" class="form-control" value="{{Auth::User()->email_usuario}}" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-1">
                        <div class="row">
                            <div class="col">
                                <label class="control-label">Senha atual</label>
                                <input type="password" name="senha_atual" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-1">
                        <div class="row">
                            <div class="col">
                                <label class="control-label">Nova senha</label>
                                <input type="password" name="nova_senha" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-1">
                        <div class="row">
                            <div class="col">
                                <label class="control-label">Confirme a nova a senha</label>
                                <input type="password" name="repetir_nova_senha" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col">
                                <input type="reset" class="btn btn-danger float-left" value="Cancelar" />
                                <input type="submit" class="btn btn-success float-right" value="Salvar dados de acesso" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
