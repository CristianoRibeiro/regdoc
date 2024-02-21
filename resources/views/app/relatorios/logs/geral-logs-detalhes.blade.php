<div class="form-group">
	<fieldset>
		<div class="form-row">
			<div class="col-12 col-md form-group">
				<label class="control-label" for="usuario">Usuário</label>
				<input type="text" name="no_usuario" id="usuario" class="form-control" disabled value="{{ $log_detalhe->log->usuario->no_usuario }}" />
			</div>
			<div class="col-12 col-md form-group">
				<label class="control-label" for="descricao">Descrição</label>
				<input type="text" name="de_log" id="descricao" class="form-control" disabled value="{{ $log_detalhe->log->de_log }}" />
			</div>
		</div>
		<div class="form-row mt-1">
			<div class="col-12 col-md form-group">
				<label class="control-label" for="ip">IP</label>
				<input type="text" name="no_endereco_ip" class="form-control" id="ip" disabled value="{{ $log_detalhe->log->no_endereco_ip }}"/>
			</div>
			<div class="col-12 col-md form-group">
				<label class="control-label" for="data_hora">Data/Hora</label>
				<input type="text" name="dt_cadastro" class="form-control" id="data_hora" disabled value="{{ Helper::formata_data_hora($log_detalhe->log->dt_cadastro) }}" />
			</div>
		</div>
		<div class="form-row mt-1">
			<div class="col-12 col-md form-group">
				<label class="control-label" for="detalhe">Detalhes do LOG</label>
				<textarea name="de_log_detalhe" id="detalhe" rows="8" class="form-control" disabled>{{$log_detalhe->de_log_detalhe}}</textarea>
			</div>
		</div>
	</fieldset>
</div>
