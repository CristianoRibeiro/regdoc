$(document).ready(function () {
    $('div#registro-fiduciario').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title'));
                $(this).find('.modal-footer').find('button.salvar-registro-fiduciario').show();
                url = URL_BASE + 'app/produtos/' + $(ev.relatedTarget).data('produto') + '/registros/novo';

                var data_args = {};
                break;
            // case 'editar':
            //     $(this).find('.modal-title').text('Editar registro fiduciário');
            //     $(this).find('.modal-footer').find('button.salvar-registro-fiduciario').show();
            //     url = URL_BASE + 'app/produtos/registros/editar';
            //
            //     var data_args = {
            //         'id_registro_fiduciario': $(ev.relatedTarget).data('idregistrofiduciario')
            //     };
            //     break;
            // case 'detalhes':
            //     $(this).find('.modal-title').text('Detalhes do registro fiduciário');
            //     $(this).find('.modal-footer').find('button.salvar-registro-fiduciario').hide();
            //     url = URL_BASE + 'app/produtos/registros/detalhes';
            //
            //     var data_args = {
            //         'id_registro_fiduciario': $(ev.relatedTarget).data('idregistrofiduciario')
            //     };
            //     break;
        }

        $.ajax({
            type: "GET",
            url: url,
            data: data_args,
            context: this,
            beforeSend: function () {
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

    $('div#registro-fiduciario').on('click', 'a.tipo-insercao-proposta', function (e) {
        $('div.tipos-insercao').hide();
        $('div.tipos-insercao-opcoes').show();
        $('div#accordion-registro').show();
        $('div#registro-fiduciario').find('button.salvar-registro-fiduciario').attr('disabled', false);

        $('label[for=tipo_insercao_proposta]').trigger('click');
    });
    $('div#registro-fiduciario').on('click', 'a.tipo-insercao-contrato', function (e) {
        $('div.tipos-insercao').hide();
        $('div.tipos-insercao-opcoes').show();
        $('div#accordion-registro').show();
        $('div#registro-fiduciario').find('button.salvar-registro-fiduciario').attr('disabled', false);

        $('label[for=tipo_insercao_contrato]').trigger('click');
    });
    $('div#registro-fiduciario').on('change', 'input[name=tipo_insercao]', function (e) {
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


    $('div#registro-fiduciario').on('change', 'select[name=id_registro_fiduciario_tipo]', function (ev) {
        var id_registro_fiduciario_tipo = $(this).val();
        var form = $(this).closest('form');
        var registro_token = form.find('input[name=registro_token]').val();
        var tipo_insercao = form.find('input[name=tipo_insercao]:checked').val();

        if (id_registro_fiduciario_tipo > 0) {
            form.find('.card.credor:hidden').slideDown();
            form.find('.card.partes:hidden').slideDown();
            form.find('.card.cartorio:hidden').slideDown();
            form.find('.card.custodiante:visible').slideUp();
            form.find('.tipo-arquivos.correspondente:visible').slideUp();
            form.find('.tipo-arquivos.cessao:visible').slideUp();

            switch (id_registro_fiduciario_tipo) {
                case '7': // Baixa de TQ
                    form.find('.card.custodiante').slideDown();
                    break;
                case '10': // Registro de garantias de correspondente
                    form.find('.card.cartorio').slideUp();
                    form.find('.tipo-arquivos.correspondente').slideDown();
                    break;
                case '5': // Registro de garantias com cessão fiduciária
                    form.find('.tipo-arquivos.cessao').slideDown();
                    break;
                case '11': // Escritura Publica
                    form.find('.card.credor').slideUp();
                    break;
                case '13': // Registro de IQ Interno
                    form.find('.card.cartorio').slideUp();
                    break;
            }

            data_args = {
                id_registro_fiduciario_tipo: id_registro_fiduciario_tipo,
                tipo_insercao: tipo_insercao
            }
            $.ajax({
                type: "GET",
                url: URL_BASE + 'app/produtos/registros/tipos/tipos-partes',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (retorno) {
                    if (retorno.tipos_partes) {
                        var partes_container = $('div#registro-fiduciario div.partes div.partes-container');

                        html = '';

                        $.each(retorno.tipos_partes, function (index, tipo_parte) {
                            // Instancia a tabela
                            html += '<div class="tipo-registro mb-3">';
                            html += '<fieldset>';
                            html += '<legend>';
                            html += tipo_parte.no_registro_tipo_parte_tipo_pessoa
                            if (tipo_parte.in_obrigatorio == 'S') {
                                html += ' (Obrigatório)'
                            }
                            html += '</legend>';
                            if (tipo_parte.in_construtora == 'S') {
                                html += '<select name="id_construtora_' + tipo_parte.id_tipo_parte_registro_fiduciario + '" class="form-control selectpicker" title="Selecione">';
                                if (retorno.construtoras) {
                                    $.each(retorno.construtoras, function (index, construtora) {
                                        html += '<option value="' + construtora.id_construtora + '">' + construtora.no_construtora + '</option>';
                                    });
                                }
                                html += '</select>';
                            } else {
                                html += '<table id="tabela-parte-' + tipo_parte.id_tipo_parte_registro_fiduciario + '" class="table table-striped table-bordered mb-0 h-middle">';
                                html += '<thead>';
                                html += '<tr>';
                                html += '<th width="40%">' + tipo_parte.colunas.nome + '</th>';
                                html += '<th width="30%">' + tipo_parte.colunas.cpf_cnpj + '</th>';
                                html += ' <th width="30%">';
                                html += '<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#registro-fiduciario-temp-parte" data-registrotoken="' + registro_token + '" data-title="Novo ' + tipo_parte.no_registro_tipo_parte_tipo_pessoa.toLowerCase() + '" data-tipoparte="' + tipo_parte.id_tipo_parte_registro_fiduciario + '"  data-idregistrotipopartetipopessoa="' + tipo_parte.id_registro_tipo_parte_tipo_pessoa + '" data-operacao="novo">';
                                html += '<i class="fas fa-plus-circle"></i> Novo';
                                html += '</button>';
                                html += '</th>';
                                html += '</tr>';
                                html += '</thead>';
                                html += '<tbody>';
                                html += '</tbody>';
                                html += '</table>';
                            }
                            html += '</fieldset>';
                            html += '</div>';
                        });
                        partes_container.html(html);
                    }

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('div.credor').slideUp();
            form.find('div.partes').slideUp();
        }
    });
    $('div#registro-fiduciario').on('change', 'select[name=id_estado_cartorio_ri]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_cartorio_ri]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_cidade_cartorio_ri]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        var id_cidade = $(this).val();
        if (id_cidade > 0) {
            var data_args = {
                'id_tipo_pessoa': [2],
                'id_tipo_serventia': [1, 10],
                'id_cidade': id_cidade
            };
            $.ajax({
                type: "POST",
                url: URL_BASE + 'app/pessoa/lista',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (pessoas) {
                    if (pessoas.length > 0) {
                        HTML = '';
                        $.each(pessoas, function (key, pessoa) {
                            HTML += '<option value="' + pessoa.id_pessoa + '">' + pessoa.no_pessoa + '</option>';
                        });
                        form.find('select[name=id_pessoa_cartorio_ri]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_pessoa_cartorio_ri]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_pessoa_cartorio_ri]').selectpicker('refresh');

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_pessoa_cartorio_ri]').html('').prop('disabled', true);
            form.find('select[name=id_pessoa_cartorio_ri]').selectpicker('refresh');
        }
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_estado_cartorio_rtd]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_cartorio_rtd]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_cidade_cartorio_rtd]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        var id_cidade = $(this).val();
        if (id_cidade > 0) {
            var data_args = {
                'id_tipo_pessoa': [2],
                'id_tipo_serventia': [2, 3, 10],
                'id_cidade': id_cidade
            };
            $.ajax({
                type: "POST",
                url: URL_BASE + 'app/pessoa/lista',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (pessoas) {
                    if (pessoas.length > 0) {
                        HTML = '';
                        $.each(pessoas, function (key, pessoa) {
                            HTML += '<option value="' + pessoa.id_pessoa + '">' + pessoa.no_pessoa + '</option>';
                        });
                        form.find('select[name=id_pessoa_cartorio_rtd]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_pessoa_cartorio_rtd]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_pessoa_cartorio_rtd]').selectpicker('refresh');

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_pessoa_cartorio_rtd]').html('').prop('disabled', true);
            form.find('select[name=id_pessoa_cartorio_rtd]').selectpicker('refresh');
        }
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_estado_credor]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_credor]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_cidade_credor]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        if ($(this).val() > 0) {
            var data_args = {
                'id_cidade': $(this).val()
            };
            $.ajax({
                type: "GET",
                url: URL_BASE + 'app/produtos/registros/credores',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (credores) {
                    if (credores) {
                        HTML = '';
                        $.each(credores, function (key, credor) {
                            if (credor.agencia) {
                                HTML += '<option value="' + credor.id_registro_fiduciario_credor + '">' + credor.no_credor + ' (' + credor.agencia.codigo_agencia + ') - ' + credor.agencia.banco.no_banco + '</option>';
                            } else {
                                HTML += '<option value="' + credor.id_registro_fiduciario_credor + '">' + credor.no_credor + '</option>';
                            }
                        });
                        form.find('select[name=id_registro_fiduciario_credor]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_registro_fiduciario_credor]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_registro_fiduciario_credor]').selectpicker('refresh');

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_registro_fiduciario_credor]').html('').prop('disabled', true);
            form.find('select[name=id_registro_fiduciario_credor]').selectpicker('refresh');
        }
    });
    $('div#registro-fiduciario').on('change', 'select[name=id_registro_fiduciario_credor]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var registro_token = form.find('input[name=registro_token]').val();
        var id_registro_fiduciario_tipo = form.find('select[name=id_registro_fiduciario_tipo]').val();

        if ($(this).val() > 0) {
            var data_args = {
                'registro_token': registro_token,
                'id_registro_fiduciario_tipo': id_registro_fiduciario_tipo,
                'inserir_gerente': true
            };
            $.ajax({
                type: "GET",
                url: URL_BASE + 'app/produtos/registros/credores/' + $(this).val(),
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (retorno) {
                    if (retorno.status == 'sucesso') {
                        // Instancia a tabela
                        tabela = $('table#tabela-parte-16');
                        tabela.find('tbody').html('');

                        $.each(retorno.responsaveis, function (index, value) {
                            // Cria uma nova linha
                            var nova_linha = $('<tr id="linha_' + index + '">');

                            // Criar as colunas
                            colunas = '<td class="no_parte">' + value.no_parte + '</td>';
                            colunas += '<td class="nu_cpf_cnpj">' + value.nu_cpf_cnpj + '</td>';
                            colunas += '<td>';

                            // Botões
                            colunas += '<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-temp-parte" data-registrotoken="' + registro_token + '" data-hash=' + index + ' data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>';
                            colunas += '<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-registrotoken="' + registro_token + '" data-hash=' + index + '><i class="fas fa-trash"></i></i> Remover</button>';

                            // Campo para validação
                            colunas += '<input type="hidden" name="in_credor_inserido" value="S" />';

                            colunas += '</td>';

                            nova_linha.append(colunas);
                            tabela.find('tbody').prepend(nova_linha);
                        });
                    }

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            // TODO: Remover partes do gerente da sessão e HTML
        }
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_registro_fiduciario_custodiante]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var registro_token = form.find('input[name=registro_token]').val();

        if ($(this).val() > 0) {
            var data_args = {
                'registro_token': registro_token,
                'inserir_custodiante': true
            };
            $.ajax({
                type: "GET",
                url: URL_BASE + 'app/produtos/registros/custodiantes/' + $(this).val(),
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (retorno) {
                    if (retorno.status == 'sucesso') {
                        // Instancia a tabela
                        tabela = $('table#tabela-parte-16');
                        tabela.find('tbody').html('');

                        $.each(retorno.responsaveis, function (index, value) {
                            // Cria uma nova linha
                            var nova_linha = $('<tr id="linha_' + index + '">');

                            // Criar as colunas
                            colunas = '<td class="no_parte">' + value.no_parte + '</td>';
                            colunas += '<td class="nu_cpf_cnpj">' + value.nu_cpf_cnpj + '</td>';
                            colunas += '<td>';

                            // Botões
                            colunas += '<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-temp-parte" data-registrotoken="' + registro_token + '" data-hash=' + index + ' data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>';
                            colunas += '<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-registrotoken="' + registro_token + '" data-hash=' + index + '><i class="fas fa-trash"></i></i> Remover</button>';

                            // Campo para validação
                            colunas += '<input type="hidden" name="in_custodiante_inserido" value="S" />';

                            colunas += '</td>';

                            nova_linha.append(colunas);
                            tabela.find('tbody').prepend(nova_linha);
                        });
                    }

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            // TODO: Remover partes do gerente da sessão e HTML
        }
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_estado_custodiante]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_custodiante]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_cidade_custodiante]', function (ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        if ($(this).val() > 0) {
            var data_args = {
                'id_cidade': $(this).val()
            };
            $.ajax({
                type: "GET",
                url: URL_BASE + 'app/produtos/registros/custodiantes',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (custodiantes) {
                    if (custodiantes) {
                        HTML = '';
                        $.each(custodiantes, function (key, custodiante) {
                            HTML += '<option value="' + custodiante.id_registro_fiduciario_custodiante + '">' + custodiante.no_custodiante + '</option>';
                        });
                        form.find('select[name=id_registro_fiduciario_custodiante]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_registro_fiduciario_custodiante]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_registro_fiduciario_custodiante]').selectpicker('refresh');

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_registro_fiduciario_custodiante]').html('').prop('disabled', true);
            form.find('select[name=id_registro_fiduciario_custodiante]').selectpicker('refresh');
        }
    });
    $('div#registro-fiduciario').on('change', 'select[name=id_registro_fiduciario_custodiante]', function (e) {
        var obj_modal = $(this).closest('.modal');

        if ($(this).val() > 0) {
            obj_modal.find('.parte-custodiante').slideDown('fast');
        } else {
            obj_modal.find('.parte-custodiante').slideUp('fast');
        }
    });

    $('div#registro-fiduciario').on('click', 'button.salvar-registro-fiduciario', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario]');
        var produto = form.find('input[name=produto]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/' + produto + '/registros',
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
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
                if (retorno.recarrega == 'true') {
                    alerta.then(function () {
                        location.reload();
                    });
                }
                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_construtora_empreendimento]', function (ev) {
        ev.preventDefault();
        var obj_modal = $(this).closest('.modal');

        var id_construtora = $(this).val();
        if (id_construtora > 0) {
            obj_modal.find('.empreendimento').show();

            var data_args = {
                'id_construtora': id_construtora
            };
            $.ajax({
                type: "POST",
                url: URL_BASE + 'app/construtora/empreendimentos',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (empreendimentos) {
                    if (empreendimentos.length > 0) {
                        HTML = '<optgroup label="Já cadastrados">';
                        $.each(empreendimentos, function (key, empreendimento) {
                            HTML += '<option value="' + empreendimento.id_empreendimento + '">' + empreendimento.no_empreendimento + '</option>';
                        });
                        HTML += '</optgroup>';
                        HTML += '<option value="-1">Outro</option>';
                        obj_modal.find('select[name=id_empreendimento]').html(HTML).prop('disabled', false);
                        obj_modal.find('select[name=no_empreendimento]').text('').prop('disabled', true);
                        obj_modal.find('.id-empreendimento').show();
                    } else {
                        obj_modal.find('select[name=id_empreendimento]').html('').prop('disabled', true);
                        obj_modal.find('select[name=no_empreendimento]').text('').prop('disabled', false);
                        obj_modal.find('.id-empreendimento').hide();
                        obj_modal.find('.no-empreendimento').show();
                    }
                    obj_modal.find('select[name=id_empreendimento]').selectpicker('refresh');

                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            obj_modal.find('select[name=id_empreendimento]').html('').prop('disabled', true);
            obj_modal.find('select[name=id_empreendimento]').selectpicker('refresh');

            obj_modal.find('select[name=no_empreendimento]').text('').prop('disabled', true);

            obj_modal.find('.empreendimento').hide();
            obj_modal.find('.id-empreendimento').hide();
            obj_modal.find('.no-empreendimento').hide();
        }
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_empreendimento]', function (ev) {
        var obj_modal = $(this).closest('.modal');

        if ($(this).val() == -1) {
            obj_modal.find('.no-empreendimento').slideDown('fast');
            obj_modal.find('select[name=no_empreendimento]').prop('disabled', false);
        } else {
            obj_modal.find('.no-empreendimento').slideUp('fast');
            obj_modal.find('select[name=no_empreendimento]').prop('disabled', true);
        }
    });

    $('div#registro-fiduciario').on('change', 'select[name=id_canal_pdv_parceiro]', function (ev) {
        var id_canal_pdv_parceiro = $(this).val();
        var form = $('form[name=form-registro-fiduciario]');

        if (id_canal_pdv_parceiro >= 0) {
            var data_args = {
                'id_canal_pdv_parceiro': id_canal_pdv_parceiro
            };

            $.ajax({
                type: "POST",
                url: URL_BASE + 'app/canais-pdv/registro-canal-pdv',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (canais) {
                    if (canais) {
                        $('select[name="parceiro_canal_pdv_parceiro"]').val(canais.id_canal_pdv_parceiro);
                        form.find('input[name=codigo_canal_pdv_parceiro]').val(canais.codigo_canal_pdv_parceiro);
                        form.find('input[name=email_canal_pdv_parceiro]').val(canais.email_canal_pdv_parceiro);
                        form.find('input[name=cnpj_canal_pdv_parceiro]').val(canais.cnpj_canal_pdv_parceiro);
                        form.find('input[name=no_pj]').prop('disabled', false);
                    } else {
                        $('select[name="parceiro_canal_pdv_parceiro"]').val(0);
                        form.find('input[name=parceiro_canal_pdv_parceiro]').val("");
                        form.find('input[name=codigo_canal_pdv_parceiro]').val("");
                        form.find('input[name=email_canal_pdv_parceiro]').val("");
                        form.find('input[name=cnpj_canal_pdv_parceiro]').val("");
                        form.find('input[name=no_pj]').prop('disabled', true).val("");
                    }
                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        }

    });

    $('div#registro-fiduciario').on('change', 'select[name=parceiro_canal_pdv_parceiro]', function (ev) {
        ev.preventDefault();

        var id_canal_pdv_parceiro = $(this).val();
        var form = $('form[name=form-registro-fiduciario]');

        if (id_canal_pdv_parceiro >= 0) {
            var data_args = {
                'id_canal_pdv_parceiro': id_canal_pdv_parceiro
            };

            $.ajax({
                type: "POST",
                url: URL_BASE + 'app/canais-pdv/registro-canal-pdv',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function (canais) {
                    if (canais) {
                        $('select[name="id_canal_pdv_parceiro"]').val(canais.id_canal_pdv_parceiro);
                        form.find('input[name=parceiro_canal_pdv_parceiro]').val(canais.parceiro_canal_pdv_parceiro);
                        form.find('input[name=codigo_canal_pdv_parceiro]').val(canais.codigo_canal_pdv_parceiro);
                        form.find('input[name=email_canal_pdv_parceiro]').val(canais.email_canal_pdv_parceiro);
                        form.find('input[name=cnpj_canal_pdv_parceiro]').val(canais.cnpj_canal_pdv_parceiro);
                        form.find('input[name=no_pj]').prop('disabled', false);
                    } else {
                        $('select[name="id_canal_pdv_parceiro"]').val(0);
                        form.find('input[name=codigo_canal_pdv_parceiro]').val("");
                        form.find('input[name=email_canal_pdv_parceiro]').val("");
                        form.find('input[name=cnpj_canal_pdv_parceiro]').val("");
                        form.find('input[name=no_pj]').prop('disabled', true).val("");
                    }
                    ajax_success();
                },
                error: function (ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        }

    });
});
