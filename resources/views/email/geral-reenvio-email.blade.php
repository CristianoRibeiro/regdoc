<div style="background:#F5F5F5; font-size:13px; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; padding:10px">
    <h2 style="margin:0 0 10px 0;"><img src="{{asset('img/logo-01.png')}}" alt="REGDOC" /></h2>
    <div style="background:#FFF;border:1px solid #005071; padding:15px">
        Olá {{$no_parte}}, este são os dados de acesso ao seu protocolo de Registro Fiduciário.<br /><br />
        <a href="{{$url_email}}"><strong>&raquo; Acessar</strong></a> <br /> <br />
        <strong>Protocolo:</strong> {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}} <br />
        <strong>Senha:</strong> {{$senha_gerada}} <br /><br />
        <a href="{{URL::to('/protocolo/acessar/'.$token)}}" target="_blank"><b>&raquo; Clique aqui para acessar o protocolo diretamente</b></a>
    </div>
    <p style="line-height:20px;font-size:11px;margin-bottom:0">Este é um e-mail automático, por favor não o responda.<br /> &copy; {{Carbon\Carbon::now()->format('Y')}} REGDOC | <strong>Valid Hub</strong> uma empresa do grupo Valid Soluções S.A.</p>
</div>
