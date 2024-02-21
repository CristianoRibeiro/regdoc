<div class="mb-3">
	@if(Gate::allows('registros-detalhes-devolutivas-nova', $registro_fiduciario))
		<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="novo">
			<i class="fas fa-plus-circle"></i> Nova nota devolutiva
		</button>
	@endif
</div>

@forelse ($registro_fiduciario->registro_fiduciario_nota_devolutivas as $nota_devolutiva)
	@switch($nota_devolutiva->registro_fiduciario_nota_devolutiva_situacao->id_registro_fiduciario_nota_devolutiva_situacao)
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_RESPOSTA'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-responder" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Responder</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_ENVIO_RESPOSTA'))
			@php
				$classe = 'alert alert-primary';
				$botao = '<a href="#" class="btn btn-primary mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.FINALIZADA'))
			@php
				$classe = 'alert alert-success';
				$botao = '<a href="#" class="btn btn-success mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.CANCELADA'))
			@php
				$classe = 'alert alert-danger';
				$botao = '<a href="#" class="btn btn-danger mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_CATEGORIZACAO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.EM_ATUACAO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_DOCUMENTOS_PELO_CLIENTE'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDAMENTO_ADITAMENTO_BANCO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.EM_DILIGENCIAS'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_CARTORIO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_ASSINATURAS_DAS_PARTES'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_DOCUMENTOS_PELO_BANCO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_PAGAMENTO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_RETORNO_CLIENTE'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_ATUACAO'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-visualizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Visualizar detalhes</a>';
				$botao .= '<a href="#" class="btn btn-primary mt-2 ml-1" data-toggle="modal" data-target="#registro-fiduciario-nota-devolutiva-categorizar" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciarionotadevolutiva="'.$nota_devolutiva->id_registro_fiduciario_nota_devolutiva.'">Definir categorização</a>';
			@endphp
		@break
		@default
			@php
				$classe = 'alert alert-warning';
			@endphp
	@endswitch
	<div class="alert {{$classe}}" id="div-notas-devolutivas-new">

		<h5><b> Nota devolutiva nº {{$nota_devolutiva->id_registro_fiduciario_nota_devolutiva}}</b></h5>
		
		@if($nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao != 3)
			
			<div style="align-content: flex-end">
				@if(Gate::allows('atualizar-registro-nota-devolutiva'))
					<form id="submit-atualizacao-notas-devolutivas" data-id-registro="{{$registro_fiduciario->id_registro_fiduciario}}" data-id-registrofiduciarionotadevolutiva="{{$nota_devolutiva->id_registro_fiduciario_nota_devolutiva}}" action="{{ route('app.produtos.registros.registro.atualizarNotaDevolutiva',['registro' => $registro_fiduciario]) }}" method="POST">
						@csrf
						<div class="btn-group bootstrap-select form-control" style="width: 337px;">
							<select name="situacao_nota_devolutiva" class="form-control selectpicker" title="Selecione" tabindex="-98">
								@foreach($situacoes_notas_devolutivas as $situacao)
								<option value="{{$situacao->id_registro_fiduciario_nota_devolutiva_situacao}}"
								@if($nota_devolutiva->registro_fiduciario_nota_devolutiva_situacao->id_registro_fiduciario_nota_devolutiva_situacao == $situacao->id_registro_fiduciario_nota_devolutiva_situacao )selected="selected"@endif
									>{{$situacao->no_nota_devolutiva_situacao}}</option>
								@endforeach
							</select>
						</div>
						<button class="btn btn-primary">Atualizar</button>
					</form>
				@else
					<b>Situação:</b> {{$nota_devolutiva->registro_fiduciario_nota_devolutiva_situacao->no_nota_devolutiva_situacao}}<br />
					@if($nota_devolutiva->id_nota_devolutiva_cumprimento)
						<b>Cumprimento:</b> {{$nota_devolutiva->nota_devolutiva_cumprimento->no_nota_devolutiva_cumprimento}}<br />
					@endif
					<br/>
				@endif
			</div>
		@else
			<b>Situação:</b> {{$nota_devolutiva->registro_fiduciario_nota_devolutiva_situacao->no_nota_devolutiva_situacao}}<br />
			@if($nota_devolutiva->id_nota_devolutiva_cumprimento)
				<b>Cumprimento:</b> {{$nota_devolutiva->nota_devolutiva_cumprimento->no_nota_devolutiva_cumprimento}}<br />
			@endif
		@endif
		{!!$botao ?? NULL!!}
	</div>
@empty
	<div class="alert alert-danger">
		Nenhuma nota devolutiva foi inserida.
	</div>
@endforelse
