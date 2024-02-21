$(document).ready(function() {
    funcoes_menu();

    $('form[name=form-dados-pessoais]').on('submit', function(e) {
        e.preventDefault();

        var data = new FormData($(this).get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/minha-conta/salvar-dados-pessoais',
            data: data,
            contentType: false,
            processData: false,
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

    $('form[name=form-dados-acesso]').on('submit', function(e) {
        e.preventDefault();

        var data = new FormData($(this).get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/minha-conta/salvar-dados-acesso',
            data: data,
            contentType: false,
            processData: false,
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
                        window.location=URL_BASE+'app/sair/app';
                    }
                });
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('form[name=form-dados-serventia]').on('submit', function(e) {
        e.preventDefault();

        var data = new FormData($(this).get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/minha-conta/salvar-dados-serventia',
            data: data,
            contentType: false,
            processData: false,
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

    $('form[name=form-dados-api]').on('submit', function(e) {
        e.preventDefault();

        var data = new FormData($(this).get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/minha-conta/salvar-dados-api',
            data: data,
            contentType: false,
            processData: false,
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

    $('form[name=form-dados-seguranca]').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData($(this).get(0));

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/minha-conta/salvar-autenticacao',
            data: data,
            contentType: false,
            processData: false,
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
                        window.location = URL_BASE + 'app/sair/app';
                    }
                });
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('form[name=form-dados-pessoais], form[name=form-dados-serventia]').on('change', 'select[name=id_estado]', function(e) {
        var form = $(this).closest('form');

        var data_args = {
            'id_estado':$(this).val(),
        };

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/listar-cidades',
            data: data_args,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(cidades) {
                form.find('select[name=id_cidade]').html('<option value="">Selecione uma cidade</option>');
                if (cidades.length>0) {
                    $.each(cidades,function(key,cidade) {
                        form.find('select[name=id_cidade]').append('<option value="'+cidade.id_cidade+'">'+cidade.no_cidade+'</option>');
                    });
                    form.find('select[name=id_cidade]').prop('disabled',false);
                } else {
                    form.find('select[name=id_cidade]').prop('disabled',true);
                }
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('form[name=form-dados-pessoais], form[name=form-dados-serventia]').on('blur', 'input[name=nu_cep]', function(e) {
        var form = $(this).closest('form');
        var cep = $(this).val().replace(/[^\d]+/g,'');

        if (cep!='' && cep.length==8) {
            var cep_url = "https://viacep.com.br/ws/" + cep + "/json/";

            $.ajax({
                url: cep_url,
                type: "GET",
                dataType: "jsonp",
                crossOrigin: true,
                crossDomain: true,
                contentType: "application/json; charset=utf-8",
                beforeSend: function () {
                     ajax_beforesend();
                },
                success: function(response){
                    form.find('select[name=id_estado]').find('option[data-uf="' + response.uf + '"]').attr('selected', true);
                    carregar_cidades(form.find('select[name=id_cidade]'), 0, 0, response.uf, response.localidade, true);

                    form.find('input[name=no_endereco]').val(response.logradouro);
                    form.find('input[name=no_bairro]').val(response.bairro);
                    form.find('input[name=no_complemento]').val(response.complemento);
                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_estado]').val('').trigger('change');

            form.find('input[name=no_endereco]').val('');
            form.find('input[name=nu_endereco]').val('');
            form.find('input[name=no_bairro]').val('');
            form.find('input[name=no_complemento]').val('');
        }
    });

    $('form[name=form-dados-pessoais]').on('click', 'input[name=tp_pessoa]', function(e) {
        form = $(this).closest('form');

        switch ($(this).val()) {
            case 'F':
                form.find('.pessoa-fisica input').attr('disabled', false);
                form.find('.pessoa-fisica select').attr('disabled', false);

                form.find(".pessoa-juridica").slideUp(function() {
                    $(this).find('input').attr('disabled', true);
                    $(this).find('select').attr('disabled', true);
                });
                form.find('.pessoa-fisica').slideDown();
                break;
            case 'J':
                form.find('.pessoa-juridica input').attr('disabled', false);
                form.find('.pessoa-juridica select').attr('disabled', false);

                form.find('.pessoa-fisica').slideUp(function() {
                    $(this).find('input').attr('disabled', true);
                    $(this).find('select').attr('disabled', true);
                });
                form.find('.pessoa-juridica').slideDown();
                break;
        }
    });

    $('form[name=form-dados-pessoais], form[name=form-dados-serventia]').on('click', 'input[name=in_digitar_telefone]', function(e) {
        form = $(this).closest('form');

        if ($(this).is(":checked")) {
            form.find('div#campos-telefone input').attr('disabled', false);
            form.find('div#campos-telefone select').attr('disabled', false);
            form.find('div#campos-telefone').slideDown();
        } else {
            form.find('div#campos-telefone').slideUp(function() {
                form.find('div#campos-telefone input').attr('disabled', true);
                form.find('div#campos-telefone select').attr('disabled', true);
            });
        }
    });

    $('form[name=form-dados-pessoais], form[name=form-dados-serventia]').on('click', 'input[name=in_digitar_endereco]', function(e) {
        form = $(this).closest('form');

        if ($(this).is(":checked")) {
            form.find('div#campos-endereco input').attr('disabled', false);
            form.find('div#campos-endereco select').attr('disabled', false);
            form.find('div#campos-endereco').slideDown();
        } else {
            form.find('div#campos-endereco').slideUp(function() {
                form.find('div#campos-endereco input').attr('disabled', true);
                form.find('div#campos-endereco select').attr('disabled', true);
            });
        }
    });

    $('form[name=form-dados-serventia]').on('click', 'input[name=in_cartorio_cnpj]', function(e) {
        form = $(this).closest('form');

        switch ($(this).val()) {
            case 'S':
                form.find('input[name=nu_cpf_cnpj]').attr('disabled', false);
                break;
            case 'N':
                form.find('input[name=nu_cpf_cnpj]').val('').attr('disabled', true);
                break;
        }
    });


    $('form[name=form-dados-acesso-salvar]').on('submit', function(e) {
        e.preventDefault();

        var data = new FormData($(this).get(0));
        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/salvar-alterar-senha',
            data: data,
            contentType: false,
            processData: false,
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
                        window.location=URL_BASE+'/app/sair/app';
                    }
                });
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });


});

function funcoes_menu() {
    $('div#minha-conta .nav-pills').on('click', '.nav-link', function(e) {
        window.location.hash = $(this).attr('href');
    });

    hash = $(location).attr('hash');
    if (hash.length>0) {
        $('div#minha-conta .nav-pills>.nav-link.active.show').removeClass('active').removeClass('show');
        $('div#minha-conta .nav-pills>.nav-link[href="' + hash + '"]').addClass('active').addClass('show');
        $('div#minha-conta .tab-content>.tab-pane.active').removeClass('active').removeClass('show');
        $('div#minha-conta .tab-content>.tab-pane' + hash).addClass('active').addClass('show');
    }
}
