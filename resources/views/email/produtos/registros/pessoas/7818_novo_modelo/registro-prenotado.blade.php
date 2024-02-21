@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="100%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				<p>
					Olá <strong>{{$args_email['no_contato']}}</strong>,
				</p>
				<p>
					O contrato número <strong>{{$registro_fiduciario->nu_contrato}}</strong> de <strong>{{$registro_fiduciario->registro_fiduciario_pedido->pedido->produto->no_produto ?? 'Registro'}}</strong> Bradesco foi enviado para registro em cartório e foi gerado o protocolo {{$args_email['numero_prenotacao'] ?? '##'}}.
				</p>
				<br>
				<p>
					<strong>Veja o status do processo: </strong>
				</p>
				<p>
					<img width="100%" src="{{asset('img/e-mails/7818/timeline-parte-4.png')}}">
				</p>
				@include('email.produtos.registros.pessoas.7818.timeline-legenda')
				<br />
				<p>
					<strong>Atenção!</strong> O cartório da região onde o imóvel esta localizado pode solicitar alguns documentos para realizar o registro e você será notificado por este canal. Antes da 4ª etapa, o <strong>comprador</strong> receberá por e-mail a guia do ITBI e custas cartoriais para pagamento.
				</p>
				<p>
					O prazo médio de resposta do cartório é de 30 dias.
				</p>
				<p>
					<a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="font-family: sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">
						ACESSE AQUI
					</a>
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
				<hr size="1" />
				<p>
					Em caso de dúvidas fale conosco pelo telefone <strong style="color: #CC092F">(11) 4007-1965</strong>. Ou se preferir envie um e-mail para <strong style="color: #CC092F">bradesco@regdoc.com.br</strong>.
				</p>
			</td>
		</tr>
	</table>
@endsection