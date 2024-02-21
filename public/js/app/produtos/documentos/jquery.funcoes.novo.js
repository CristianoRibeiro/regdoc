$(document).ready(function() {
    $('div#documento').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title'));
                $(this).find('.modal-footer').find('button.salvar-documento').show();
                url = URL_BASE + 'app/produtos/documentos/novo';

                var data_args = {};
                break;
        }

        $.ajax({
            type: "GET",
            url: url,
            data: data_args,
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

    $('div#documento').on('click', 'a.tipo-insercao-proposta', function(e) {
        $('div.tipos-insercao').hide();
        $('div.tipos-insercao-opcoes').show();
        $('div#accordion-documento').show();
        $('div#documento').find('button.salvar-documento').attr('disabled', false);

        $('label[for=tipo_insercao_proposta]').trigger('click');
    });
    $('div#documento').on('click', 'a.tipo-insercao-contrato', function(e) {
        $('div.tipos-insercao').hide();
        $('div.tipos-insercao-opcoes').show();
        $('div#accordion-documento').show();
        $('div#documento').find('button.salvar-documento').attr('disabled', false);

        $('label[for=tipo_insercao_contrato]').trigger('click');
    });
    $('div#documento').on('change', 'input[name=tipo_insercao]', function(e) {
        switch ($(this).val()) {
            case 'P':
                $('div.tipo-insercao.contrato').hide();
                $('div.tipo-insercao.proposta').show();
                break;
            case 'C':
                $('div.tipo-insercao.proposta').hide();
                $('div.tipo-insercao.contrato').show();
                break;
        }
    });

    $('div#documento').on('change', 'select[name=id_documento_tipo]', function(ev) {
        var id_documento_tipo = $(this).val();
        var obj_modal = $(this).closest('.modal');

        switch (id_documento_tipo) {
            case '1': // Contrato de Cessão de Direitos Econômicos
                obj_modal.find('.tipo-documento.cessao-direitos').slideDown('fast');
                break;
        }
    });

    $('div#documento, div#documento-contrato').on('change', 'select[name=tp_forma_pagamento]', function(e) {
        switch ($(this).val()) {
            case '1':
                $('div.forma-pagamento-2').slideUp(function() {
                    $('div.forma-pagamento-1').slideDown();
                });
                break;
            case '2':
                $('div.forma-pagamento-1').slideUp(function() {
                    $('div.forma-pagamento-2').slideDown();
                });
                break;
        }
    });

    $('div#documento').on('click', 'button.salvar-documento', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento]');
        var produto = form.find('input[name=produto]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/documentos',
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

    $('div#documento').on('change', 'select[name=id_estado_foro]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_foro]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });
});
