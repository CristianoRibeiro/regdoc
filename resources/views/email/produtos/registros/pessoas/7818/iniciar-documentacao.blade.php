@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="80%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				Olá {{$args_email['no_contato']}},<br /><br />

			    Concluímos as emissões dos certificados digitais (e-CPF) e o Contrato de Crédito Imobiliário Bradesco já está disponível para assinatura.<br /><br />

			    @if($registro_fiduciario->empreendimento)
					Empreendimento / Unidade: 
					{{$registro_fiduciario->empreendimento->no_empreendimento}} / 
					{{$registro_fiduciario->nu_unidade_empreendimento}} <br />
				@elseif($registro_fiduciario->no_empreendimento)
					Empreendimento / Unidade: 
					{{$registro_fiduciario->no_empreendimento}} / 
					{{$registro_fiduciario->nu_unidade_empreendimento}} <br />
				@endif
				@if($registro_fiduciario->nu_proposta)
					Proposta: {{$registro_fiduciario->nu_proposta}}
				@endif
				@if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
					<br />
				@endif
				@if($registro_fiduciario->nu_contrato)
					Contrato: {{$registro_fiduciario->nu_contrato}}
				@endif
			    <br /><br />

			    Para assinar utilize uma das opções abaixo:<br /><br />

			    <a 
			    	href="{{URL::to('/')}}" target="_blank" 
			    	style="font-family: 'BradescoSans', sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;"
			    >
					ACESSAR COM PROTOCOLO E SENHA
				</a>
				<br /><br />

			    Protocolo: {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}} <br />
			    Senha: {{$args_email['senha']}} <br /><br />

			    Ou <br /><br />

			    <a 
			    	href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" 
			    	target="_blank"
			    	style="font-family: 'BradescoSans', sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;"
			    >
					ACESSAR DIRETAMENTE
				</a>
				<br />
				<hr size="1" /> 
				<p>
					Em caso de dúvidas contate nossos canais de atendimento<br />
					Telefone: (11) 4007-1965 <br />
					WhatsApp: (11) 9 8982-3818 <br />
					E-mail: bradesco@regdoc.com.br
				</p>
			</td>
		</tr>
	</table>
@endsection