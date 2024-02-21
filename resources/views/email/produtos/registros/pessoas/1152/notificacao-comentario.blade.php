@extends('email.produtos.registros.pessoas.1152.layout.secundario')

@section('content')
	<table width="80%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
		<tr>
			<td>
				<h1 style="color: #31b7f3; font-size: 23px; font-family: Verdana, Geneva, Tahoma, sans-serif; font-weight: bold; line-height: 34.5px; margin-bottom: 0;">
					Olá, tudo bem?
				</h1>
			</td>
		</tr>
		<tr>
			<td style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px; font-weight: regular; line-height: 21px; color: #423a3d;">
				<p style="margin-top: 10px; margin-bottom: 0;">
					Um novo comentário foi inserido no contrato:
				</p>
				<p style="margin-top: 30px; margin-bottom: 0; font-weight: bold;">
					Processo: {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}

					@if($registro_fiduciario->empreendimento)
						<br />{{$registro_fiduciario->empreendimento->no_empreendimento}} - {{$registro_fiduciario->nu_unidade_empreendimento}}
					@elseif($registro_fiduciario->no_empreendimento)
						<br />{{$registro_fiduciario->no_empreendimento}} - {{$registro_fiduciario->nu_unidade_empreendimento}}
					@endif
				</p>
				<p style="margin-top: 30px; margin-bottom: 0;">
					<b>Comentario:</b> {{$comentario}}
				</p>   			

				<a href="{{URL::to('/app')}}" style="display: block; margin-top: 35px; margin-bottom: 0; color:#31b7f3; font-size: 21px; font-weight: bold; text-decoration:none; text-align:center">
					[ ACESSE AQUI ]
				</a>

				<p style="margin-top: 30px; margin-bottom: 0;">
					Atenciosamente,
				</p>
			</td>
		</tr>
	</table>
@endsection
