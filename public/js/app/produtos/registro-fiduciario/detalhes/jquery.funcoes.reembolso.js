$(document).ready(function() {
    $('div#registro-fiduciario-reembolso').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'novo':
                $(this).find('.modal-title').text('Novo reembolso');
                $(this).find('.modal-footer').find('button.salvar-registro-fiduciario-reembolso').text('Salvar reembolso').show();
                url = URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/reembolsos/novo';

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

    $('div#registro-fiduciario-reembolso').on('click', 'button.salvar-reembolso', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-reembolso]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/reembolsos',
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

    $('div#registro-fiduciario-reembolso-visualizar').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
        var id_registro_fiduciario_reembolso = $(ev.relatedTarget).data('idregistrofiduciarioreembolso');

        $(this).find('.modal-title').text('Reembolso');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/reembolsos/' + id_registro_fiduciario_reembolso,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                $(this).find('.modal-body').html(retorno);
                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-reembolso-arquivos').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro')
        var id_registro_fiduciario_reembolso = $(ev.relatedTarget).data('idreembolso');
        var obj_modal = $(this).closest('.modal');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/reembolsos/'+id_registro_fiduciario_reembolso+'/arquivos',
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                obj_modal.find('.modal-body form').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, obj_modal, true);
            }
        });
    });

});
