$(document).ready(function () {
    $('div#registro-fiduciario-parte, div#registro-fiduciario-completar-parte').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');
        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Nova parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/registros/partes/novo';
                break;
            case 'editar':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Editar a parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/registros/' + $(ev.relatedTarget).data('idregistro') + '/partes/' + $(ev.relatedTarget).data('idparte') + '/completar';
                break;
            case 'detalhes':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Detalhes da parte');
                $(this).find('.modal-footer').find('button.salvar-parte').hide();
                var url = URL_BASE + 'app/produtos/registros/' + $(ev.relatedTarget).data('idregistro') + '/partes/' + $(ev.relatedTarget).data('idparte') + '';
                break;
            case 'completar':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Completar a parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/registros/' + $(ev.relatedTarget).data('idregistro') + '/partes/' + $(ev.relatedTarget).data('idparte') + '/completar';
                break;
        }
        $.ajax({
            type: "GET", url: url, context: this, beforeSend: function () {
                ajax_beforesend();
            }, success: function (retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();
            }, error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-parte').on('click', 'button.salvar-parte', function (ev) {
        ev.preventDefault()
        var form = $('form[name=form-registro-fiduciario-parte]');

        id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        id_registro_fiduciario_parte = form.find('input[name=id_registro_fiduciario_parte]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/partes/' + id_registro_fiduciario_parte,
            context: this,
            data: form.serialize(),
            beforeSend: function () {
                ajax_beforesend();
            },
            success: function (retorno) {
                switch (retorno.status) {
                    case 'erro':
                        alerta = swal("Erro!", retorno.message, "error");
                        break;
                    case 'sucesso':
                        alerta = swal("Sucesso!", retorno.message, "success");
                        break;
                    case 'alerta':
                        alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                alerta.then(function () {
                    if (retorno.recarrega == 'true') {
                        location.reload();
                    }
                    ajax_success();
                })
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-completar-parte').on('click', 'button.completar-parte', function (ev) {
        ev.preventDefault()
        var form = $('form[name=form-registro-fiduciario-completar-parte]');

        id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        id_registro_fiduciario_parte = form.find('input[name=id_registro_fiduciario_parte]').val();

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/partes/' + id_registro_fiduciario_parte + '/salvar-completar',
            context: this,
            data: form.serialize(),
            beforeSend: function () {
                ajax_beforesend();
            },
            success: function (retorno) {
                switch (retorno.status) {
                    case 'erro':
                        alerta = swal("Erro!", retorno.message, "error");
                        break;
                    case 'sucesso':
                        alerta = swal("Sucesso!", retorno.message, "success");
                        break;
                    case 'alerta':
                        alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                alerta.then(function () {
                    if (retorno.recarrega == 'true') {
                        location.reload();
                    }
                    ajax_success();
                })
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-parte, div#registro-fiduciario-completar-parte').on('change', 'select[name=id_estado]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario-parte').on('click', 'input[name=in_emitir_certificado]', function (ev) {
        var form = $(this).closest('form');
        var in_emitir_certificado = $(this).is(':checked');

        if (in_emitir_certificado) {
            form.find('div.in_cnh').slideDown();
            if (!form.find('input[name=in_cnh]').is(':checked')) {
                form.find('div.endereco').slideDown();
            }
        } else {
            form.find('div.in_cnh').slideUp();
        }
    });

    $('div#registro-fiduciario-parte').on('click', 'input[name=in_cnh]', function (ev) {
        var form = $(this).closest('form');
        var in_cnh = $(this).is(':checked');

        if (in_cnh) {
            form.find('div.endereco').slideUp();
        } else {
            form.find('div.endereco').slideDown();
        }
    });

    $('div#registro-fiduciario-parte, div#registro-fiduciario-completar-parte').on('blur', 'input[name=nu_cep]', function (e) {
        var form = $(this).closest('form');

        var cep = $(this).val().replace(/[^\d]+/g, '');

        if (cep != '' && cep.length == 8) {
            var cep_url = "https://viacep.com.br/ws/" + cep + "/json/";

            $.ajax({
                url: cep_url,
                type: "GET",
                dataType: "jsonp",
                crossOrigin: true,
                crossDomain: true,
                contentType: "application/json; charset=utf-8",
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (response) {
                    if (!response.erro) {
                        id_estado = form.find('select[name=id_estado]').find('option[data-uf="' + response.uf + '"]').val();
                        form.find('select[name=id_estado]').val(id_estado);

                        if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                            form.find('select[name=id_estado]').addClass('readonly');
                        } else {
                            form.find('select[name=id_estado]').attr('readonly', true);
                        }
                        carregar_cidades(form.find('select[name=id_cidade]'), 0, 0, response.uf, response.localidade, true);

                        form.find('input[name=no_endereco]').val(response.logradouro);
                        form.find('input[name=no_bairro]').val(response.bairro);
                    } else {
                        form.find('select[name=id_estado]').val('0').trigger('change');
                        if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                            form.find('select[name=id_estado]').removeClass('readonly');
                            form.find('select[name=id_estado]').closest('.bootstrap-select').removeClass('readonly');
                        } else {
                            form.find('select[name=id_estado]').attr('readonly', true);
                        }

                        form.find('input[name=no_endereco]').val('');
                        form.find('input[name=no_bairro]').val('');
                    }

                    if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                        form.find('select[name=id_estado]').selectpicker('refresh');
                    }

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_estado]').val('0').trigger('change');
            if (form.find('select[name=id_estado]').hasClass('selectpicker')) {
                form.find('select[name=id_estado]').removeClass('readonly');
                form.find('select[name=id_estado]').closest('.bootstrap-select').removeClass('readonly');
                form.find('select[name=id_estado]').selectpicker('refresh');
            } else {
                form.find('select[name=id_estado]').attr('readonly', false);
            }

            form.find('input[name=no_endereco]').val('');
            form.find('input[name=nu_numero]').val('');
            form.find('input[name=no_bairro]').val('');
        }
    });
    
    $("table.table").on('click', 'button.desvincular_parte', function (e) {

        e.preventDefault();

        var id_parte = $(this).data('idparte');
        var id_registro = $(this).data('idregistro');


        swal({
            title: 'Desvincular parte',
            text: 'Você tem certeza que deseja desvincular esta parte deste protocolo?',
            type: 'question',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            showLoaderOnConfirm: true,

        }).then(function (retorno) {
            if (retorno.value == true) {

                $.ajax({
                    type: "GET",
                    url: URL_BASE + 'app/produtos/registros/partes/desvincular/' + id_parte,
                    context: this,
                    beforeSend: function () {
                        ajax_beforesend();
                    },
                    success: function (retorno) {
                        switch (retorno.status) {
                            case 'erro':
                                var alerta = swal("Erro!", retorno.message, "error");
                                break;
                            case 'success':
                                var alerta = swal("Sucesso!", retorno.message, "success");
                                location.reload();
                            break;
                            case 'alerta':
                                var alerta = swal("Ops!", retorno.message, "warning");
                            break;
                            default:
                                var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning" + retorno.message);
                            break;
                        }

                        alerta.then(function () {
                            if (retorno.recarrega == 'true') {
                                location.reload();
                            }
                        });
                        ajax_success();
                    },

                    error: function (ev, xhr, settings, error) {
                        ajax_error(ev);
                    }
                });
            }
        });
    });

    $('div#registro-fiduciario-parte').on('click', 'button.salvar-telefone-adicional', function (ev) {
        var num_tel = $('#nu_telefone_contato_adicional').val();
        var registro = $('#registro').val();
        var idparte = $('#idparte').val();

        swal({
            title: 'Tem certeza que deseja salvar',
            text: 'Você tem certeza que deseja salvar o telefone adicional',
            type: 'question',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            showLoaderOnConfirm: true,

        }).then(function (retorno) {
            if (retorno.value == true) {

                $.ajax({
                    type: "GET",
                    url: URL_BASE + 'app/produtos/registros/' + registro + '/partes/'+ idparte + '/' + num_tel,
                    context: this,
                    beforeSend: function () {
                        ajax_beforesend();
                    },
                    success: function (retorno) {
                        switch (retorno.status) {
                            case 'erro':
                                var alerta = swal("Erro!", retorno.message, "error");
                                break;

                            case 'success':
                                var alerta = swal("Sucesso!", retorno.message, "success");
                                location.reload();
                                break;

                            case 'alerta':
                                var alerta = swal("Ops!", retorno.message, "warning");
                                break;

                            default:
                                var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning" + retorno.message);
                                break;
                        }

                        alerta.then(function () {
                            if (retorno.recarrega == 'true') {
                                location.reload();
                            }
                        });
                        ajax_success();
                    },

                    error: function (ev, xhr, settings, error) {
                        ajax_error(ev);
                    }
                });
            }
        });
    });

    $("div#registro-fiduciario-adicionar-parte").on('show.bs.modal', function (ev) {

        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var id_registro_tipo_parte_tipo_pessoa = $(ev.relatedTarget).data('idtipopartepessoa');
        var id_tipo_parte_registro_fiduciario = $(ev.relatedTarget).data('idtipoparteregistrofiduciario');


        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/id-tipo-pessoa/' + id_registro_tipo_parte_tipo_pessoa + '/id-tipo-parte-registro/' + id_tipo_parte_registro_fiduciario,
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

    $('div#registro-fiduciario-adicionar-parte').on('click', 'button.salvar-parte', function (ev) {

        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-adicionar-parte]');

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/salvar-parte',
            context: this,
            data: form.serialize(),
            beforeSend: function () {
                ajax_beforesend();
            },
            success: function (retorno) {
                switch (retorno.status) {
                    case 'erro':
                        alerta = swal("Erro!", retorno.message, "error");
                        break;
                    case 'success':
                        alerta = swal("Sucesso!", retorno.message, "success");
                        break;
                    case 'alerta':
                        alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                ajax_success();
                location.reload();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });

    });

    $('div#registro-fiduciario-adicionar-parte').on('change', 'select[name=parte_cadastrada]', function(ev) {

        ev.preventDefault();
        var form = $(this).closest('form');

        if ($(this).val()) {
            parte_cadastrada = JSON.parse($(this).val());

            form.find('input[name=no_parte]').val(parte_cadastrada.no_parte);
            form.find('input[name=nu_cpf]').val(parte_cadastrada.nu_cpf_cnpj);
            form.find('input[name=nu_telefone_contato]').val(parte_cadastrada.nu_telefone_contato);
            form.find('input[name=no_email_contato]').val(parte_cadastrada.no_email_contato);
            if (parte_cadastrada.uuid_procuracao) {
                form.find('select[name=uuid_procuracao]').val(parte_cadastrada.uuid_procuracao);
            }
            if (parte_cadastrada.in_emitir_certificado) {
                if (parte_cadastrada.in_emitir_certificado=='S') {
                    form.find('input[name=in_emitir_certificado]').prop('checked', true);
                    carrega_emitir_certificado(form, true);
                } else {
                    form.find('input[name=in_emitir_certificado]').prop('checked', false);
                    carrega_emitir_certificado(form, false);
                }
            }
        }
    });

    $('div#registro-fiduciario-adicionar-parte').on('change', 'select[name=id_estado]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario-adicionar-parte').on('click', 'input[name=in_emitir_certificado]', function (ev) {
        var form = $(this).closest('form');
        var in_emitir_certificado = $(this).is(':checked');

        carrega_emitir_certificado(form, in_emitir_certificado);
    });

    $('div#registro-fiduciario-adicionar-parte').on('click', 'input[name=in_cnh]', function (ev) {
        var form = $(this).closest('form');
        var in_cnh = $(this).is(':checked');

        if (in_cnh) {
            form.find('div.endereco').slideUp();
        } else {
            form.find('div.endereco').slideDown();
        }
    });

});

function carrega_emitir_certificado(form, in_emitir_certificado) {
    if (in_emitir_certificado) {
        form.find('div.in_cnh').slideDown();
        if (!form.find('input[name=in_cnh]').is(':checked')) {
            form.find('div.endereco').slideDown();
        }
    } else {
        form.find('div.in_cnh').slideUp();
        form.find('div.endereco').slideUp();
    }
}
