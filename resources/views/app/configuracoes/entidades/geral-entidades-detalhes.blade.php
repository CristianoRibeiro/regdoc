<div class="accordion" id="accordion-entidade">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#accordion-entidade-tipo" aria-expanded="true" aria-controls="entidade-tipo">
                    DADOS PRINCIPAIS
                </button>
            </h2>
        </div>
        <div id="accordion-entidade-tipo" class="collapse show" data-parent="#accordion-entidade">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Documento identificador (CNPJ)</label>
                        <input class="form-control cnpj" name="nu_cnpj" value="{{$pessoa->nu_cpf_cnpj}}" disabled />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label">Razão Social</label>
                        <input class="form-control" name="no_pessoa" value="{{$pessoa->no_pessoa}}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Inscrição municipal</label>
                        <input type="text" class="form-control" name="nu_inscricao_municipal" value="{{$pessoa->nu_inscricao_municipal}}" disabled/>
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Nome fantasia</label>
                        <input type="text" class="form-control" name="no_fantasia" value="{{$pessoa->no_fantasia}}" disabled/>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">E-mail</label>
                        <input type="text" class="form-control" name="no_email_pessoa" value="{{$pessoa->no_email_pessoa}}" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-entidade-telefones" aria-expanded="true" aria-controls="entidade-telefones">
                    TELEFONES
                </button>
            </h2>
        </div>
        <div id="accordion-entidade-telefones" class="collapse" data-parent="#accordion-entidade">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-5">
                        <label class="control-label">Tipo de telefone</label>
                        <input type="text" class="form-control" name="id_tipo_telefone" value="{{$pessoa->telefones[0]->tipo_telefone->no_tipo_telefone ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">DDD</label>
                        <input type="text" class="form-control ddd" name="nu_ddd" value="{{$pessoa->telefones[0]->nu_ddd ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Número do telefone</label>
                        <input type="text" class="form-control telefone" name="nu_telefone" value="{{$pessoa->telefones[0]->nu_telefone ?? NULL}}" disabled/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-entidade-enderecos" aria-expanded="true" aria-controls="entidade-enderecos">
                    ENDEREÇOS
                </button>
            </h2>
        </div>
        <div id="accordion-entidade-enderecos" class="collapse" data-parent="#accordion-entidade">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="control-label">CEP</label>
                        <input class="form-control cep" name="nu_cep" value="{{$pessoa->enderecos[0]->nu_cep ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md-7">
                        <label class="control-label">Endereço</label>
                        <input type="text" class="form-control" name="no_endereco" value="{{$pessoa->enderecos[0]->no_endereco ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Número</label>
                        <input type="text" class="form-control" name="nu_endereco" value="{{$pessoa->enderecos[0]->nu_endereco ?? NULL}}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Bairro</label>
                        <input class="form-control" name="no_bairro" value="{{$pessoa->enderecos[0]->no_bairro ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Complemento</label>
                        <input type="text" class="form-control" name="no_complemento" value="{{$pessoa->enderecos[0]->no_complemento ?? NULL}}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Estado</label>
                        <input type="text" class="form-control" name="id_estado" value="{{$pessoa->enderecos[0]->cidade->estado->no_estado ?? NULL}}" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label">Cidade</label>
                        <input type="text" class="form-control" name="id_cidade" value="{{$pessoa->enderecos[0]->cidade->no_cidade ?? NULL}}" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-entidade-vinculos" aria-expanded="true" aria-controls="entidade-vinculos">
                    VÍNCULOS
                </button>
            </h2>
        </div>
        <div id="accordion-entidade-vinculos" class="collapse" data-parent="#accordion-entidade">
            <div class="card-body">
                <fieldset>
                    <legend>TIPOS DE REGISTRO</legend>
                    <table class="table table-striped table-bordered table-fixed">
                        <thead>
                            <tr>
                                <th width="45%">Nome</th>
                                <th width="25%">Produto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pessoa->registro_tipos_vinculados as $registro_fiduciario_tipo)
                                <tr>
                                    <td>{{$registro_fiduciario_tipo->no_registro_fiduciario_tipo}}</td>
                                    <td>{{$registro_fiduciario_tipo->produto->no_produto}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-danger mb-0">
                                            Nenhum tipo de registro foi vinculado.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="mt-1">
                    <legend>CREDORES</legend>
                    <table class="table table-striped table-bordered table-fixed">
                        <thead>
                            <tr>
                                <th width="55%">Razão social</th>
                                <th width="25%">CNPJ</th>
                                <th width="20%">Cidade / UF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pessoa->credores_vinculados as $registro_fiduciario_credor)
                                <tr>
                                    <td>{{$registro_fiduciario_credor->no_credor}}</td>
                                    <td>{{Helper::pontuacao_cpf_cnpj($registro_fiduciario_credor->nu_cpf_cnpj)}}</td>
                                    <td>{{$registro_fiduciario_credor->cidade->no_cidade ?? NULL}} / {{$registro_fiduciario_credor->cidade->estado->uf ?? NULL}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-danger mb-0">
                                            Nenhum credor foi vinculado.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="mt-1">
                    <legend>PROCURAÇÕES</legend>
                    <table class="table table-striped table-bordered table-fixed">
                        <thead>
                            <tr>
                                <th width="25%">Tipo</th>
                                <th width="45%">Título</th>
                                <th width="15%">Número</th>
                                <th width="15%">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pessoa->procuracoes_vinculadas as $procuracao)
                                <tr>
                                    <td>{{$procuracao->tipo_instrumento->no_tipo_instrumento}}</td>
                                    <td>{{$procuracao->no_identificacao}}</td>
                                    <td>{{$procuracao->nu_instrumento}}</td>
                                    <td>{{Helper::formata_data($procuracao->dt_instrumento_registro)}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-danger mb-0">
                                            Nenhuma procuração foi vinculada.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="mt-1">
                    <legend>CONSTRUTORAS</legend>
                    <table class="table table-striped table-bordered table-fixed">
                        <thead>
                            <tr>
                                <th width="30%">Razão social</th>
                                <th width="30%">Empreendimento</th>
                                <th width="20%">CNPJ</th>
                                <th width="20%">Cidade / UF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pessoa->construtoras_vinculadas as $construtora)
                                <tr>
                                    <td>{{$construtora->no_construtora}}</td>
                                    <td>{{$construtora->no_empreendimento}}</td>
                                    <td>{{Helper::pontuacao_cpf_cnpj($construtora->nu_cpf_cnpj)}}</td>
                                    <td>{{$construtora->cidade->no_cidade ?? NULL}} / {{$construtora->cidade->estado->uf ?? NULL}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-danger mb-0">
                                            Nenhuma construtora foi vinculada.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-entidade-configuracoes" aria-expanded="true" aria-controls="entidade-configuracoes">
                    CONFIGURAÇÕES
                </button>
            </h2>
        </div>
        <div id="accordion-entidade-configuracoes" class="collapse" data-parent="#accordion-entidade">
            <div class="card-body">
                <table class="table table-striped table-bordered table-fixed">
                    <thead>
                        <tr>
                            <th width="45%">Configuração</th>
                            <th width="15%">Situação</th>
                            <th width="40%">Valor configurado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($configuracoes_pessoa as $configuracao_pessoa)
                            <tr>
                                <td>{{$configuracao_pessoa->no_configuracao}}</td>
                                <td>
                                    @if($configuracao_pessoa->no_valor)
                                        <span class="badge badge-success badge-sm">Configurado</span>
        							@else
        								<span class="badge badge-danger badge-sm">Não configurado</span>
        							@endif
                                </td>
                                <td class="text-truncate">
                                    @if(strpos($configuracao_pessoa->no_valor, 'data:image/')===false)
                                        <span data-toggle="tooltip" title="{{$configuracao_pessoa->no_valor}}">{{$configuracao_pessoa->no_valor}}</span>
                                    @else
                                        {{$configuracao_pessoa->no_valor}}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="alert alert-danger mb-0">
                                        Nenhuma configuração foi encontrada.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
