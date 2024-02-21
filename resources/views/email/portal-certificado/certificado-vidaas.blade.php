@extends('email.layouts.principal')

@section('content')
    Uma nova requisição de certificado VIDaaS foi enviada e salvo via formulário no site, segue os dados enviados:<br /><br >

    @if($portal_certificado_vidaas->portal_certificado_vidaas_cliente)
	   <b>Cliente: </b> {{ $portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_cliente }} <br /><br />
    @endif
	<b>Nome:</b> {{$portal_certificado_vidaas->nome}}<br />
	<b>CPF:</b> {{$portal_certificado_vidaas->cpf}}<br />
	<b>E-mail:</b> {{$portal_certificado_vidaas->email}}<br />
	<b>Celular / Telefone:</b> {{$portal_certificado_vidaas->telefone}}<br />
	<b>Data de nascimento:</b> {{Helper::formata_data($portal_certificado_vidaas->data_nascimento)}}<br />
	<b>Possui CNH?:</b> {{($portal_certificado_vidaas->in_cnh=='S'?'Sim':'Não')}}<br /><br />

	<b>Endereço:</b><br />
	<b>CEP:</b> {{$portal_certificado_vidaas->cep}}<br />
	<b>Endereço:</b> {{$portal_certificado_vidaas->endereco}}<br />
	<b>Número:</b> {{$portal_certificado_vidaas->numero}}<br />
	<b>Bairro:</b> {{$portal_certificado_vidaas->bairro}}<br />
	<b>Cidade / UF:</b> {{$portal_certificado_vidaas->cidade->no_cidade ?? ''}} / {{$portal_certificado_vidaas->cidade->estado->no_estado ?? ''}}<br /><br />

	{{-- <b>Deseja atendimento delivery?</b> {{$portal_certificado_vidaas->in_delivery=='S'?'Sim':'Não'}}<br /><br /> --}}

	<b>Observações:</b> {{$portal_certificado_vidaas->observacoes}}<br />
@endsection
