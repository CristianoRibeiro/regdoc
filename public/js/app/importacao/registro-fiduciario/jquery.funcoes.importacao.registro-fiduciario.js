$(document).ready(function() {
	$('div#novo-importacao-registro').on('show.bs.modal', function (ev) {
	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/importacao/registro-fiduciario/novo',
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

	$('div#novo-importacao-registro').on('click', 'button.enviar-arquivos', function (ev) {
		var form = $('form[name=form-novo-importacao-registro]');
		var erros = new Array();
		var obj_modal = $(this).closest('.modal');

		if (form.find('div#arquivos-registro>div.arquivo').length<=0) {
			erros.push('Você deve inserir ao menos um arquivo.');
		}
		if (form.find('div#arquivos-registro>div.arquivo[data-inassdigital="O"]>button.assinatura:not(.in_assinado)').length>0) {
			erros.push(form.find('div#arquivos-registro>div.arquivo[data-inassdigital="O"]>button.assinatura:not(.in_assinado)').length+' dos arquivos inseridos são de assinatura obrigatória.');
		}
		if (erros.length>0) {
			form_error(erros);
		} else {
			var data = new FormData(form.get(0));
		    $.ajax({
				type: "POST",
				url: URL_BASE+'app/importacao/registro-fiduciario/preimportar',
				data: data,
				contentType: false,
				processData: false,
				context: this,
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(retorno) {
					obj_modal.find('.modal-body form').html(retorno);
					obj_modal.find('.modal-footer button.enviar-arquivos').removeClass('enviar-arquivos').addClass('finalizar-importacao').html('Finalizar importação');
					ajax_success();
				},
				error: function(ev, xhr, settings, error) {
			    	ajax_error(ev);
			    }
			});
		}
	});

	$('div#novo-importacao-registro').on('click', 'button.finalizar-importacao', function (ev) {
		var form = $('form[name=form-novo-importacao-registro]');
		var erros = new Array();

		if (form.find('div#arquivos-registro>div.arquivo[data-inassdigital="O"]>button.assinatura:not(.in_assinado)').length>0) {
			erros.push(form.find('div#arquivos-registro>div.arquivo[data-inassdigital="O"]>button.assinatura:not(.in_assinado)').length+' dos arquivos inseridos são de assinatura obrigatória.');
		}
		if (erros.length>0) {
			form_error(erros);
		} else {
			var data = new FormData(form.get(0));
		    $.ajax({
				type: "POST",
				url: URL_BASE+'app/importacao/registro-fiduciario/importar',
				data: data,
				contentType: false,
				processData: false,
				context: this,
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(retorno) {
					swal("Sucesso!", retorno.message, "success").then(function(ev) {
						location.reload();
					});
				},
				error: function(ev, xhr, settings, error) {
			    	ajax_error(ev);
			    }
			});
		}
	});

	$('div#detalhes-arquivo-registro').on('show.bs.modal', function (ev) {
		var id_arquivo_controle_xml = $(ev.relatedTarget).data('idarquivo');
		var subtitulo = $(ev.relatedTarget).data('subtitulo');

		var data_args = {
			'id_arquivo_controle_xml':id_arquivo_controle_xml,
		};

	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/importacao/registro-fiduciario/detalhes',
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

	$('div#visualizar-certificado-registro').on('show.bs.modal', function (ev) {
		var id_arquivo_controle_xml = $(ev.relatedTarget).data('idarquivo');
		var subtitulo = $(ev.relatedTarget).data('subtitulo');

		var data_args = {
			'id_arquivo_controle_xml':id_arquivo_controle_xml,
		};

	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/importacao/registro-fiduciario/certificado',
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

});
