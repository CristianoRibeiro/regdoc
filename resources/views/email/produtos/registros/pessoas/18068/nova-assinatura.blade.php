@extends('email.produtos.registros.pessoas.18068.layout.principal')

@section('content')
	<table width="100%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				<p>
					Olá <strong>{{$args_email['no_contato']}}</strong>,
				</p>
				<p>
					Existem novos documentos disponíveis para sua assinatura na plataforma.
				</p>
				<p>
					<a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="font-family: sans-serif; font-size: 14px; background-color: #f16c08 !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">
						ACESSE AQUI
					</a>
				</p>
				<p>
					Clique e acesse, seguindo a rota abaixo: <br />
					<strong>Acessar o contrato</strong> > <strong>Opção "Assinatura de Outros Documentos"</strong> > <strong>Iniciar minha assinatura</strong>
				</p>						
				<p style="text-align: left">
					Protocolo: <strong>{{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}</strong> <br />
					Senha: <strong>{{$args_email['senha']}}</strong>
				</p>
				<p>
					Caso não consiga visualizar copie o link a seguir: <br />
					<span style="color: #f16c08;">
						{{URL::to('/protocolo/acessar/'.$args_email['token'])}}
					</span>
				</p>
				<hr size="1" />
				<h3 style="color: #f16c08">Como realizar a assinatura?</h3>
				<p>
					É necessário ter em mãos o celular com o aplicativo VIDaaS instalado, pois a assinatura será realizada por ele.
				</p>
				<p>
					Nessa etapa, você deverá escolher de que forma a sua assinatura eletrônica será feita, se por QRCode ou por notificação do ViDaaS.
				</p>
				<p>
					Para saber mais sobre como realizar a assinatura do seu contrato, veja o vídeo abaixo:
				</p>
				<p>
					<a href="https://youtu.be/F43EhIvWVqo" target="_blank" style="font-family: sans-serif; font-size: 14px; background-color: #000 !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">
						Como realizar a assinatura de um documento
					</a>
				</p>
				<p>
					Em caso de dúvidas fale conosco pelo telefone <strong style="color: #f16c08">(11) 4007-1965</strong>. Ou se preferir envie um e-mail para <strong style="color: #f16c08">itau@regdoc.com.br</strong>.
				</p>
			</td>
		</tr>
	</table>
@endsection