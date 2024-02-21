@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/inicio/jquery.funcoes.operadores.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.operadores.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <section id="app">
    	<div class="container">
            <div class="card box-app mt-3">
                <div class="card-header">
                    Operadores x Registros
                    <div class="card-subtitle">
                        Página inicial
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-fixed">
                        <thead>
                            <tr>
                                <th width="60%">Usuário</th>
                                <th width="25%">Totais de registros</th>
                                <th width="15%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($registros_usuarios_operadores) > 0)
                                @foreach($registros_usuarios_operadores as $usuario_operador)
                                    <tr>
                                        <td>
                                            <b>{{$usuario_operador->no_usuario}}</b><br />
                                            {{$usuario_operador->email_usuario}}
                                        </td>
                                        <td>
                                            @php
                                            $totais = $usuario_operador->registro_fiduciario_operador_situacao();
                                            @endphp
                                            @if($totais['em_andamento']>0)
                                                <span class="badge badge-warning">{{$totais['em_andamento']}} registros em andamento</span><br />
                                            @endif
                                            @if($totais['nota_devolutiva']>0)
                                                <span class="badge badge-danger">{{$totais['nota_devolutiva']}} registros com nota devolutiva</span><br />
                                            @endif
                                            @if($totais['finalizado']>0)
                                                <span class="badge badge-success">{{$totais['finalizado']}} registros finalizados</span>
                                            @endif
                                        </td>                 
                                        <td class="options">
                                            <a href="#operadores-registros" class="btn btn-primary" data-toggle="modal" data-idusuariooperador="{{$usuario_operador->id_usuario}}">
                                                Ver registros
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(count($registros_sem_operadores) > 0)
                                <tr class="table-danger">
                                    <td>
                                        <b>Sem operadores</b>
                                    </td>
                                    <td>
                                        @if($registros_sem_operadores['em_andamento']>0)
                                            <span class="badge badge-warning">{{$registros_sem_operadores['em_andamento']}} registros em andamento</span><br />
                                        @endif
                                        @if($registros_sem_operadores['nota_devolutiva']>0)
                                            <span class="badge badge-danger">{{$registros_sem_operadores['nota_devolutiva']}} registros com nota devolutiva</span><br />
                                        @endif
                                        @if($registros_sem_operadores['finalizado']>0)
                                            <span class="badge badge-success">{{$registros_sem_operadores['finalizado']}} registros finalizados</span>
                                        @endif
                                    </td>                 
                                    <td class="options">
                                        <a href="#operadores-registros" class="btn btn-primary" data-toggle="modal" data-idusuariooperador="-1">
                                            Ver registros
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
    	</div>
    </section>
    @include('app.inicio.operadores.registro-fiduciario.geral-operadores-registros-modais')
@endsection