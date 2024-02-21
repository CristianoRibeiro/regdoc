<form name="form-arquivos-registro-filtro" method="post" action="">
	{{csrf_field()}}
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md">
				<label class="control-label" for="protocolo">Protocolo</label>
				<input type="text" class="form-control" name="protocolo" id="protocolo" placeholder="Digite o protocolo" value="{{$request->protocolo}}">
			</div>
			<div class="col-12 col-md">
				<label class="control-label" for="data_importacao_ini">Data da importação</label>
				<div class="periodo input-group input-daterange">
                    <input type="text" class="form-control pull-left" name="data_importacao_ini" id="data_importacao_ini" value="{{$request->data_importacao_ini}}" placeholder="Data inicial" />
                    <span class="input-group-addon small pull-left">até</span>
                    <input type="text" class="form-control pull-left" name="data_importacao_fim" id="data_importacao_fim" value="{{$request->data_importacao_fim}}" placeholder="Data final" />
                </div>
			</div>
			<div class="col-12 col-md">
				<label class="control-label" for="situacao">Situação</label>
				<select class="form-control selectpicker" name="situacao[]" id="situacao" title="Selecione uma situação" multiple data-actions-box="true" data-select-all-text="Selecionar todas" data-deselect-all-text="Deselecionar todas" data-count-selected-text="{0} situações selecionadas" data-selected-text-format="count>1">
					@if(count($situacoes)>0)
						@foreach($situacoes as $situacao)
							<option value="{{$situacao->id_arquivo_controle_xml_situacao}}" @if(isset($request->situacao)) {{(in_array($situacao->id_arquivo_controle_xml_situacao,$request->situacao)?'selected="selected"':'')}} @endif>{{$situacao->no_arquivo_controle_xml_situacao}}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
	</div>
	<div class="buttons form-group mt-2 text-right">
		@if($request->isMethod('post'))
			<button type="reset" class="limpar-filtro btn btn-outline-danger">Limpar filtro</button>
		@else
			<button type="reset" class="cancelar-filtro btn btn-outline-danger">Cancelar</button>
		@endif
		<button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Aplicar filtro
		</button>
	</div>
</form>
