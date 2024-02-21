<div class="accordion" id="accordion-registro" style="display:none">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-tipo" aria-expanded="true" aria-controls="registro-tipo">
                    TIPO DO REGISTRO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-tipo" class="collapse show" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="form-group">
                    <label class="control-label asterisk">Tipo do registro</label>
                    <select name="id_registro_fiduciario_tipo" class="form-control selectpicker"
                        title="Selecione um tipo de registro">
                        @if (count($tipos_registro) > 0)
                            @foreach ($tipos_registro as $tipo_registro)
                                <option value="{{ $tipo_registro->id_registro_fiduciario_tipo }}">
                                    {{ $tipo_registro->no_registro_fiduciario_tipo }}</option>
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
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-cartorios" aria-expanded="false"
                    aria-controls="registro-cartorios">
                    CARTÓRIO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-cartorios" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <fieldset id="cartorio_ri">
                    <legend>CARTÓRIO DE REGISTRO DE IMÓVEIS</legend>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="control-label asterisk">Estado</label>
                            <select name="id_estado_cartorio_ri" class="selecionar-cidade form-control selectpicker"
                                title="Selecione">
                                @if (count($estados_disponiveis) > 0)
                                    @foreach ($estados_disponiveis as $estado)
                                        <option value="{{ $estado->id_estado }}">{{ $estado->no_estado }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label asterisk">Cidade</label>
                            <select name="id_cidade_cartorio_ri" class="form-control selectpicker" title="Selecione"
                                data-live-search="true" disabled>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <label class="control-label asterisk">Cartório de Registro de Imóveis</label>
                            <select name="id_pessoa_cartorio_ri" class="form-control selectpicker" title="Selecione"
                                data-live-search="true" disabled>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="card credor" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-credor" aria-expanded="false" aria-controls="registro-credor">
                    CREDOR FIDUCIÁRIO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-credor" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Estado</label>
                        <select name="id_estado_credor" class="selecionar-cidade form-control selectpicker"
                            title="Selecione">
                            @if (count($estados_disponiveis) > 0)
                                @foreach ($estados_disponiveis as $estado)
                                    <option value="{{ $estado->id_estado }}">{{ $estado->no_estado }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Cidade</label>
                        <select name="id_cidade_credor" class="form-control selectpicker" title="Selecione"
                            data-live-search="true" disabled>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <label class="control-label asterisk">Credor fiduciário</label>
                        <select name="id_registro_fiduciario_credor" class="form-control selectpicker" title="Selecione"
                            data-live-search="true" disabled>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card custodiante" style="display:none;">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-custodiante" aria-expanded="true"
                    aria-controls="registro-custodiante">
                    CUSTODIANTE
                </button>
            </h2>
        </div>
        <div id="accordion-registro-custodiante" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Estado</label>
                        <select name="id_estado_custodiante" class="selecionar-cidade form-control selectpicker"
                            title="Selecione">
                            @if (count($estados_disponiveis) > 0)
                                @foreach ($estados_disponiveis as $estado)
                                    <option value="{{ $estado->id_estado }}">{{ $estado->no_estado }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Cidade</label>
                        <select name="id_cidade_custodiante" class="form-control selectpicker" title="Selecione"
                            data-live-search="true" disabled>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <label class="control-label asterisk">Custodiante</label>
                        <select name="id_registro_fiduciario_custodiante" class="form-control selectpicker"
                            title="Selecione" data-live-search="true" disabled>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card tipo-insercao proposta" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-proposta" aria-expanded="true"
                    aria-controls="registro-proposta">
                    PROPOSTA
                </button>
            </h2>
        </div>
        <div id="accordion-registro-proposta" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <label class="control-label asterisk">Número da proposta</label>
                        <input name="nu_proposta" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card tipo-insercao contrato" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-contrato" aria-expanded="true"
                    aria-controls="registro-contrato">
                    CONTRATO
                </button>
            </h2>
        </div>
        <div id="accordion-registro-contrato" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <label class="control-label asterisk">Número do contrato</label>
                        <input name="nu_contrato" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-empreendimento" aria-expanded="true" aria-controls="registro-cartorios">
                    EMPREENDIMENTO
                </button>
            </h2>
        </div>
        <div id="accordion-empreendimento" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                @if (count($construtoras) > 0)
                    <div class="row">
                        <div class="col">
                            <label class="control-label">Construtora</label>
                            <select name="id_construtora_empreendimento" class="form-control selectpicker"
                                title="Selecione" data-live-search="true">
                                @foreach ($construtoras as $construtora)
                                    <option value="{{ $construtora->id_construtora }}">
                                        {{ $construtora->no_construtora }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row empreendimento" style="display:none">
                        <div class="col-12 col-md-6 id-empreendimento" style="display:none">
                            <label class="control-label">Empreendimento</label>
                            <select name="id_empreendimento" class="form-control selectpicker" title="Selecione"
                                data-live-search="true" disabled>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 no-empreendimento" style="display:none">
                            <label class="control-label">Nome do empreendimento</label>
                            <input name="no_empreendimento" class="form-control" disabled />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label">Número da unidade</label>
                            <input name="nu_unidade_empreendimento" class="form-control" />
                        </div>
                    </div>
                @else
                    <input name="id_empreendimento" type="hidden" value="-2" />
                    <div class="row">
                        <div class="col-12 col-md-6 no-empreendimento">
                            <label class="control-label">Nome do empreendimento</label>
                            <input name="no_empreendimento" class="form-control" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label">Número da unidade</label>
                            <input name="nu_unidade_empreendimento" class="form-control" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card partes" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-partes" aria-expanded="true"
                    aria-controls="registro-temp-partes">
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
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-canal" aria-expanded="true" aria-controls="registro-canais">
                    CANAL
                </button>
            </h2>
        </div>
        <div id="accordion-canal" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Nome (Pessoa Física)</label>
                        <select name="id_canal_pdv_parceiro" class="form-control selectpicker"
                            data-live-search="true" title="Selecione">
                            <option value="0">Selecione</option>
                            @foreach ($canais_pessoas as $canais_pessoa)
                                <option value="{{ $canais_pessoa->id_canal_pdv_parceiro }}">
                                    {{ $canais_pessoa->nome_canal_pdv_parceiro }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label">Parceiro (Pessoa Jurídica)</label>
                        <select name="parceiro_canal_pdv_parceiro" class="form-control selectpicker"
                            data-live-search="true" title="Selecione">
                            <option value="0">Selecione</option>
                            @foreach ($canais_pessoas_juridicas as $canais_pessoa)
                                <option value="{{ $canais_pessoa->id_canal_pdv_parceiro }}">
                                    {{ $canais_pessoa->parceiro_canal_pdv_parceiro }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Código</label>
                        <input type="text" name="codigo_canal_pdv_parceiro" class="form-control" disabled />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label">Email</label>
                        <input name="email_canal_pdv_parceiro" class="form-control" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">CNPJ</label>
                        <input name="cnpj_canal_pdv_parceiro" class="form-control cnpj" disabled />
                    </div>
                    <div class="col-12 col-md-6">
                        {{-- Este campo não é obrigatório --}}
                        <label class="control-label">BP</label>
                        <input name="no_pj" class="form-control" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card tipo-insercao contrato" style="display: none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#accordion-registro-arquivos" aria-expanded="true"
                    aria-controls="registro-arquivos">
                    ARQUIVOS
                </button>
            </h2>
        </div>
        <div id="accordion-registro-arquivos" class="collapse" data-parent="#accordion-registro">
            <div class="card-body">
                <div class="form-group">
                    <fieldset>
                        <legend>ARQUIVO DO CONTRATO <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-registro" class="arquivos obrigatorio btn-list"
                            data-token="{{ $registro_token }}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm"
                                data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="31"
                                data-token="{{ $registro_token }}" data-limite="0"
                                data-container="div#arquivos-registro" data-pasta='registro' data-inassdigital="N">
                                Adicionar contrato
                            </button>
                        </div>
                    </fieldset>
                    <div class="alert alert-info mt-2 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="in_contrato_assinado" id="in_contrato_assinado"
                                class="custom-control-input" value="S">
                            <label class="custom-control-label" for="in_contrato_assinado">O contrato inserido acima
                                já foi assinado pelas partes e não precisará ser assinado novamente.</label>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <fieldset>
                        <legend>OUTROS ARQUIVOS INICIAIS <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-outros-documentos" class="arquivos obrigatorio btn-list"
                            data-token="{{ $registro_token }}" title="Arquivos">

                        </div>
                        <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal"
                            data-target="#novo-arquivo-multiplo" data-idtipoarquivo="33"
                            data-token="{{ $registro_token }}" data-limite="0"
                            data-container="div#arquivos-outros-documentos" data-pasta='registro'
                            data-inassdigital="N">
                            Adicionar arquivos
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
