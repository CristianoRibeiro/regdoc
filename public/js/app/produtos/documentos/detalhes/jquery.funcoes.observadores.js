$(document).ready(function() {
    $('div#documento-observadores').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento')

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/observadores',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                $(this).find('.modal-body').html(retorno);
                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#documento-observadores').on('click', 'button.salvar-observador', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-observador-novo]');
        var uuid_documento = form.find('input[name=uuid_documento]').val();
        var obj_modal = $(this).closest('.modal')
        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/observadores',
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
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
                alerta.then(function () {
                    if (retorno.recarrega === 'true') {
                        carrega_observadores(obj_modal, uuid_documento);
                    }
                    ajax_success();
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#documento-observadores').on('click', 'button.remover-observador', function (ev) {
        ev.preventDefault();
        var uuid_documento = $(this).data('uuiddocumento');
        var id_documento_observador = $(this).data('iddocumentoobservador');
        var obj_modal = $(this).closest('.modal');

        $.ajax({
            type: "DELETE",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/observadores/' + id_documento_observador,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
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
                alerta.then(function () {
                    if (retorno.recarrega === 'true') {
                        carrega_observadores(obj_modal, uuid_documento);
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

function carrega_observadores(obj_modal, uuid_documento) {
    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/observadores',
        context: this,
        beforeSend: function() {
            ajax_beforesend();
        },
        success: function(retorno) {
            obj_modal.find('.modal-body').html(retorno);
            ajax_success();
        },
        error: function(ev, xhr, settings, error) {
            ajax_error(ev, obj_modal, true);
        }
    });
}
