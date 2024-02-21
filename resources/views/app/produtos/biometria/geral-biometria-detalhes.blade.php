@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/produtos/biometria/jquery.funcoes.biometria.js')}}?v={{config('app.version')}}"></script>
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
                            @switch($vscore_transacao->in_biometria_cpf)
                                @case(true)
                                    @php
                                        $classe_alerta = 'alert alert-success';
                                        $texto_resultado = '<i class="fas fa-check-circle"></i> O CPF consultado foi encontrato em nossas bases e poderá emitir certificado via videoconferência.';
                                    @endphp
                                    @break
                                @default
                                    @php
                                        $classe_alerta = 'alert alert-danger';
                                        $texto_resultado = '<i class="fas fa-times-circle"></i> O CPF consultado não foi encontrato em nossas bases e não poderá emitir certificado via videoconferência.';
                                    @endphp
                                    @break
                            @endswitch

							<div class="{{$classe_alerta}}">
								<h5 class="font-weight-bold mt-1">Dados consulta:</h5>
                                <p>
                                    <b>CPF consultado:</b> {{Helper::pontuacao_cpf_cnpj($vscore_transacao->nu_cpf_cnpj)}}<br />
                                    <b>Data da consulta:</b> {{Helper::formata_data_hora($vscore_transacao->dt_cadastro)}}<br />
                                    <b>Usuário:</b> {{$vscore_transacao->usuario_cad->no_usuario}}
                                </p>
                                <p class="font-weight-bold mb-0">
                                    {!!$texto_resultado!!}
                                </p>
				            </div>
                            <div class="mb-2 text-right">
                                <a href="{{route('app.produtos.biometrias.index')}}" class="btn btn-primary btn-w-100-sm">Consultar outro CPF</a>
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
	</section>
@endsection