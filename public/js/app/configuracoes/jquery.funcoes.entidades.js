$(document).ready(function() {

    $('form[name=form-banco-filtro]').on('change','select[name=id_estado]',function(e) {
        var form = $(this).closest('form');

        var data_args = {
            'id_estado': $(this).val(),
        };

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/cidade/lista',
            data: data_args,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(cidades) {
                form.find('select[name=id_cidade]').html('');
                if (cidades.length>0) {
                    $.each(cidades,function(key, cidade) {
                        form.find('select[name=id_cidade]').append('<option value="'+cidade.id_cidade+'">'+cidade.no_cidade+'</option>');
                    });
                    form.find('select[name=id_cidade]').prop('disabled', false);
                } else {
                    form.find('select[name=id_cidade]').prop('disabled', true);
                }
                form.find('select[name=id_cidade]').selectpicker('refresh');
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });
    //
    // $('div#novo-banco').on('show.bs.modal', function(e) {
    //     $.ajax({
    //         type: "POST",
    //         url: URL_BASE + 'app/bancos/novo-banco',
    //         context: this,
    //         beforeSend: function () {
    //             ajax_beforesend();
    //         },
    //         success: function (retorno) {
    //             $(this).find('.modal-body form[name="form-novo-banco"]').html(retorno);
    //             ajax_success();
    //         },
    //         error: function(ev, xhr, settings, error) {
    //             ajax_error(ev, $(this), true);
    //         }
    //     });
    // });
    //
    // $('div#alterar-banco, div#novo-banco').on('click', 'input[name=in_credor_fiduciario]', function(e) {
    //     form = $(this).closest('form');
    //
    //     if ($(this).val()=='S') {
    //         form.find('.novo-credor input[name!=senha_usuario]').attr('disabled', false);
    //         form.find('.novo-credor').slideDown();
    //     } else {
    //         form.find('.novo-credor input[name!=senha_usuario]').attr('disabled', true);
    //         form.find('.novo-credor').slideUp();
    //     }
    // });
    //
    // $('div#alterar-banco, div#novo-banco').on('click', 'input[name=in_usuario_existente]', function(e) {
    //     form = $(this).closest('form');
    //
    //     if ($(this).val()=='S') {
    //         form.find('.usuario-existente input').attr('disabled', false);
    //         form.find('.usuario-existente select').attr('disabled', false);
    //
    //         form.find(".novo-usuario").slideUp(function() {
    //             $(this).find('input[name!=senha_usuario]').attr('disabled', true);
    //             $(this).find('select').attr('disabled', true);
    //         });
    //         form.find('.usuario-existente').slideDown();
    //     } else {
    //         form.find('.novo-usuario input[name!=senha_usuario]').attr('disabled', false);
    //         form.find('.novo-usuario select').attr('disabled', false);
    //
    //         form.find(".usuario-existente").slideUp(function() {
    //             $(this).find('input[name!=senha_usuario]').attr('disabled', true);
    //             $(this).find('select').attr('disabled', true);
    //         });
    //         form.find('.novo-usuario').slideDown();
    //     }
    // });
    //
    // $('div#novo-banco, div#alterar-banco').on('change', 'select[name=id_estado]', function(e) {
    //     var form = $(this).closest('form');
    //
    //     var data_args = {
    //         'id_estado':$(this).val(),
    //     };
    //
    //     $.ajax({
    //         type: "POST",
    //         url: URL_BASE+'app/cidade/lista',
    //         data: data_args,
    //         context: this,
    //         beforeSend: function() {
    //             ajax_beforesend();
    //         },
    //         success: function(cidades) {
    //             form.find('select[name=id_cidade]').html('<option value="">Selecione uma cidade</option>');
    //             if (cidades.length>0) {
    //                 $.each(cidades,function(key,cidade) {
    //                     form.find('select[name=id_cidade]').append('<option value="'+cidade.id_cidade+'">'+cidade.no_cidade+'</option>');
    //                 });
    //                 form.find('select[name=id_cidade]').prop('disabled',false);
    //             } else {
    //                 form.find('select[name=id_cidade]').prop('disabled',true);
    //             }
    //             ajax_success();
    //         },
    //         error: function(ev, xhr, settings, error) {
    //             ajax_error(ev);
    //         }
    //     });
    // });
    //
    // $('div#novo-banco').on('click', 'button.enviar-banco', function(e) {
    //     var form = $('form[name=form-novo-banco]');
    //     var data = new FormData(form.get(0));
    //
    //     $.ajax({
    //         type: "POST",
    //         url: URL_BASE+'app/bancos/inserir-banco',
    //         data: data,
    //         contentType: false,
    //         processData: false,
    //         beforeSend: function() {
    //             ajax_beforesend();
    //         },
    //         success: function(retorno) {
    //             switch (retorno.status) {
    //                 case 'erro':
    //                     var alerta = swal("Erro!",retorno.message,"error");
    //                     break;
    //                 case 'sucesso':
    //                     var alerta = swal("Sucesso!",retorno.message,"success");
    //                     break;
    //                 case 'alerta':
    //                     var alerta = swal("Ops!",retorno.message,"warning");
    //                     break;
    //                 default:
    //                     var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
    //                     break;
    //             }
    //             alerta.then(function(){
    //                 if (retorno.recarrega == 'true') {
    //                     location.reload();
    //                 }
    //             });
    //             ajax_success();
    //         },
    //         error: function(ev, xhr, settings, error) {
    //             ajax_error(ev);
    //         }
    //     });
    // });
    //
    // $('div#novo-banco, div#alterar-banco').on('blur', 'input[name=nu_cep]', function(e) {
    //     var form = $(this).closest('form');
    //     var cep = $(this).val().replace(/[^\d]+/g,'');
    //
    //     if (cep!='' && cep.length==8) {
    //         var cep_url = "https://viacep.com.br/ws/" + cep + "/json/";
    //
    //         $.ajax({
    //             url: cep_url,
    //             type: "GET",
    //             dataType: "jsonp",
    //             crossOrigin: true,
    //             crossDomain: true,
    //             contentType: "application/json; charset=utf-8",
    //             beforeSend: function () {
    //                 ajax_beforesend();
    //             },
    //             success: function(response){
    //                 form.find('select[name=id_estado]').find('option[data-uf="' + response.uf + '"]').attr('selected', true);
    //                 carregar_cidades(form.find('select[name=id_cidade]'), 0, 0, response.uf, response.localidade, true);
    //
    //                 form.find('input[name=no_endereco]').val(response.logradouro);
    //                 form.find('input[name=no_bairro]').val(response.bairro);
    //                 form.find('input[name=no_complemento]').val(response.complemento);
    //                 ajax_success();
    //             },
    //             error: function(ev, xhr, settings, error) {
    //                 ajax_error(ev);
    //             }
    //         });
    //     } else {
    //         form.find('select[name=id_estado]').val('').trigger('change');
    //
    //         form.find('input[name=no_endereco]').val('');
    //         form.find('input[name=nu_endereco]').val('');
    //         form.find('input[name=no_bairro]').val('');
    //         form.find('input[name=no_complemento]').val('');
    //     }
    // });
    //
    // $('div#alterar-banco').on('show.bs.modal', function(e) {
    //     var id_pessoa = $(e.relatedTarget).data('idbanco');
    //     var no_pessoa = $(e.relatedTarget).data('nobanco');
    //
    //     $(this).find('.modal-header h4.modal-title span').html(no_pessoa);
    //
    //     $.ajax({
    //         type: "POST",
    //         url: URL_BASE + 'app/bancos/alterar-banco',
    //         data: {'id_pessoa':id_pessoa},
    //         context: this,
    //         beforeSend: function () {
    //             ajax_beforesend();
    //         },
    //         success: function (retorno) {
    //             $(this).find('.modal-body form').html(retorno);
    //             ajax_success();
    //         },
    //         error: function(ev, xhr, settings, error) {
    //             ajax_error(ev, $(this), true);
    //         }
    //     });
    // });
    // $('div#alterar-banco').on('click', 'button.alterar-banco', function(e) {
    //     var form = $('form[name=form-alterar-banco]');
    //     var data = new FormData(form.get(0));
    //
    //     $.ajax({
    //         type: "POST",
    //         url: URL_BASE+'app/bancos/salvar-banco',
    //         data: data,
    //         contentType: false,
    //         processData: false,
    //         beforeSend: function () {
    //             ajax_beforesend();
    //         },
    //         success: function (retorno) {
    //             switch (retorno.status) {
    //                 case 'erro':
    //                     var alerta = swal("Erro!",retorno.message,"error");
    //                     break;
    //                 case 'sucesso':
    //                     var alerta = swal("Sucesso!",retorno.message,"success");
    //                     break;
    //                 case 'alerta':
    //                     var alerta = swal("Ops!",retorno.message,"warning");
    //                     break;
    //                 default:
    //                     var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
    //                     break;
    //             }
    //             alerta.then(function(){
    //                 if (retorno.recarrega=='true') {
    //                     location.reload();
    //                 }
    //             });
    //             ajax_success();
    //         },
    //         error: function(ev, xhr, settings, error) {
    //             ajax_error(ev);
    //         }
    //     });
    // });

    $('div#detalhes-entidade').on('show.bs.modal', function(e) {
        var id_pessoa = $(e.relatedTarget).data('idpessoa');
        var no_pessoa = $(e.relatedTarget).data('nopessoa');

        $(this).find('.modal-header .modal-title span').html(no_pessoa);

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/entidades/' + id_pessoa,
            context: this,
            beforeSend: function () {
                ajax_beforesend();
            },
            success: function (retorno) {
                $(this).find('.modal-body').html(retorno);
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('form[name=form-entidade-filtro]').on('change','select[name=id_estado]',function(e) {
        
        var form = $(this).closest('form');

        var data_args = {
            'id_estado': $(this).val(),
        };

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/cidade/lista',
            data: data_args,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(cidades) {
                form.find('select[name=id_cidade]').html('');
                if (cidades.length>0) {
                    $.each(cidades,function(key, cidade) {
                        form.find('select[name=id_cidade]').append('<option value="'+cidade.id_cidade+'">'+cidade.no_cidade+'</option>');
                    });
                    form.find('select[name=id_cidade]').prop('disabled', false);
                } else {
                    form.find('select[name=id_cidade]').prop('disabled', true);
                }
                form.find('select[name=id_cidade]').selectpicker('refresh');
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

});
