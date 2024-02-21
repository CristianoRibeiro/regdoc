<table id="canais-pdv" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="20%">Nome</th>
            <th width="20%">E-mail</th>
            <th width="15%">Código</th>
            <th width="15%">Parceiro</th>
            <th width="15%">CNPJ</th>
            <th width="15%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if ($todas_canais->count() > 0)
            @foreach ($todas_canais as $canais)
                <tr>
                    <td>{{ $canais->nome_canal_pdv_parceiro }}</td>
                    <td>{{ $canais->email_canal_pdv_parceiro }}</td>
                    <td>{{ $canais->codigo_canal_pdv_parceiro }}</td>
                    <td>{{ $canais->parceiro_canal_pdv_parceiro }}</td>
                    <td class="cnpj">{{ $canais->cnpj_canal_pdv_parceiro }}</td>
                    <td class="opcoes">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#detalhes-canal-pdv"
                                data-idcanalpdvparceiro="{{ $canais->id_canal_pdv_parceiro }}">
                                Visualizar
                            </button>
                            @if (Gate::allows('novo-canal-pdv-parceiro'))
                            <div class="btn-group" role="group">
                                <button id="opcoes" type="button" class="btn btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="opcoes">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#editar-canal-pdv"
                                        data-idcanalpdvparceiro="{{ $canais->id_canal_pdv_parceiro }}">
                                        Editar
                                    </a>
                                    <a class="dropdown-item desativar"
                                        data-idcanalpdvparceiro="{{ $canais->id_canal_pdv_parceiro }}">
                                        Desativar
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">
                    <div class="single alert alert-danger mb-0">
                        <i class="glyphicon glyphicon-remove"></i>
                        <div class="mensagem">
                            Nenhum Canal Pdv parceiro foi encontrado.
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
