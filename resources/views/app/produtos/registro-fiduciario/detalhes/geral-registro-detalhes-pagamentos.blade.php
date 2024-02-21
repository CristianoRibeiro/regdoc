@if(Gate::allows('registros-detalhes-pagamentos-novo', $registro_fiduciario))
	<div class="mb-3">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#registro-fiduciario-pagamento" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="novo">
			<i class="fas fa-plus-circle"></i> Novo pagamento
		</button>
	</div>
@endif
@forelse ($registro_fiduciario->registro_fiduciario_pagamentos as $pagamento)
	@php
		$botao = NULL;
	@endphp
	@switch($pagamento->registro_fiduciario_pagamento_situacao->id_registro_fiduciario_pagamento_situacao)
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_GUIA'))
			@php
				$classe = 'alert alert-primary';
				$botao = '<a href="#" class="btn btn-primary mt-2" data-toggle="modal" data-target="#registro-fiduciario-pagamento-guia" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'">
					Enviar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'
				</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE'))
			@php
				$classe = 'alert alert-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_VALIDACAO'))
			@php
				$classe = 'alert alert-dark';
				$botao = '<a href="#" class="btn btn-dark mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.PAGO'))
			@php
				$classe = 'alert alert-success';
				$botao = '<a href="#" class="btn btn-success mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.ISENTO'))
			@php
				$classe = 'alert alert-success';
				$botao = '<a href="#" class="btn btn-success mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciario="'.$registro_fiduciario->id_registro_fiduciario.'" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar a declaração de isenção</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.CANCELADO'))
			@php
				$classe = 'alert alert-info';
			@endphp
			@break
		@default
			@php
				$classe = 'alert alert-info';
			@endphp
	@endswitch
	<div class="alert {{$classe}}" id="div-registro">
		<h5><b>{{$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo ?? NULL}}</b></h5>
		<div style="align-content: flex-end">
			@if(Gate::allows('atualizar-registro-itbi'))
				<form class="submit-atualizacao-pagamento-itbi" data-id-registro="{{$registro_fiduciario->id_registro_fiduciario}}">
					@csrf
					<input type="hidden"  name="id_registro_fiduciario_pagamento" value="{{$pagamento->id_registro_fiduciario_pagamento}}">
					<div class="btn-group bootstrap-select form-control" style="width: 220px;">
						<select name="situacao" class="form-control selectpicker" title="Selecione" tabindex="-98">
							@foreach($situacoes as $situacao)
							<option value="{{$situacao->id_registro_fiduciario_pagamento_situacao}}"
							@if($pagamento->registro_fiduciario_pagamento_situacao->id_registro_fiduciario_pagamento_situacao == $situacao->id_registro_fiduciario_pagamento_situacao )selected="selected"@endif
								>{{$situacao->no_registro_fiduciario_pagamento_situacao}}</option>
							@endforeach
						</select>
					</div>
					<button type="submit" class="btn btn-primary">Atualizar</button>
				</form>
			@else
				<b>Situação:</b> {{$pagamento->registro_fiduciario_pagamento_situacao->no_registro_fiduciario_pagamento_situacao}}</i>
				<br/>
			@endif
		</div>
		{!!$botao ?? NULL!!}
		@if(Gate::allows('registros-pagamentos-cancelar', $pagamento))
			<button type="button" class="btn btn-danger mt-2 cancelar-pagamento" data-idregistrofiduciario="{{$pagamento->id_registro_fiduciario}}" data-idregistrofiduciariopagamento="{{$pagamento->id_registro_fiduciario_pagamento}}">
				<i class="fas fa-plus-circle"></i> Cancelar
			</button>
		@endif
	</div>
@empty
	<div class="alert alert-danger">
		Nenhum pagamento foi inserido.
	</div>
@endforelse
<hr class="mt-4" />
<h3>Reembolsos</h3>
@if (Gate::allows('registros-reembolso-novo', $registro_fiduciario))
	<div class="mb-3">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#registro-fiduciario-reembolso" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="novo">
			<i class="fas fa-plus-circle"></i> Novo reembolso
		</button>
	</div>
@endif
@forelse ($registro_fiduciario->registro_fiduciario_reembolsos as $reembolso)
	@switch($reembolso->registro_fiduciario_reembolso_situacao->id_registro_fiduciario_reembolso_situacao)
		@case(config('constants.REGISTRO_FIDUCIARIO.REEMBOLSOS.SITUACOES.AGUARDANDO'))
			@php
			$classe = 'primary';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.REEMBOLSOS.SITUACOES.FINALIZADO'))
			@php
			$classe = 'success';
			@endphp
		@break
		@case(config('constants.REGISTRO_FIDUCIARIO.REEMBOLSOS.SITUACOES.CANCELADO'))
			@php
			$classe = 'warning';
			@endphp
		@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.EXCLUIDO'))
			@php
				$classe = 'info';
			@endphp
		@break
		@default
			@php
				$classe = 'info';
			@endphp
	@endswitch
	<div class="alert alert-{{$classe}}">
		<h5><b>Reembolso</b></h5>
		<b>Situação:</b> {{$reembolso->registro_fiduciario_reembolso_situacao->no_registro_fiduciario_reembolso_situacao}}<br />
		<a href="#" class="btn btn-{{$classe}} mt-2" data-toggle="modal" data-target="#registro-fiduciario-reembolso-visualizar" data-idregistrofiduciario="{{$registro_fiduciario->id_registro_fiduciario}}" data-idregistrofiduciarioreembolso="{{$reembolso->id_registro_fiduciario_reembolso}}">Visualizar detalhes</a>
	</div>
@empty
	<div class="alert alert-danger">
		Nenhum reembolso foi inserido.
	</div>
@endforelse



