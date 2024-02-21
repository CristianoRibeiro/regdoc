@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="100%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				<p>
					Olá <strong>{{$args_email['no_contato']}}</strong>,
				</p>
				<p>
					O envio do registro nº {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}} para a Central de Registros está aguardando a sua assinatura.
				</p>
				<p>
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
				</p>
			    <p>
					<a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="font-family: sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">
						ACESSE AQUI
					</a>
				</p>
				<p>
					Clique e acesse, seguindo a rota abaixo: <br />
					<strong>Acessar o contrato</strong> > <strong>Aba "Assinatura do XML"</strong> > <strong>Iniciar minha assinatura</strong>
				</p>						
				<p style="text-align: left">
					Protocolo: <strong>{{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}</strong> <br />
					Senha: <strong>{{$args_email['senha']}}</strong>
				</p>
				<p>
					Caso não consiga visualizar copie o link a seguir: <br />
					<span style="color: #CC092F;">
						{{URL::to('/protocolo/acessar/'.$args_email['token'])}}
					</span>
				</p>
			</td>
		</tr>
	</table>
@endsection