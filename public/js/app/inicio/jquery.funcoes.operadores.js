$(document).ready(function() {
	$('div#operadores-registros').on('show.bs.modal', function (ev) {
		data_args = {
			'id_usuario_operador': $(ev.relatedTarget).data('idusuariooperador')
		}
	    $.ajax({
			type: "GET",
			url: URL_BASE + 'app/operadores/registros/detalhes',
			data: data_args,
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body').html(retorno);
				init_datatable();
				ajax_success();
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});
	});
});
