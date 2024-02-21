@if(count($todas_situacoes)>0)
	<div class="alertas row">
		@foreach ($todas_situacoes as $key => $situacao)
			<div class="col-md-4">
				<a href="{{route('app.produtos.registros.index', [request()->produto])}}?id_situacao_pedido_grupo_produto[]={{$situacao->id_situacao_pedido_grupo_produto}}" class="alerta h-100">
					<span class="badge {{($situacao->pedidos->count() > 0 ? 'badge-danger' : 'badge-secondary')}}">
						{{$situacao->pedidos->count() }}
					</span>
					<i class="fas fa-bell"></i>
					<h5 class="mb-0" style="word-break: break-word;">
						{{$situacao->no_situacao_pedido_grupo_produto}}
					</h5>
				</a>
			</div>
			@if(($key+1)%3==0)
				</div>
				<div class="alertas row mt-3">
			@endif
		@endforeach
	</div>
@else
	<div class="alert alert-danger mb-0">
        Nenhuma situação foi encontrada.
    </div>
@endif
