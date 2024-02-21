$(document).ready(function() {
	/* Funções para a criação de usuários
	*/

	$('form[name=form-configuracoes-acesso]').on('submit', function (ev) {
		ev.preventDefault();
		var form = $(this);

	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/usuario/configuracoes/acesso',
			data: form.serialize(),
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				switch (retorno.status) {
					case 'sucesso':
						var alerta = swal({title: 'Sucesso!', html: retorno.message, type: 'success'});
						break;
					case 'alerta':
						var alerta = swal("Ops!", retorno.message, "warning");
						break;
					default:
						var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
						break;
				}
				alerta.then(function () {
					if (retorno.recarrega == 'true') {
						window.location = URL_ATUAL;
						ajax_success();
					}
				});
			},
			error: function(ev, xhr, settings, error) {
				ajax_error(ev);
		    }
		});
	});
});
