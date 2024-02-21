$(document).ready(function() {

    $('div#registro-fiduciario-nota-devolutiva').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Nova nota devolutiva');
                $(this).find('.modal-footer').find('button.salvar-registro-fiduciario-nota-devolutiva').text('Salvar nota devolutiva').show();
                url = URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/novo';

                var data_args = {};
                break;
        }

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
    });

    $('div#registro-fiduciario-nota-devolutiva').on('click', 'button.salvar-nota-devolutiva', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-nota-devolutiva]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        var data = form.serialize();

        form.find('select#causa_raiz_to option').each(function() {
            data += '&id_nota_devolutiva_causa_raizes[]=' + $(this).val();
        });

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas',
            data: data,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!",retorno.message,"error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!",retorno.message,"success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!",retorno.message,"warning");
                        break;
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                if (retorno.recarrega == 'true') {
                    alerta.then(function(){
                        location.reload();
                    });
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-nota-devolutiva-visualizar').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_nota_devolutiva = $(ev.relatedTarget).data('idregistrofiduciarionotadevolutiva');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/' + id_registro_fiduciario_nota_devolutiva,
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

    $('div#registro-fiduciario-nota-devolutiva-responder').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_nota_devolutiva = $(ev.relatedTarget).data('idregistrofiduciarionotadevolutiva');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/' + id_registro_fiduciario_nota_devolutiva + '/editar',
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

    $('div#registro-fiduciario-nota-devolutiva-responder').on('click', 'button.salvar-resposta', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-nota-devolutiva-responder]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_registro_fiduciario_nota_devolutiva = form.find('input[name=id_registro_fiduciario_nota_devolutiva]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/' + id_registro_fiduciario_nota_devolutiva,
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!",retorno.message,"error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!",retorno.message,"success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!",retorno.message,"warning");
                        break;
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                if (retorno.recarrega == 'true') {
                    alerta.then(function(){
                        location.reload();
                    });
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-nota-devolutiva').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Nova nota devolutiva');
                $(this).find('.modal-footer').find('button.salvar-registro-fiduciario-nota-devolutiva').text('Salvar nota devolutiva').show();
                url = URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/novo';

                var data_args = {};
                break;
        }

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
    });

    $('div#registro-fiduciario-nota-devolutiva-categorizar').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_nota_devolutiva = $(ev.relatedTarget).data('idregistrofiduciarionotadevolutiva');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/' + id_registro_fiduciario_nota_devolutiva + '/categorizar',
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

    $('div#registro-fiduciario-nota-devolutiva-categorizar').on('click', 'button.salvar-nota-devolutiva-categorizar', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-nota-devolutiva-categorizar]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_registro_fiduciario_nota_devolutiva = form.find('input[name=id_registro_fiduciario_nota_devolutiva]').val();

        var data = form.serialize();
        form.find('select#causa_raiz_to option').each(function() {
            data += '&id_nota_devolutiva_causa_raizes[]=' + $(this).val();
        });

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/devolutivas/' + id_registro_fiduciario_nota_devolutiva + '/categorizar',
            data: data,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!",retorno.message,"error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!",retorno.message,"success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!",retorno.message,"warning");
                        break;
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                if (retorno.recarrega == 'true') {
                    alerta.then(function(){
                        location.reload();
                    });
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-nota-devolutiva, div#registro-fiduciario-nota-devolutiva-categorizar').on('change', 'select[name=id_causa_raiz_classificacao]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_causa_raiz_grupo = form.find('select[name=id_causa_raiz_grupo]');
        var id_causa_raiz_classificacao = $(this).val();

        var data_args = {
            'id_causa_raiz_classificacao': id_causa_raiz_classificacao,
        };

        var readonly = false;

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/notas-devolutivas/lista-nota-devolutiva-causa-grupo',
            data: data_args,
            async: true,
            beforeSend: function () {
                ajax_beforesend();
            },
            success: function(causa_raiz_grupos) {
                if (obj_causa_raiz_grupo.hasClass('selectpicker')) {
                    obj_causa_raiz_grupo.html('');
                } else {
                    obj_causa_raiz_grupo.html('<option value="">Selecione</option>');
                }
                if (causa_raiz_grupos.length>0) {
                    $.each(causa_raiz_grupos,function(key, causa_raiz_grupo) {
                        obj_causa_raiz_grupo.append('<option value="'+causa_raiz_grupo.id_nota_devolutiva_causa_grupo +'">'+causa_raiz_grupo.no_nota_devolutiva_causa_grupo+'</option>');
                    });
                    obj_causa_raiz_grupo.prop('disabled',false);
                } else {
                    obj_causa_raiz_grupo.prop('disabled',true);
                }
                if (obj_causa_raiz_grupo.hasClass('selectpicker')) {
                    if (readonly) {
                        obj_causa_raiz_grupo.addClass('readonly');
                    } else {
                        obj_causa_raiz_grupo.removeClass('readonly');
                        obj_causa_raiz_grupo.closest('.bootstrap-select').removeClass('readonly');
                    }
                    obj_causa_raiz_grupo.selectpicker('refresh');
                } else {
                    obj_causa_raiz_grupo.attr('readonly', readonly);
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });  
        
        $('div#registro-fiduciario-nota-devolutiva, div#registro-fiduciario-nota-devolutiva-categorizar').on('change', 'select[name=id_causa_raiz_grupo]', function(ev) {
            ev.preventDefault();
            var form = $(this).closest('form');
            var obj_causa_raiz = form.find('select#causa_raiz');
            var id_causa_raiz_grupo = $(this).val();
            var no_causa_raiz_grupo = $(this).find('option:selected').text();
    
            var data_args = {
                'id_causa_raiz_grupo': id_causa_raiz_grupo
            };

            form.find('label[for=causa_raiz]').find('span').html(no_causa_raiz_grupo);

            $.ajax({
                type: "POST",
                url: URL_BASE+'app/notas-devolutivas/lista-nota-devolutiva-causa-raiz',
                data: data_args,
                async: true,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function(causa_raizes) {
                    if (causa_raizes.length>0) {
                        obj_causa_raiz.html('');
                        $.each(causa_raizes,function(key, causa_raiz) {
                            obj_causa_raiz.append('<option value="' + causa_raiz.id_nota_devolutiva_causa_raiz + '">' + no_causa_raiz_grupo + ' - ' + causa_raiz.no_nota_devolutiva_causa_raiz + '</option>');
                        });
                        obj_causa_raiz.prop('disabled',false);
                    } else {
                        obj_causa_raiz.prop('disabled',true);
                    }
                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });  
        });
    });

    document.querySelector(`#submit-atualizacao-notas-devolutivas`)?.addEventListener(`submit`, submitAtualizacaoStatusNotasDevolutivas);

    function submitAtualizacaoStatusNotasDevolutivas(evt) {

        evt.preventDefault();

        const form = evt.target;
        const idRegistro = form.dataset.idRegistro;
        const idRegistrofiduciarionotadevolutiva = form.dataset.idRegistrofiduciarionotadevolutiva;
        const data = new FormData(form);
        const situacao = data.get(`situacao_nota_devolutiva`); 

        $.ajax({
            type: `PATCH`,
            url: URL_BASE + `app/produtos/registros/${idRegistro}/atualizar-situacao-nota-devolutiva`,
            data: {
                situacao: situacao,
                id_registro_fiduciario_nota_devolutiva: idRegistrofiduciarionotadevolutiva,
            },
            beforeSend: () => ajax_beforesend(),
            success: async () => {
                const tabDevolutivas = document.querySelector(`#registro-devolutivas`);
                const div = document.querySelector(`#div-notas-devolutivas-new`);

                const response = await fetch(URL_BASE + `app/produtos/registros/${idRegistro}/devolutivas-tab`);
                const html = await response.text();

                tabDevolutivas.innerHTML = html;
                document.querySelector(`#submit-atualizacao-notas-devolutivas`)?.addEventListener(`submit`, submitAtualizacaoStatusNotasDevolutivas);

                ajax_success();
                $(div).load(location.href);
            },
            error: (ev) => ajax_error(ev)
        });

    }
});
