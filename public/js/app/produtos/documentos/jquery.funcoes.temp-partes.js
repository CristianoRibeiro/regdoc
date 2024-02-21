$(document).ready(function() {
    $('div#documento-temp-parte').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Nova parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/documentos/temp-partes/novo';

                var data_args = {
                    'documento_token': $(ev.relatedTarget).data('documentotoken'),
                    'id_documento_parte_tipo': $(ev.relatedTarget).data('tipoparte')
                };
                break;
            case 'editar':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Editar parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + $(ev.relatedTarget).data('hash') + '/editar';

                var data_args = {
                    'documento_token': $(ev.relatedTarget).data('documentotoken')
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

    $('div#documento-temp-parte').on('change', 'input[name=tp_pessoa]', function (ev) {
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

    $('div#documento-temp-parte').on('change', 'select[name=id_estado]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#documento-temp-parte').on('blur', 'input[name=nu_cep]', function(e){
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

    $('div#documento-temp-parte').on('click', 'button.salvar-parte', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-temp-parte]');
        var obj_modal = $(this).closest('.modal');

        var documento_token = form.find('input[name=documento_token]').val();
        var hash = form.find('input[name=hash]').val();

        if (hash) {
            var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + hash;
            var verb = 'PUT';
        } else {
            var url = URL_BASE + 'app/produtos/documentos/temp-partes';
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
                        tabela = $('table#tabela-parte-' + retorno.parte.id_documento_parte_tipo);

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
                                colunas += '<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="' + documento_token + '" data-hash=' + retorno.hash + ' data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>';
                                colunas += '<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="' + documento_token + '" data-hash=' + retorno.hash + '><i class="fas fa-trash"></i></i> Remover</button>';

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

    $('div#documento').on('click', 'a.remover-parte', function (ev) {
        ev.preventDefault();
        var documento_token = $(this).data('documentotoken');
        var hash = $(this).data('hash');
        var botao = $(this);

        var data_args = {
            'documento_token' : documento_token
        };

        $.ajax({
            type: "DELETE",
            url: URL_BASE+'app/produtos/documentos/temp-partes/'+hash,
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
});
