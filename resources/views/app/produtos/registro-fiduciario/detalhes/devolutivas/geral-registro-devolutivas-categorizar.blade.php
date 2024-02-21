<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input name="id_registro_fiduciario_nota_devolutiva" type="hidden" value="{{$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva}}" />

<fieldset>
    <legend>CAUSA RAIZ</legend>
    <div class="row">
        <div class="col-12 col-md">
            <label class="control-label" for="id_causa_raiz_classificacao">Classificação</label>
            <select name="id_causa_raiz_classificacao" class="form-control selectpicker" title="Selecione">
                @if (count($nota_devolutiva_causa_classificacoes) > 0)
                    @foreach ($nota_devolutiva_causa_classificacoes as $nota_devolutiva_causa_classificacao)
                        <option value="{{$nota_devolutiva_causa_classificacao->id_nota_devolutiva_causa_classificacao }}">
                            {{$nota_devolutiva_causa_classificacao->no_nota_devolutiva_causa_classificacao }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label" for="id_causa_raiz_grupo">Grupo</label>
            <select name="id_causa_raiz_grupo" id="id_causa_raiz_grupo" class="form-control selectpicker" title="Selecione" data-live-search="true" disabled>
            </select>
        </div>
    </div>
    <div class="multiselect">
        <div class="row mt-1">
            <div class="col-12 col-md">
                <label class="control-label" for="causa_raiz">Causas raizes - <span></span></label>
                <select name="causa_raiz" id="causa_raiz" class="multiselect-from form-control" size="8" multiple disabled>
                </select>
            </div>    
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md">
                <button type="button" id="causa_raiz_rightAll" class="btn btn-primary">
                    <i class="fas fa-angle-double-down"></i>
                </button>
                <button type="button" id="causa_raiz_rightSelected" class="btn btn-primary">
                    <i class="fas fa-angle-down"></i>
                </button>
                <button type="button" id="causa_raiz_leftSelected" class="btn btn-primary">
                    <i class="fas fa-angle-up"></i>
                </button>
                <button type="button" id="causa_raiz_leftAll" class="btn btn-primary">
                    <i class="fas fa-angle-double-up"></i>
                </button>
            </div>    
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md">
                <label class="control-label" for="causa_raiz_to">Causas raizes selecionadas</label>
                <select name="id_nota_devolutiva_causa_raizes[]" id="causa_raiz_to" class="form-control" size="8" multiple></select>
            </div>    
        </div>
    </div>
</fieldset>
<fieldset class="mt-2">
    <legend>CUMPRIMENTO DA NOTA DEVOLUTIVA</legend>
    <label class="control-label" for="id_nota_devolutiva_cumprimento">Quem irá cumprir a nota devolutiva?</label>
    <select name="id_nota_devolutiva_cumprimento" class="form-control selectpicker" title="Selecione">
        @if (count($nota_devolutiva_cumprimentos) > 0)
            @foreach ($nota_devolutiva_cumprimentos as $nota_devolutiva_cumprimento)
                <option value="{{$nota_devolutiva_cumprimento->id_nota_devolutiva_cumprimento}}">
                    {{$nota_devolutiva_cumprimento->no_nota_devolutiva_cumprimento}}
                </option>
            @endforeach
        @endif
    </select>
</fieldset>
<fieldset class="mt-2">
    <legend>OBSERVAÇÕES DA NOTA</legend>
    <textarea name="de_nota_devolutiva" class="form-control" rows="5">{{$registro_fiduciario_nota_devolutiva->de_nota_devolutiva}}</textarea>
</fieldset>