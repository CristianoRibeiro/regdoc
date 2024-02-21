<input type="hidden" name="registro_token" value="{{$registro_token}}"/>
<input type="hidden" name="produto" value="{{request()->produto}}"/>

<div class="tipos-insercao alert alert-warning">
	<h3 class="mb-4">Deseja cadastrar o {{mb_strtolower(__('messages.registros.'.request()->produto.'.titulo'), 'UTF-8')}} como?</h3>
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title mb-0"><b>Proposta</b></h5>
					<p class="card-text">O {{mb_strtolower(__('messages.registros.'.request()->produto.'.titulo'), 'UTF-8')}} será cadastrado como proposta, e não será necessário inserir o arquivo do contrato.</p>
					<a href="javascript:void(0)" class="tipo-insercao-proposta btn btn-primary btn-w-100-sm">Seguir como proposta</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 mt-2 mt-md-0">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title mb-0"><b>Contrato</b></h5>
					<p class="card-text">O {{mb_strtolower(__('messages.registros.'.request()->produto.'.titulo'), 'UTF-8')}} será cadastrado como contrato, sendo obrigatório o envio do arquivo do contrato.</p>
					<a href="javascript:void(0)" class="tipo-insercao-contrato btn btn-primary btn-w-100-sm">Seguir como contrato</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="tipos-insercao-opcoes options row" style="display:none">
    <div class="option col-6">
    	<input name="tipo_insercao" id="tipo_insercao_proposta" type="radio" value="P">
    	<label for="tipo_insercao_proposta">Proposta</label>
	</div>
    <div class="option col-6">
    	<input name="tipo_insercao" id="tipo_insercao_contrato" type="radio" value="C">
    	<label for="tipo_insercao_contrato">Contrato</label>
	</div>
</div>
@switch(request()->produto)
	@case('fiduciario')
		@include('app.produtos.registro-fiduciario.novo.geral-registro-novo-fiduciario')
		@break;
	@case('garantias')
		@include('app.produtos.registro-fiduciario.novo.geral-registro-novo-garantias')
		@break;
@endswitch
