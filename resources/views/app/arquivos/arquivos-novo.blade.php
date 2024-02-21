<input type="hidden" name="token" value="{{$request->token}}" />
<input type="hidden" name="id_tipo_arquivo_grupo_produto" value="{{$request->id_tipo_arquivo_grupo_produto}}" />
<input type="hidden" name="id_flex" value="{{$request->id_flex}}" />
<input type="hidden" name="texto" value="{{$request->texto}}" />
<input type="hidden" name="pasta" value="{{$request->pasta}}" />
<input type="hidden" name="container" value="{{$request->container}}" />
<input type="hidden" name="index_arquivos" value="{{$request->index_arquivos}}" />
<input type="hidden" name="extensoes" value="{{$request->extensoes}}" />
<input type="hidden" name="in_ass_digital" value="{{$request->in_ass_digital}}" />
<input type="hidden" name="in_converter_pdf" value="{{$in_converter_pdf}}" />
<div class="fieldset-group">
    <div class="progresso-upload progress" style="display:none;height: 20px;">
    	<div class="progress-bar" style="width: 0%;">0%</div>
    </div>
    <label class="arquivo-upload" for="arquivo-upload">
        <span>
            <i class="fas fa-upload"></i>
            <h4>Selecionar arquivo</h4>
        </span>
        <input type="file" name="no_arquivo" id="arquivo-upload">
    </label>
</div>
<div class="msg-arquivo fieldset-group" style="display:none">
    <div class="alert alert-warning mb-0">
        <i class="icon glyphicon glyphicon-exclamation-sign pull-left"></i>
        <div class="mensagem"></div>
    </div>
</div>
