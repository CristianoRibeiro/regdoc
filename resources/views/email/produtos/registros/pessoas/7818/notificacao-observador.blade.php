@extends('email.produtos.registros.pessoas.7818.layout.principal')

@section('content')
	<table width="80%" align="center" bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="text-align: justify; font-size: 15px;">
		<tr>
			<td>
				Olá,<br><br>
				Compradores: 
				@forelse($registro_fiduciario->registro_fiduciario_parte as $item)
				
					@if($item->id_tipo_parte_registro_fiduciario == 1)
						{{$item->no_parte}}, 
					@endif
				@empty
					Nenhum comprador foi encontrado!
				@endforelse
				<br />
				
			    @if($registro_fiduciario->empreendimento)
					Empreendimento / Unidade: 
					{{$registro_fiduciario->empreendimento->no_empreendimento}} / 
					{{$registro_fiduciario->nu_unidade_empreendimento}}<br />
				@elseif($registro_fiduciario->no_empreendimento)
					Empreendimento / Unidade: 
					{{$registro_fiduciario->no_empreendimento}} / 
					{{$registro_fiduciario->nu_unidade_empreendimento}}<br />
				@endif
				@if($registro_fiduciario->nu_proposta)
					Proposta: {{$registro_fiduciario->nu_proposta}}
				@endif
				@if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
					<br />
				@endif
				@if($registro_fiduciario->nu_contrato)
					Contrato: {{$registro_fiduciario->nu_contrato}}
				@endif <br /><br />

				{{-- Mensagem observador --}}
				{!! $mensagemBradesco  !!}<br /><br />

				<hr size="1" /> 

				<p style="text-align: center !important;" align="center">
					<strong>Importante:</strong> 
					Caso o comprador não consiga acessar o link, 
					contate nossos canais de atendimento.
				</p>

				<p style="text-align: center !important;" align="center">
					Telefone: (11) 4007-1965 / WhatsApp: (11) 9 8982-3818 <br />
					E-mail: bradesco@regdoc.com.br
				</p>
			</td>
		</tr>
	</table>
@endsection