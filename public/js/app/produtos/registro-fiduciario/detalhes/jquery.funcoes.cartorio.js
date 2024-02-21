$(document).ready(function() {
    $('div#registro-fiduciario-cartorio').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/cartorio/editar',
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

    $('div#registro-fiduciario-cartorio').on('click', 'button.atualizar-cartorio', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-cartorio]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/cartorio',
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

    $('div#registro-fiduciario-cartorio, div#registro-fiduciario-transformar-contrato').on('change', 'select[name=id_estado_cartorio_ri]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_cartorio_ri]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario-cartorio, div#registro-fiduciario-transformar-contrato').on('change', 'select[name=id_cidade_cartorio_ri]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        var id_cidade = $(this).val();
        if (id_cidade>0) {
            var data_args = {
                'id_tipo_pessoa': [2],
                'id_tipo_serventia': [1, 10],
                'id_cidade': id_cidade
            };
            $.ajax({
                type: "POST",
                url: URL_BASE+'app/pessoa/lista',
                data: data_args,
                beforeSend: function() {
                    ajax_beforesend();
                },
                success: function(pessoas) {
                    if (pessoas.length>0) {
                        HTML = '';
                        $.each(pessoas, function (key, pessoa) {
                            HTML += '<option value="'+pessoa.id_pessoa+'">'+pessoa.no_pessoa+'</option>';
                        });
                        form.find('select[name=id_pessoa_cartorio_ri]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_pessoa_cartorio_ri]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_pessoa_cartorio_ri]').selectpicker('refresh');

                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_pessoa_cartorio_ri]').html('').prop('disabled', true);
            form.find('select[name=id_pessoa_cartorio_ri]').selectpicker('refresh');
        }
    });   
    
    $('div#registro-fiduciario-cartorio, div#registro-fiduciario-transformar-contrato').on('change', 'select[name=id_estado_cartorio_rtd]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_cartorio_rtd]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario-cartorio, div#registro-fiduciario-transformar-contrato').on('change', 'select[name=id_cidade_cartorio_rtd]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        var id_cidade = $(this).val();
        if (id_cidade>0) {
            var data_args = {
                'id_tipo_pessoa': [2],
                'id_tipo_serventia': [2, 3, 10],
                'id_cidade': id_cidade
            };
            $.ajax({
                type: "POST",
                url: URL_BASE+'app/pessoa/lista',
                data: data_args,
                beforeSend: function() {
                    ajax_beforesend();
                },
                success: function(pessoas) {
                    if (pessoas.length>0) {
                        HTML = '';
                        $.each(pessoas, function (key, pessoa) {
                            HTML += '<option value="'+pessoa.id_pessoa+'">'+pessoa.no_pessoa+'</option>';
                        });
                        form.find('select[name=id_pessoa_cartorio_rtd]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_pessoa_cartorio_rtd]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_pessoa_cartorio_rtd]').selectpicker('refresh');

                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_pessoa_cartorio_rtd]').html('').prop('disabled', true);
            form.find('select[name=id_pessoa_cartorio_rtd]').selectpicker('refresh');
        }
    });
});