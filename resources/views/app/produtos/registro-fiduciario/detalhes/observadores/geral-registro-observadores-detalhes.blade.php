<table class="table table-striped table-bordered mb-0">
    <thead>
        <tr>
            <th width="45%">Nome</th>
            <th width="45%">E-mail</th>
            <th width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($registro_fiduciario->registro_fiduciario_observadores as $registro_fiduciario_observador)
            <tr>
                <td>{{ $registro_fiduciario_observador->no_observador }}</td>
                <td>{{ $registro_fiduciario_observador->no_email_observador }}</td>
                <td>
                    @if(Gate::allows('registros-observadores-remover'))
                        <button class="remover-observador btn btn-danger" data-idregistro="{{$registro_fiduciario_observador->id_registro_fiduciario}}" data-idregistroobservador="{{$registro_fiduciario_observador->id_registro_fiduciario_observador}}">Remover</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">
                    <div class="alert alert-danger mb-0">
                        Nenhum observador foi encontrado.
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if (Gate::allows('registros-observadores-novo'))
    <fieldset class="mt-3">
        <legend>NOVO OBSERVADOR</legend>
        <form name="form-registro-fiduciario-observador-novo" action="POST" method="">
            <input type="hidden" name="id_registro_fiduciario" value="{{ $registro_fiduciario->id_registro_fiduciario }}" />
            <div class="form-row">
                <div class="col-12 col-md-5">
                    <label class="control-label asterisk" for="no_observador">Nome</label>
                    <input type="text" class="form-control" name="no_observador" value="" />
                </div>
                <div class="col-12 col-md-5">
                    <label class="control-label asterisk" for="no_email_observador">E-mail</label>
                    <input type="email" class="form-control text-lowercase" name="no_email_observador" value="" />
                </div>
                <div class="col-12 col-md pt-4">
                    <button type="submit" class="salvar-observador btn btn-success btn-block">Adicionar</button>
                </div>
            </div>
        </form>
    </fieldset>
@endif
