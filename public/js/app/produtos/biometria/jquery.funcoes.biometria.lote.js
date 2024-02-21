$(document).ready(function() {
    $('div#biometria-lote-detalhes').on('show.bs.modal', function (ev) {
        var uuid = $(ev.relatedTarget).data('uuid');

        $(this).find('.modal-title').text('Detalhes do lote - ' + $(ev.relatedTarget).data('uuid'));

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/biometria-lotes/' + uuid,
            context: this,
            beforeSend: function() {
                ajax_beforesend()
            },
            success: function (retorno) {
                $(this).find('.modal-body').html(retorno)
                ajax_success()
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#biometria-lote-detalhes').on('click', 'table#biometria-resultados a.reprocessar', function(e) {
		e.preventDefault();
		var uuid = $(this).data('uuid');
		var cpf = $(this).data('cpf');

		swal({
			title: 'Reprocessar consulta',
			text: 'Deseja realmente reprocessar a consulta do CPF ' + cpf + ' (UUID: ' + uuid + ')?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Não',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim',
			showLoaderOnConfirm: true,
		}).then(function( retorno ) {
			if (retorno.value == true) {
				$.ajax({
					type: "POST",
					url: URL_BASE + 'app/produtos/biometrias/' + uuid + '/reprocessar',
					context: this,
					beforeSend: function () {
						ajax_beforesend();
					},
					success: function (retorno) {
						switch (retorno.status) {
							case 'erro':
								var alerta = swal("Erro!",retorno.message,"error");
								break;
							case 'sucesso':
								var alerta = swal("Sucesso!",retorno.message,"success");
								break;
							case 'alerta':
								var alerta = swal("Ops!",retorno.message,"warning");
								break;
							default:
								var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
								break;
						}
						alerta.then(function(){
							if (retorno.recarrega=='true') {
								location.reload();
							}
						});
						ajax_success();
					},
					error: function(ev, xhr, settings, error) {
						ajax_error(ev);
					}
				});
			}
		});
	});

    $('table#lotes').on('click', 'a.reprocessar', function(e) {
		e.preventDefault();
		var uuid = $(this).data('uuid');
		var cpf = $(this).data('cpf');

		swal({
			title: 'Reprocessar consultas com erro',
			text: 'Deseja realmente reprocessar as consultas com erro do lote UUID: ' + uuid + '?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Não',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim',
			showLoaderOnConfirm: true,
		}).then(function( retorno ) {
			if (retorno.value == true) {
				$.ajax({
					type: "POST",
					url: URL_BASE + 'app/produtos/biometria-lotes/' + uuid + '/reprocessar',
					context: this,
					beforeSend: function () {
						ajax_beforesend();
					},
					success: function (retorno) {
						switch (retorno.status) {
							case 'erro':
								var alerta = swal("Erro!",retorno.message,"error");
								break;
							case 'sucesso':
								var alerta = swal("Sucesso!",retorno.message,"success");
								break;
							case 'alerta':
								var alerta = swal("Ops!",retorno.message,"warning");
								break;
							default:
								var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
								break;
						}
						alerta.then(function(){
							if (retorno.recarrega=='true') {
								location.reload();
							}
						});
						ajax_success();
					},
					error: function(ev, xhr, settings, error) {
						ajax_error(ev);
					}
				});
			}
		});
	});

	$('table#lotes').on('click', 'a.reenviar-notificacao', function(e) {
		e.preventDefault();
		var uuid = $(this).data('uuid');
		var cpf = $(this).data('cpf');

		swal({
			title: 'Reenviar notificação',
			text: 'Deseja realmente reenviar a notificação de finalização lote UUID: ' + uuid + '?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Não',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim',
			showLoaderOnConfirm: true,
		}).then(function( retorno ) {
			if (retorno.value == true) {
				$.ajax({
					type: "POST",
					url: URL_BASE + 'app/produtos/biometria-lotes/' + uuid + '/reenviar-notificacao',
					context: this,
					beforeSend: function () {
						ajax_beforesend();
					},
					success: function (retorno) {
						switch (retorno.status) {
							case 'erro':
								var alerta = swal("Erro!",retorno.message,"error");
								break;
							case 'sucesso':
								var alerta = swal("Sucesso!",retorno.message,"success");
								break;
							case 'alerta':
								var alerta = swal("Ops!",retorno.message,"warning");
								break;
							default:
								var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
								break;
						}
						alerta.then(function(){
							if (retorno.recarrega=='true') {
								location.reload();
							}
						});
						ajax_success();
					},
					error: function(ev, xhr, settings, error) {
						ajax_error(ev);
					}
				});
			}
		});
	});
});
