<input type="hidden" name="registro_token" value="{{$registro_token}}">
<input type="hidden" name="id_registro_fiduciario" value="{{ $registro_fiduciario->id_registro_fiduciario }}" />
<fieldset>
    <legend>Observações</legend>
    <textarea name="de_observacoes" class="form-control" rows="4"></textarea>
</fieldset>
<div id="arquivos-outros-documentos" class="arquivos obrigatorio btn-list mt-2" data-token="{{$registro_token}}" title="Arquivos">
    <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="55" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-outros-documentos" data-pasta='registro-fiduciario' data-inassdigital="N">
        Adicionar arquivos
    </button>
</div>

