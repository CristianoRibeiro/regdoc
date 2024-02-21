$(document).ready(function() {
    $('div#certificados').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Nova emissão de certificado');
                $(this).find('.modal-footer').find('button.salvar-certificado').show();
                
                url = URL_BASE + 'app/certificados-vidaas/novo';

                args = {
                    'campos': $(ev.relatedTarget).data('campos')
                };
                break;
            case 'editar':
                $(this).find('.modal-title').text('Alteração situação do certificado');
                $(this).find('.modal-footer').find('button.salvar-certificado').show();
                
                url = URL_BASE + 'app/certificados-vidaas/' + $(ev.relatedTarget).data('idparteemissao') + '/editar';

                args = {};
                break;
            case 'detalhes':
                $(this).find('.modal-title').text('Detalhes da emissão de certificado');
                $(this).find('.modal-footer').find('button.salvar-certificado').hide();

                url = URL_BASE + 'app/certificados-vidaas/' + $(ev.relatedTarget).data('idparteemissao');

                args = {};
                break;
        }

        $.ajax({
            type: 'GET',
            url: url,
            context: this,
            data: args,
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

    $('div#certificados').on('change', 'select[name=id_parte_emissao_certificado_situacao]', function () {
        var form = $(this).closest('form');

        switch ($(this).val()) {
            case '3':
                form.find('div.agendado').slideDown();
                form.find('div.emissao').slideUp();
                break;

            case '5':
                form.find('div.emissao').slideDown();
                form.find('div.agendado').slideUp();
                break;

            default:
                form.find('div.agendado').slideUp();
                form.find('div.emissao').slideUp();
                break;
        }
    });

    $('div#certificados').on('click', 'button.salvar-certificado', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-certificados]');

        var id_parte_emissao_certificado = form.find('input[name=id_parte_emissao_certificado]').val();

        if (id_parte_emissao_certificado) {
            url = URL_BASE + 'app/certificados-vidaas/'+ id_parte_emissao_certificado,
            verb = 'PUT';
        } else {
            url = URL_BASE + 'app/certificados-vidaas';
            verb = 'POST';
        }

        $.ajax({
            type: verb,
            url: url,
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
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })
    });

    $('div#certificados').on('blur', 'input[name=nu_cep]', function(e){
        var form = $(this).closest('form');

        var cep = $(this).val().replace(/[^\d]+/g,'');

        if (cep!='' && cep.length==8) {
            var cep_url = "https://viacep.com.br/ws/" + cep + "/json/";

            $.ajax({
                url: cep_url,
                type: "GET",
                dataType: "jsonp",
                crossOrigin: true,
                crossDomain: true,
                contentType: "application/json; charset=utf-8",
                beforeSend: function() {
                    ajax_beforesend();
                },
                success: function(response){
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
                error: function(ev, xhr, settings, error) {
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

    $('div#certificados').on('click', 'input[name=in_cnh]', function () {
        var form = $(this).closest('form');

        if ($(this).is(':checked')) {
            form.find('fieldset.endereco').slideUp();
        } else {
            form.find('fieldset.endereco').slideDown();
        }
    });


    $('div#certificados-enviar').on('show.bs.modal', function (ev) {
        var id_parte_emissao_certificado = $(ev.relatedTarget).data('idparteemissao');

        $.ajax({
            type: 'GET',
            url: URL_BASE + 'app/certificados-vidaas/' + id_parte_emissao_certificado + '/enviar-emissao',
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

    $('div#certificados-enviar').on('click', 'button.enviar-certificado', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-certificados-enviar]');
        var id_parte_emissao_certificado = form.find('input[name=id_parte_emissao_certificado]').val();
        $.ajax({
            type: 'POST',
            url: URL_BASE + 'app/certificados-vidaas/' + id_parte_emissao_certificado + '/enviar-emissao',
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
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })
    });

    $('div#certificados-enviar-emitir').on('show.bs.modal', function (ev) {
        var id_parte_emissao_certificado = $(ev.relatedTarget).data('idparteemissao');

        $.ajax({
            type: 'GET',
            url: URL_BASE + 'app/certificados-vidaas/' + id_parte_emissao_certificado + '/enviar-emissao-emitir',
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

    $('div#certificados-enviar-emitir').on('click', 'button.enviar-certificado', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-certificados-enviar]');
        var id_parte_emissao_certificado = form.find('input[name=id_parte_emissao_certificado]').val();
        $.ajax({
            type: 'POST',
            url: URL_BASE + 'app/certificados-vidaas/' + id_parte_emissao_certificado + '/enviar-emissao-emitir',
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
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })
    });

    $('div#certificados-alterar-ticket').on('show.bs.modal', function (ev) {
        $.ajax({
            type: 'GET',
            url: URL_BASE + 'app/certificados-vidaas/' + $(ev.relatedTarget).data('idparteemissao') + '/alterar-ticket',
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

    $('div#certificados-alterar-ticket').on('click', 'button.alterar-ticket', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-certificados-alterar-ticket]');

        var id_parte_emissao_certificado = form.find('input[name=id_parte_emissao_certificado]').val();

        $.ajax({
            type: 'POST',
            url: URL_BASE + 'app/certificados-vidaas/'+ id_parte_emissao_certificado+'/alterar-ticket',
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
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })
    });

    $('div#certificados-cancelar').on('show.bs.modal', function (ev) {
        $.ajax({
            type: 'GET',
            url: URL_BASE + 'app/certificados-vidaas/' + $(ev.relatedTarget).data('idparteemissao') + '/cancelar-emissao',
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

    $('div#certificados-cancelar').on('click', 'button.cancelar-certificado', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-certificados-cancelar]');

        var id_parte_emissao_certificado = form.find('input[name=id_parte_emissao_certificado]').val();

        $.ajax({
            type: 'POST',
            url: URL_BASE + 'app/certificados-vidaas/'+ id_parte_emissao_certificado+'/cancelar-emissao',
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
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        })
    });

    $('a.atualizar-ticket').on('click', function (ev) {
        ev.preventDefault();

        $.ajax({
            type: 'GET',
            url: URL_BASE + 'app/certificados-vidaas/' + $(this).data('idparteemissao') + '/atualizar-ticket',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
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
                })
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });
});
