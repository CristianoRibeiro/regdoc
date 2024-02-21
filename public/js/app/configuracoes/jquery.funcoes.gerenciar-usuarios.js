$(document).ready(function() {
	/* Funções para a criação de usuários
	*/

	$('div#novo-usuario').on('show.bs.modal', function (ev) {
	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/gerenciar-usuarios/novo',
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body').html(retorno);
				ajax_success();
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});
	});

	$('div#novo-usuario').on('change', 'form[name=form-adicionar-usuario] select[name=tp_pessoa]', function(ev) {
		let obj_input = $('div#novo-usuario form[name=form-adicionar-usuario] input[name=nu_cpf_cnpj]');

		switch($(this).val()) {
			case 'F':
				obj_input.parent('div').find('label').html('CPF');
				obj_input.mask('000.000.000-00');
				break;
			case 'J':
				obj_input.parent('div').find('label').html('CNPJ');
				obj_input.mask('00.000.000/0000-00');
				break;
		}
		obj_input.prop('disabled', false);
	});

	$('div#novo-usuario, div#novo-vinculo').on('change', 'form[name=form-adicionar-usuario] select[name=id_tipo_pessoa], form[name=form-buscar-usuario] select[name=id_tipo_pessoa]', function(ev) {
		ev.preventDefault();
		var form = $(this).closest('form');

		var id_tipo_pessoa = $(this).val();
		if (id_tipo_pessoa>0) {
			var data_args = {
				'id_tipo_pessoa': id_tipo_pessoa
			};
		    $.ajax({
				type: "POST",
				url: URL_BASE+'app/gerenciar-usuarios/listar-pessoas',
				data: data_args,
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(cidades) {
					form.find('select#id_pessoa').prop('disabled', false);
	                if (!$.isEmptyObject(cidades)) {
	                	HTML = '';
	                    $.each(cidades, function (key, cidade) {
	                    	HTML += '<optgroup label="'+cidade.no_cidade+'">';
		                    	$.each(cidade.pessoas, function (key, pessoa)
		                    	{
		                        	HTML += '<option value="'+pessoa.id_pessoa+'" data-tokens="'+pessoa.no_pessoa+' '+cidade.no_cidade+'">'+pessoa.no_pessoa+'</option>';
		                        });
	                        HTML += '</optgroup>';
	                    });
	                    form.find('select#id_pessoa').html(HTML);
	                } else {
	                    form.find('select#id_pessoa').html('').prop('disabled', true);
	                }

	                form.find('select#id_pessoa').selectpicker('refresh');

	                ajax_success();
				},
				error: function(ev, xhr, settings, error) {
			    	ajax_error(ev);
			    }
			});
		}
	});

	$('div#novo-usuario').on('submit', 'form[name=form-adicionar-usuario]', function (ev) {
		ev.preventDefault();
		var form = $(this);

	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/gerenciar-usuarios/inserir',
			data: form.serialize(),
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
	});

	/* Funções para a criação de vínculos de usuários
	*/

	$('div#novo-vinculo').on('show.bs.modal', function (ev) {
	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/gerenciar-usuarios/vinculos/novo',
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body').html(retorno);
				ajax_success();
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});
	});

	$('div#novo-vinculo').on('submit', 'form[name=form-buscar-usuario]', function (ev) {
		ev.preventDefault();
		var form = $(this);
		var erros = new Array();
		var obj_modal = $(this).closest('.modal');

		if (form.find('input[name=busca_usuario]').val()=='') {
			erros.push('O campo de login ou e-mail é obrigatório.');
		}

		if (erros.length>0) {
			form_error(erros);
		} else {
			var data_args = {
				'busca_usuario': form.find('input[name=busca_usuario]').val()
			};
		    $.ajax({
				type: "POST",
				url: URL_BASE+'app/gerenciar-usuarios/vinculos/novo',
				data: data_args,
				context: this,
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(retorno) {
					obj_modal.find('.modal-body').html(retorno);
					$('.selectpicker').selectpicker('render');
					ajax_success();
				},
				error: function(ev, xhr, settings, error) {
			    	ajax_error(ev);
			    }
			});
		}
	});

	$('div#novo-vinculo').on('click', 'form[name=form-buscar-usuario] table#usuarios button.vincular-usuario', function (ev) {
		ev.preventDefault();
		var form = $(this).closest('form');
		var id_usuario = $(this).data('idusuario');
		var obj_modal = $(this).closest('.modal');

		if (form.find('select[name=id_pessoa]').length>0) {
			var id_pessoa = form.find('select[name=id_pessoa]').val()
		} else {
			var id_pessoa = 0;
		}

		html = 'Um e-mail será enviado ao usuário informando esta ação.<br /><br />';
		html += 'Tem certeza que deseja vincular o usuário selecionado?<br /><br />';
		html += '<div class="bite-checkbox">';
			html += '<input name="in_usuario_master" id="usuario-master" type="checkbox" value="S">';
			html += '<label for="usuario-master">';
				html += 'Inserir como usuário master';
			html += '</label>';
		html += '</div>';
		html += '<br />';

		swal({
			title: 'Tem certeza?',
			html: html,
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
					'id_usuario': id_usuario,
					'in_usuario_master': $('div#swal2-content input#usuario-master').is(':checked')?'S':'N',
					'id_pessoa': (id_pessoa>0?id_pessoa:0)
				};
			    $.ajax({
					type: "POST",
					url: URL_BASE+'app/gerenciar-usuarios/vinculos/inserir',
					data: data_args,
					beforeSend: function() {
						ajax_beforesend();
					},
					success: function(retorno) {
						swal("Sucesso!", retorno.message, "success").then(function(ev) {
							location.reload();
							ajax_success(obj_modal);
						});
					},
					error: function(ev, xhr, settings, error) {
				    	ajax_error(ev);
				    }
				});
			}
		})
	});

	$('div#detalhes-usuario').on('show.bs.modal', function (ev) {
		var idusuario = $(ev.relatedTarget).data('idusuario');
		var subtitulo = $(ev.relatedTarget).data('subtitulo');

		var data_args = {
			'id_usuario': idusuario,
		};

	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/gerenciar-usuarios/detalhes',
			data: data_args,
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body').html(retorno);
				ajax_success(subtitulo, $(this));
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});
	});

	$('div#detalhes-usuario').on('click', 'table#vinculos-usuario button.remover-vinculo', function (ev) {
		ev.preventDefault();
		var id_usuario_pessoa = $(this).data('idusuariopessoa');

		swal({
			title: 'Tem certeza?',
			html: 'Tem certeza que deseja remover o vínculo do usuário selecionado?',
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
					'id_usuario_pessoa': id_usuario_pessoa
				};
			    $.ajax({
					type: "POST",
					url: URL_BASE+'app/gerenciar-usuarios/vinculos/remover',
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

	$("table#usuarios").on('click', 'a.gerar-nova-senha', function(e) {
		e.preventDefault();
		var id_usuario = $(this).data('idusuario');
		var no_usuario = $(this).data('nousuario');

		swal({
			title: 'Gerar nova senha',
			text: 'Deseja realmente gerar uma nova senha para o usuário "'+no_usuario+'"?',
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
					url: URL_BASE+'app/gerenciar-usuarios/gerar-nova-senha',
					data: 'id_usuario='+id_usuario,
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

	$("table#usuarios").on('click', 'a.desativar-usuario', function(e) {
		e.preventDefault();
		var id_usuario = $(this).data('idusuario');
		var no_usuario = $(this).data('nousuario');

		swal({
			title: 'Desabilitar Usuário?',
			text: 'Deseja realmente desativar o usuário "'+no_usuario+'"?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Não',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim',
			showLoaderOnConfirm: true,
		}).then(function(retorno) {
			if (retorno.value == true ) {
				$.ajax({
					type: "POST",
					url: URL_BASE+'app/gerenciar-usuarios/desativar-usuario',
					data: 'id_usuario='+id_usuario,
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

	$("table#usuarios").on('click', 'a.reativar-usuario', function(e) {
		e.preventDefault();
		var id_usuario = $(this).data('idusuario');
		var no_usuario = $(this).data('nousuario');

		var obj_modal = $(this).closest('.modal');

		swal({
			title: 'Reativar usuário',
			text: 'Deseja realmente reativar o usuário "'+no_usuario+'"?',
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
					url: URL_BASE+'app/gerenciar-usuarios/reativar-usuario',
					data: 'id_usuario='+id_usuario,
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
						if (retorno.recarrega=='true') {
							alerta.then(function(){
								location.reload();
							});
						}
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
