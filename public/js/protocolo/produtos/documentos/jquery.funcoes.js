$(document).ready(function() {
    funcoes_menu();

    $('a.pdavh-acessar-assinatura').each(function() {
        url_atual = window.location.origin + window.location.pathname + '?retorno_assinatura=true' + window.location.hash;

        url = $(this).attr('href') + '?return_url=' + Base64.encode(url_atual);

        $(this).attr('href', url);
    });
});

function retorno_assinatura() {
    $.blockUI();
    setTimeout(function() {
        window.location = window.location.href.split("?")[0] + window.location.hash;
    }, 5000);
}
function funcoes_menu() {
    $('ul#documento-tab').on('click', '.nav-link', function(e) {
        window.location.hash = $(this).attr('href');
    });

    hash = $(location).attr('hash');
    if (hash.length>0) {
        $('ul#documento-tab .nav-link.active').removeClass('active').removeClass('show');
        $('ul#documento-tab .nav-link[href="' + hash + '"]').addClass('active').addClass('show');
        $('div#documento-content>.tab-pane.active').removeClass('active').removeClass('show');
        $('div#documento-content>.tab-pane' + hash).addClass('active').addClass('show');
    }
}
