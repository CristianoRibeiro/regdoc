@extends('portal.layouts.principal')

@section('meta-portal')
	<meta name="robots" content="noindex">
@endsection

@section('js-portal')
	<script defer type="text/javascript" src="{{asset('js/portal/jquery.funcoes.certificado-vidaas.js')}}?v={{config('app.version')}}"></script>
	@if(old('id_estado'))
		<script defer>
			$(document).ready(function() {
				carregar_cidades($('select[name=id_cidade]'), {{ old('id_estado') }}, {{ (old('id_cidade') ? old('id_cidade') : 0) }});
			});
		</script>
	@endif
@endsection

@section('portal')
    <section id="conteudos">
        <div class="container">
            <h3 class="titulo">Formulário de requisição de certificado - VIDaaS</h3>
            <img src="{{ asset('img/vidaas-header.png') }}" class="img-fluid" title="Certificado Vidaas" alt="Certificado Vidaas" />
            <div class="card">
                <div class="card-body">
					@if (session('status'))
						@switch (session('status'))
							@case('sucesso')
								<div class="alert alert-success">
									<h2>Sucesso!</h2>
									<p>Seu formulário foi enviado com sucesso, em breve entraremos em contato para realização do agendamento.</p>
								</div>
								@break
							@case('erro')
								<div class="alert alert-danger">
									<h2>Erro!</h2>
									<p>{{session('message')}}</p>
								</div>
								@break
						@endswitch
					@endif
                    <div class="row">
                        <div class="col-12 col-md-4 text-center">
                            @if($portal_certificado_vidaas_cliente)
                                {!! $portal_certificado_vidaas_cliente->no_logo !!}
                            @endif
                            <div class="alert alert-info mb-0 mt-2 text-left">
                                Você está na página para início da emissão do seu certificado digital, assim poderá assinar todos documentos de forma eletrônica.
                            </div>
                        </div>
                        <div class="col-12 col-md-8 certificado-vidaas">
                            <form name="form-certificado" method="POST" action="{{ route('certificado-vidaas', request()->link ?? '') }}">
                                @csrf
								<input type="hidden" name="cliente" value="{{ request()->link ?? '' }}" />

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            &bull; {{ $error }}<br />
                                        @endforeach
                                    </div>
                                @endif
								<fieldset>
									<legend>DADOS PESSOAIS</legend>
	                                <div class="row">
	                                    <div class="col-12">
	                                        <label class="control-label"><b>Nome *</b></label>
	                                        <input name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome')}}" />
	                                    </div>
									</div>
	                                <div class="row mt-1">
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>CPF *</b></label>
	                                        <input name="cpf" class="form-control cpf @error('cpf') is-invalid @enderror" value="{{old('cpf')}}">
	                                    </div>
										<div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Data de nascimento *</b></label>
	                                        <input name="data_nascimento" class="form-control data_ate_hoje @error('data_nascimento') is-invalid @enderror" value="{{old('data_nascimento')}}">
	                                    </div>
	                                </div>
	                                <div class="row mt-1">
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>E-mail *</b></label>
	                                        <input name="email" class="text-lowercase form-control @error('email') is-invalid @enderror" value="{{old('email')}}" />
	                                    </div>
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Celular / Telefone *</b></label>
	                                        <input name="telefone" class="form-control celular @error('telefone') is-invalid @enderror" value="{{old('telefone')}}">
	                                    </div>
	                                </div>
									<div class="alert @error('in_cnh') alert-danger @else alert-info @enderror mt-2 mb-0">
										<b>Você possui CNH (Carteira Nacional de Habilitação)?</b><br />
										<div class="custom-control custom-radio custom-control-inline mt-1">
                                            <input type="radio" class="custom-control-input" id="in_cnh_S" name="in_cnh" value='S' {{$errors->any()?(old('in_cnh')=='S'?'checked':''):''}} />
                                            <label class="custom-control-label" for="in_cnh_S">Sim</label>
                                        </div>
										<div class="custom-control custom-radio custom-control-inline mt-1">
                                            <input type="radio" class="custom-control-input" id="in_cnh_N" name="in_cnh" value='N' {{$errors->any()?(old('in_cnh')=='N'?'checked':''):''}} />
                                            <label class="custom-control-label" for="in_cnh_N">Não</label>
                                        </div>
									</div>
								</fieldset>
								<fieldset class="mt-3">
									<legend>ENDEREÇO</legend>
	                                <div class="row mt-1">
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>CEP</b></label>
	                                        <input name="cep" class="form-control cep @error('cep') is-invalid @enderror" value="{{old('cep')}}" />
	                                    </div>
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Endereço</b></label>
	                                        <input name="endereco" class="form-control @error('endereco') is-invalid @enderror" maxlength="255" value="{{old('endereco')}}" />
	                                    </div>
	                                </div>
	                                <div class="row mt-1">
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Número</b></label>
	                                        <input name="numero" class="form-control @error('numero') is-invalid @enderror" maxlength="10" value="{{old('numero')}}" />
	                                    </div>
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Bairro</b></label>
	                                        <input name="bairro" class="form-control @error('bairro') is-invalid @enderror" maxlength="120" value="{{old('bairro')}}" />
	                                    </div>
	                                </div>
	                                <div class="row mt-1">
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Estado</b></label>
	                                        <select name="id_estado" class="form-control selectpicker @error('id_estado') is-invalid @enderror" data-live-search="true" title="Selecione">
	                                            @if(count($estados)>0)
	                                                @foreach($estados as $estado)
	                                                    <option value="{{$estado->id_estado}}" data-uf="{{$estado->uf}}" {{$estado->id_estado==old('id_estado')?'selected':''}}>{{$estado->no_estado}}</option>
	                                                @endforeach
	                                            @endif
	                                        </select>
	                                    </div>
	                                    <div class="col-12 col-md-6">
	                                        <label class="control-label"><b>Cidade</b></label>
	                                        <select name="id_cidade" class="form-control selectpicker @error('id_cidade') is-invalid @enderror" data-live-search="true" title="Selecione" disabled></select>
	                                    </div>
	                                </div>
									<div class="alert alert-warning mt-2 mb-0">
										Os campos de endereço são obrigatórios caso não possua uma CNH (Carteira Nacional de Habilitação).
									</div>
								</fieldset>
								<fieldset class="mt-3">
									<legend>OUTROS</legend>
	                                <div class="mt-1">
	                                    <label class="control-label">Observações</label>
	                                    <textarea name="observacoes" class="form-control">{{old('nome')}}</textarea>
	                                </div>
									<div class="alert alert-info mt-2 mb-0">
										O seu certificado será emitido via video conferência.
									</div>
								</fieldset>
								{{--
                                <div class="mt-2">
                                    <div class="alert alert-info mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="in_delivery" name="in_delivery" value='S' {{$errors->any()?(old('in_delivery')=='S'?'checked':''):''}} />
                                            <label class="custom-control-label" for="in_delivery">Desejo realizar minha emissão em casa/trabalho (delivery)</label>
                                        </div>
                                    </div>
                                </div>
								--}}
                                <div class="mt-3 text-right">
                                    <button type="submit" class="enviar-cadastro btn btn-success">Enviar requisição</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
