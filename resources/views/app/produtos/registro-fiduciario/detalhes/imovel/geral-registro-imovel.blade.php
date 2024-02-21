<input type="hidden" name="id_registro_fiduciario" value="{{ $registro_fiduciario->id_registro_fiduciario }}" />
<input type="hidden" name="id_registro_fiduciario_tipo" value="{{ $registro_fiduciario->id_registro_fiduciario_tipo }}" />
<input type="hidden" name="id_registro_fiduciario_imovel" value="{{ request()->imovel ?? NULL }}" />

<div class="accordion" id="formulario-imovel">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#formulario-imovel-dados" aria-expanded="true" aria-controls="formulario-imovel-dados">
                    DADOS DO IMÓVEL
                </button>
            </h2>
        </div>
        <div id="formulario-imovel-dados" class="collapse show" data-parent="#formulario-imovel">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Tipo do imóvel</label>
                        <select name="id_registro_fiduciario_imovel_tipo" id="id_registro_fiduciario_imovel_tipo" class="form-control selectpicker" title="Selecione" {{ $disabled ?? NULL }}>
                            @if(count($imovel_tipos) > 0)
                                @foreach($imovel_tipos as $imovel_tipo)
                                    <option value="{{ $imovel_tipo->id_registro_fiduciario_imovel_tipo }}" {{ (($imovel->id_registro_fiduciario_imovel_tipo ?? NULL) == $imovel_tipo->id_registro_fiduciario_imovel_tipo ? 'selected' : NULL) }}>{{ $imovel_tipo->no_tipo }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Localização do imóvel</label>
                        <select name="id_registro_fiduciario_imovel_localizacao" id="id_registro_fiduciario_imovel_localizacao" class="form-control selectpicker" title="Selecione" {{ $disabled ?? NULL }}>
                            @if(count($imovel_localizacoes) > 0)
                                @foreach($imovel_localizacoes as $imovel_localizacao)
                                    <option value="{{ $imovel_localizacao->id_registro_fiduciario_imovel_localizacao }}" {{(($imovel->id_registro_fiduciario_imovel_localizacao ?? NULL) == $imovel_localizacao->id_registro_fiduciario_imovel_localizacao ? 'selected' : NULL) }}>{{ $imovel_localizacao->no_localizacao }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Livro de Registro do imóvel</label>
                        <select name="id_registro_fiduciario_imovel_livro" id="id_registro_fiduciario_imovel_livro" class="form-control selectpicker" title="Selecione" {{ $disabled ?? NULL }}>
                            @if(count($imovel_livros) > 0)
                                @foreach($imovel_livros as $imovel_livro)
                                    <option value="{{ $imovel_livro->id_registro_fiduciario_imovel_livro }}" {{ (($imovel->id_registro_fiduciario_imovel_livro ?? NULL) == $imovel_livro->id_registro_fiduciario_imovel_livro ? 'selected' : NULL) }}>{{ $imovel_livro->no_livro }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Matrícula do imóvel</label>
                        <input name="nu_matricula" class="form-control" value="{{ $imovel->nu_matricula ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">IPTU do imóvel</label>
                        <input name="nu_iptu" class="form-control" value="{{ $imovel->nu_iptu ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">CCIR do imóvel</label>
                        <input name="nu_ccir" class="form-control" value="{{ $imovel->nu_ccir ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">NIRF do imóvel</label>
                        <input name="nu_nirf" class="form-control" value="{{ $imovel->nu_nirf ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                </div>
			</div>
        </div>
    </div>
    @if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 2, 3]))
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#formulario-imovel-valor" aria-expanded="true" aria-controls="formulario-imovel-valor">
                        VALOR DO IMÓVEL
                    </button>
                </h2>
            </div>
            <div id="formulario-imovel-valor" class="collapse" data-parent="#formulario-imovel">
                <div class="card-body">
                    <div class="row">
                        @if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 3]))
                            <div class="col-12 col-md-6">
                                <label class="control-label asterisk">Valor de compra e venda (Proporcional)</label>
                                <input name="va_compra_venda" class="form-control real" value="{{ $imovel->va_compra_venda ?? NULL }}" {{ $disabled ?? NULL }} />
                            </div>
                        @endif
                        <div class="col-12 col-md-6">
                            <label class="control-label asterisk">Valor venal do imóvel (Proporcional)</label>
                            <input name="va_venal" class="form-control real" value="{{ $imovel->va_venal ?? NULL }}" {{ $disabled ?? NULL }} />
                        </div>
                    </div>
    			</div>
            </div>
        </div>
    @endif
	<div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#formulario-imovel-endereco" aria-expanded="true" aria-controls="formulario-imovel-endereco">
                    ENDEREÇO
                </button>
            </h2>
        </div>
        <div id="formulario-imovel-endereco" class="collapse" data-parent="#formulario-imovel">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="control-label asterisk">CEP do Imóvel</label>
                        <input name="nu_cep" class="form-control cep" value="{{ $imovel->endereco->nu_cep ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="control-label asterisk">Endereço do imóvel</label>
                        <input name="no_endereco" class="form-control" maxlength="300" value="{{ $imovel->endereco->no_endereco ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">Complemento</label>
                        <input name="no_complemento" class="form-control" maxlength="50" value="{{ $imovel->endereco->no_complemento ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-4">
                        <label class="control-label asterisk">Número</label>
                        <input name="nu_endereco" class="form-control" maxlength="10" value="{{ $imovel->endereco->nu_endereco ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="control-label asterisk">Bairro</label>
                        <input name="no_bairro" class="form-control" maxlength="50" value="{{$imovel->endereco->no_bairro ?? NULL }}" {{ $disabled ?? NULL }} />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">Estado</label>
                        <select name="id_estado" class="form-control selectpicker" data-live-search="true" title="Selecione" {{ $disabled ?? NULL }}>
                            @if(count($estados_disponiveis) > 0)
                                @foreach($estados_disponiveis as $estado)
                                    <option value="{{ $estado->id_estado }}" data-uf="{{ $estado->uf }}" {{ (($imovel->endereco->id_estado ?? NULL) == $estado->id_estado ? 'selected' : NULL) }}>{{ $estado->no_estado }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">Cidade</label>
                        <select name="id_cidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{ (count($cidades_disponiveis) <= 0 ? 'disabled' : NULL) }} {{ $disabled ?? NULL }}>
                            @if(count($cidades_disponiveis) > 0)
                                @foreach($cidades_disponiveis as $cidade)
                                    <option value="{{ $cidade->id_cidade }}" {{ (($imovel->endereco->id_cidade ?? NULL) == $cidade->id_cidade ? 'selected' : NULL) }}>{{ $cidade->no_cidade }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
			</div>
        </div>
    </div>
</div>
