@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto==config('constants.SITUACAO.11.ID_REGISTRADO'))
	<div class="alert alert-success">
		<h5><b>Arquivo(s) do(s) resultado</b></h5>
		{{$total_arquivos_resultado}} arquivos foram enviados<br />
		<a href="#registro-fiduciario-arquivos" class="btn btn-light d-block d-md-inline-block mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) do(s) resultado" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_RESULTADO')}}">Visualizar arquivos</a>
	</div>
@endif
<div class="alert alert-secondary">
	<h5><b>Arquivo(s) do(s) contrato(s)</b></h5>
	{{$total_arquivos_contrato}} arquivos foram enviados<br />
	<a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) do(s) contrato(s)" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_CONTRATO')}}">Visualizar / atualizar arquivos</a>
</div>
@if($registro_fiduciario->id_registro_fiduciario_tipo==config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CESSAO'))
	<div class="alert alert-secondary">
		<h5><b>Arquivo(s) do(s) instrumento(s) particular</b></h5>
		{{$total_arquivos_instrumento_particular}} arquivos foram enviados<br />
		<a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) do(s) instrumento(s) particular" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR')}}">Visualizar / atualizar arquivos</a>
	</div>
@endif
@if($registro_fiduciario->id_registro_fiduciario_tipo!=config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CORRESPONDENTE'))
	<div class="alert alert-secondary">
		<h5><b>Arquivos do imóvel</b></h5>
		{{$total_arquivos_imovel}} arquivos foram enviados<br />
		<a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivos do imóvel" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_IMOVEL')}}">Visualizar / atualizar arquivos</a>
	</div>
@endif
@if($registro_fiduciario->id_registro_fiduciario_tipo==config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CORRESPONDENTE'))
	<div class="alert alert-secondary">
		<h5><b>Arquivos formulário</b></h5>
		{{$total_arquivos_formulario}} arquivos foram enviados<br />
		<a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivos formulários" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_FORMULARIO')}}">Visualizar / atualizar arquivos</a>
	</div>
@endif
<div class="alert alert-secondary">
	<h5><b>Outros arquivos</b></h5>
	{{$total_arquivos_outros}} arquivos foram enviados<br />
	<a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Outros arquivos" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_OUTROS')}}">Visualizar / atualizar arquivos</a>
</div>
@if(count($partes_exigencia_documentos)>0)
	<div class="alert alert-secondary">
		<h5><b>Arquivos das partes</b></h5>
		<table class="table bg-white table-striped table-bordered mb-0">
		    <thead>
		        <tr>
		            <th width="50%" class="d-none d-md-table-cell">Parte</th>
		            <th width="20%" class="d-none d-md-table-cell">Tipo da Parte</th>
		            <th width="20%" class="d-none d-md-table-cell">Arquivos</th>
		            <th width="10%" class="d-none d-md-table-cell"></th>

		            <th width="100%" class="d-md-none">Parte</th>
		        </tr>
		    </thead>
		    <tbody>
		        @foreach($partes_exigencia_documentos as $registro_fiduciario_parte)
					@php
						$total_enviados = $registro_fiduciario_parte->arquivos_grupo->count();
					@endphp
		            <tr>
		                <td class="d-none d-md-table-cell">{{$registro_fiduciario_parte->no_parte}}</td>
		                <td class="d-none d-md-table-cell">{{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
		                <td class="d-none d-md-table-cell">
		                    {{sprintf(ngettext("%d arquivo enviado", "%d arquivos enviados", $total_enviados), $total_enviados)}}<br />
		                </td>
		                <td class="d-none d-md-table-cell">
		                    <a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block text-nowrap" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) da parte {{$registro_fiduciario_parte->no_parte}}" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES')}}" data-idparte="{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}">
		                        Visualizar / atualizar arquivos
		                    </a>
		                </td>

						<td class="d-md-none">
							<p class="mb-2">
								{{$registro_fiduciario_parte->no_parte}}<br />
								<span class="small">{{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</span>
							</p>
							<p class="mb-0">
								<b>Arquivos:</b><br />
								{{sprintf(ngettext("%d arquivo enviado", "%d arquivos enviados", $total_enviados), $total_enviados)}}<br />
								<a href="#registro-fiduciario-arquivos" class="btn btn-primary d-block d-md-inline-block text-nowrap mt-1" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) da parte {{$registro_fiduciario_parte->no_parte}}" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES')}}" data-idparte="{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}">
			                        Visualizar / atualizar arquivos
			                    </a>
							</p>
						</td>
		            </tr>
		        @endforeach
		    </tbody>
		</table>
	</div>
@endif
