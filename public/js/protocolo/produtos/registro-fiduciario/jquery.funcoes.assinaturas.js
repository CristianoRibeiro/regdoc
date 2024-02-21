$(document).ready(function() {
    $('div#registro-fiduciario-visualizar-assinatura').on('show.bs.modal', function (ev) {
        var id_parte_assinatura = $(ev.relatedTarget).data('idparteassinatura');

        $(this).find('.modal-title>span').html($(ev.relatedTarget).data('subtitulo'));

        $.ajax({
            type: "GET",
            url: URL_BASE + 'protocolo/registro/assinaturas/' + id_parte_assinatura,
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

    $("button[name='assinar_lote']").click(function(ev) {

        const checados = [...document.querySelectorAll("input[name='id_arquivo_grupo_produto[]']:checked")];

        if (checados.length === 0) return swal("ATENÇÃO", "Nenhum arquivo foi selecionado", "warning");

        const ids_parte_assinatura = checados.map(checado => checado.dataset.idRegistroFiduciarioParteAssinatura).join(`,`);
        const qualificacoes = checados.map(checado => checado.dataset.qualificacao).join(`,`);
        
        $.ajax({
            type: "GET",
            url: URL_BASE + `protocolo/iniciar-assinatura-lote/${ids_parte_assinatura}/${qualificacoes}` ,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                url_atual = window.location.origin + window.location.pathname + '?retorno_assinatura=true' + window.location.hash;
                url_final = retorno + '?return_url=' + Base64.encode(url_atual);

                window.location.href = url_final;
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });
});
