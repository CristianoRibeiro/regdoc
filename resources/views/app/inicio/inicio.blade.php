@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/inicio/jquery.funcoes.inicio.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <section id="app">
    	<div class="container">
    		<div class="card box-app">
				<div class="card-header">
					Alertas
					<div class="card-subtitle">
						Página inicial
					</div>
				</div>
				<div class="card-body">
					<p>Olá <b>{{Auth::User()->no_usuario}}</b>, seja bem vindo!
					<div class="alertas row">
						@if (Gate::allows('registros-fiduciario'))
							<div class="col-12 col-md-3">
								<a href="#" class="alerta h-100" data-toggle="modal" data-target="#alertas-registro" data-produto="fiduciario" data-title="Registros fiduciários">
									<span class="badge {{ ($total_pedidos_fiduciario > 0?'badge-danger':'badge-secondary') }}">
										{{ $total_pedidos_fiduciario }}
									</span>
									<i class="fas fa-bell"></i>
									<h5 class="mb-0">Registros fiduciários</h5>
								</a>
							</div>
						@endif
						@if (Gate::allows('registros-garantias'))
							<div class="col-12 col-md-3">
								<a href="#" class="alerta h-100" data-toggle="modal" data-target="#alertas-registro" data-produto="garantias" class="Registros de garantias / contratos">
									<span class="badge {{ ($total_pedidos_garantias > 0?'badge-danger':'badge-secondary') }}">
										{{ $total_pedidos_garantias }}
									</span>
									<i class="fas fa-bell"></i>
									<h5 class="mb-0">Registros de garantias / contratos</h5>
								</a>
							</div>
						@endif
						@if (Gate::allows('documentos'))
							<div class="col-12 col-md-3">
								<a href="#" class="alerta h-100" data-toggle="modal" data-target="#alertas-documentos" class="e-Doc">
									<span class="badge {{ ($total_pedidos_documentos > 0?'badge-danger':'badge-secondary') }}">
										{{ $total_pedidos_documentos }}
									</span>
									<i class="fas fa-bell"></i>
									<h5 class="mb-0">e-Doc</h5>
								</a>
							</div>
						@endif
					</div>
				</div>
			</div>
    	</div>
    </section>

    @include('app.inicio.inicio-modais')
@endsection