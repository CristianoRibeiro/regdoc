$(document).ready(function() {
    $('div#registro-fiduciario-pagamento').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Novo pagamento');
                $(this).find('.modal-footer').find('button.salvar-registro-fiduciario-pagamento').text('Salvar pagamento').show();
                url = URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/novo';

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

    function submitAtualizacaoPagamentoITBI(evt) {

        evt.preventDefault();

        const form = evt.target;
        const idRegistro = form.dataset.idRegistro;
        const data = new FormData(form);

        $.ajax({
            type: `PATCH`,
            url: URL_BASE + `app/produtos/registros/${idRegistro}/pagamentos/${data.get('id_registro_fiduciario_pagamento')}`,
            data: {
                situacao: data.get('situacao')
            },
            beforeSend: () => ajax_beforesend(),
            success: async () => {
                const tabPagamentos = document.querySelector(`#registro-pagamentos`);
                const div = document.querySelector(`#div-registro`);

                const response = await fetch(URL_BASE + `app/produtos/registros/${idRegistro}/pagamentos-tab`);
                const html = await response.text();

                tabPagamentos.innerHTML = html;
                document.querySelectorAll(`.submit-atualizacao-pagamento-itbi`).forEach((form) => form.addEventListener('submit',submitAtualizacaoPagamentoITBI));

                ajax_success();
                $(div).load(location.href);
            },
            error: (ev) => ajax_error(ev)
        });

    }

    document.querySelectorAll(`.submit-atualizacao-pagamento-itbi`).forEach((form) => form.addEventListener('submit',submitAtualizacaoPagamentoITBI));

    $('div#registro-fiduciario-pagamento').on('change', 'input[name=in_isento]', function (ev) {
        var form = $('form[name=form-registro-fiduciario-pagamento]');
        
        if ($(this).is(':checked')) {
            form.find('.arquivos').slideDown();
        } else {
            form.find('.arquivos').slideUp();
        }
    })


    $('div#registro-fiduciario-pagamento').on('click', 'button.salvar-pagamento', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-pagamento]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos',
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

    $('div#registro-fiduciario-pagamento-guia').on('show.bs.modal', function (ev) {
        var tipo = $(ev.relatedTarget).data('tipo');
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_pagamento = $(ev.relatedTarget).data('idregistrofiduciariopagamento');

        $(this).find('.modal-title').text('Enviar guias de ' + tipo);
        $(this).find('.modal-footer').find('button.salvar-pagamento-guia').text('Salvar guia ' + tipo);

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/novo',
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

    $('div#registro-fiduciario-pagamento-guia').on('click', 'button.salvar-guia', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-pagamento-guia]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_registro_fiduciario_pagamento = form.find('input[name=id_registro_fiduciario_pagamento]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento + '/guias',
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

    $('div#registro-fiduciario-pagamento-visualizar-guias').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_pagamento = $(ev.relatedTarget).data('idregistrofiduciariopagamento');
        var tipo = $(ev.relatedTarget).data('tipo');

        $(this).find('.modal-title').text('Visualizar guias de ' + tipo);

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento,
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
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_pagamento = $(ev.relatedTarget).data('idregistrofiduciariopagamento');
        var id_registro_fiduciario_pagamento_guia = $(ev.relatedTarget).data('idregistrofiduciariopagamentoguia');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/' + id_registro_fiduciario_pagamento_guia + '/enviar-comprovante' ,
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
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_registro_fiduciario_pagamento = form.find('input[name=id_registro_fiduciario_pagamento]').val();
        var id_registro_fiduciario_pagamento_guia = form.find('input[name=id_registro_fiduciario_pagamento_guia]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/' + id_registro_fiduciario_pagamento_guia + '/salvar-comprovante' ,
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

    $('div#registro-fiduciario-pagamento-guia-comprovante-validar').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_pagamento = $(ev.relatedTarget).data('idregistrofiduciariopagamento');
        var id_registro_fiduciario_pagamento_guia = $(ev.relatedTarget).data('idregistrofiduciariopagamentoguia');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/' + id_registro_fiduciario_pagamento_guia + '/verificar-comprovante' ,
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

    $('div#registro-fiduciario-pagamento-guia-comprovante-validar').on('click', 'button.salvar-validacao-comprovante', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-pagamento-comprovante-validar]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_registro_fiduciario_pagamento = form.find('input[name=id_registro_fiduciario_pagamento]').val();
        var id_registro_fiduciario_pagamento_guia = form.find('input[name=id_registro_fiduciario_pagamento_guia]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento + '/guias/' + id_registro_fiduciario_pagamento_guia + '/salvar-validacao-comprovante' ,
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

    $('div#registro-pagamentos').on('click', 'button.cancelar-pagamento', function(ev) {
        ev.preventDefault();

        var id_registro_fiduciario = $(this).data('idregistrofiduciario');
        var id_registro_fiduciario_pagamento = $(this).data('idregistrofiduciariopagamento');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja cancelar este pagamento?<br />Esta ação não poderá ser desfeita!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            confirmButtonClass: 'btn btn-success btn-lg ml-3',
            cancelButtonClass: 'btn btn-danger btn-lg ml-3',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/pagamentos/' + id_registro_fiduciario_pagamento,
                    beforeSend: function () {
                        ajax_beforesend();
                    },
                    success: function (retorno) {
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
                            if (retorno.recarrega == 'true') {
                                location.reload();
                            }
                            ajax_success();
                        });
                    },
                    error: function (ev, xhr, settings, error) {
                        ajax_error(ev);
                    }
                });
            }
        })
    });    

});
