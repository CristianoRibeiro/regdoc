$(document).ready(function() {
    $('div#documento-parte').on('show.bs.modal', function (ev) {
        var operacao = $(ev.relatedTarget).data('operacao');
        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Nova parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/documentos/partes/novo';
                break;
            case 'editar':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Editar a parte');
                $(this).find('.modal-footer').find('button.salvar-parte').show();
                var url = URL_BASE + 'app/produtos/documentos/' + $(ev.relatedTarget).data('uuiddocumento') + '/partes/' + $(ev.relatedTarget).data('uuidparte') + '/editar';
                break;
            case 'detalhes':
                $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Detalhes da parte');
                $(this).find('.modal-footer').find('button.salvar-parte').hide();
                var url = URL_BASE + 'app/produtos/documentos/' + $(ev.relatedTarget).data('uuiddocumento') + '/partes/' + $(ev.relatedTarget).data('uuidparte') + '';
                break;
        }
        $.ajax({
            type: "GET",
            url: url,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#documento-parte').on('blur', 'input[name=nu_cep]', function(e){
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

    $('div#documento-parte').on('click', 'button.salvar-parte', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-documento-parte]');

        var uuid_documento = form.find('input[name=uuid_documento]').val();
        var uuid_documento_parte = form.find('input[name=uuid_documento_parte]').val();

        $.ajax({
            type: 'PUT',
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/partes/' + uuid_documento_parte,
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
        });
    });



});
