$(document).ready(function() {
    $('form[name=form-consulta-biometria]').on('submit', function(ev) {
        ev.preventDefault();

        var cpf = $(this).find('input[name=cpf]').val();

        ConsultarBiometria.init({
            cpf: cpf,
            tentativas_fator: 1,
            segundos: 5,
            // botao_atualizar: $('a#loading-update')
        });
    });
});

var ConsultarBiometria = (function() {
    var cpf = null;
    var tentativas_fator = null;
    var segundos = null;
    // var botao_atualizar = null;

    var tentativas = 1;
    var momento = null;
    var uuid = null;

    function init(args) {
        cpf = args.cpf;
        tentativas_fator = args.tentativas_fator;
        segundos = args.segundos;
        // botao_atualizar = args.botao_atualizar.on('click', function(ev) {
        //     ev.preventDefault();
        //     $('p.loading-timer').fadeOut();
        //     consultar_status();
        // });

        consultar_primeirabase();
    }

    function consultar_primeirabase() {
        ajax_beforesend();

        momento = 'primeirabase';
        $('p.loading-text').html('Consultando a primeira base da VALID ...');

        var data_args = {
        	'cpf': cpf
        };

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/biometrias/primeirabase',
            data: data_args,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                uuid = retorno.uuid;
                iniciar_countdown();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    }

    function consultar_segundabase() {
        ajax_beforesend();

        tentativas = 1;
        momento = 'segundabase';
        $('p.loading-timer').fadeOut();
        $('p.loading-text').html('Consultando a segunda base da VALID ...');

        var data_args = {
        	'uuid': uuid,
        	'cpf': cpf
        };

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/biometrias/segundabase',
            data: data_args,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                iniciar_countdown();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    }

    function consultar_status() {
        var data_args = {
            'uuid': uuid
        };

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/biometrias/status',
            data: data_args,
            success: function(retorno) {
                if (retorno.status=='sucesso') {
                    if (retorno.biometria==true) {
                        window.location=retorno.url_resultado;
                    } else {
                        switch(momento) {
                            case 'primeirabase':
                                consultar_segundabase();
                                break;
                            case 'segundabase':
                                window.location=retorno.url_resultado;
                                break;
                        }
                    }
                } else {
                    tentativas++;
                    iniciar_countdown();
                }
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    }

    function iniciar_countdown() {
        var now = new Date();
        if (tentativas==1) {
            var segundos_add = segundos
        } else {
            var segundos_add = (tentativas*tentativas_fator)*segundos
        }
        now.setSeconds(now.getSeconds() + segundos_add);

        var final_date = now.getFullYear()+'/'+
                         (now.getMonth()+1).zeropad()+'/'+
                         (now.getDate()).zeropad()+' '+
                         (now.getHours()).zeropad()+":"+
                         (now.getMinutes()).zeropad()+':'+
                         (now.getSeconds()).zeropad();

        var finished = false;
        $('p.loading-timer').fadeIn();
        $('p.loading-timer>span').countdown(final_date, function(event) {
            $(this).html(
                event.strftime('%T')
            );
        }).on('finish.countdown', function() {
            if (!finished) {
                finished = true;
                consultar_status();
            }
        });
    }

    return {
        init: init
    };
})();
