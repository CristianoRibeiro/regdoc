<div class="form-group">
	<fieldset>
		<legend>IMPORTAÇÃO</legend>
		<div class="row">
			<div class="col-12 col-md">
				<label class="control-label">Data da importação</label>
				<input class="form-control" value="{{Helper::formata_data($arquivo_controle_xml->dt_cadastro)}}" readonly />
			</div>
			<div class="col-12 col-md">
				<label class="control-label">Registros processados</label>
				<input class="form-control" value="{{$arquivo_controle_xml->nu_registro_processados}}" readonly />
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md">
				<label class="control-label">Situação</label>
				<input class="form-control" value="{{$arquivo_controle_xml->arquivo_controle_xml_situacao->no_arquivo_controle_xml_situacao}}" readonly />
			</div>
		</div>
	</fieldset>
</div>
<div class="form-group mt-3">
	<fieldset>
		<legend>ARQUIVO</legend>
		<div class="arquivo btn-group">
            <button type="button" class="btn btn-sm btn-primary" data-idarquivo="{{$arquivo_controle_xml->id_arquivo_controle_xml}}">{{$arquivo_controle_xml->no_arquivo}}</button>
        </div>
        @if($arquivo_controle_xml->id_usuario_certificado)
            <div class="alert alert-success show text-left mt-2 mb-0">
            	O arquivo foi assinado digitalmente.<br /><br />
		        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#visualizar-certificado-registro" data-idarquivo="{{$arquivo_controle_xml->id_arquivo_controle_xml}}" data-subtitulo="{{$arquivo_controle_xml->no_arquivo}}">Visualizar assinatura</button>
		    </div>
		@else
			<div class="alert alert-danger show text-left mt-2 mb-0">
            	O arquivo não foi assinado digitalmente.
		    </div>
		@endif
	</fieldset>
</div>
