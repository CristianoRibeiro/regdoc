<table>
    <thead>
        <tr>
            <th>Protocolo</th>
            <th>Título</th>
            <th>Contrato</th>
            <th>Cessionária</th>
            <th>CNPJ da cessionária</th>
            <th>Cedente</th>
            <th>CNPJ da cedente</th>
            <th>Administradora da cedente</th>
            <th>CNPJ da administradora da cedente</th>
            <th>Escritório de cobrança</th>
            <th>CNPJ do escritório de cobrança</th>
            <th>Escritório de advocacia</th>
            <th>CNPJ do escritório de advocacia</th>
            <th>Situação</th>
            <th>Data de cadastro</th>
            <th>Data da última atualização</th>
            <th>Data de início da proposta</th>
            <th>Data de transformação para contrato</th>
            <th>Data de geração dos documentos</th>
            <th>Data de início da assinatura</th>
            <th>Data de finalização</th>
        </tr>
    </thead>
    <tbody>
        @if ($documentos->count()>0)
            @foreach ($documentos as $documento)
                <tr>
                    <td>{{$documento->pedido->protocolo_pedido}}</td>
                    <td>{{$documento->no_titulo ?? NULL}}</td>
                    <td>{{$documento->nu_contrato ?? NULL}}</td>
                    <td>{{$documento->documento_parte_cessionaria->no_parte ?? NULL}}</td>
                    <td>{{Helper::pontuacao_cpf_cnpj($documento->documento_parte_cessionaria->nu_cpf_cnpj ?? NULL)}}</td>
                    <td>{{$documento->documento_parte_cedente->no_parte ?? NULL}}</td>
                    <td>{{Helper::pontuacao_cpf_cnpj($documento->documento_parte_cedente->nu_cpf_cnpj ?? NULL)}}</td>
                    <td>{{$documento->documento_administradora_cedente->no_parte ?? NULL}}</td>
                    <td>{{Helper::pontuacao_cpf_cnpj($documento->documento_administradora_cedente->nu_cpf_cnpj ?? NULL)}}</td>
                    <td>{{$documento->documento_escritorio_cobranca->no_parte ?? NULL}}</td>
                    <td>{{Helper::pontuacao_cpf_cnpj($documento->documento_escritorio_cobranca->nu_cpf_cnpj ?? NULL)}}</td>
                    <td>{{$documento->documento_escritorio_advocacia->no_parte ?? NULL}}</td>
                    <td>{{Helper::pontuacao_cpf_cnpj($documento->documento_escritorio_advocacia->nu_cpf_cnpj ?? NULL)}}</td>
                    <td>{{$documento->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_cadastro)}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_alteracao)}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_inicio_proposta)}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_transformacao_contrato)}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_documentos_gerados)}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_inicio_assinatura)}}</td>
                    <td>{{Helper::formata_data_hora($documento->dt_finalizacao)}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>
                    Nenhum documento foi encontrado.
                </td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td>
				Total de&nbsp;<b>{{count($documentos)}}</b>&nbsp;documento(s)
			</td>
		</tr>
	</tfoot>
</table>
