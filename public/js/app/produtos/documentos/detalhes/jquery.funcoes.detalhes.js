$(document).ready(function() {
    funcoes_menu();

    $('div#documento div.opcoes').on('click', 'a.iniciar-proposta', function (ev) {
        ev.preventDefault();

        var uuid_documento = $(this).data('uuiddocumento');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar o fluxo de proposta do Documento?<br />Esta ação não poderá ser desfeita!',
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
                    type: "POST",
                    url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/iniciar-proposta',
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

    $('div#documento div.opcoes').on('click', 'a.gerar-documentos', function (ev) {
        ev.preventDefault();

        var uuid_documento = $(this).data('uuiddocumento');

        swal({
            title: 'Deseja continuar?',
            html: 'Após a geração, os documentos estarão disponíveis na aba "Arquivos".',
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
                    type: "POST",
                    url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/gerar-documentos',
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

    $('div#documento-transformar-contrato').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/transformar-em-contrato',
            context: this,
            beforeSend: function() {
                ajax_beforesend()
            },
            success: function (retorno) {
                $(this).find('.modal-body form').html(retorno)
                ajax_success()
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });
    $('div#documento-transformar-contrato').on('change', 'select[name=tp_forma_pagamento]', function(e) {
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
    $('div#documento-transformar-contrato').on('click', 'button.salvar-contrato', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-transformar-contrato]');
        var uuid_documento = form.find('input[name=uuid_documento]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/transformar-em-contrato',
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
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });
    $('div#documento-transformar-contrato, div#documento-contrato').on('change', 'select[name=id_estado_foro]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_foro]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#documento div.opcoes').on('click', 'a.iniciar-assinatura', function (ev) {
        ev.preventDefault();

        var uuid_documento = $(this).data('uuiddocumento');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar o fluxo de assinaturas do Documento?<br />Esta ação não poderá ser desfeita!',
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
                    type: "POST",
                    url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/iniciar-assinatura',
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

    $('div#documento-reenviar-email').on('show.bs.modal', function (e) {
        $.ajax({
            type: "GET",
            url : URL_BASE+'app/produtos/documentos/' + $(e.relatedTarget).data('uuiddocumento') + '/reenviar-email',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error){
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#documento-reenviar-email').on('click', 'button.reenviar-emails', function (e) {
        e.preventDefault();
        var form = $('form[name=form-documento-reenviar-email]');
        var uuid_documento = form.find('input[name=uuid_documento]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/produtos/documentos/' + uuid_documento + '/reenviar-email',
            data: data,
            contentType: false,
            processData: false,
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
                    case 'erro':
                        var alerta = swal("Ops!", retorno.message, 'error');
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
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#documento div.opcoes').on('click', 'a.cancelar-documento', function (ev) {
        ev.preventDefault();

        var uuid_documento = $(this).data('uuiddocumento');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja cancelar o Documento?',
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
                    url: URL_BASE + 'app/produtos/documentos/' + uuid_documento,
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
                            window.location = URL_BASE + 'app/produtos/documentos';
                        });
                    },
                    error: function (ev, xhr, settings, error) {
                        ajax_error(ev);
                    }
                });
            }
        })
    });

    $('div#documento-datas').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/datas',
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

    $('div#documento div.opcoes').on('click', 'a.regerar-documento', function (ev) {
        ev.preventDefault();

        var uuid_documento = $(this).data('uuiddocumento');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja regerar o Documento?<br />Esta ação não poderá ser desfeita!',
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
                    type: "POST",
                    url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/regerar-documentos',
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

    $('div#documento-contrato').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/contrato/editar',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error){
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#documento-contrato').on('click', 'button.atualizar-contrato', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-contrato]');
        var uuid_documento = form.find('input[name=uuid_documento]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/contrato',
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
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#documento-contrato').on('change', 'select[name=tp_forma_pagamento]', function(e) {
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

});
function funcoes_menu() {
    $('ul#documento-tab').on('click', '.nav-link', function(e) {
        window.location.hash = $(this).attr('href');
    });

    hash = $(location).attr('hash');
    if (hash.length>0) {
        $('ul#documento-tab .nav-link.active').removeClass('active').removeClass('show');
        $('ul#documento-tab .nav-link[href="' + hash + '"]').addClass('active').addClass('show');
        $('div#documento-content>.tab-pane.active').removeClass('active').removeClass('show');
        $('div#documento-content>.tab-pane' + hash).addClass('active').addClass('show');
    }
}
