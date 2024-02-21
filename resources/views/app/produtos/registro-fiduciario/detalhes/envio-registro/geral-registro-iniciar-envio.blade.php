<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

@if(count($erros_validacao)>0)
    <div class="alert alert-warning mb-0">
        <h4>Ações pendentes para o envio do registro:</h4>
        <ul class="mb-0">
            @foreach($erros_validacao as $erro)
                <li>{{$erro}}</li>
            @endforeach
        </ul>
    </div>
@else
    <div class="alert alert-info" role="alert">
        <p>Esta etapa é responsável por gerar o arquivo XML e iniciar a assinatura do arquivo gerado para um ou mais responsáveis pelo credor fiduciário.</p>
        <p>O XML acima mencionado é o contrato com todos os dados do Registro Eletrônico de forma estruturada, que será assinado e enviado para a Central de Registro de Imóveis do Estado.</p>
        <p class="font-weight-bold">Selecione abaixo um responsável que irá receber o e-mail para realizar a assinatura do XML.</p>
        <a href="visualizar-xml" class="btn btn-primary" data-toggle="modal" data-target="#registro-fiduciario-visualizar-xml" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">Visualizar prévia do XML</a>
    </div>

    <fieldset>
        <legend>RESPONSÁVEIS PELO CREDOR FIDUCIÁRIO</legend>
        <select name="id_registro_fiduciario_parte[]" class="form-control selectpicker" data-live-search="true" title="Selecione um responsável" multiple>
            @foreach($gerentes as $gerente)
                <option value="{{$gerente->id_registro_fiduciario_parte}}">{{$gerente->no_parte}}</option>
            @endforeach
        </select>
    </fieldset>
@endif
