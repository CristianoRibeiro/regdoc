$(document).ready(function() {
    $('div#registro-fiduciario-visualizar-assinatura').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var id_registro_fiduciario_assinatura = $(ev.relatedTarget).data('idassinatura');
        var id_registro_fiduciario_parte_assinatura = $(ev.relatedTarget).data('idparteassinatura');

        $(this).find('.modal-title>span').html($(ev.relatedTarget).data('subtitulo'));

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/assinaturas/' + id_registro_fiduciario_assinatura + '/parte/' + id_registro_fiduciario_parte_assinatura,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });


    $('div#registro-fiduciario-detalhes-assinaturas').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');
        var id_registro_fiduciario_assinatura = $(ev.relatedTarget).data('idregistroassinatura');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/assinaturas/' + id_registro_fiduciario_assinatura,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                $(this).find('.modal-body').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

});
