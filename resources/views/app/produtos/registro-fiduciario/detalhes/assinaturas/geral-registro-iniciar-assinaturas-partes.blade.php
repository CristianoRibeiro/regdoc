<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input type="hidden" name="id_arquivo_grupo_produto" value="{{$arquivo_grupo_produto->id_arquivo_grupo_produto}}" />
<input type="hidden" name="registro_token" value="{{request()->registro_token}}"/>
<fieldset>
    <legend>PARTES / PROCURADORES</legend>
    <table class="table table-striped table-bordered table-fixed mb-0">
        <thead>
            <tr>
                <th width="10%">Assinar?</th>
                <th width="60%">Parte</th>
                <th width="30%">Tipo</th>
            </tr>
        </thead>
        <tbody>
            @if (count($registro_fiduciario->registro_fiduciario_parte) > 0)
                @foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte)
                    @php
                    if (count($signatarios_arquivo)>0) {
                        $marcar_parte = in_array($registro_fiduciario_parte->id_registro_fiduciario_parte, $signatarios_arquivo);
                    } else {
                        $marcar_parte = true;
                    }
                    @endphp
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="partes[]" id="parte-{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}" class="custom-control-input" value="{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}" {!!($marcar_parte ? 'checked' : '')!!}>
                                <label class="custom-control-label" for="parte-{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}">SIM</label>
                            </div>
                        </td>
                        <td>
                            {{$registro_fiduciario_parte->no_parte}}
                            @if(count($registro_fiduciario_parte->registro_fiduciario_procurador)>0)
                                {{count($registro_fiduciario_parte->registro_fiduciario_procurador)}} procurador(es)
                            @endif
                        </td>
                        <td>
                            {{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</fieldset>
