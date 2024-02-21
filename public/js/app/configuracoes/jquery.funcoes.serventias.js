$(document).ready(function() {

	// Popular html do modal
	$('div#nova-serventia').on('show.bs.modal', function (ev) {
	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/serventias/novo',
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

	// Função para buscar endereço no modal
	$('div#nova-serventia').on('blur', 'input[name=nu_cep]', function(e){

        var form = $(this).closest('form');
        var cep = $(this).val().replace(/[^\d]+/g,'');

        if (cep!='' && cep.length==8) {
            var cep_url = "https://viacep.com.br/ws/" + cep + "/json/";

            $.ajax({
                url: cep_url,
                type: "GET",
                dataType: "jsonp",
                crossOrigin: true,
                crossDomain: true,
                contentType: "application/json; charset=utf-8",
                beforeSend: function() {
                    ajax_beforesend();
                },
                success: function(response){
                    if (!response.erro) {
                        id_estado = form.find('select[name=id_estado]').find('option[data-uf="' + response.uf + '"]').val();
                        form.find('select[name=id_estado]').val(id_estado);

                        if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                            form.find('select[name=id_estado]').addClass('readonly');
                        } else {
                            form.find('select[name=id_estado]').attr('readonly', true);
                        }
                        carregar_cidades(form.find('select[name=id_cidade]'), 0, 0, response.uf, response.localidade, true);

                        form.find('input[name=no_endereco]').val(response.logradouro);
                        form.find('input[name=no_bairro]').val(response.bairro);
                    } else {
                        form.find('select[name=id_estado]').val('0').trigger('change');
                        if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                            form.find('select[name=id_estado]').removeClass('readonly');
                            form.find('select[name=id_estado]').closest('.bootstrap-select').removeClass('readonly');
                        } else {
                            form.find('select[name=id_estado]').attr('readonly', true);
                        }

                        form.find('input[name=no_endereco]').val('');
                        form.find('input[name=no_bairro]').val('');
                    }

                    if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                        form.find('select[name=id_estado]').selectpicker('refresh');
                    }

                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_estado]').val('0').trigger('change');
            if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                form.find('select[name=id_estado]').removeClass('readonly');
                form.find('select[name=id_estado]').closest('.bootstrap-select').removeClass('readonly');
                form.find('select[name=id_estado]').selectpicker('refresh');
            } else {
                form.find('select[name=id_estado]').attr('readonly', false);
            }

            form.find('input[name=no_endereco]').val('');
            form.find('input[name=nu_numero]').val('');
            form.find('input[name=no_bairro]').val('');
        }
    });

    // Função para carregar cidades e estados filtro e modal
    $('form[name=form-serventias-filtro], div#nova-serventia, div#editar-serventia ').on('change','select[name=id_estado]',function(e) {
        
        var form = $(this).closest('form');
        var data_args = {
            'id_estado': $(this).val(),
        };

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/cidade/lista',
            data: data_args,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(cidades) {
                form.find('select[name=id_cidade]').html('');
                if (cidades.length>0) {
                    $.each(cidades,function(key, cidade) {
                        form.find('select[name=id_cidade]').append('<option value="'+cidade.id_cidade+'">'+cidade.no_cidade+'</option>');
                    });
                    form.find('select[name=id_cidade]').prop('disabled', false);
                } else {
                    form.find('select[name=id_cidade]').prop('disabled', true);
                }
                form.find('select[name=id_cidade]').selectpicker('refresh');

                if(!form.find('select[name=id_estado]').hasClass('readonly')) {

                	form.find('select[name=id_cidade]').removeClass('readonly');
                	form.find('select[name=id_cidade]').closest('.bootstrap-select').removeClass('readonly');
                }

                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#nova-serventia').on('click', 'button.salvar_serventia', function(e) {
        var form = $('form[name=form-nova-serventia]');

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/serventias/salvar',
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
    

	$('div#detalhes-serventia').on('show.bs.modal', function (ev) {
        
        var id_serventia = $(ev.relatedTarget).data('idserventia');


	    $.ajax({
			type: "GET",
			url: URL_BASE+'app/serventias/detalhes/' + id_serventia,
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

    $('div#editar-serventia').on('show.bs.modal', function(ev) { 

        var id_serventia = $(ev.relatedTarget).data('idserventia');

	    $.ajax({
			type: "GET",
			url: URL_BASE+'app/serventias/editar/' + id_serventia,
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

    $('div#editar-serventia').on('click', 'button.editar_serventia', function(e) {

        var form = $('form[name=form-editar-serventia]');

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/serventias/alterar',
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

});
