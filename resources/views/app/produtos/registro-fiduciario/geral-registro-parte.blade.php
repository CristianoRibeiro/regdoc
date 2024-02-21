@php
    $id_tipo_parte_registro_fiduciario = $parte['id_tipo_parte_registro_fiduciario'] ?? request()->id_tipo_parte_registro_fiduciario;

    if($id_tipo_parte_registro_fiduciario == NULL){
        if(isset($id_tipo_parte_registro)){
            $id_tipo_parte_registro_fiduciario = $id_tipo_parte_registro;
        }
    }

    $editar = $editar ?? false;
    $completar = $completar ?? false;
    $exibir_todos_campos = $exibir_todos_campos ?? false;

    if ($completar || $exibir_todos_campos=='S' ||
        (($parte['in_cnh'] ?? NULL) == 'N' && ($parte['in_emitir_certificado'] ?? NULL) == 'S')) {
        $exibir_endereco = true;
    } else {
        $exibir_endereco = false;
    }

    $partes_cadastradas = $partes_cadastradas ?? [];
@endphp
<input type="hidden" name="registro_token" value="{{$registro_token ?? request()->registro_token}}" />
<input type="hidden" name="parte_token" value="{{$parte_token ?? NULL}}" />
<input type="hidden" name="hash" value="{{$hash ?? NULL}}" />
<input type="hidden" name="id_registro_fiduciario" value="{{ request()->registro ?? NULL }}" />
<input type="hidden" name="id_registro_fiduciario_parte" value="{{ request()->parte ?? NULL }}" />
<input type="hidden" name="id_tipo_parte_registro_fiduciario" value="{{$id_tipo_parte_registro_fiduciario}}" />
<input type="hidden" name="id_registro_tipo_parte_tipo_pessoa" value="{{$registro_tipo_parte_tipo_pessoa->id_registro_tipo_parte_tipo_pessoa}}" />

<input type="hidden" name="in_completado" value="{{$parte['in_completado'] ?? NULL}}" />

@if($registro_tipo_parte_tipo_pessoa->in_simples=='S')
    <input type="hidden" name="tp_pessoa" value="{{$parte['tp_pessoa'] ?? 'F'}}" />
@else
    <div class="options row">
        <div class="option col-md-6">
            <input type="radio" name="tp_pessoa" id="tp_pessoa_F" value="F" @if(isset($parte['tp_pessoa'])) {{$parte['tp_pessoa']=='F'?'checked':''}} @else checked @endif {{$disabled ?? NULL}} {{($completar?'readonly':'')}}>
            <label for="tp_pessoa_F">Pessoa física</label>
        </div>
        <div class="option col-md-6">
            <input type="radio" name="tp_pessoa" id="tp_pessoa_J" value="J" @if(isset($parte['tp_pessoa'])) {{$parte['tp_pessoa']=='J'?'checked':''}} @endif {{$disabled ?? NULL}} {{($completar?'readonly':'')}}>
            <label for="tp_pessoa_J">Pessoa jurídica</label>
        </div>
    </div>
@endif

@if (count($partes_cadastradas)>0)
    <div class="alert alert-info">
        <h5><b>Deseja preencher os dados com uma parte já cadastrada?</b></h5>
        <label class="control-label asterisk">Partes ja cadastrada</label>
        <select name="parte_cadastrada" class="form-control selectpicker" data-live-search="true" title="Selecione" >
            @foreach($partes_cadastradas as $parte_cadastrada)
                <option value="{{json_encode($parte_cadastrada)}}">{{$parte_cadastrada['no_parte']}} - CPF: {{Helper::pontuacao_cpf_cnpj($parte_cadastrada['nu_cpf_cnpj'])}}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="accordion" id="nova-parte">
    <div class="card tipo-parte pessoa-fisica" @if(isset($parte['tp_pessoa'])) {!!$parte['tp_pessoa']=='J'?'style="display:none"':''!!} @endif>
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left dados-parte" type="button" data-toggle="collapse" data-target="#nova-parte-pessoafisica" aria-expanded="true" aria-controls="nova-parte-pessoafisica">
                    DADOS DA PARTE - PESSOA FÍSICA
                </button>
            </h2>
        </div>
        <div id="nova-parte-pessoafisica" class="collapse show" data-parent="#nova-parte">
            <div class="card-body">
				<div class="form-group">
					<fieldset>
						<legend>DADOS PESSOAIS</legend>
						<div class="row">
							<div class="col-12 col-md-6">
								<label class="control-label asterisk">Nome completo</label>
								<input name="no_parte" class="form-control" maxlength="60" @if(($parte['tp_pessoa'] ?? NULL)=='F') value="{{$parte['no_parte'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label asterisk">CPF</label>
								<input name="nu_cpf" class="form-control cpf" @if(($parte['tp_pessoa'] ?? NULL)=='F') value="{{$parte['nu_cpf_cnpj'] ?? NULL}}" @endif {{$disabled ?? NULL}} {{($completar || $editar?'disabled':'')}} />
							</div>
						</div>
                        @if ($completar or $exibir_todos_campos)
                            <div class="row mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="control-label asterisk">Fração</label>
                                    <input name="fracao" class="form-control porcent" value="{{$parte['fracao'] ?? NULL}}" @if(isset($parte['tp_pessoa'])) {{$parte['tp_pessoa']=='J'?'disabled':''}} @endif {{$disabled ?? NULL}} />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label asterisk">Nacionalidade</label>
                                    <select name="no_nacionalidade" class="form-control selectpicker" title="Selecione" {{$disabled ?? NULL}}>
                                        <option value="Brasileira" {{($parte['no_nacionalidade'] == 'Brasileira' ? 'selected' : NULL)}}>Brasileira</option>
                                        <option value="Estrangeira" {{($parte['no_nacionalidade'] == 'Estrangeira' ? 'selected' : NULL)}}>Estrangeira</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="control-label asterisk">Gênero</label>
                                    <select name="tp_sexo" class="form-control selectpicker" title="Selecione" {{$disabled ?? NULL}}>
                                        <option value="M" {{($parte['tp_sexo'] == 'M' ? 'selected' : NULL)}}>Masculino</option>
                                        <option value="F" {{($parte['tp_sexo'] == 'F' ? 'selected' : NULL)}}>Feminino</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label">Profissão</label>
                                    <input name="no_profissao" class="form-control" value="{{$parte['no_profissao'] ?? NULL}}" maxlength="150" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="control-label">É menor de idade?</label>
                                    <select name="in_menor_idade" class="form-control selectpicker" title="Selecione" {{$disabled ?? NULL}}>
                                        <option value="N" @if(isset($parte['in_menor_idade'])) {{($parte['in_menor_idade'] == 'N' ? 'selected' : NULL)}} @else selected @endif>Não</option>
                                        <option value="S" {{($parte['in_menor_idade'] == 'S' ? 'selected' : NULL)}}>Sim</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label">Capacidade civil</label>
                                    <select name="id_registro_fiduciario_parte_capacidade_civil" class="form-control selectpicker" title="Selecione" {{$disabled ?? NULL}}>
                                        @foreach($capacidades_civis as $capacidade_civil)
                                            <option value="{{$capacidade_civil->id_registro_fiduciario_parte_capacidade_civil}}" {{(($parte['id_registro_fiduciario_parte_capacidade_civil'] ?? NULL) == $capacidade_civil->id_registro_fiduciario_parte_capacidade_civil ? 'selected' : NULL)}}>{{$capacidade_civil->no_capacidade}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="control-label">Filiação 1</label>
                                    <input name="no_filiacao1" class="form-control" value="{{$parte['no_filiacao1'] ?? NULL}}" maxlength="200" {{$disabled ?? NULL}} />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label">Filiação 2</label>
                                    <input name="no_filiacao2" class="form-control" value="{{$parte['no_filiacao2'] ?? NULL}}" maxlength="200" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="control-label asterisk">Data de nascimento</label>
                                    <input name="dt_nascimento" class="form-control data_ate_hoje" value="{{Helper::formata_data($parte['dt_nascimento'])}}" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                        @endif
					</fieldset>
				</div>
                @if ($completar or $exibir_todos_campos)
                    <div class="form-group mt-2">
                        <fieldset>
                            <legend class="text-uppercase">Documento de identificação</legend>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Tipo de Documento</label>
                                    <select name="no_tipo_documento" class="form-control" {{$disabled ?? NULL}}>
                                        <option value selected>Selecione</option>
                                        <option value="RG" {{(($parte['no_tipo_documento'] ?? NULL) == 'RG' ? 'selected' : NULL)}}>RG</option>
                                        <option value="CNH" {{(($parte['no_tipo_documento'] ?? NULL) == 'CNH' ? 'selected' : NULL)}}>CNH</option>
                                        <option value="Passaport" {{(($parte['no_tipo_documento'] ?? NULL) == 'Passaport' ? 'selected' : NULL)}}>Passaport</option>
                                        <option value="RNE" {{(($parte['no_tipo_documento'] ?? NULL) == 'RNE' ? 'selected' : NULL)}}>RNE</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Número</label>
                                    <input name="numero_documento" class="form-control" value="{{$parte['numero_documento'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Órgão / UF Expedidor</label>
                                    <input name="no_orgao_expedidor_documento" class="form-control" value="{{$parte['no_orgao_expedidor_documento'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                        </fieldset>
                    </div>
                @endif
			</div>
        </div>
    </div>
    <div class="card tipo-parte pessoa-juridica" @if(isset($parte['tp_pessoa'])) {!!$parte['tp_pessoa']=='F'?'style="display:none"':''!!} @else style="display:none" @endif>
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#nova-parte-pessoajuridica" aria-expanded="true" aria-controls="nova-parte-pessoajuridica">
                    DADOS DA PARTE - PESSOA JURÍDICA
                </button>
            </h2>
        </div>
        <div id="nova-parte-pessoajuridica" class="collapse show" data-parent="#nova-parte">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">Razão Social</label>
                        <input name="no_razao_social" class="form-control" maxlength="60" @if(($parte['tp_pessoa'] ?? NULL)=='J') value="{{$parte['no_parte'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">CNPJ</label>
                        <input name="nu_cnpj" class="form-control cnpj" @if(($parte['tp_pessoa'] ?? NULL)=='J') value="{{$parte['nu_cpf_cnpj'] ?? NULL}}" @endif {{$disabled ?? NULL}} {{($completar || $editar?'disabled':'')}} />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label asterisk">Fração</label>
                        <input name="fracao" class="form-control porcent" value="{{$parte['fracao'] ?? NULL}}" {{($parte['tp_pessoa'] ?? NULL)=='F'?'disabled':''}} {{$disabled ?? NULL}} />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!$completar && !$editar)
        @if($registro_tipo_parte_tipo_pessoa->in_simples!='S')
            <div class="card tipo-parte pessoa-fisica" @if(isset($parte['tp_pessoa'])) {!!$parte['tp_pessoa']=='J'?'style="display:none"':''!!} @endif>
                <div class="card-header">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-estadocivil" aria-expanded="true" aria-controls="nova-parte-estadocivil">
                            ESTADO CIVIL
                        </button>
                    </h2>
                </div>
                <div id="nova-parte-estadocivil" class="collapse" data-parent="#nova-parte">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label class="control-label ">Estado civil</label>
                                <select name="no_estado_civil" class="form-control" {{$disabled ?? NULL}}>
                                    <option value="">Selecione</option>
                                    <option value="Solteiro"  @if(isset($parte['no_estado_civil'])) {!!$parte['no_estado_civil']=='Solteiro'?'selected':''!!} @else selected @endif>Solteiro</option>
                                    <option value="Casado" {{(($parte['no_estado_civil'] ?? NULL) == 'Casado' ? 'selected' : NULL)}}>Casado</option>
                                    <option value="Separado" {{(($parte['no_estado_civil'] ?? NULL) == 'Separado' ? 'selected' : NULL)}}>Separado</option>
                                    <option value="Separado judicialmente" {{(($parte['no_estado_civil'] ?? NULL) == 'Separado judicialmente' ? 'selected' : NULL)}}>Separado judicialmente</option>
                                    <option value="Divorciado" {{(($parte['no_estado_civil'] ?? NULL) == 'Divorciado' ? 'selected' : NULL)}}>Divorciado</option>
                                    <option value="Viúvo" {{(($parte['no_estado_civil'] ?? NULL) == 'Viúvo' ? 'selected' : NULL)}}>Viúvo</option>
                                    <option value="União estável" {{(($parte['no_estado_civil'] ?? NULL) == 'União estável' ? 'selected' : NULL)}}>União estável</option>
                                </select>
                            </div>
                        </div>
                        <div class="estadocivil regime-bens row mt-1" @if(isset($parte['no_estado_civil'])) {!!(!in_array($parte['no_estado_civil'], ['Casado', 'Separado', 'União estável', 'Separado judicialmente'])?'style="display:none"':'')!!} @else style="display:none" @endif>
                            <div class="col">
                                <label class="control-label">Regime de bens</label>
                                <select name="no_regime_bens" class="form-control" {{$disabled ?? NULL}}>
                                    <option value="">Selecione</option>
                                    <option value="Comunhão parcial de bens" {{(($parte['no_regime_bens'] ?? NULL) == 'Comunhão parcial de bens' ? 'selected' : NULL)}}>Comunhão parcial de bens</option>
                                    <option value="Comunhão universal de bens" {{(($parte['no_regime_bens'] ?? NULL) == 'Comunhão universal de bens' ? 'selected' : NULL)}}>Comunhão universal de bens</option>
                                    <option value="Separação total de bens" {{(($parte['no_regime_bens'] ?? NULL) == 'Separação total de bens' ? 'selected' : NULL)}}>Separação total de bens</option>
                                    <option value="Participação final nos aquestos" {{(($parte['no_regime_bens'] ?? NULL) == 'Participação final nos aquestos' ? 'selected' : NULL)}}>Participação final nos aquestos</option>
                                </select>
                            </div>
                        </div>
                        <div class="estadocivil conjuge mt-1" @if(isset($parte['no_regime_bens'])) {!!(!in_array($parte['no_regime_bens'], ['Comunhão parcial de bens', 'Comunhão universal de bens', 'Participação final nos aquestos'])?'style="display:none"':'')!!} @else style="display:none" @endif>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="control-label asterisk">O cônjuge é ausente?</label>
                                    <select name="in_conjuge_ausente" class="form-control" title="Selecione" {{$disabled ?? NULL}}>
                                        <option value="N" {{(($parte['in_conjuge_ausente'] ?? NULL) == 'N' ? 'selected' : NULL)}}>Não</option>
                                        <option value="S" {{(($parte['in_conjuge_ausente'] ?? NULL) == 'S' ? 'selected' : NULL)}}>Sim</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label asterisk">CPF do cônjuge</label>
                                    <input name="cpf_conjuge" class="form-control cpf" value="{{$parte['cpf_conjuge'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="data-casamento control-label asterisk">
                                        @if(isset($parte['no_estado_civil']))
                                            @if($parte['no_estado_civil'] == 'União estável')
                                                Data da união
                                            @else
                                                Data de casamento
                                            @endif
                                        @else
                                            Data de casamento
                                        @endif
                                    </label>
                                    <input name="dt_casamento" class="form-control data_ate_hoje" value="{{$parte['dt_casamento'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($registro_tipo_parte_tipo_pessoa->in_procurador=='S')
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-procurador" aria-expanded="true" aria-controls="nova-parte-procurador">
                            PROCURADOR
                        </button>
                    </h2>
                </div>
                <div id="nova-parte-procurador" class="collapse" data-parent="#nova-parte">
                    <div class="card-body">
                        <table id="tabela-procuradores" class="table table-striped table-bordered mb-0 h-middle">
                            <thead>
                                <tr>
                                    <th width="50%">Nome</th>
                                    <th width="20%">CPF</th>
                                    <th width="30%">
                                        @if(!isset($disabled))
                                            <button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#registro-fiduciario-temp-procurador" data-partetoken="{{$parte_token}}" data-operacao="novo">
                                                <i class="fas fa-plus-circle"></i> Novo procurador
                                            </button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($parte['procuradores'] ?? [])>0)
                                    @foreach($parte['procuradores'] as $hash => $procurador)
                                        <tr id="linha_{{$hash}}">
                                            <td class="no_procurador">{{$procurador['no_procurador']}}</td>
                                            <td class="nu_cpf_cnpj">{{$procurador['nu_cpf_cnpj']}}</td>
                                            <td>
                                                @if(!isset($disabled))
                                                    <a href="javascript:void(0);" class="remover-procurador btn btn-danger btn-sm" data-partetoken="{{$parte_token}}" data-hash="{{$hash}}"><i class="fas fa-trash"></i></i> Remover</button>
                                                    <input type="hidden" name="in_procurador_inserido" value="S" />
                                                @else
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-temp-procurador" data-partetoken="{{$parte_token}}" data-hash="{{$hash}}" data-operacao="detalhes">Detalhes</button>
                                                    <a href="javascript:void(0);" class="ml-1 btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-temp-procurador" data-partetoken="{{$parte_token}}" data-hash="{{$hash}}" data-operacao="editar"><i class="fas fa-edit"></i></i>Editar</button>    
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
    @if($completar && !$editar)
        @if($registro_tipo_parte_tipo_pessoa->in_procurador=='S')  
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed text-uppercase" type="button" data-toggle="collapse" data-target="#parte-procurador" aria-expanded="true" aria-controls="parte-procurador">
                            PROCURADOR
                        </button>
                    </h2>
                </div>
                <div id="parte-procurador" class="collapse" data-parent="#nova-parte">
                    <div class="card-body">
                        <table id="tabela-procuradores" class="table table-striped table-bordered mb-0 h-middle">
                            <thead>
                                <tr>
                                    <th width="50%">Nome</th>
                                    <th width="20%">CPF</th>
                                    <th width="30%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($procuradores)>0)
                                    @foreach($procuradores as $hash => $procurador)
                                        <tr>
                                            <td class="no_procurador">{{$procurador['no_procurador']}}</td>
                                            <td class="nu_cpf_cnpj">{{$procurador['nu_cpf_cnpj']}}</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-procurador-detalhes-editar" data-idprocurador="{{$procurador['id_procurador']}}" data-operacao="detalhes">Detalhes</button>
                                                <a href="javascript:void(0);" class="ml-1 btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-procurador-detalhes-editar" data-idprocurador="{{$procurador['id_procurador']}}" data-operacao="editar"><i class="fas fa-edit"></i></i>Editar</button>    
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>    
            </div>           
        @endif
    @endif
    @if($registro_tipo_parte_tipo_pessoa->in_procuracao=='S')
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed text-uppercase" type="button" data-toggle="collapse" data-target="#nova-parte-procuracao" aria-expanded="true" aria-controls="nova-parte-procuracao">
                        PROCURAÇÃO
                    </button>
                </h2>
            </div>
            <div id="nova-parte-procuracao" class="collapse" data-parent="#nova-parte">
                <div class="card-body">
                    <select name="uuid_procuracao" id="procuracao" class="form-control" {{$disabled ?? NULL}}>
                        <option value selected>Selecione a procuração</option>
                        @foreach($procuracoes as $procuracao)
                            <option value="{{ $procuracao->uuid }}" {{ ($parte['uuid_procuracao'] ?? NULL) == $procuracao->uuid ? 'selected' : '' }}>{{ $procuracao->no_identificacao }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
    <div class="endereco card" @if(!$exibir_endereco) style="display: none" @endif>
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-endereco" aria-expanded="true" aria-controls="nova-parte-endereco">
                    ENDEREÇO
                </button>
            </h2>
        </div>
        <div id="nova-parte-endereco" class="collapse" data-parent="#nova-parte">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="control-label asterisk">CEP</label>
                        <input name="nu_cep" class="form-control cep" value="{{$parte['nu_cep'] ?? ""}}" {{$disabled ?? NULL}} />
                    </div>
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Endereço</label>
                    <input name="no_endereco" class="form-control" maxlength="200" value="{{$parte['no_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md-4">
                    <label class="control-label asterisk">Número</label>
                    <input name="nu_endereco" class="form-control" maxlength="10" value="{{$parte['nu_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
                </div>
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Bairro</label>
                    <input name="no_bairro" class="form-control" maxlength="60" value="{{$parte['no_bairro'] ?? ""}}" {{$disabled ?? NULL}} />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Estado</label>
                    <select name="id_estado" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
                        @if(count($estados_disponiveis)>0)
                            @foreach($estados_disponiveis as $estado)
                                <option value="{{$estado->id_estado}}" {{($parte['cidade']->id_estado ?? 0) == $estado->id_estado ? 'selected' : '' }} data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Cidade</label>
                        <select name="id_cidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{(count($cidades_disponiveis)<=0?'disabled':'')}} {{$disabled ?? NULL}}>
                            @if(count($cidades_disponiveis)>0)
                                @foreach($cidades_disponiveis as $cidade)
                                    <option value="{{$cidade->id_cidade}}" {{$parte['cidade']->id_cidade==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-contato" aria-expanded="true" aria-controls="nova-parte-contato">
                    DADOS DE CONTATO
                </button>
            </h2>
        </div>
        <div id="nova-parte-contato" class="collapse" data-parent="#nova-parte">
            <div class="card-body">
                <div class="alert alert-warning mb-0" role="alert">
                    <div class="row mt-1">
                        <div class="col-12 col-md">
                            <label class="control-label asterisk">Telefone</label>
                            <input name="nu_telefone_contato" class="form-control celular" value="{{$parte['nu_telefone_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
                            <label class="control-label">Telefone Adicional</label>
                            <input min="5" id="nu_telefone_contato_adicional" name="nu_telefone_contato_adicional" class="form-control" value="{{$parte['nu_telefone_contato_adicional'] ?? NULL}}"/>
                            @isset($disabled)
                            @if($disabled)
                                    <br>
                                    <input type="hidden" id="idparte" value="{{$parte['id_registro_fiduciario_parte'] ?? NULL}}">
                                    <input type="hidden" id="registro" value="{{$parte['id_registro_fiduciario'] ?? NULL}}">
                                    <button type="button" class="btn btn-primary btn-sm mt-1 salvar-telefone-adicional">
                                        Salvar
                                    </button>
                            @endif
                            @endisset
                        </div>
                        <div class="col-12 col-md">
                            <label class="control-label asterisk">E-mail</label>
                            <input name="no_email_contato" class="form-control text-lowercase" maxlength="100" value="{{$parte['no_email_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
                        </div>
                    </div>
                    <div class="text-left mt-2">
                        <span>A parte receberá usuário e senha por meio do telefone e e-mail.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info mb-0 mt-2" role="alert">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_emitir_certificado" class="custom-control-input" id="in_emitir_certificado" value="S" @if(isset($parte['in_emitir_certificado'])) {{ ($parte['in_emitir_certificado'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif {{$disabled ?? NULL}}>
            <label class="custom-control-label" for="in_emitir_certificado">Desejo iniciar a emissão do certificado digital da parte caso ela não possua.</label>
        </div>
    </div>
    <div class="in_cnh alert alert-info mb-0 mt-2" role="alert" @if (isset($parte['in_emitir_certificado'])) {!!($parte['in_emitir_certificado'] != 'S' ? 'style="display: none"' : '')!!} @endif>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_cnh" class="custom-control-input" id="in_cnh" value="S" @if(isset($parte['in_cnh'])) {{ ($parte['in_cnh'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif {{$disabled ?? NULL}}>
            <label class="custom-control-label" for="in_cnh">A parte possui uma CNH (Carteira Nacional de Habilitação) para emissão do certificado digital.</label>
        </div>
    </div>
</div>
