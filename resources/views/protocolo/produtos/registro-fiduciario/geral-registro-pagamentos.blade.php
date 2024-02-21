@forelse ($registro_fiduciario->registro_fiduciario_pagamentos as $pagamento)
	@php
		$botao = NULL;
	@endphp
	@switch($pagamento->registro_fiduciario_pagamento_situacao->id_registro_fiduciario_pagamento_situacao)
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_GUIA'))
				@php
					$classe = 'alert alert-primary';
				@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE'))
			@php
				$classe = 'alert alert-light-warning';
				$botao = '<a href="#" class="btn btn-warning mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'</a>';
			@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_VALIDACAO'))
				@php
					$classe = 'alert alert-light-dark';
					$botao = '<a href="#" class="btn btn-dark mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'</a>';
				@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.PAGO'))
				@php
					$classe = 'alert alert-light-success';
					$botao = '<a href="#" class="btn btn-success mt-2" data-toggle="modal" data-tipo="'.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'" data-target="#registro-fiduciario-pagamento-visualizar-guias" data-idregistrofiduciariopagamento="'.$pagamento->id_registro_fiduciario_pagamento.'">Visualizar guias de '.$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.'</a>';
				@endphp
			@break
		@case(config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.ISENTO'))
				@php
					$classe = 'alert alert-light-success';
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
	<div class="alert {{$classe}}">
		<h5><b>{{$pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo ?? NULL}}</b></h5>
		<b>Situação:</b> {{$pagamento->registro_fiduciario_pagamento_situacao->no_registro_fiduciario_pagamento_situacao}}<br />
		{!!($botao ?? NULL)!!}
	</div>
@empty
	<div class="alert alert-light-danger">
		Nenhum pagamento foi inserido.
	</div>
@endforelse
