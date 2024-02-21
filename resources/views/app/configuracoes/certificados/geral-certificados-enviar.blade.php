<input type="hidden" name="id_parte_emissao_certificado" id='id_parte_emissao_certificado' class="form-control" value="{{request()->certificado}}" />
<x-certificados.detalhes-identificacao :parteemissaocertificado="$parte_emissao_certificado"/>
<fieldset>
    <legend>DADOS PRINCIPAIS</legend>
    <div class="card card-body">
        <div class="pessoa-juridica">
            <div class="row">
                <div class="col-12 col-md">
                    <label class="control-label">Nome</label>
                    <input name="no_parte" class="form-control" value="{{$parte_emissao_certificado->no_parte ?? NULL}}" disabled />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label" for="cpf_cnpj">CPF/CNPJ</label>
				    <input name="nu_cpf_cnpj" type="text" class="form-control cpf_cnpj" value="{{$parte_emissao_certificado->nu_cpf_cnpj ?? NULL}}" disabled />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label">Telefone</label>
                    <input name="nu_telefone_contato" type="text" class="form-control telefone_celular" value="{{$nu_telefone_contato ?? NULL}}" />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label">E-mail</label>
                    <input name="no_email_contato" type="text" class="form-control" value="{{$no_email_contato ?? NULL}}" />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 col-md">
                    <div class="alert alert-warning mb-0" role="alert">
                        <h5><b>Alerta!</b></h5>
                        As alterações feitas aqui impactam somente a emissão do certificado e não alteram os dados da parte no Registro / e-Doc de origem.
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<div class="row mt-2">
    <div class="col-12 col-md">
        <label for="de_observacao" class="control-label">Observações</label>
        <textarea name="de_observacao" id="de_observacao" class="form-control" rows="7">{{$observacao . PHP_EOL . PHP_EOL}}{{"Parceiro: " . $parte_emissao_certificado->pedido->pessoa_origem->no_pessoa . "."}}</textarea>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-md">
        <div class="alert alert-info mb-0" role="alert">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="in_atualizacao_automatica" class="custom-control-input" id="in_atualizacao_automatica" value="S" checked>
                <label class="custom-control-label" for="in_atualizacao_automatica">Permitir que o sistema atualize a situação do ticket automaticamente.</label>
            </div>
        </div>
    </div>
</div>
