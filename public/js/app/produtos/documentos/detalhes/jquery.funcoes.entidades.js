$(document).ready(function() {
    $('div#documento-vincular-entidade').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento')

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/vincular-entidade',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#documento-vincular-entidade').on('click', 'button.vincular-entidade', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-vincular-entidade]');
        var uuid_documento = form.find('input[name=uuid_documento]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/vincular-entidade',
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
                ajax_error(ev)
            }
        });
    });
});
