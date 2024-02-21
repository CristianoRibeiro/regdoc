$(document).ready(function() {
    $('div#detalhes-logs').on('show.bs.modal', function (ev) {
        carrega_detalhes(ev,$(this));
    });
});

function carrega_detalhes(ev, obj_modal) {
    var id_logs = $(ev.relatedTarget).data('idlog');
    var descricao_detalhe = $(ev.relatedTarget).data('descricaodetalhe');
    var data_args = {
        'id_log': id_logs,
    };

    $.ajax({
        type: "POST",
        url: URL_BASE + 'app/relatorio/logs/detalhes',
        data: data_args,
        beforeSend: function() {
            ajax_beforesend();
        },
        success: function (retorno) {
            obj_modal.find('.modal-body').html(retorno);
            ajax_success(descricao_detalhe, obj_modal);
        },
        error: function (ev, xhr, settings, error) {
            ajax_error(ev, obj_modal, true);
        }
    });
}
