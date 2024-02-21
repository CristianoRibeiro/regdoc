$(document).ready(function() {

    $('form[name=form-certificado]').on('change','select[name=id_estado]',function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]')

        carregar_cidades(obj_cidade, $(this).val());
    });

    $('form[name=form-certificado]').on('blur', 'input[name=cep]', function(e){
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
                beforeSend: function() {
                    ajax_beforesend();
                },
                success: function(response){
                    if (!response.erro) {
                        id_estado = form.find('select[name=id_estado]').find('option[data-uf="' + response.uf + '"]').val();
                        form.find('select[name=id_estado]').val(id_estado);

                        form.find('select[name=id_estado]').addClass('readonly');
                        form.find('select[name=id_cidade]').addClass('readonly');
                        carregar_cidades(form.find('select[name=id_cidade]'), 0, 0, response.uf, response.localidade, true);

                        form.find('input[name=endereco]').val(response.logradouro);
                        form.find('input[name=bairro]').val(response.bairro);
                    } else {
                        form.find('select[name=id_estado]').val('0').trigger('change').removeClass('readonly');
                        form.find('select[name=id_estado]').closest('div').removeClass('readonly');

                        form.find('select[name=id_cidade]').removeClass('readonly');
                        form.find('select[name=id_cidade]').closest('div').removeClass('readonly');

                        form.find('input[name=endereco]').val('');
                        form.find('input[name=bairro]').val('');
                    }
                    form.find('select[name=id_estado]').selectpicker('refresh');
                    form.find('select[name=id_cidade]').selectpicker('refresh');

                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_estado]').val('0').trigger('change').removeClass('readonly');
            form.find('select[name=id_estado]').closest('div').removeClass('readonly');
            form.find('select[name=id_estado]').selectpicker('refresh');

            form.find('select[name=id_cidade]').removeClass('readonly');
            form.find('select[name=id_cidade]').closest('div').removeClass('readonly');
            form.find('select[name=id_cidade]').selectpicker('refresh');

            form.find('input[name=endereco]').val('');
            form.find('input[name=numero]').val('');
            form.find('input[name=bairro]').val('');
        }
    })

    $('form[name=form-certificado]').on('click', 'button.enviar-cadastro', function (e) {
        e.preventDefault();

        ajax_beforesend();

        $('form[name=form-certificado]').submit();
    });
});

function carregar_cidades(obj_cidade, id_estado = 0, id_cidade = 0, uf_estado = '', no_cidade = '') {
    var data_args = {
        'id_estado': id_estado,
        'uf_estado': uf_estado,
    };

    $.ajax({
        type: "POST",
        url: URL_BASE+'app/cidade/lista',
        data: data_args,
        async: false,
        beforeSend: function () {
            ajax_beforesend();
        },
        success: function(cidades) {
            if (cidades.length>0) {
                obj_cidade.html('');
                $.each(cidades,function(key, cidade) {
                    selected = ''
                    if (id_cidade>0) {
                        if (cidade.id_cidade == id_cidade) {
                            selected = 'selected="selected"';
                        }
                    } else if (no_cidade!='') {
                        if (cidade.no_cidade == no_cidade) {
                            selected = 'selected="selected"';
                        }
                    }
                    obj_cidade.append('<option value="'+cidade.id_cidade+'" ' + selected + '>'+cidade.no_cidade+'</option>');
                });
                obj_cidade.prop('disabled',false);
            } else {
                obj_cidade.html('');
                obj_cidade.prop('disabled',true);
            }
            if (obj_cidade.hasClass('selectpicker')) {
                obj_cidade.selectpicker('refresh');
            }
            ajax_success();
        },
        error: function(ev, xhr, settings, error) {
            ajax_error(ev);
        }
    });
}
