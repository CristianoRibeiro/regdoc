@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')
	<h3>Olá, {{ $registro_fiduciario_observador->no_observador }}!</h3>

	<p>{!! $mensagem !!}</p>

	@if($registro_fiduciario->empreendimento)
        <strong>Empreendimento / Unidade:</strong> 
        {{$registro_fiduciario->empreendimento->no_empreendimento}} / 
        {{$registro_fiduciario->nu_unidade_empreendimento}}<br>
    @elseif($registro_fiduciario->no_empreendimento)
        <strong>Empreendimento / Unidade:</strong> 
        {{$registro_fiduciario->no_empreendimento}} / 
        {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
    @endif
    @if($registro_fiduciario->nu_proposta)
        <strong>Proposta:</strong> {{$registro_fiduciario->nu_proposta}}
    @endif
    @if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
        <br>
    @endif
    @if($registro_fiduciario->nu_contrato)
        <strong>Contrato:</strong> {{$registro_fiduciario->nu_contrato}}
    @endif
@endsection
