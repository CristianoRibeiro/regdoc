@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/produtos/biometria/jquery.funcoes.biometria.consulta.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col">
							Consultar biometria
							<div class="card-subtitle">
								Produtos
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-7">
							<div class="alert alert-info">
								<form name="form-consulta-biometria" method="get" action="">
									<h5 class="font-weight-bold mt-1">Dados para consulta:</h5>
									<div class="form-group mt-2">
										<div class="form-group">
						                    <label class="control-label"><b>CPF:</b></label>
						                    <input name="cpf" type="text" class="form-control cpf" required />
						                </div>
						            </div>
									<div class="mt-3 text-right">
										<button type="submit" class="btn btn-primary btn-w-100-sm">Consultar</button>
									</div>
								</form>
				            </div>
						</div>
						<div class="col-12 col-md-5">
							<div class="alert alert-warning">
								<h3>Atenção!</h3>
								<p>
									Essa consulta utiliza bases biométricas para determinar se uma pessoa pode ou não emitir um certificado com videoconferência.
								</p>
								<p>
									Para realizar a emissão com videoconferência, o resultado dessa consulta precisa ser verdadeiro.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@section('loading')
    <div id="loading" class="d-flex">
        <div class="mx-auto text-white">
			<img src="{{asset('img/carregando02.gif')}}" alt="Carregando" />
			<p class="loading-text px-5"></p>
            <p class="loading-timer mb-0 px-5" style="display:none">
                Nova atualização em <span></span>s 
                <?php
				/*<br /><br /> <a id="loading-update" href="javscript:void(0);" class="btn btn-light">Atualizar agora</a>*/
				?>
            </p>
        </div>
    </div>
@endsection