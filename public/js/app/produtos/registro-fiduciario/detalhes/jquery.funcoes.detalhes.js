$(document).ready(function() {
    funcoes_menu();

    $('div#registro-fiduciario div.opcoes').on('click', 'a.iniciar-proposta', function (ev) {
        ev.preventDefault();

        var id_registro_fiduciario = $(this).data('idregistro');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar o fluxo de proposta do Registro?<br />Esta ação não poderá ser desfeita!',
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
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-proposta',
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

    $('div#registro-fiduciario div.opcoes').on('click', 'a.iniciar-emissoes', function (ev) {
        ev.preventDefault();

        var id_registro_fiduciario = $(this).data('idregistro');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar as emissões de certificado do Registro?<br />Esta ação não poderá ser desfeita!',
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
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-emissoes',
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


    $('div#registro-fiduciario-transformar-contrato').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/transformar-em-contrato',
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
    $('div#registro-fiduciario-transformar-contrato').on('click', 'button.salvar-contrato', async function (ev) {
        ev.preventDefault();

        var form = $('form[name=form-registro-fiduciario-transformar-contrato]');
        var nameCidadeCartorio = form.find('select[name=id_cidade_cartorio_ri] option:selected').text();
        var cidades = [
            "Guarujá",
            "São Paulo",
            "Lençois",
            "Fortaleza",
            "Aquiraz",
            "Eusebio",
            "Itaitinga",
            "Ipojuca",
            "Caruaru",
            "Nova Prata",
            "Aguas de São Pedro",
            "Botucati",
            "Cabreuva",
            "Cesario Lange",
            "Charqueada",
            "Conchas",
            "Cordeiropolis",
            "Fernandopolis",
            "Guarei",
            "Ibate",
            "Ipeuna",
            "Iracenopolis",
            "Itatinga",
            "Jacarei",
            "Jaguariuna",
            "Limeira",
            "Matão",
            "Maua",
            "Mogi Mirim",
            "Mombuca",
            "Monte Mor",
            "Nova Odessa",
            "Osasco",
            "Ourinhos",
            "Paranapanema",
            "Paulinia",
            "Pereiras",
            "Piracicaba",
            "Porangaba",
            "Porto Ferreira",
            "Rio Claro",
            "Rio das Pedras",
            "Saltinho",
            "Santa Barbara D Oeste",
            "Santa Gertrudes",
            "Santo Andre",
            "São Pedro",
            "Serra Negra",
            "Taboão da Serra",
            "Praia Grande",
            "Rio de Janeiro"
        ];

        if (cidades.includes(nameCidadeCartorio)) {
            
            await swal({
                type: 'warning',
                title: "Aviso!",
                text: "Esta proposta tem prazo de ITBI reduzido. Verificar a guia do ITBI imediatamente",
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                input: 'checkbox',
                inputValue: 0,
                inputPlaceholder: 'Estou ciente, vou verificar',
                inputValidator: (result) => {
                    return !result && 'Você precisa declarar que está ciente!'
                }
            })
        } 

        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        
        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/transformar-em-contrato',
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

    $('div#registro-fiduciario div.opcoes').on('click', 'a.iniciar-documentacao', function (ev) {
        ev.preventDefault();

        var id_registro_fiduciario = $(this).data('idregistro');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar o fluxo de documentação do Registro?<br />Esta ação não poderá ser desfeita!',
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
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-documentacao',
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

    $('div#registro-fiduciario-iniciar-processamento-manual').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-processamento',
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

    })

    $('div#registro-fiduciario-iniciar-processamento-manual').on('click', 'button.salvar-iniciar-processamento-manual', function (ev) {
        ev.preventDefault();

        var form = $('form[name=form-registro-fiduciario-iniciar-processamento-manual]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fundiciario]').val();

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar o fluxo de processamento do Registro manualmente?<br /><br />Este fluxo não possui integração com uma central de registros, e o envio precisará ser feito manualmente para a central.<br /><br />Esta ação não poderá ser desfeita!',
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
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-processamento',
                    data: form.serialize(),
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

    $('div#registro-fiduciario-iniciar-envio-registro').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-envio-registro',
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
    $('div#registro-fiduciario-iniciar-envio-registro').on('click', 'button.salvar-iniciar-envio', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-iniciar-envio-registro]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-envio-registro',
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

    $('div#registro-fiduciario-enviar-registro').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/enviar-registro',
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
    $('div#registro-fiduciario-enviar-registro').on('click', 'button.enviar-registro', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-enviar-registro]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/enviar-registro',
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

    $('div#registro-fiduciario-inserir-resultado').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/inserir-resultado',
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
    $('div#registro-fiduciario-inserir-resultado').on('click', 'button.inserir-resultado', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-inserir-resultado]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/inserir-resultado',
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

    $('div#registro-fiduciario-reenviar-email').on('show.bs.modal', function (e) {
        $.ajax({
            type: "GET",
            url : URL_BASE+'app/produtos/registros/' + $(e.relatedTarget).data('idregistro') + '/reenviar-email',
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

    $('div#registro-fiduciario-reenviar-email').on('click', 'button.reenviar-emails', function (e) {
        e.preventDefault();
        var form = $('form[name=form-registro-fiduciario-reenviar-email]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/produtos/registros/' + id_registro_fiduciario + '/reenviar-email',
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

    $('div#registro-fiduciario-cancelar').on('show.bs.modal', function (ev) {
        var produto = $(ev.relatedTarget).data('produto');
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/' + produto + '/registros/' + id_registro_fiduciario + '/cancelar',
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

    $('div#registro-fiduciario-cancelar').on('click', 'button.cancelar', function (ev) {
        ev.preventDefault();

        var form = $('form[name=form-registro-fiduciario-cancelar]');
        var produto = form.find('input[name=produto]').val();
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja Cancelar Registro?<br />Esta ação não poderá ser desfeita!',
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
            $.ajax({
                type: "DELETE",
                url: URL_BASE + 'app/produtos/' + produto + '/registros/' + id_registro_fiduciario,
                data: form.serialize(),
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
                        window.location = URL_BASE + 'app/produtos/' + produto + '/registros';
                    });
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        })
    });

    $('div#registro-fiduciario-datas').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/datas',
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

    $('div#registro-fiduciario-visualizar-xml').on('show.bs.modal', function (ev) {
        var form = $('form[name=form-registro-fiduciario-iniciar-envio-registro]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-envio-registro/previa',
            context: this,
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend()
            },
            success: function (retorno) {
                $(this).find('.modal-body').html(retorno)
                ajax_success()
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('a.acessar-historico-central-registro').on('click', function(ev) {
        ev.preventDefault();
        $('ul#registro-tab a[href="#registro-arisp"]').tab('show');
        window.location.hash = '#registro-arisp';
    });

    $('div#registro-fiduciario div.opcoes').on('click', 'a.finalizar-registro', function (ev) {
        ev.preventDefault();

        var id_registro_fiduciario = $(this).data('idregistro');

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja Finalizar o Registro?<br />Esta ação não poderá ser desfeita!',
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
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/finalizar-registro',
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
                            location.reload();
                        });
                    },
                    error: function (ev, xhr, settings, error) {
                        ajax_error(ev);
                    }
                });
            }
        })
    });

    $('div#registro-alterar-integracao').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/alterar-integracao',
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

    $('div#registro-alterar-integracao').on('click', 'button.alterar-integracao', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-alterar-integracao]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/alterar-integracao',
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
                ajax_error(ev)
            }
        });
    });

    $('div#registro-fiduciario-iniciar-assinaturas').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-assinaturas',
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

    $('div#registro-fiduciario-iniciar-assinaturas').on('click', 'button.iniciar-assinatura', function (ev) {
        ev.preventDefault();

        var form = $('form[name=form-registro-fiduciario-iniciar-assinaturas]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        swal({
            title: 'Tem certeza?',
            html: 'Tem certeza que deseja iniciar a assinaturas? Um e-mail será enviado aos signatários.',
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
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-assinaturas',
                    data: form.serialize(),
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
                        alerta.then(function() {
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

    $('div#registro-fiduciario-iniciar-assinaturas-partes').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var id_arquivo_grupo_produto = $(ev.relatedTarget).data('idarquivo');
        var registro_token = $(ev.relatedTarget).data('registrotoken');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-assinaturas/' + id_arquivo_grupo_produto + '/configurar-partes',
            data: {
                id_arquivo_grupo_produto: id_arquivo_grupo_produto,
                registro_token: registro_token
            },
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

    $('div#registro-fiduciario-iniciar-assinaturas-partes').on('click', 'button.configurar-partes', function (ev) {
        ev.preventDefault();

        var obj_modal = $(this).closest('.modal');
        var form = $('form[name=form-registro-fiduciario-iniciar-assinaturas-partes]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_arquivo_grupo_produto = form.find('input[name=id_arquivo_grupo_produto]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/iniciar-assinaturas/' + id_arquivo_grupo_produto + '/configurar-partes',
            data: form.serialize(),
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
                alerta.then(function() {
                    if (retorno.status == 'sucesso') {
                        obj_modal.modal('hide');
                    }

                    ajax_success();
                });
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-retrocesso-situacao').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/retroceder-situacao',
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

    $('div#registro-retrocesso-situacao').on('click', 'button.retroceder-situacao', function (ev) {
        ev.preventDefault();

        var obj_modal = $(this).closest('.modal');
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')
        var form = $('form[name=form-registro-retrocesso-situcao]');

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/retroceder-situacao',
            data: form.serialize(),
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
                alerta.then(function() {
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
    });


});
function funcoes_menu() {
    $('ul#registro-tab').on('click', '.nav-link', function(e) {
        window.location.hash = $(this).attr('href');
    });

    hash = $(location).attr('hash');
    if (hash.length>0) {
        $('ul#registro-tab .nav-link.active').removeClass('active').removeClass('show');
        $('ul#registro-tab .nav-link[href="' + hash + '"]').addClass('active').addClass('show');
        $('div#registro-content>.tab-pane.active').removeClass('active').removeClass('show');
        $('div#registro-content>.tab-pane' + hash).addClass('active').addClass('show');
    }
}
