$(document).ready(function() {
    $('a.reenviar-codigo').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/reenviar-autenticacao-2fa',
            beforeSend: function () {
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
                alerta.then(function(){
                    if (retorno.recarrega == 'true') {
                        location.reload();
                    }
                });
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('form[name=form-autenticacao-2fa]').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/validar-autenticacao-2fa',
            data: $(this).serialize(),
            beforeSend: function () {
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
                alerta.then(function() {
                    window.location.href = retorno.redirect_url;
                });
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

});
