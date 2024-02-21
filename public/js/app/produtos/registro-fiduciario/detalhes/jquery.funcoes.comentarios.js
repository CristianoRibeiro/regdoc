$(document).ready(function() {
    $('div#registro-fiduciario-comentarios').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')

        carrega_comentarios($(this), id_registro_fiduciario);
    });

    function updateComentarioNumber()
    {
        const textSpan = document.querySelector(`#botao-abrir-modal-comentario > span`);
        const newNumber = parseInt(textSpan.textContent.replace(/\D/g,``), 10) + 1;

        textSpan.textContent = newNumber === 1 ? `${newNumber} comentário` : `${newNumber} comentários`;
    }

    $('div#registro-fiduciario-comentarios').on('click', 'button.salvar-comentario', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-comentario-novo]');
        var obj_modal = $(this).closest('.modal')
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/comentarios',
            context: this,
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'sucesso':
                        updateComentarioNumber();
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
                        carrega_comentarios(obj_modal, id_registro_fiduciario);
                    }
                    ajax_success();
                });
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-comentarios-arquivos').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')
        var id_registro_fiduciario_comentario = $(ev.relatedTarget).data('idcomentario');
        var obj_modal = $(this).closest('.modal');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/comentarios/'+id_registro_fiduciario_comentario+'/arquivos',
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

function carrega_comentarios(obj_modal, id_registro_fiduciario) {
    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/comentarios',
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
