<div class="accordion" id="accordion-registro" style="display:none">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#accordion-registro-tipo" aria-expanded="true" aria-controls="registro-tipo">
                    TIPO DO REGISTRO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-tipo" class="collapse show" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="form-group">
                    <label class="control-label asterisk">Tipo do registro</label>
                    <select name="id_registro_fiduciario_tipo" class="form-control selectpicker" title="Selecione um tipo de registro">
                        @if(count($tipos_registro)>0)
                            @foreach ($tipos_registro as $tipo_registro)
                                <option value="{{$tipo_registro->id_registro_fiduciario_tipo}}">{{$tipo_registro->no_registro_fiduciario_tipo}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card cartorio">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-registro-cartorios" aria-expanded="true" aria-controls="registro-cartorios">
                    CARTÓRIO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-cartorios" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <fieldset id="cartorio_rtd">
                    <legend>CARTÓRIO DE REGISTRO DE TÍTULOS E DOCUMENTOS</legend>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="control-label asterisk">Estado</label>
                            <select name="id_estado_cartorio_rtd" class="form-control selectpicker" title="Selecione">
                                @if(count($estados_disponiveis)>0)
                                    @foreach($estados_disponiveis as $estado)
                                        <option value="{{$estado->id_estado}}">{{$estado->no_estado}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label asterisk">Cidade</label>
                            <select name="id_cidade_cartorio_rtd" class="form-control selectpicker" title="Selecione" data-live-search="true" disabled>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <label class="control-label asterisk">Cartório de Registro de Títulos e Documentos</label>
                            <select name="id_pessoa_cartorio_rtd" class="form-control selectpicker" title="Selecione" data-live-search="true" disabled>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="card tipo-insercao proposta" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-registro-proposta" aria-expanded="true" aria-controls="registro-proposta">
                    PROPOSTA
                </button>
            </h2>
        </div>
        <div id="accordion-registro-proposta" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
				<div class="row">
	                <div class="col">
	                    <label class="control-label asterisk">Número da proposta</label>
	                    <input name="nu_proposta" class="form-control numero-s-ponto"/>
	                </div>
	            </div>
            </div>
        </div>
    </div>
    <div class="card tipo-insercao contrato" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-registro-contrato" aria-expanded="true" aria-controls="registro-contrato">
                    CONTRATO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-contrato" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
				<div class="row">
	                <div class="col">
	                    <label class="control-label asterisk">Número do contrato</label>
	                    <input name="nu_contrato" class="form-control numero-s-ponto"/>
	                </div>
	            </div>
            </div>
        </div>
    </div>
    <div class="card partes" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-registro-partes" aria-expanded="true" aria-controls="registro-partes">
                    PARTES
                </button>
            </h2>
        </div>
        <div id="accordion-registro-partes" class="collapse" data-parent="#accordion-registro">
            <div class="card-body partes-container">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-registro-arquivos" aria-expanded="true" aria-controls="registro-arquivos">
                    ARQUIVOS
                </button>
            </h2>
        </div>
        <div id="accordion-registro-arquivos" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="form-group">
                    <fieldset>
                        <legend>ARQUIVO DO CONTRATO <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-registro" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="31" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-registro" data-pasta='registro' data-inassdigital="N">
                                Adicionar contrato
                            </button>
                        </div>
                    </fieldset>
                    <div class="alert alert-info mt-2 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="in_contrato_assinado" id="in_contrato_assinado" class="custom-control-input" value="S">
                            <label class="custom-control-label" for="in_contrato_assinado">O contrato inserido acima já foi assinado pelas partes e não precisará ser assinado novamente.</label>
                        </div>
                    </div>
                </div>
                <div class="form-group tipo-arquivos cessao mt-3" style="display: none">
                    <fieldset>
                        <legend>INSTRUMENTO PARTICULAR <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-instrumento-particular" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="48" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-instrumento-particular" data-pasta='registro' data-inassdigital="N">
                                Adicionar instrumento
                            </button>
                        </div>
                    </fieldset>
                    <div class="alert alert-info mt-2 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="in_instrumento_assinado" id="in_instrumento_assinado" class="custom-control-input" value="S">
                            <label class="custom-control-label" for="in_instrumento_assinado">O instrumento inserido acima já foi assinado pelas partes e não precisará ser assinado novamente.</label>
                        </div>
                    </div>
                </div>
                <div class="form-group tipo-arquivos correspondente mt-3" style="display: none">
                    <fieldset>
                        <legend>FORMULÁRIO<label class="control-label asterisk"></label></legend>
                        <div id="arquivos-formulario" class="arquivos obrigatorio btn-list btn-w-100-sm" data-token="{{$registro_token}}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="55" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-formulario" data-pasta='registro' data-inassdigital="N">
                                Adicionar formulário
                            </button>
                        </div>
                    </fieldset>
                </div>
                <div class="form-group mt-3">
                    <fieldset>
                        <legend>OUTROS ARQUIVOS INICIAIS <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-outros-documentos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal" data-target="#novo-arquivo-multiplo" data-idtipoarquivo="33" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-outros-documentos" data-pasta='registro' data-inassdigital="N">
                                Adicionar arquivos
                            </button>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
