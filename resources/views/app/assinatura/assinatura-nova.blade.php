@extends('layouts.principal')

@section('titulo', 'Assinar arquivos')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}?v={{config('app.version')}}">
@endsection

@section('principal')
	<?php
	if ($request->session()->has('arquivos_'.$request->token)) {
		$arquivos = $request->session()->get('arquivos_'.$request->token);
		$arquivosIds = array();
		$arquivosNomes = array();
		if (!isset($request->index_arquivo)) {
			foreach($arquivos as $key => $arquivo) {
				if (in_array($arquivo['in_ass_digital'],['S','O']) and $arquivo['in_assinado']=='N') {
					$arquivosIds[] = $key;
					$arquivosNomes[] = $arquivo['no_arquivo'];
				}
			}
		} else {
			if (in_array($arquivos[$request->index_arquivo]['in_ass_digital'],['S','O']) and $arquivos[$request->index_arquivo]['in_assinado']=='N') {
				$arquivosIds[] = $request->index_arquivo;
				$arquivosNomes[] = $arquivos[$request->index_arquivo]['no_arquivo'];
			}
		}
	?>
		<section id="assinatura">
			<div class="card box-app">
				<div class="card-header">
					Assinar arquivos
				</div>
				<div class="card-body">
					<form id="signForm" method="POST">
						<div class="form-group">
				            <div id="docList" class="btn-list"></div>
						</div>
						<div class="form-group mt-3">
							<label class="control-label" for="certificateSelect">Selecione um certificado</label>
							<select id="certificateSelect" class="form-control"></select>
						</div>
						<div class="form-group mt-3">
							<button id="signButton" type="button" class="btn btn-primary">Assinar arquivos</button>
							<button id="refreshButton" type="button" class="btn btn-default">Atualizar certificados</button>
						</div>
					</form>
				</div>
			</div>
		</section>

	<?php
	}
	?>
    @section('js')
      <script defer type="text/javascript" src="{{asset('js/libs/jquery.blockUI.js')}}"></script>
    	<script defer type="text/javascript" src="{{asset('js/libs/lacuna-web-pki-2.6.1.js')}}"></script>
    	<script defer type="text/javascript" src="{{asset('js/libs/sweetalert2.all.js')}}"></script>
    	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.batchSignatureForm.js')}}?v={{config('app.version')}}"></script>
			<script defer>
				$(document).ready(function () {
						batchSignatureForm.init({
								documentsIds: <?=json_encode($arquivosIds);?>,
								documentsNames: <?=json_encode($arquivosNomes);?>,
								arquivosToken: '<?=$request->token;?>',
								certificateSelect: $('#certificateSelect'),
								refreshButton: $('#refreshButton'),
								signButton: $('#signButton'),
						});
				});
			</script>
    @endsection
@endsection
