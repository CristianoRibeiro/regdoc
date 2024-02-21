$(document).ready(function() {
    $('div#documento-visualizar-assinatura').on('show.bs.modal', function (ev) {
        var id_parte_assinatura = $(ev.relatedTarget).data('idparteassinatura');

        $(this).find('.modal-title>span').html($(ev.relatedTarget).data('subtitulo'));

        $.ajax({
            type: "GET",
            url: URL_BASE + 'protocolo/documentos/assinaturas/' + id_parte_assinatura,
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
