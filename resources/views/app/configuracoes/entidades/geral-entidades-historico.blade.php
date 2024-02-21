<table class="table table-striped table-bordered mb-1">
    <thead>
        <tr>
            <th width="10%">Logo</th>
            <th width="40%">Razão social</th>
            <th width="15%">Cidade / UF</th>
            <th width="15%">Data de cadastro</th>
            <th width="13%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if (count($pessoas)>0)
            @foreach ($pessoas as $pessoa)
                <tr>
                    <td>
                        @if($pessoa->logo_interna)
                            <img src="{{$pessoa->logo_interna->no_valor}}" class="img-fluid" />
                        @else
                            -
                        @endif
                    </td>
                    <td>{{$pessoa->no_pessoa}}</td>
                    <td>{{$pessoa->enderecos[0]->cidade->no_cidade ?? NULL}} / {{$pessoa->enderecos[0]->cidade->estado->uf ?? NULL}}</td>
                    <td>{{Carbon\Carbon::parse($pessoa->dt_cadastro)->format('d/m/Y H:i:s')}}</td>
                    <td class="opcoes">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detalhes-entidade" data-idpessoa="{{$pessoa->id_pessoa}}" data-nopessoa="{{$pessoa->no_pessoa}}">Detalhes</button>
                            <?php
                            /*
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#alterar-entidade" data-idpessoa="{{$pessoa->id_pessoa}}" data-nopessoa="{{$pessoa->no_pessoa}}">Alterar entidade</a>
                                  {{-- <a class="dropdown-item desativar-informante" href="javascript:void(0);" data-idusuario="{{$informante->id_pessoa}}" data-nousuario="{{$informante->no_pessoa}}">Desativar Empresa/Serventia</a>  --}}
                                </div>
                            </div>
                            */
                            ?>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">
                    <div class="single alert alert-danger mb-0">
                        <i class="glyphicon glyphicon-remove"></i>
                        <div class="mensagem">
                            Nenhuma entidade foi encontrada.
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
<div class="container">
    <div class="row mt-2">
        <div class="col-6">
            Exibindo <b>{{count($pessoas)}}</b> de <b>{{$pessoas->total()}}</b> {{($pessoas->total()>1?'entidades':'entidade')}}.
        </div>
        <div class="col text-right">
            {{$pessoas->fragment('usuarios-ativos')->render()}}
        </div>
    </div>
</div>
