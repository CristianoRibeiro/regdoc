@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="100%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				<p>
					Olá <strong>{{$args_email['no_contato']}}</strong>,
				</p>
				<p>
					Olha que legal! Finalizamos o registro eletrônico do contrato. <br> 
					O cartório já emitiu o recibo do registro e a matricula atualizados
				</p>
				<br>
				<p>
					<strong>Veja o status do processo: </strong>
				</p>
				<p>
					<img width="100%" src="{{asset('img/e-mails/7818/timeline-parte-6.png')}}">
				</p>
				@include('email.produtos.registros.pessoas.7818.timeline-legenda')
				<br />
				<p>
					Acesse abaixo a plataforma RegDoc da Valid para consultar e baixar o contrato assinado e a matrícula atualizados.
				</p>
				<p>
					<a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="font-family: sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">
						ACESSE AQUI
					</a>
				</p>
				<p>
					Clique e acesse, seguindo a rota abaixo: <br />
					<strong>Acessar o contrato</strong> > <strong>Aba "Arquivos"</strong>
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