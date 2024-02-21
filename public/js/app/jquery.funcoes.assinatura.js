$(document).ready(function() {
	$('body').on('click','div.arquivo>button.assinatura:not(.in_assinado),div#assinatura-arquivos>button.assinatura',function(ev) {
		ev.preventDefault();

		var token = $(this).data('token');
		var index_arquivo = $(this).data('indexarquivo')>=0?$(this).data('indexarquivo'):-1;
		var nome = $(this).data('nome')?$(this).data('nome'):null;
		var cpf_cnpj = $(this).data('cpf_cnpj')>=0?$(this).data('cpf_cnpj'):null;

		var data_args = {
			'arquivos_token': token,
			'index_arquivo': index_arquivo,
			'nome': nome,
			'cpf_cnpj': cpf_cnpj
		};

		if(isMobile.iOS()) {
			var url_key;
			var url_popup;

			$.ajax({
	            type: "POST",
	            url: URL_BASE+'app/arquivos/assinatura/iniciar-lote',
				data: data_args,
				async: false,
				beforeSend: function() {
					ajax_beforesend();
				},
	            success: function(retorno) {
					ajax_success();

					url_key = retorno.key;
					url_popup = retorno.url;
	            },
	            error: function(ev, xhr, settings, error) {
	                ajax_error(ev);
	            }
	        });

			var width = 570;
			var height = 640;
			var left = (screen.width/2)-(width/2);
			var top = (screen.height/2)-(height/2);

			window.onmessage = function (e) {
				if (e.data===url_key) {
					retorno_assinatura(url_key, token, index_arquivo);
				}
			};

			popup_obj = window.open(url_popup, 'Assinar arquivos', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+width+', height='+height+', top='+top+', left='+left);

			swal({
				title: 'Aguarde!',
				text: 'Aguardando retorno da assinatura digital.',
				type: "info",
				allowOutsideClick: false,
				allowEscapeKey: false,
				showConfirmButton: false,
				showCancelButton: true,
				cancelButtonText: 'Cancelar assinatura',
				cancelButtonColor: '#d33',
			}).then(function(result) {
				if (result.dismiss === 'cancel') {
					popup_obj.close();
				}
			});

			return popup_obj;
		} else {
			$.ajax({
	            type: "POST",
	            url: URL_BASE+'app/arquivos/assinatura/iniciar-lote',
				data: data_args,
				beforeSend: function() {
					ajax_beforesend();
				},
	            success: function(retorno) {
					ajax_success()

					var width = 570;
					var height = 640;
					var left = (screen.width/2)-(width/2);
					var top = (screen.height/2)-(height/2);

					window.onmessage = function (e) {
						if (e.data===retorno.key) {
							retorno_assinatura(retorno.key, token, index_arquivo);
						}
					};

					popup_obj = window.open(retorno.url, 'Assinar arquivos', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+width+', height='+height+', top='+top+', left='+left);

					swal({
						title: 'Aguarde!',
						text: 'Aguardando retorno da assinatura digital.',
						type: "info",
						allowOutsideClick: false,
						allowEscapeKey: false,
						showConfirmButton: false,
						showCancelButton: true,
						cancelButtonText: 'Cancelar assinatura',
						cancelButtonColor: '#d33',
					}).then(function(result) {
						if (result.dismiss === 'cancel') {
							popup_obj.close();
						}
					});

					return popup_obj;
	            },
	            error: function(ev, xhr, settings, error) {
	                ajax_error(ev);
	            }
	        });
		}
	});
});
function atualiza_assinar_todos(token) {
	obj_container = $('body').find('div.arquivos[data-token="'+token+'"]').closest('#novo-andamento');
	if (obj_container.length<=0) {
		obj_container = $('body').find('div.arquivos[data-token="'+token+'"]').closest('.modal-body');
	}

	total_arquivos = obj_container.find('div.arquivo>button.assinatura').length;

	if (total_arquivos>0) {
		total_assinaveis = obj_container.find('div.arquivo>button.assinatura:not(.in_assinado):not(.erro)').length;
		total_assinados = obj_container.find('div.arquivo>button.assinatura.in_assinado').length;
		total_erro = obj_container.find('div.arquivo>button.assinatura.erro').length;

		msgs = new Array();
		classe_alerta = '';
		desativar_btn = false;

		if(total_assinados>0) {
			msgs.push('<b>'+total_assinados+'</b> '+(total_assinados>1?' arquivos foram assinados corretamente.':'arquivo foi assinado corretamente.'));
			classe_alerta = 'primary';
			desativar_btn = true;
		}
		if (total_assinaveis>0) {
			msgs.push('<b>'+total_assinaveis+'</b> '+(total_assinaveis>1?'arquivos faltam ser assinados.':'arquivo falta ser assinado.'));
			classe_alerta = 'warning';
			desativar_btn = false;
		}
		if(total_erro>0) {
			msgs.push('<b>'+total_erro+'</b> '+(total_erro>1?'arquivos não foram assinados corretamente.':'arquivo não foi assinado corretamente.'));
			classe_alerta = 'danger';
			desativar_btn = false;
		}

		obj_container.find('div#assinatura-arquivos').attr('class','alert alert-'+classe_alerta+' mt-2 mb-0');
		obj_container.find('div#assinatura-arquivos>button.assinatura').attr('class','assinatura btn btn-'+classe_alerta+' mt-1').attr('disabled',desativar_btn);
		obj_container.find('div#assinatura-arquivos>div.mensagem').html(msgs.join('<br />'));

		if (obj_container.find('div#assinatura-arquivos:visible').length<=0) {
			obj_container.find('div#assinatura-arquivos').slideDown();
		}
	} else {
		obj_container.find('div#assinatura-arquivos').slideUp();
	}
}
function retorno_assinatura(key, token, index_arquivo) {
	var data_args = {
		'key': key,
		'arquivos_token': token,
		'index_arquivo': index_arquivo
	};

	$.ajax({
		type: "POST",
		url: URL_BASE+'app/arquivos/assinatura/retornar-lote',
		data: data_args,
		beforeSend: function() {
			ajax_beforesend();
		},
		success: function(retorno) {
			ajax_success()

			swal("Concluído", 'O processo de assinatura foi <b>concluído</b>. <br /><br />'+retorno.sucessos.length+' arquivo(s) obtiveram sucesso.<br />'+retorno.erros.length+' arquivo(s) não obtiveram sucesso.', "info").then(function() {
				$.each(retorno.sucessos,function(key, val) {
					$('div.arquivo#'+val).find('button.assinatura').removeClass('erro').addClass('in_assinado');
				});
				atualiza_assinar_todos(token);
			});
		},
		error: function(ev, xhr, settings, error) {
			ajax_error(ev);
		}
	});
}
function total_arquivos_assinaveis(obj_container) {
	return obj_container.find('div.arquivo>button.assinatura:not(.in_assinado):not(.erro)').length;
}
function total_arquivos_assinados(obj_container) {
	return obj_container.find('div.arquivo>button.assinatura.in_assinado').length;
}
