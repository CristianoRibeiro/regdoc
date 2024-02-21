@if($parte_emissao_certificado)
    <span class="text-{{($cores_situacoes[$parte_emissao_certificado->id_parte_emissao_certificado_situacao] ?? 'primary')}} font-weight-bold" data-toggle="tooltip" data-html="true" title="{{$parte_emissao_certificado->de_observacao_situacao}}">
        <i class="{{($icones_situacoes[$parte_emissao_certificado->id_parte_emissao_certificado_situacao] ?? 'fas fa-info-circle')}} fa-fw"></i> {{$parte_emissao_certificado->parte_emissao_certificado_situacao->no_situacao}}<br />
    </span>
    <small>
        @if($parte_emissao_certificado->dt_situacao)
            @if ($parte_emissao_certificado->id_parte_emissao_certificado == config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO'))
                <b>Data da emissão: {{Carbon\Carbon::parse($parte_emissao_certificado->dt_situacao)->format('d/m/Y H:i:s')}}</b>
            @else
                <b>Data da situação: {{Carbon\Carbon::parse($parte_emissao_certificado->dt_situacao)->format('d/m/Y H:i:s')}}</b>
            @endif
        @endif
        @if($parte_emissao_certificado->dt_agendamento)
            <br /><b>Data do agendamento: {{Carbon\Carbon::parse($parte_emissao_certificado->dt_agendamento)->format('d/m/Y H:i:s')}}</b>
        @endif
    </small>
@else
    <span class="font-weight-bold" data-toggle="tooltip" title="A parte não terá emissão de certificado.">
        <i class="fas fa-minus-circle"></i> Sem emissão de certificado
    </span>
@endif