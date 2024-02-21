$(document).ready(function() {
    $('div#registro-fiduciario-operacao').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/operacao/editar',
            context: this,
            beforeSend: function() {
                ajax_beforesend()
            },
            success: function (retorno) {
                $(this).find('.modal-body form').html(retorno)
                ajax_success()
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-operacao').on('click', 'button.atualizar-operacao', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-operacao]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/operacao',
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
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });
});
