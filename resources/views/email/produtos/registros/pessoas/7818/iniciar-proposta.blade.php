@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="80%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				Olá {{$args_email['no_contato']}},<br>
				<p>
					Nós somos do RegDoc da Valid e em parceria com o Bradesco iremos te auxiliar no processo de assinatura digital do contrato e de registro em cartório de forma eletrônica, sem precisar sair de casa.
				</p>
				<p>
					Veja como é fácil: <br>
					O primeiro passo é o agendamento de uma videoconferência para que possamos emitir e ativar seu certificado digital (e-CPF).<br><br>

					Ele permitirá que você assine documentos digitais com a mesma validade jurídica de um documento assinado fisicamente e autenticado em cartório.

					<br><br>
					Na data escolhida será necessário:

					<p style="margin-left: 32px !important;">
						&#10004; Câmera ativa no momento da videoconferência;<br>
						&#10004; Numero do ticket (será enviado por e-mail, após confirmação do agendamento);<br>
						&#10004; Documento de identificação em mãos.
					</p>
				</p>

			    <a 
			    	href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" 
			    	target="_blank" 
			    	style="font-family: 'BradescoSans', sans-serif; font-size: 14px; background-color: #CC092F !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;"
			    >
					CLIQUE AQUI PARA AGENDAR
				</a>
				<p>
					Caso não consiga visualizar copie o link a seguir: <br />
					<span style="color: #CC092F;">
						<strong>
							{{URL::to('/protocolo/acessar/'.$args_email['token'])}}
						</strong>
					</span>
				</p>
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