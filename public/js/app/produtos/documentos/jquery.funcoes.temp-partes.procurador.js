$(document).ready(function() {
    $('div#documento-temp-procurador').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Novo procurador');
                $(this).find('.modal-footer').find('button.salvar-procurador').show();
                var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + $(ev.relatedTarget).data('partetoken') + '/procuradores/novo';

                data_args = {
                    'id_documento_parte_tipo': $(ev.relatedTarget).data('idpartetipo')
                }
                break;
            case 'editar':
                $(this).find('.modal-title').text('Editar procurador');
                $(this).find('.modal-footer').find('button.salvar-procurador').show();
                var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + $(ev.relatedTarget).data('partetoken') + '/procuradores/' + $(ev.relatedTarget).data('hash') + '/editar';

                data_args = {}
                break;
            case 'detalhes':
                $(this).find('.modal-title').text('Detalhes do procurador');
                $(this).find('.modal-footer').find('button.salvar-procurador').hide();
                var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + $(ev.relatedTarget).data('partetoken') + '/procuradores/' + $(ev.relatedTarget).data('hash');

                data_args = {}
                break;
        }

        $.ajax({
            type: "GET",
            url: url,
            context: this,
            data: data_args,
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

    $('div#documento-temp-procurador').on('change', 'select[name=id_estado]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#documento-temp-procurador').on('blur', 'input[name=nu_cep]', function(e){
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

    $('div#documento-temp-procurador').on('click', 'button.salvar-procurador', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-temp-procurador]');
        var obj_modal = $(this).closest('.modal');

        var parte_token = form.find('input[name=parte_token]').val();
        var hash = form.find('input[name=hash]').val();

        if (hash) {
            var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + parte_token + '/procuradores/' + hash;
            var verb = 'PUT';
        } else {
            var url = URL_BASE + 'app/produtos/documentos/temp-partes/' + parte_token + '/procuradores';
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
                    if (retorno.recarrega == 'true') {
                        location.reload();
                    }

                    ajax_success();

                    if (retorno.status == 'sucesso') {
                        obj_modal.modal('hide');

                        // Instancia a tabela
                        tabela = $('table#tabela-procuradores');

                        if (tabela.find('tr#linha_'+retorno.hash).length>0) {
                            tabela.find('tr#linha_'+retorno.hash).find('td.no_procurador').html(retorno.procurador.no_procurador);
                            tabela.find('tr#linha_'+retorno.hash).find('td.nu_cpf_cnpj').html(retorno.procurador.nu_cpf_cnpj);
                        } else {
                            // Cria uma nova linha
                            var nova_linha = $('<tr id="linha_' + retorno.hash + '">');

                            // Criar as colunas
                            colunas = '<td class="no_procurador">' + retorno.procurador.no_procurador + '</td>';
                            colunas += '<td class="nu_cpf_cnpj">' + retorno.procurador.nu_cpf_cnpj + '</td>';
                            colunas += '<td>';

                                // Botões
                                colunas += '<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-procurador" data-partetoken="' + parte_token + '" data-hash=' + retorno.hash + ' data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>';
                                colunas += '<a href="javascript:void(0);" class="remover-procurador btn btn-danger btn-sm" data-partetoken="' + parte_token + '" data-hash=' + retorno.hash + '><i class="fas fa-trash"></i></i> Remover</button>';

                            colunas += '</td>';

                            nova_linha.append(colunas);
                            tabela.prepend(nova_linha);
                        }
                    }
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#documento-temp-parte').on('click', 'a.remover-procurador', function (ev) {
        ev.preventDefault();
        var parte_token = $(this).data('partetoken');
        var hash = $(this).data('hash');
        var botao = $(this);

        $.ajax({
            type: "DELETE",
            url: URL_BASE+'app/produtos/documentos/temp-partes/' + parte_token + '/procuradores/' + hash,
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
