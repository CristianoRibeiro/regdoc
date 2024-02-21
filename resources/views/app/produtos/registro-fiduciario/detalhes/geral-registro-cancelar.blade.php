<input type="hidden" name="produto"  value="{{request()->produto}}" />
<input type="hidden" name="id_registro_fiduciario"  value="{{request()->registro}}" />
<input type="hidden" name="id_situacao_pedido_grupo_produto"  value="{{$id_situacao_pedido_grupo_produto ?? NULL}}" />

<div class="form-group">
    <label class="control-label" for="de_motivo_cancelamento">Motivo do cancelamento</label>
    <textarea class="form-control" id="de_motivo_cancelamento" name="de_motivo_cancelamento" rows="3"></textarea>
</div>
@if(config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO') == $id_situacao_pedido_grupo_produto)
    <div class="form-group">
        <label class="control-label" for="de_termo_admissao">Termo de admissão</label>
        <textarea class="form-control" id="de_termo_admissao" name="de_termo_admissao" rows="3"></textarea>
    </div>
    <div class="alert alert-info mb-0 mt-2">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_finalizar_cartorio" id="in_finalizar_cartorio" class="custom-control-input" value="S" checked>
            <label class="custom-control-label" for="in_finalizar_cartorio">Já realizei o cancelamento no cartório!</label>
        </div>
    </div>  
@endif
<div class="alert alert-info mb-0 mt-2">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" name="in_finalizar" id="in_finalizar" class="custom-control-input" value="S">
        <label class="custom-control-label" for="in_finalizar">Alterar a situação do registro para <b>Finalizado</b></label>
    </div>
</div>