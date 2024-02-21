$(document).ready(function() {
    $('div#documento-visualizar-assinatura').on('show.bs.modal', function (ev) {
        var uuid_documento = $(ev.relatedTarget).data('uuiddocumento');
        var id_parte_assinatura = $(ev.relatedTarget).data('idparteassinatura');

        $(this).find('.modal-title>span').html($(ev.relatedTarget).data('subtitulo'));

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/documentos/' + uuid_documento + '/assinaturas/' + id_parte_assinatura,
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
