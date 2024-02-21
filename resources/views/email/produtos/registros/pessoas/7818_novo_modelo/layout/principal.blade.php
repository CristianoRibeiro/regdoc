<table width="600" height="auto" cellspacing="0" cellpadding="0" bgcolor="#ffffff" border="0" style="margin-bottom: 45px;" align="center">
	<tr>
		<td style="width: 100%; color: #000; background-color: #fff; vertical-align: top; text-align: center; font-family: Verdana, sans-serif; font-weight: 700; font-size: 32px; padding: 10px;">
			<img src="{{asset('img/e-mails/7818/logo-bradesco.png')}}" alt="Bradesco" height="60" />
		</td>
	</tr>
	<tr>
		<td>
			<table width="95%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding-top: 5px; padding-bottom: 20px;">
				<tr>
					<td style="border-top: 1px solid #939598;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr width="100%">
		<td style="font-family: \'Trebuchet MS\', Arial, Helvetica, sans-serif; padding-left: 15px; padding-right: 15px;">
			@yield('content')
		</td>
	</tr>
	<tr width="100%">
		<td>
			<table width="95%" cellspacing="0" cellpadding="0" align="center" style="margin-top: 25px;">
				<tr>
					<td style="background-color: #CC092F; height: 10px;"></td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#ededed" style="padding-bottom: 25px; padding-top: 25px; padding-left: 20px; padding-right: 20px;">
				<tr style="padding-top: 16px;">
					<td>
						<p style="font-family: Verdana, sans-serif; font-weight: bold; margin-top: 0; margin-bottom: 0; line-height: 18px; font-size: 11px; color: #261d18;">
							Este é um e-mail automático, por favor não o responda.<br /> &copy; {{Carbon\Carbon::now()->format('Y')}} REGDOC | <strong>Valid Hub</strong> uma empresa do grupo Valid Soluções S.A.
						</p>
					</td>
				</tr>
			</table>

			<table width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#ededed" style="padding-bottom: 15px; padding-left: 20px; padding-right: 20px;">
				<tr>
					<td>
						<p style="margin-top: 0; margin-bottom: 0; line-height: 10px; font-size: 8px; color: #261d18; font-family: Verdana, sans-serif; font-weight: 400;">
							Esta mensagem, incluindo seus anexos, tem caráter confidencial e seu conteúdo é restrito ao destinatário da mensagem. Caso você a tenha recebido por engano, queira por favor apagá-la de seus arquivos. Qualquer uso não autorizado, replicação ou disseminação desta ou parte dela é expressamente proibido. <br>
							Mensagem autómática. Favor não responder.
						</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>