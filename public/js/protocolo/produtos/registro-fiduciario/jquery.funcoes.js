$(document).ready(function() {
    funcoes_menu();

    $('a.pdavh-acessar-assinatura').each(function() {
        url_atual = window.location.origin + window.location.pathname + '?retorno_assinatura=true' + window.location.hash;

        url = $(this).attr('href') + '?return_url=' + Base64.encode(url_atual);

        $(this).attr('href', url);
    });

    $('a.acessar-pagamentos').on('click', function() {
        $('ul#registro-tab a[href="#registro-pagamentos"]').tab('show');
        window.location.hash = '#registro-pagamentos';
    });
});

function retorno_assinatura() {
    $.blockUI();
    setTimeout(function() {
        window.location = window.location.href.split("?")[0] + window.location.hash;
    }, 5000);
}
function funcoes_menu() {
    $('ul#registro-tab').on('click', '.nav-link', function(e) {
        window.location.hash = $(this).attr('href');
    });

    hash = $(location).attr('hash');
    if (hash.length>0) {
        $('ul#registro-tab .nav-link.active').removeClass('active').removeClass('show');
        $('ul#registro-tab .nav-link[href="' + hash + '"]').addClass('active').addClass('show');
        $('div#registro-content>.tab-pane.active').removeClass('active').removeClass('show');
        $('div#registro-content>.tab-pane' + hash).addClass('active').addClass('show');
    }
}
