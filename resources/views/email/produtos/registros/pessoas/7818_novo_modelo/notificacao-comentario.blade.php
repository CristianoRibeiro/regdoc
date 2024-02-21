@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="80%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				Olá {{$nome}},<br /><br />

				Temos um comentário referente ao registro nº {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}: <br /><br />
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
			</td>
		</tr>
	</table>
@endsection