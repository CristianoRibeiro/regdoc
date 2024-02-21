$(document).ready(function() {

    $('div#registro-fiduciario-pedido-central-historico').on('show.bs.modal', function (ev) {
        var id_registro_fidunciario = $(ev.relatedTarget).data('idregistro');
        var id_pedido_central = $(ev.relatedTarget).data('idpedidocentral');

        var url = URL_BASE + 'app/produtos/registros/'+ id_registro_fidunciario +'/pedidos-central/'+id_pedido_central+'/historicos/novo';

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

    $('div#registro-fiduciario-pedido-central-historico').on('click', 'button.salvar-novo-pedido-central', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-novo-pedido-cental]');

        var id_registro_fidunciario = $('#id_registro_fundiciario').val();
        var id_pedido_central = $('#id_pedido_central').val();

        var data = new FormData(form.get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/produtos/registros/'+id_registro_fidunciario+'/pedidos-central/'+id_pedido_central+'/historicos',
            data: data,
            contentType: false,
            processData: false,
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

    $('div#registro-fiduciario-pedido-central-acesso').on('show.bs.modal', function (ev) {
        var id_registro_fidunciario = $(ev.relatedTarget).data('idregistro');
        var id_pedido_central = $(ev.relatedTarget).data('idpedidocentral');

        var url = URL_BASE + 'app/produtos/registros/'+ id_registro_fidunciario +'/pedidos-central/'+id_pedido_central+'/editar';

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

    $('div#registro-fiduciario-pedido-central-acesso').on('click', 'button.atualizar-acesso', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-pedido-central-acesso]');

        var id_registro_fidunciario = form.find('input[name=id_registro_fidunciario]').val();
        var id_pedido_central = form.find('input[name=id_pedido_central]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE+'app/produtos/registros/'+id_registro_fidunciario+'/pedidos-central/'+id_pedido_central,
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
    $('div#registro-fiduciario-arisp-acesso').on('show.bs.modal', function (ev) {
        var id_registro_fidunciario = $(ev.relatedTarget).data('idregistro');
        var id_arisp_pedido = $(ev.relatedTarget).data('idarisppedido');

        var url = URL_BASE + 'app/produtos/registros/'+ id_registro_fidunciario +'/arisp/'+id_arisp_pedido+'/editar';

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

    $('div#registro-fiduciario-arisp-acesso').on('click', 'button.atualizar-acesso', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-arisp-acesso]');

        var id_registro_fidunciario = form.find('input[name=id_registro_fidunciario]').val();
        var id_arisp_pedido = form.find('input[name=id_arisp_pedido]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE+'app/produtos/registros/'+id_registro_fidunciario+'/arisp/'+id_arisp_pedido,
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

});
