$(document).ready(function() {
    $('div#registro-fiduciario-temp-parte').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');
        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Nova parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/registros/temp-partes/novo';

                var data_args = {
                    'registro_token': $(ev.relatedTarget).data('registrotoken'),
                    'id_tipo_parte_registro_fiduciario': $(ev.relatedTarget).data('tipoparte'),
                    'id_registro_tipo_parte_tipo_pessoa': $(ev.relatedTarget).data('idregistrotipopartetipopessoa')
                };
                break;
            case 'editar':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Editar parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/registros/temp-partes/' + $(ev.relatedTarget).data('hash') + '/editar';

                var data_args = {
                    'registro_token': $(ev.relatedTarget).data('registrotoken')
                };
                break;
        }

        if (url) {
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
        }
    });

    $('div#registro-fiduciario-temp-parte').on('click', 'button.salvar-parte', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-temp-parte]');
        var obj_modal = $(this).closest('.modal');

        var registro_token = form.find('input[name=registro_token]').val();
        var hash = form.find('input[name=hash]').val();
        
        if (hash) {
            var url = URL_BASE + 'app/produtos/registros/temp-partes/' + hash;
            var verb = 'PUT';
        } else {
            var url = URL_BASE + 'app/produtos/registros/temp-partes';
            var verb = 'POST';
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
                    ajax_success();

                    if (retorno.status == 'sucesso') {
                        obj_modal.modal('hide');

                        // Instancia a tabela
                        tabela = $('table#tabela-parte-' + retorno.parte.id_tipo_parte_registro_fiduciario);

                        if (tabela.find('tr#linha_'+retorno.hash).length>0) {
                            tabela.find('tr#linha_'+retorno.hash).find('td.no_parte').html(retorno.parte.no_parte);
                            tabela.find('tr#linha_'+retorno.hash).find('td.nu_cpf_cnpj').html(retorno.parte.nu_cpf_cnpj);
                        } else {
                            // Cria uma nova linha
                            var nova_linha = $('<tr id="linha_' + retorno.hash + '">');

                            // Criar as colunas
                            colunas = '<td class="no_parte">' + retorno.parte.no_parte + '</td>';
                            colunas += '<td class="nu_cpf_cnpj">' + retorno.parte.nu_cpf_cnpj + '</td>';
                            colunas += '<td>';

                                // Botões
                                colunas += '<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-temp-parte" data-registrotoken="' + registro_token + '" data-hash=' + retorno.hash + ' data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>';
                                colunas += '<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-registrotoken="' + registro_token + '" data-hash=' + retorno.hash + '><i class="fas fa-trash"></i></i> Remover</button>';
                                // Campo para validação

                            colunas += '</td>';

                            nova_linha.append(colunas);
                            tabela.find('tbody').prepend(nova_linha);
                        }
                    }
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $(this, 'div#registro-fiduciario-temp-parte', 'div#registro-fiduciario-adicionar-parte').on('change', 'input[name=tp_pessoa]', function (ev) {
        var form = $(this).closest('form');
        var tp_pessoa = $(this).val();

        form.find('div.tipo-parte:visible').slideUp(function(e) {
            switch (tp_pessoa) {
                case 'F':
                    form.find('div.tipo-parte.pessoa-fisica div.collapse').collapse('show');
                    form.find('div.tipo-parte.pessoa-fisica:hidden').slideDown();
                    break;
                case 'J':
                    form.find('div.tipo-parte.pessoa-juridica div.collapse').collapse('show');
                    form.find('div.tipo-parte.pessoa-juridica:hidden').slideDown();
                    break;
            }
            $(this).find('div.collapse').collapse('hide');
        });
    });

    $('div#registro-fiduciario , div#registro-fiduciario-transformar-contrato').on('click', 'a.remover-parte', function (ev) {
        ev.preventDefault();
        var registro_token = $(this).data('registrotoken');
        var hash = $(this).data('hash');
        var botao = $(this);

        var data_args = {
            'registro_token' : registro_token
        };

        $.ajax({
            type: "DELETE",
            url: URL_BASE+'app/produtos/registros/temp-partes/'+hash,
            data: data_args,
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

                    if (retorno.status == 'sucesso') {
                        botao.closest("tr").remove();
                    }
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-temp-parte').on('change', 'select[name=no_estado_civil]', function (ev) {
        var form = $(this).closest('form');
        var no_estado_civil = $(this).val();

        form.find('div.estadocivil.conjuge').find('label.data-casamento').html('Data do casamento');

        switch (no_estado_civil) {
            case 'Casado':
            case 'Separado':
            case 'Separado judicialmente':
                form.find('div.estadocivil.regime-bens').slideDown();
                break;
            case 'União estável':
                form.find('div.estadocivil.regime-bens').slideDown();
                form.find('div.estadocivil.conjuge').find('label.data-casamento').html('Data da união');
                break;
            default:
                form.find('div.estadocivil.regime-bens').slideUp();
                break;
        }

        form.find('select[name=no_regime_bens]').val('').trigger('change');
    });

    $('div#registro-fiduciario-temp-parte').on('change', 'select[name=no_regime_bens]', function (ev) {
        var form = $(this).closest('form');
        var no_regime_bens = $(this).val();

        switch (no_regime_bens) {
            case 'Comunhão parcial de bens':
            case 'Comunhão universal de bens':
            case 'Participação final nos aquestos':
                form.find('div.estadocivil.conjuge').slideDown();
                break;
            default:
                form.find('div.estadocivil.conjuge').slideUp();
                break;
        }
    });

    $('div#registro-fiduciario-temp-parte').on('change', 'input[name=nu_cpf]', function (ev) {
        var form = $(this).closest('form');
        var nu_cpf = $(this).val();
        var registro_token = form.find('input[name=registro_token]').val();

        data_args = {
            'registro_token': registro_token,
            'nu_cpf': nu_cpf
        }

        $.ajax({
            type: "GET",
            url: URL_BASE+'app/produtos/registros/temp-partes/buscar_conjuge',
            data: data_args,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                if(retorno.conjuge) {
                    form.find('select[name=no_estado_civil]').val(retorno.conjuge.no_estado_civil).attr('readonly', true).trigger('change');
                    form.find('select[name=no_regime_bens]').val(retorno.conjuge.no_regime_bens).attr('readonly', true).trigger('change');
                    form.find('select[name=in_conjuge_ausente]').val(retorno.conjuge.in_conjuge_ausente).attr('readonly', true);

                    form.find('input[name=cpf_conjuge]').val(retorno.conjuge.nu_cpf_cnpj).attr('readonly', true);
                    form.find('input[name=dt_casamento]').val(retorno.conjuge.dt_casamento).attr('readonly', true);
                } else {
                    form.find('select[name=no_estado_civil]').val('Solteiro').attr('readonly', false).trigger('change');
                    form.find('select[name=no_regime_bens]').attr('readonly', false);
                    form.find('select[name=in_conjuge_ausente]').attr('readonly', false);

                    form.find('input[name=cpf_conjuge]').val('').attr('readonly', false);
                    form.find('input[name=dt_casamento]').val('').attr('readonly', false);
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-temp-parte').on('change', 'select[name=parte_cadastrada]', function(ev) {
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

    $('div#registro-fiduciario-temp-parte').on('change', 'select[name=id_estado]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario-temp-parte').on('click', 'input[name=in_emitir_certificado]', function (ev) {
        var form = $(this).closest('form');
        var in_emitir_certificado = $(this).is(':checked');

        carrega_emitir_certificado(form, in_emitir_certificado);
    });

    $('div#registro-fiduciario-temp-parte').on('click', 'input[name=in_cnh]', function (ev) {
        var form = $(this).closest('form');
        var in_cnh = $(this).is(':checked');

        if (in_cnh) {
            form.find('div.endereco').slideUp();
        } else {
            form.find('div.endereco').slideDown();
        }
    });

    $('div#registro-fiduciario-temp-parte').on('blur', 'input[name=nu_cep]', function(e) {
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
                success: function(response) {
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
