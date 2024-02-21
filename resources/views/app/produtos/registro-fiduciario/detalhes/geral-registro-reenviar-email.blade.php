<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<table class="table table-hover table-striped table-bordered" id="tabela">
    <thead>
        <tr>
            <th width="5%"></th>
            <th width="15%">Nome</th>
            <th width="10%">Email</th>
            <th width="10%">Link</th>
        </tr>
    </thead>
    <tbody>
        @if (count($registro_fiduciario->registro_fiduciario_parte) > 0)
            @foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte)
                @if(count($registro_fiduciario_parte->registro_fiduciario_procurador)>0)
                    @foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="ids_partes[{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}][{{$registro_fiduciario_procurador->id_registro_fiduciario_procurador}}]" id="parte-{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}-{{$registro_fiduciario_procurador->id_registro_fiduciario_procurador}}" class="custom-control-input" value="S">
                                    <label class="custom-control-label" for="parte-{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}-{{$registro_fiduciario_procurador->id_registro_fiduciario_procurador}}">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                {{$registro_fiduciario_parte->no_parte}}<br />
                                <span class="badge badge-primary badge-sm">{{$registro_fiduciario_procurador->no_procurador}}</span>
                            </td>
                            <td>{{$registro_fiduciario_procurador->no_email_contato}}</td>
                            <td>
                                <a href="{{URL::to('/protocolo/acessar/'.$registro_fiduciario_procurador->pedido_usuario->token)}}" class="btn btn-primary copiar-link">Copiar link</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="ids_partes[{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}]" id="parte-{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}" class="custom-control-input" value="S">
                                <label class="custom-control-label" for="parte-{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{$registro_fiduciario_parte->no_parte}}</td>
                        <td>{{$registro_fiduciario_parte->no_email_contato}}</td>
                        <td>
                            <a href="{{URL::to('/protocolo/acessar/'.$registro_fiduciario_parte->pedido_usuario->token)}}" class="btn btn-primary copiar-link">Copiar link</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
