<input type="hidden" name="arquivos_token" value="{{$arquivos_token}}" />
<div id="arquivos-registro" class="arquivos btn-list" data-token="{{$arquivos_token}}">
	<button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="27" data-token="{{$arquivos_token}}" data-limite="1" data-container="div#arquivos-registro" data-pasta="xml-registro" data-extensoes="xml">
        <i class="fas fa-plus-circle"></i> Inserir arquivo
	</button>
</div>
<div id="assinatura-arquivos" class="alert alert-warning mt-3 mb-0" style="display:none">
	<div class="mensagem"></div>
	<button type="button" class="assinatura btn btn-warning mt-1" data-token="{{$arquivos_token}}" disabled>Assinar todos</button>
</div>
