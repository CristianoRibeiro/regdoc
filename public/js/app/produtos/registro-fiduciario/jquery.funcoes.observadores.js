$(document).ready(function() {
    $('div#registro-fiduciario-observadores').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/observadores',
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

    function updateObservadorNumber(operation)
    {
        const textSpan = document.querySelector(`#botao-abrir-modal-observadores > span`);
        const currentNumber = parseInt(textSpan.textContent.replace(/\D/g,``), 10);
        const newNumber = operation === `add`? currentNumber + 1 : currentNumber - 1;

        textSpan.textContent = newNumber === 1 ? `${newNumber} observador(a)` : `${newNumber} observadores`;
    }

    $('div#registro-fiduciario-observadores').on('click', 'button.salvar-observador', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-observador-novo]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var obj_modal = $(this).closest('.modal')
        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/observadores',
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
                        updateObservadorNumber(`add`);
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
                        carrega_observadores(obj_modal, id_registro_fiduciario);
                    }
                    ajax_success();
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-observadores').on('click', 'button.remover-observador', function (ev) {
        ev.preventDefault();
        var id_registro_fiduciario = $(this).data('idregistro');
        var id_registro_fiduciario_observador = $(this).data('idregistroobservador');
        var obj_modal = $(this).closest('.modal');

        $.ajax({
            type: "DELETE",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/observadores/' + id_registro_fiduciario_observador,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!",retorno.message,"error");
                        break;
                    case 'sucesso':
                        updateObservadorNumber(`subtract`);
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
                        carrega_observadores(obj_modal, id_registro_fiduciario);
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

function carrega_observadores(obj_modal, id_registro_fiduciario) {
    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/observadores',
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
