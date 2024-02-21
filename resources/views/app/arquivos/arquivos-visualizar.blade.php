@php($total_assinaturas = $arquivo_grupo_produto->arquivo_grupo_produto_assinatura->count())
@if($total_assinaturas>0)
    <div class="certificado alert alert-success clearfix">
        <div class="pull-left">
            O documento foi assinado digitalmente por: {{sprintf(ngettext("%d pessoa", "%d pessoas", $total_assinaturas), $total_assinaturas)}}
        </div>
        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo_grupo_produto->id_arquivo_grupo_produto}}" data-noarquivo="{{$arquivo_grupo_produto->no_descricao_arquivo}}">
            <i class="fa fa-lock"></i>
            {{ngettext("Ver assinaturas", "Ver assinaturas", $total_assinaturas)}}
        </button>
    </div>
@endif
@if(in_array($arquivo_grupo_produto->no_extensao, array('pdf','jpg','png','bmp','gif')))
	@if($arquivo_grupo_produto->no_extensao=='pdf')
        <object data="{{URL::to('/app/arquivos/render/'.$arquivo_token.'/'.$arquivo_grupo_produto->no_descricao_arquivo)}}" type="application/pdf" class="pdf @if($arquivo_grupo_produto->id_usuario_certificado>0) assinado @endif object-pdf-view">
            <p>Seu navegador não tem um plugin pra PDF</p>
        </object>
	@else
    	<img src="{{URL::to('/app/arquivos/render/'.$arquivo_token.'/'.$arquivo_grupo_produto->no_descricao_arquivo)}}" width="100%" class="img-responsive" />
    @endif
@else
    <div class="alert alert-warning single">
        <i class="icon glyphicon glyphicon-exclamation-sign pull-left"></i>
        <div class="mensagem">
            O arquivo não pode ser visualizado, por favor, faça o download.
        </div>
    </div>
@endif
