<input type="hidden" name="uuid_documento" value="{{$documento->uuid}}" />

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
        @if (count($documento->documento_parte) > 0)
            @foreach ($documento->documento_parte as $documento_parte)
                @if(count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N')
                    @foreach($documento_parte->documento_procurador as $documento_procurador)
                        @if($documento_procurador->pedido_usuario)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="ids_partes[{{$documento_parte->id_documento_parte}}][{{$documento_procurador->id_documento_procurador}}]" id="parte-{{$documento_parte->id_documento_parte}}-{{$documento_procurador->id_documento_procurador}}" class="custom-control-input" value="S">
                                        <label class="custom-control-label" for="parte-{{$documento_parte->id_documento_parte}}-{{$documento_procurador->id_documento_procurador}}">&nbsp;</label>
                                    </div>
                                </td>
                                <td>
                                    {{$documento_parte->no_parte}}<br />
                                    <span class="badge badge-primary badge-sm">{{$documento_procurador->no_procurador}}</span>
                                </td>
                                <td>{{$documento_procurador->no_email_contato}}</td>
                                <td>
                                    <a href="{{URL::to('/protocolo/acessar/'.$documento_procurador->pedido_usuario->token)}}" class="btn btn-primary copiar-link">Copiar link</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @if($documento_parte->pedido_usuario)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="ids_partes[{{$documento_parte->id_documento_parte}}]" id="parte-{{$documento_parte->id_documento_parte}}" class="custom-control-input" value="S">
                                    <label class="custom-control-label" for="parte-{{$documento_parte->id_documento_parte}}">&nbsp;</label>
                                </div>
                            </td>
                            <td>{{$documento_parte->no_parte}}</td>
                            <td>{{$documento_parte->no_email_contato}}</td>
                            <td>
                                <a href="{{URL::to('/protocolo/acessar/'.$documento_parte->pedido_usuario->token)}}" class="btn btn-primary copiar-link">Copiar link</a>
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @endif
    </tbody>
</table>
