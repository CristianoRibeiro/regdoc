<table class="table table-striped table-bordered mb-0">
    <thead>
        <tr>
            <th width="50%">Nome</th>
            <th width="40%">E-mail</th>
            <th width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($registro_fiduciario->registro_fiduciario_operadores as $registro_fiduciario_operador)
            <tr>
                <td>
                    <b>{{$registro_fiduciario_operador->usuario->no_usuario}}</b>                    
                </td>
                <td>
                    {{$registro_fiduciario_operador->usuario->email_usuario}}
                </td>
                <td>
                    @if(Gate::allows('registros-operadores-remover'))
                        <button class="remover-operador btn btn-danger" data-idregistro="{{$registro_fiduciario_operador->id_registro_fiduciario}}" data-idregistrooperador="{{$registro_fiduciario_operador->id_registro_fiduciario_operador}}">Remover</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">
                    <div class="alert alert-danger mb-0">
                        Nenhum operador foi encontrado.
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if (Gate::allows('registros-operadores-novo'))
    <fieldset class="mt-3">
        <legend>NOVO OPERADOR(A)</legend>
        <form name="form-registro-fiduciario-operador-novo" action="POST" method="">
            <input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
            <div class="form-row">
                <div class="col-4">
                    <label class="control-label asterisk" for="id_pessoa">Entidade</label>
                    <select id="id_pessoa" name="id_pessoa" class="form-control selectpicker" title="Selecione uma entidade" data-live-search="true">
                        @if (count($pessoas) > 0)
                            @foreach($pessoas as $pessoa)
                                <option value="{{$pessoa->id_pessoa}}">{{$pessoa->no_pessoa}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-6">
                    <label class="control-label asterisk" for="id_usuario">Usuário operador(a)</label>
                    <select id="id_usuario" name="id_usuario[]" class="form-control selectpicker" title="Selecione um usuário" data-live-search="true" multiple disabled>
                    </select>
                </div>
                <div class="col pt-4">
                    <button type="submit" class="salvar-operador btn btn-success btn-block">Adicionar</button>
                </div>
            </div>
        </form>
    </fieldset>
@endif
