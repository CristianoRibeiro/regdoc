$(document).ready(function() {
    $('div#registro-fiduciario-imovel').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Novo imóvel');
                $(this).find('.modal-footer').find('button.salvar-imovel').show();
                url = URL_BASE + `app/produtos/registros/${$(ev.relatedTarget).data('idregistro')}/imoveis/novo`;
                break;
            case 'editar':
                $(this).find('.modal-title').text('Editar imóvel');
                $(this).find('.modal-footer').find('button.salvar-imovel').show();
                $(this).find('.modal-footer').find('button.salvar-imovel').html('Atualizar imóvel'); // Muda o texto do botão
                url = URL_BASE + `app/produtos/registros/${$(ev.relatedTarget).data('idregistro')}/imoveis/${$(ev.relatedTarget).data('idimovel')}/editar`;
                break;
            case 'detalhes':
                $(this).find('.modal-title').text('Detalhes do imóvel');
                $(this).find('.modal-footer').find('button.salvar-imovel').hide();
                url = URL_BASE + `app/produtos/registros/${$(ev.relatedTarget).data('idregistro')}/imoveis/${$(ev.relatedTarget).data('idimovel')}`;
                break;
        }

        $.ajax({
            type: 'GET',
            url: url,
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

    $('div#registro-fiduciario-imovel').on('blur', 'input[name=nu_cep]', function(e){
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

                        form.find('select[name=id_estado]').attr('readonly', true);
                        carregar_cidades(form.find('select[name=id_cidade]'), 0, 0, response.uf, response.localidade, true);

                        form.find('input[name=no_endereco]').val(response.logradouro);
                        form.find('input[name=no_bairro]').val(response.bairro);
                        form.find('input[name=no_complemento]').val(response.complemento);
                    } else {
                        form.find('select[name=id_estado]').val('0').trigger('change').attr('readonly', false);

                        form.find('input[name=no_endereco]').val('');
                        form.find('input[name=no_bairro]').val('');
                        form.find('input[name=no_complemento]').val('');
                    }

                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_estado]').val('0').trigger('change').attr('readonly', false);;

            form.find('input[name=no_endereco]').val('');
            form.find('input[name=nu_numero]').val('');
            form.find('input[name=no_bairro]').val('');
            form.find('input[name=no_complemento]').val('');
        }
    });

    $('div#registro-fiduciario-imovel').on('click', 'button.salvar-imovel', function (ev) {
        ev.preventDefault();
        let form = $('form[name=form-registro-fiduciario-imovel]')

        let id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val()
        let id_registro_fiduciario_imovel = form.find('input[name=id_registro_fiduciario_imovel]').val()

        let url, verb

        if (id_registro_fiduciario_imovel) {
            url = URL_BASE + `app/produtos/registros/${id_registro_fiduciario}/imoveis/${id_registro_fiduciario_imovel}`
            verb = 'PUT';
        } else {
            url = URL_BASE + `app/produtos/registros/${id_registro_fiduciario}/imoveis`
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
                        var alerta = swal("Erro!", retorno.message, "error")
                        break
                    case 'sucesso':
                        var alerta = swal("Sucesso!", retorno.message, "success")
                        break
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.message, "warning")
                        break
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning")
                        break
                }
                alerta.then(function() {
                    if (retorno.recarrega == 'true') {
                        location.reload()
                    }

                    ajax_success()
                })
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev)
            }
        })
    })

    $('div#registro-fiduciario').on('click', 'button.remover-imovel', function (ev) {
        ev.preventDefault();

        $.ajax({
            type: "DELETE",
            url: URL_BASE + `app/produtos/registros/${$(this).data('idregistro')}/imoveis/${$(this).data('idimovel')}`,
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
});
