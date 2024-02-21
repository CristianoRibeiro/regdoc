$(document).ready(function() {
    $('div#registro-fiduciario-procurador-detalhes-editar').on('show.bs.modal', function (ev) {

        var id_procurador = $(ev.relatedTarget).data('idprocurador');
        var operacao = $(ev.relatedTarget).data('operacao');

        switch (operacao) {
            case 'editar':
                $(this).find('.modal-title').text('Editar procurador');
                $(this).find('.modal-footer').find('button.editar-procurador').show();
                break;
            case 'detalhes':
                $(this).find('.modal-title').text('Detalhes do procurador');
                $(this).find('.modal-footer').find('button.editar-procurador').hide();
                break;
        }
    
        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/procurador/' + id_procurador + '/' + operacao,
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

    $('div#registro-fiduciario-procurador-detalhes-editar').on('click', 'button.editar-procurador', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-procurador-editar]');
       
        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/registros/procurador/atualizar',
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


