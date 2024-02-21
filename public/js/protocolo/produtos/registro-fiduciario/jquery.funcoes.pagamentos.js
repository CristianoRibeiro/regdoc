$(document).ready(function() {
    $('div#registro-fiduciario-pagamento-visualizar-guias').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario_pagamento = $(ev.relatedTarget).data('idregistrofiduciariopagamento');
        var tipo = $(ev.relatedTarget).data('tipo');

        $(this).find('.modal-title').text('Visualizar guias de ' + tipo);

        $.ajax({
            type: "GET",
            url: URL_BASE + 'protocolo/registro/pagamentos/' + id_registro_fiduciario_pagamento,
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

    $('div#registro-fiduciario-pagamento-guia-comprovante').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario_pagamento = $(ev.relatedTarget).data('idregistrofiduciariopagamento');
        var id_registro_fiduciario_pagamento_guia = $(ev.relatedTarget).data('idregistrofiduciariopagamentoguia');

        $(this).find('.modal-title').text('Enviar comprovante');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'protocolo/registro/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/' + id_registro_fiduciario_pagamento_guia + '/enviar-comprovante' ,
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

    $('div#registro-fiduciario-pagamento-guia-comprovante').on('click', 'button.salvar-comprovante', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-pagamento-comprovante]');
        var id_registro_fiduciario_pagamento = form.find('input[name=id_registro_fiduciario_pagamento]').val();
        var id_registro_fiduciario_pagamento_guia = form.find('input[name=id_registro_fiduciario_pagamento_guia]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'protocolo/registro/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/' + id_registro_fiduciario_pagamento_guia + '/salvar-comprovante' ,
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
                if (retorno.recarrega == 'true') {
                    alerta.then(function(){
                        location.reload();
                    });
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });
});
