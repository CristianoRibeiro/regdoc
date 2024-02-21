@if($arquivo_grupo_produto->arquivo_grupo_produto_assinatura->count()>0)
    <div class="fieldset-group">
        <div class="panel table-rounded">
            <table class="table table-striped table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>CPF/CNPJ</th>
                        <th>Data / Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arquivo_grupo_produto->arquivo_grupo_produto_assinatura as $arquivo_assinatura)
                        <tr>
                            <td>{{$arquivo_assinatura->usuario_certificado->no_comum}}</td>
                            <td>{{$arquivo_assinatura->usuario_certificado->no_email}}</td>
                            <td>{{Helper::pontuacao_cpf_cnpj($arquivo_assinatura->usuario_certificado->nu_cpf_cnpj)}}</td>
                            <td>{{Helper::formata_data_hora($arquivo_assinatura->dt_ass_digital)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
