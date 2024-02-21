<form name="form-configuracoes-acesso" method="post">
	{{csrf_field()}}
	<div class="form-group">
		<label class="control-label" for="senha_atual">Senha atual</label>
		<input type="text" class="form-control" name="senha_atual" id="senha_atual" placeholder="Digite a senha atual">
	</div>
    <div class="form-group">
        <label class="control-label" for="nova_senha">Nova senha</label>
        <input type="text" class="form-control" name="nova_senha" id="nova_senha" placeholder="Digite uma nova senha">
    </div>
    <div class="form-group">
        <label class="control-label" for="repetir_nova_senha">Repetir a nova senha</label>
        <input type="text" class="form-control" name="repetir_nova_senha" id="repetir_nova_senha" placeholder="Repita a nova senha">
    </div>
	<div class="buttons form-group mt-2 text-right">
		<button type="reset" class="cancelar-filtro btn btn-outline-danger">Cancelar</button>
		<button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Salvar
		</button>
	</div>
</form>
