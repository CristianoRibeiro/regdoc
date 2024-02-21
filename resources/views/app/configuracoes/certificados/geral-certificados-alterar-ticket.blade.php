<input type="hidden" name="id_parte_emissao_certificado" id='id_parte_emissao_certificado' class="form-control" value="{{request()->certificado}}" />
<div class="row">
   <div class="col-12 col-md">
        <label for="nu_ticket_vidaas" class="control-label">Ticket de emissão do VIDaaS</label>
        <input type="text" name="nu_ticket_vidaas" id="nu_ticket_vidaas" class="form-control" value="{{$parte_emissao_certificado->nu_ticket_vidaas}}"/>
   </div> 
</div>
<div class="row mt-2">
    <div class="col-12 col-md">
        <div class="alert alert-info mb-0" role="alert">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="in_atualizacao_automatica" class="custom-control-input" id="in_atualizacao_automatica" value="S" @if($parte_emissao_certificado->in_atualizacao_automatica=='S') checked @endif>
                <label class="custom-control-label" for="in_atualizacao_automatica">Permitir que o sistema atualize a situação do ticket automaticamente.</label>
            </div>
        </div>
    </div>
</div>
