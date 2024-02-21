$(document).ready(function() {
    $('div#documento-comentarios').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento')

        carrega_comentarios($(this), uuid_documento);
    });

    $('div#documento-comentarios').on('click', 'button.salvar-comentario', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-comentario-novo]');
        var obj_modal = $(this).closest('.modal')
        var uuid_documento = form.find('input[name=uuid_documento]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/comentarios',
            context: this,
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
                    if (retorno.recarrega === 'true') {
                        carrega_comentarios(obj_modal, uuid_documento);
                    }
                    ajax_success();
                });
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#documento-comentarios-arquivos').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento')
        var uuid_documento_comentario = $(ev.relatedTarget).data('idcomentario');
        var obj_modal = $(this).closest('.modal');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/comentarios/'+uuid_documento_comentario+'/arquivos',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                obj_modal.find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, obj_modal, true);
            }
        });
    });

});

function carrega_comentarios(obj_modal, uuid_documento) {
    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/comentarios',
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
