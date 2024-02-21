<style type="text/css">
	@font-face {
		font-family: 'BradescoSans';
		src: url('{{asset('fonts/BradescoSans/WebFonts/WOFF/BradescoSans-Regular.woff') }}');
		font-weight: normal;
		font-style: normal;
	}
</style>

<table width="600" height="auto" cellspacing="0" cellpadding="0" bgcolor="#ffffff" border="0" style="margin-bottom: 45px;" align="center">
	<tr>
		<td style="width: 100%; color: #000; background-color: #0C881E; vertical-align: top; text-align: center; font-family: 'BradescoSans', Verdana, sans-serif; font-weight: 700; font-size: 32px; padding: 30px;">
			<img src="{{asset('img/e-mails/76450/logo-branco-e-agro-1.png')}}" alt="e-agro" height="33" />
		</td>
	</tr>

	<tr width="100%">
		<td style="font-family: 'BradescoSans', Arial, Helvetica, sans-serif; padding-left: 15px; padding-right: 15px;">
			<tr width="100%">
				<td style="font-family: Arial, Helvetica, sans-serif; padding: 38px 64px; font-size: 18px;">
					@yield('content')
				</td>
			</tr>
		</td>
	</tr>
	
	<tr width="100%">
		<td>
			<table width="100%" cellspacing="0" cellpadding="0" align="center" style="margin-top: 25px;">
				<tr>
					<td style="background-color: #eee; height: 10px;"></td>
				</tr>
			</table>

			<table width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#eee" style="padding-bottom: 15px; padding-left: 20px; padding-right: 20px;">
				<tr>
					<td>
						<p style="margin-top: 0; margin-bottom: 0; line-height: 26px; font-size: 15px; color: #261d18; font-family: Verdana, sans-serif; font-weight: 400; text-align: center; padding: 16px 0">
							© 2022 e-agro - Instituição de Crédito LTDA.<br>
                            CNPJ: 15.010.931/0001-48<br><br>
                            Avenida Evangélica, 2.529 - Rua da Consolação, 2.302 | Bela Vista - São Paulo/SP<br>
                            © Todos os direitos reservados<br><br>
                            Caso não queira mais receber nossos e-mails, descadastre-se
						</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
