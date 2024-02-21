$(document).ready(function() {
	$('div#alertas-registro').on('show.bs.modal', function (ev) {
		$(this).find('.modal-title').html($(ev.relatedTarget).data('title'));

		data_args = {
			'produto': $(ev.relatedTarget).data('produto')
		}
	    $.ajax({
			type: "GET",
			url: URL_BASE+'app/alertas/registros',
			data: data_args,
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

	$('div#alertas-documentos').on('show.bs.modal', function (ev) {
		$(this).find('.modal-title').html($(ev.relatedTarget).data('title'));

	    $.ajax({
			type: "GET",
			url: URL_BASE+'app/alertas/documentos',
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
});
