$(document).ready(function() {
    $('div#editar-registro').on('show.bs.modal', function(ev){
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var form = $('form[name=form-editar-registro]');

        var data_args = {
            'id_registro_fiduciario' : id_registro_fiduciario
        }

        $.ajax({
            type: "GET",
            url : URL_BASE+'app/produtos/registros/editar',
            data: data_args,
            context: this,
            beforeSend: function () {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body form').html(retorno);
                ajax_success();

                //Atualiza assinatura
                atualiza_assinar_todos($('[name=registro_token]').val());
            },
            error: function(ev, xhr, settings, error){
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#editar-registro button.editar-registro').on('click', function(ev){
        ev.preventDefault();
        var form = $('form[name=form-editar-registro-fiduciario]');

        var data = new FormData(form.get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/produtos/registros/editar',
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
});
