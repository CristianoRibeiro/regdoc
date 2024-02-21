@extends('email.layouts.principal')

@section('content')
	Olá {{$nome}},<br /><br />

	Um novo comentário foi inserido no registro nº {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}, acesse o sistema para conferir o comentário completo.<br /><br />
    <b>Comentario:</b> {{$comentario}} <br /><br />

	@if($registro_fiduciario->empreendimento)
		<b>Empreendimento / Unidade:</b> {{$registro_fiduciario->empreendimento->no_empreendimento}} / {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
	@elseif($registro_fiduciario->no_empreendimento)
		<b>Empreendimento / Unidade:</b> {{$registro_fiduciario->no_empreendimento}} / {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
	@endif
    @if($registro_fiduciario->nu_proposta)
        <b>Proposta:</b> {{$registro_fiduciario->nu_proposta}}
    @endif
    @if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
        <br />
    @endif
    @if($registro_fiduciario->nu_contrato)
        <b>Contrato:</b> {{$registro_fiduciario->nu_contrato}}
    @endif
@endsection
