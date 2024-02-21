$(document).ready(function() {
    $('div#registro-fiduciario-comentarios-internos').on('show.bs.modal', function (evt) {

        const idRegistroFiduciario = $(evt.relatedTarget).data('idregistro');

        carrega_comentarios_internos($(this), idRegistroFiduciario);
    });

    function updateComentarioInternoNumber()
    {
        const textSpan = document.querySelector(`#botao-abrir-modal-comentario-interno > span`);
        const newNumber = parseInt(textSpan.textContent.replace(/\D/g,``), 10) + 1;

        textSpan.textContent = newNumber === 1 ? `${newNumber} comentário interno` : `${newNumber} comentários internos`;
    }

    $('div#registro-fiduciario-comentarios-internos').on('click', 'button.salvar-comentario', function (evt) {
        evt.preventDefault();
        const form = $('form[name=form-registro-fiduciario-comentarios-internos-novo]');
        const obj_modal = $(this).closest('.modal')
        const id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/comentarios-internos',
            context: this,
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                let alerta;
                switch (retorno.status) {
                    case 'sucesso':
                        updateComentarioInternoNumber();
                        alerta = swal({title: 'Sucesso!', html: retorno.message, type: 'success'});
                        break;
                    case 'alerta':
                        alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                alerta.then(function () {
                    if (retorno.recarrega === 'true') {
                        carrega_comentarios_internos(obj_modal, id_registro_fiduciario);
                    }
                    ajax_success();
                });
            },
            error: function (ev) {
                ajax_error(ev, $(this), true);
            }
        });
    });

});

function carrega_comentarios_internos(obj_modal, id_registro_fiduciario) {

    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/comentarios-internos',
        context: this,
        beforeSend: function() {
            ajax_beforesend();
        },
        success: function(retorno) {
            obj_modal.find('.modal-body').html(retorno);
            ajax_success();
        },
        error: function(ev) {
            ajax_error(ev, obj_modal, true);
        }
    });
}