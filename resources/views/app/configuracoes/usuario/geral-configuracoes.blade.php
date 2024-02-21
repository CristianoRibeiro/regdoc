@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.usuario.configuracoes.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <section id="app">
    	<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md">
							Meu usuário
							<div class="card-subtitle">
								Configurações
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-3">
							<div class="nav flex-column nav-pills" id="configuracoes-tab" role="tablist" aria-orientation="vertical">
								<a class="nav-link active" id="acesso-tab" data-toggle="pill" href="#acesso-content" role="tab" aria-controls="acesso-content" aria-selected="true">
									<b>Acesso</b>
								</a>
							</div>
						</div>
						<div class="col-12 col-md-9">
							<div class="tab-content" id="configuracoes-ontent">
								<div class="tab-pane fade show active" id="acesso-content" role="tabpanel" aria-labelledby="acesso-tab">
									@include('app.configuracoes.usuario.geral-configuracoes-acesso')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </section>
@endsection
