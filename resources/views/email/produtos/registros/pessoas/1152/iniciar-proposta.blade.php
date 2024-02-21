<?php
$array_name = explode(' ', $args_email['no_contato']);
if(strlen($array_name[0])<=3) {
	$name = $array_name[0].' '.($array_name[1] ?? '');
} else {
	$name = $array_name[0];
}
?>

@extends('email.produtos.registros.pessoas.1152.layout.principal')

@section('content')
	<table width="80%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
		<tr>
			<td>
				<h1 style="color: #f5822a; font-size: 23px; font-family: Verdana, Geneva, Tahoma, sans-serif; font-weight: bold; line-height: 34.5px; margin-bottom: 0;">
					Olá, {{ucfirst(mb_strtolower($name ?? NULL, 'UTF-8'))}}
				</h1>
			</td>
		</tr>
		<tr>
			<td style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px; font-weight: regular; line-height: 21px; color: #423a3d;">
				<p style="margin-top: 10px; margin-bottom: 0; font-weight: bold;">
					Processo: {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}

					@if($registro_fiduciario->empreendimento)
						<br />{{$registro_fiduciario->empreendimento->no_empreendimento}} - {{$registro_fiduciario->nu_unidade_empreendimento}}
					@elseif($registro_fiduciario->no_empreendimento)
						<br />{{$registro_fiduciario->no_empreendimento}} - {{$registro_fiduciario->nu_unidade_empreendimento}}
					@endif
				</p>
				<p style="margin-top: 30px; margin-bottom: 0;">
					Queremos te comunicar que foi solicitado o agendamento para emissão do seu certificado digital (ICP-Brasil) via Certificação em nuvem - <strong>Valid</strong>.
				</p>
				<p style="margin-top: 10px; margin-bottom: 0;">
					Por isso, pedimos para lhe contatar.
				</p>

				<h1 style="margin-top: 30px; margin-bottom: 0; color: #f5822a; font-size: 23px; font-family: Verdana, Geneva, Tahoma, sans-serif; font-weight: bold; line-height: 34.5px;">
					Atenção
				</h1>

				<p style="margin-top: 30px; margin-bottom: 0;">
					Os serviços prestados pela certificadora não gerarão custo adicional aos nossos clientes.
				</p>
				<p style="margin-top: 10px; margin-bottom: 0;">
					Documentos que poderão ser solicitado: Carteira Nacional de Habilitação (CNH) ou passaporte vigente.
				</p>
			</td>
		</tr>
	</table>
@endsection
