$(document).ready(function() {
    $('div#registro-fiduciario-operadores').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')
        var protocolo_pedido = $(ev.relatedTarget).data('protocolopedido')
        $(this).find('.modal-title span').text(protocolo_pedido ? protocolo_pedido : 'Nova parte');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/operadores',
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

    $('div#registro-fiduciario-operadores').on('change', 'select[name=id_pessoa]',function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        var data_args = {
            'id_pessoa_origem': $(this).val()
        };

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/usuario/listar',
            data: data_args,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(usuarios) {
                form.find('select#id_usuario').html('');
                if (usuarios.length>0) {
                    $.each(usuarios,function(key, usuarios) {
                        if (usuarios.in_registro_ativo=='S') {
                            form.find('select#id_usuario').append('<option value="' + usuarios.id_usuario + '">' + usuarios.no_usuario + '</option>');
                        }
                    });
                    form.find('select#id_usuario').prop('disabled', false);
                } else {
                    form.find('select#id_usuario').prop('disabled', true);
                }
                form.find('select#id_usuario').selectpicker('refresh');
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    function updateNumber(operation, amount = 1)
    {
        const textSpan = document.querySelector(`#botao-abrir-modal-operadores > span`);
        const currentNumber = parseInt(textSpan.textContent.replace(/\D/g,``), 10);
        const newNumber = operation === `add`? currentNumber + amount : currentNumber - amount;

        textSpan.textContent = newNumber === 1 ? `${newNumber} operador(a)` : `${newNumber} operadores`;

        const button = textSpan.parentElement;

        if(operation === `add`) {
            button.classList.remove(`btn-light-danger`);
            button.classList.add(`btn-light-success`);
        } else if(newNumber === 0) {
            button.classList.remove(`btn-light-success`);
            button.classList.add(`btn-light-danger`);
        }
    }

    $('div#registro-fiduciario-operadores').on('click', 'button.salvar-operador', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-operador-novo]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var obj_modal = $(this).closest('.modal')
        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/operadores',
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
                        updateNumber(`add`, data.getAll(`id_usuario[]`).length);
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
                        carrega_operadores(obj_modal, id_registro_fiduciario);
                    }
                    ajax_success();
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-operadores').on('click', 'button.remover-operador', function (ev) {
        ev.preventDefault();
        var id_registro_fiduciario = $(this).data('idregistro');
        var id_registro_fiduciario_operador = $(this).data('idregistrooperador');
        var obj_modal = $(this).closest('.modal');

        $.ajax({
            type: "DELETE",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/operadores/' + id_registro_fiduciario_operador,
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
                        updateNumber(`subtract`);
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
                        carrega_operadores(obj_modal, id_registro_fiduciario);
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

function carrega_operadores(obj_modal, id_registro_fiduciario) {
    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/operadores',
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
