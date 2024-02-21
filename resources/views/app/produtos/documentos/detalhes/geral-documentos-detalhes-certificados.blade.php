<table class="table table-striped table-bordered mt-2 mb-0">
    <thead>
    <tr>
        @if (Gate::allows('documentos-certificados-acoes'))
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
        @foreach($documento_partes_emissao_certificado as $documento_parte)
            @if(count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N')
                @foreach($documento_parte->documento_procurador as $documento_procurador)
                    @php
                        $parte_emissao_certificado = $documento_procurador->parte_emissao_certificado;
                    @endphp
                    <tr>
                        <td>
                            {{$documento_procurador->no_procurador}}<br />
                            <span class="badge badge-primary badge-sm">Procurador da parte: {{$documento_parte->no_parte}}</span>
                        </td>
                        <td>{{$documento_parte->documento_parte_tipo->no_documento_parte_tipo}}</td>
                        <td>
                            <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                        </td>
                        @if (Gate::allows('documentos-certificados-acoes'))
                            <td>
                                @if (Gate::allows('documentos-certificados-novo', $parte_emissao_certificado))
                                    @php
                                        $campos_novo = [
                                            'no_parte' => $documento_procurador->no_procurador,
                                            'nu_cpf_cnpj' => $documento_procurador->nu_cpf_cnpj,
                                            'nu_telefone_contato' => $documento_procurador->nu_telefone_contato,
                                            'no_email_contato' => $documento_procurador->no_email_contato,
                                            'in_cnh' => $documento_procurador->in_cnh,
                                            'nu_cep' => $documento_procurador->nu_cep,
                                            'no_endereco' => $documento_procurador->no_endereco,
                                            'nu_endereco' => $documento_procurador->nu_endereco,
                                            'no_bairro' => $documento_procurador->no_bairro,
                                            'id_cidade' => $documento_procurador->id_cidade,
                                            'id_pedido' => $documento_procurador->documento_parte->documento->id_pedido
                                        ];
                                    @endphp
                                    <button class="btn btn-success btn-sm" type="button" data-target="#certificados" data-toggle="modal" data-campos="{{json_encode($campos_novo)}}" data-operacao="novo">
                                        Nova emissão
                                    </button>
                                @endif
                                @if (Gate::allows('documentos-certificados-alterar', $parte_emissao_certificado))
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
                    $parte_emissao_certificado = $documento_parte->parte_emissao_certificado;
                @endphp
                <tr>
                    <td>
                        {{$documento_parte->no_parte}}<br />
                    </td>
                    <td>{{$documento_parte->documento_parte_tipo->no_documento_parte_tipo}}</td>
                    <td>
                        <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                    </td>
                    @if (Gate::allows('documentos-certificados-acoes'))
                        <td>
                            @if (Gate::allows('documentos-certificados-novo', $parte_emissao_certificado))
                                @php
                                    $campos_novo = [
                                        'no_parte' => $documento_parte->no_parte,
                                        'nu_cpf_cnpj' => $documento_parte->nu_cpf_cnpj,
                                        'nu_telefone_contato' => $documento_parte->nu_telefone_contato,
                                        'no_email_contato' => $documento_parte->no_email_contato,
                                        'in_cnh' => $documento_parte->in_cnh ?? 'N',
                                        'nu_cep' => $documento_parte->nu_cep,
                                        'no_endereco' => $documento_parte->no_endereco,
                                        'nu_endereco' => $documento_parte->nu_endereco,
                                        'no_bairro' => $documento_parte->no_bairro,
                                        'id_cidade' => $documento_parte->id_cidade,
                                        'id_pedido' => $documento_parte->documento->id_pedido
                                    ];
                                @endphp
                                <button class="btn btn-success btn-sm" type="button" data-target="#certificados" data-toggle="modal" data-campos="{{json_encode($campos_novo)}}" data-operacao="novo">
                                    Nova emissão
                                </button>
                            @endif
                            @if (Gate::allows('documentos-certificados-alterar', $parte_emissao_certificado))
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
