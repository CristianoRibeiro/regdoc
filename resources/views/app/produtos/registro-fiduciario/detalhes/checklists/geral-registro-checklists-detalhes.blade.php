<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<table class="table table-hover table-striped table-bordered" id="tabela">
    <thead>
        <tr>
            <th width="5%"></th>
            <th width="95%">Descrição</th>
        </tr>
    </thead>
    <tbody>
        @if (count($registro_fiduciario->registro_fiduciario_checklists) > 0)
            @foreach ($registro_fiduciario->registro_fiduciario_checklists as $registro_fiduciario_checklist)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                    name="id_registro_fiduciario_checklist[{{$registro_fiduciario_checklist->id_registro_fiduciario_checklist}}]"
                                    id="id_registro_fiduciario_checklist-{{$registro_fiduciario_checklist->id_registro_fiduciario_checklist}}"
                                    class="custom-control-input"
                                    value="S"
                                    @if ($registro_fiduciario_checklist->in_marcado == 'S')
                                        checked
                                    @endif
                                    >
                            <label class="custom-control-label" for="id_registro_fiduciario_checklist-{{$registro_fiduciario_checklist->id_registro_fiduciario_checklist}}">&nbsp;</label>
                        </div>
                    </td>
                    <td>{{$registro_fiduciario_checklist->checklist->no_checklist}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
