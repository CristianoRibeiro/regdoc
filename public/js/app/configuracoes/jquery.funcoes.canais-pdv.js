$(document).ready(function() {

	$('div#novo-canal-pdv').on('show.bs.modal', function (ev) {
	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/canais-pdv/novo',
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body form').html(retorno);
				ajax_success();
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});
	});

    $('div#detalhes-canal-pdv').on('show.bs.modal', function (ev) {

        var idcanalpdvparceiro = $(ev.relatedTarget).data('idcanalpdvparceiro');

        $.ajax({
            type: "GET",
            url: URL_BASE+'app/canais-pdv/detalhes/'+ idcanalpdvparceiro,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#editar-canal-pdv').on('show.bs.modal', function(ev) { 

        var idcanalpdvparceiro = $(ev.relatedTarget).data('idcanalpdvparceiro'); 

	    $.ajax({
			type: "GET",
			url: URL_BASE+'app/canais-pdv/editar/'+ idcanalpdvparceiro,
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body form').html(retorno);
				ajax_success();
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});

    });

	$('div#novo-canal-pdv').on('click', 'button.salvar_canal_pdv', function(e) {
		var form = $('form[name=form-novo-canal-pdv]');

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/canais-pdv/salvar_parceiro',
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.message, "error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!", retorno.message, "success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                alerta.then(function() {
                    if (retorno.recarrega == 'true') {
                        location.reload();
                    }

                    ajax_success();
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })
	});

    $('div#editar-canal-pdv').on('click', 'button.editar_canal_pdv', function(e) {

        var form = $('form[name=form-editar-canal-pdv]');

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/canais-pdv/alterar',
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.message, "error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!", retorno.message, "success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                alerta.then(function() {
                    if (retorno.recarrega == 'true') {
                        location.reload();
                    }

                    ajax_success();
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })

    });

    $('table#canais-pdv').on('click', 'a.desativar', function (ev) {
        ev.preventDefault();
		var idcanalpdvparceiro = $(this).data('idcanalpdvparceiro'); 

		swal({
			title: 'Tem certeza?',
			html: 'Tem certeza que deseja desativar o canal pdv parceiro selecionado?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim',
			cancelButtonText: 'Não',
			confirmButtonClass: 'btn btn-success btn-lg ml-3',
			cancelButtonClass: 'btn btn-danger btn-lg ml-3',
			buttonsStyling: false,
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				var data_args = {
					'id_canal_pdv_parceiro': idcanalpdvparceiro
				};
			    $.ajax({
					type: "POST",
					url: URL_BASE+'app/canais-pdv/desativar',
					data: data_args,
					beforeSend: function() {
						ajax_beforesend();
					},
					success: function(retorno) {
						swal("Sucesso!", retorno.message, "success").then(function(ev) {
							location.reload();
							ajax_success();
						});
					},
					error: function(ev, xhr, settings, error) {
				    	ajax_error(ev);
				    }
				});
			}
		})
	});

});
