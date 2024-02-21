<div style="background:#F5F5F5; font-size:13px; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; padding:10px">
	<h2 style="margin:0 0 10px 0;"><img src="{{asset('img/e-mails/2769/logo_superlogica.png')}}" alt="SUPERLÓGICA" width="200" /></h2>
	<div style="background:#FFF;border:1px solid #0064FF; padding:15px">
        @yield('content')
	</div>
	<p style="line-height:20px;font-size:11px;margin-bottom:0">Este é um e-mail automático, por favor não o responda.<br /> &copy; {{Carbon\Carbon::now()->format('Y')}} REGDOC | <strong>Valid Hub</strong> uma empresa do grupo Valid Soluções S.A.</p>
</div>
