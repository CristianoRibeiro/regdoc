<div class="alert alert-info">
	<h5><b>Arquivo do contrato</b></h5>
	{{$total_arquivos_contrato}} arquivo foi gerado<br />
	<a href="#documento-arquivos-modal" class="btn btn-info mt-2 btn-w-100-sm" data-toggle="modal" data-uuiddocumento="{{$documento->uuid}}" data-title="Arquivo do contrato" data-idtipoarquivo="{{config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO')}}">Visualizar arquivo</a>
</div>
<div class="alert alert-info">
	<h5><b>Arquivo da procuração</b></h5>
	{{$total_arquivos_procuracao}} arquivo foi gerado<br />
	<a href="#documento-arquivos-modal" class="btn btn-info mt-2 btn-w-100-sm" data-toggle="modal" data-uuiddocumento="{{$documento->uuid}}" data-title="Arquivo da Procuração" data-idtipoarquivo="{{config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO')}}">Visualizar arquivo</a>
</div>
<div class="alert alert-info">
	<h5><b>Arquivo do contrato do assessor legal</b></h5>
	{{$total_arquivos_assessor_legal}} arquivo foi gerado<br />
	<a href="#documento-arquivos-modal" class="btn btn-info mt-2 btn-w-100-sm" data-toggle="modal" data-uuiddocumento="{{$documento->uuid}}" data-title="Arquivo do contrato do assessor legal" data-idtipoarquivo="{{config('constants.DOCUMENTO.ARQUIVOS.ID_ASSESSOR_LEGAL')}}">Visualizar arquivo</a>
</div>
