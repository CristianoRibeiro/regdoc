<table class="table table-striped table-bordered mt-2 mb-0">
    <thead>
    <tr>
        @if (Gate::allows('registros-certificados-acoes'))
            <th width="40%">Parte</th>
            <th width="20%">Tipo</th>
            <th width="25%">Situação</th>
            <th width="15%">Ações</th>
        @else
            <th width="55%">Parte</th>
            <th width="20%">Tipo</th>
            <th width="25%">Situação</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte)
            @if(count($registro_fiduciario_parte->registro_fiduciario_procurador)>0)
                @foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador)
                    @php
                        $parte_emissao_certificado = $registro_fiduciario_procurador->parte_emissao_certificado;
                    @endphp
                    <tr>
                        <td>
                            {{$registro_fiduciario_procurador->no_procurador}}<br />
                            <span class="badge badge-primary badge-sm">Procurador da parte: {{$registro_fiduciario_parte->no_parte}}</span>
                        </td>
                        <td>{{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
                        <td>
                            <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                        </td>
                        @if (Gate::allows('registros-certificados-acoes'))
                            <td>
                                @if (Gate::allows('registros-certificados-novo', $parte_emissao_certificado))
                                    @php
                                        $campos_novo = [
                                            'no_parte' => $registro_fiduciario_procurador->no_procurador,
                                            'nu_cpf_cnpj' => $registro_fiduciario_procurador->nu_cpf_cnpj,
                                            'nu_telefone_contato' => $registro_fiduciario_procurador->nu_telefone_contato,
                                            'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                                            'in_cnh' => $registro_fiduciario_procurador->in_cnh,
                                            'nu_cep' => $registro_fiduciario_procurador->nu_cep,
                                            'no_endereco' => $registro_fiduciario_procurador->no_endereco,
                                            'nu_endereco' => $registro_fiduciario_procurador->nu_endereco,
                                            'no_bairro' => $registro_fiduciario_procurador->no_bairro,
                                            'id_cidade' => $registro_fiduciario_procurador->id_cidade,
                                            'id_pedido' => $registro_fiduciario_parte->registro_fiduciario->registro_fiduciario_pedido->id_pedido
                                        ];
                                    @endphp
                                    <button class="btn btn-success btn-sm" type="button" data-target="#certificados" data-toggle="modal" data-campos="{{json_encode($campos_novo)}}" data-operacao="novo">
                                        Nova emissão
                                    </button>
                                @endif
                                @if (Gate::allows('registros-certificados-alterar', $parte_emissao_certificado))
                                    <button class="btn btn-primary btn-sm" type="button" data-target="#certificados" data-toggle="modal" data-idparteemissao="{{ $parte_emissao_certificado->id_parte_emissao_certificado }}" data-operacao="editar">
                                        Alterar situação
                                    </button>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                @php
                    $parte_emissao_certificado = $registro_fiduciario_parte->parte_emissao_certificado;
                @endphp
                <tr>
                    <td>
                        {{$registro_fiduciario_parte->no_parte}}<br />
                    </td>
                    <td>{{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
                    <td>
                        <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                    </td>
                    @if (Gate::allows('registros-certificados-acoes'))
                        <td>
                            @if (Gate::allows('registros-certificados-novo', $parte_emissao_certificado))
                                @php
                                    $campos_novo = [
                                        'no_parte' => $registro_fiduciario_parte->no_parte,
                                        'nu_cpf_cnpj' => $registro_fiduciario_parte->nu_cpf_cnpj,
                                        'nu_telefone_contato' => $registro_fiduciario_parte->nu_telefone_contato,
                                        'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                        'in_cnh' => $registro_fiduciario_parte->in_cnh,
                                        'nu_cep' => $registro_fiduciario_parte->nu_cep,
                                        'no_endereco' => $registro_fiduciario_parte->no_endereco,
                                        'nu_endereco' => $registro_fiduciario_parte->nu_endereco,
                                        'no_bairro' => $registro_fiduciario_parte->no_bairro,
                                        'id_cidade' => $registro_fiduciario_parte->id_cidade,
                                        'id_pedido' => $registro_fiduciario_parte->registro_fiduciario->registro_fiduciario_pedido->id_pedido
                                    ];
                                @endphp
                                <button class="btn btn-success btn-sm" type="button" data-target="#certificados" data-toggle="modal" data-campos="{{json_encode($campos_novo)}}" data-operacao="novo">
                                    Nova emissão
                                </button>
                            @endif
                            @if (Gate::allows('registros-certificados-alterar', $parte_emissao_certificado))
                                <button class="btn btn-primary btn-sm" type="button" data-target="#certificados" data-toggle="modal" data-idparteemissao="{{ $parte_emissao_certificado->id_parte_emissao_certificado }}" data-operacao="editar">
                                    Alterar situação
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
