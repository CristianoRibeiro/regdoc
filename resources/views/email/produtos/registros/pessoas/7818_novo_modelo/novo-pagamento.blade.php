@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="100%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				<p>
					Olá <strong>{{$args_email['no_contato']}}</strong>,
				</p>
				<p>
					Agora que esta tudo certo, vamos iniciar a etapa de registro do seu contrato número <strong>{{$registro_fiduciario->nu_contrato}}</strong> de <strong>{{$registro_fiduciario->registro_fiduciario_pedido->pedido->produto->no_produto ?? 'Registro'}}</strong>.
				</p>
				<p>
					É necessário que você realize o pagamento do Imposto de Transmissão de Bens Imóveis (ITBI) e das custas cartorárias que estão disponíveis na plataforma RegDoc. Após o pagamento envie os comprovantes por meio da guia <strong>Pagamentos</strong>.
				</p>
				<p>
					Fique atento ao prazo de vencimento do pagamento, ele pode variar de acordo com cada cartório.
				</p>
				<p>
					<a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="font-family: sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">
						ACESSE AQUI
					</a>
				</p>
				<p>
					Clique e acesse para obter as guias e enviar os comprovantes de pagamento, seguindo a rota abaixo: <br />
					<strong>Acessar o contrato</strong> > <strong>Aba "Pagamentos"</strong>
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
				<p>
					Se você optou por incorporar esses custos ao financiamento, independente do valor que você pagará à Prefeitura e ao cartório, o valor que foi aprovado em contrato será creditado em sua conta corrente, como forma de reembolso, após a finalização do registro. 
				</p>
				<hr size="1" />
				<p>
					Em caso de dúvidas fale conosco pelo telefone <strong style="color: #CC092F">(11) 4007-1965</strong>. Ou se preferir envie um e-mail para <strong style="color: #CC092F">bradesco@regdoc.com.br</strong>.
				</p>
			</td>
		</tr>
	</table>
@endsection