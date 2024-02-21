$(document).ready(function() {
    $('form[name=form-calculadora]').on('change', 'select[name=id_produto]', function(ev) {
        var form = $(this).closest('form');
        var id_produto = $(this).val();

        ocultar_todos(form);
        form.find('select[name=id_estado]').trigger('change');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/calculadora/tipos-registro',
            data: {
                id_produto: id_produto
            },
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                if (retorno.tipos_registro.length>0) {
                    HTML = '';
                    $.each(retorno.tipos_registro, function (key, tipo_registro) {
                        HTML += '<option value="'+tipo_registro.id_registro_fiduciario_tipo+'">'+tipo_registro.no_registro_fiduciario_tipo+'</option>';
                    });
                    form.find('select[name=id_registro_fiduciario_tipo]').html(HTML).prop('disabled', false);
                } else {
                    form.find('select[name=id_registro_fiduciario_tipo]').html('').prop('disabled', true);
                }
                form.find('select[name=id_registro_fiduciario_tipo]').selectpicker('refresh');

                if (retorno.estados_disponiveis.length>0) {
                    HTML = '';
                    $.each(retorno.estados_disponiveis, function (key, estado) {
                        HTML += '<option value="'+estado.id_estado+'">'+estado.no_estado+'</option>';
                    });
                    form.find('select[name=id_estado]').html(HTML).prop('disabled', false);
                } else {
                    form.find('select[name=id_estado]').html('').prop('disabled', true);
                }
                form.find('select[name=id_estado]').selectpicker('refresh');

                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('form[name=form-calculadora]').on('change', 'select[name=id_registro_fiduciario_tipo]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');

        form.find('div.cartorio').slideDown('fast');
    });

    $('form[name=form-calculadora]').on('change', 'select[name=id_estado]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade]');

        form.find('div.variaveis, div.botoes').slideUp('fast');
        $('div.resultado').slideUp('fast');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
        obj_cidade.trigger('change');
    });

    $('form[name=form-calculadora]').on('change', 'select[name=id_cidade]', function(ev) {
        var form = $(this).closest('form');
        var id_cidade = $(this).val();
        var id_produto = form.find('select[name=id_produto]').val();

        switch (id_produto) {
            case '25':
                id_tipo_serventia = [1, 10];
                break;
            case '26':
                id_tipo_serventia = [2, 3, 10];
                break;
        }

        if (id_cidade>0) {
            var data_args = {
                'id_tipo_pessoa': [2],
                'id_tipo_serventia': id_tipo_serventia,
                'id_cidade': id_cidade
            };
            $.ajax({
                type: "POST",
                url: URL_BASE+'app/pessoa/lista',
                data: data_args,
                beforeSend: function() {
                    ajax_beforesend();
                },
                success: function(pessoas) {
                    if (pessoas.length>0) {
                        HTML = '';
                        $.each(pessoas, function (key, pessoa) {
                            HTML += '<option value="'+pessoa.id_pessoa+'">'+pessoa.no_pessoa+'</option>';
                        });
                        form.find('select[name=id_pessoa]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_pessoa]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_pessoa]').selectpicker('refresh');

                    ajax_success();
                },
                error: function(ev, xhr, settings, error) {
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_pessoa]').html('').prop('disabled', true);
            form.find('select[name=id_pessoa]').selectpicker('refresh');
        }
    });

    $('form[name=form-calculadora]').on('change', 'select[name=id_pessoa]', function(ev) {
        var form = $(this).closest('form');
        var id_pessoa = $(this).val();
        var id_produto = form.find('select[name=id_produto]').val();

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/calculadora/variaveis',
            data: {
                id_produto: id_produto,
                id_pessoa: id_pessoa
            },
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                if(retorno.valor_ato) {
                    form.find('div.variaveis div.valor-ato').show();
                    form.find('div.variaveis div.tamanho-imovel').hide();
                    form.find('div.variaveis, div.botoes').slideDown('fast');
                } else if (retorno.tamanho_imovel) {
                    form.find('div.variaveis div.valor-ato').hide();
                    form.find('div.variaveis div.tamanho-imovel').show();
                    form.find('div.variaveis, div.botoes').slideDown('fast');
                } else {
                    form.find('div.variaveis div.valor-ato, div.variaveis div.tamanho-imovel').hide();
                    form.find('div.variaveis, div.botoes').slideUp('fast');
                }

                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('form[name=form-calculadora]').on('submit', function(ev) {
        ev.preventDefault();
        var form = $(this);

        $.ajax({
            type: "POST",
            url: URL_BASE + 'app/produtos/calculadora',
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function (retorno) {
                if (retorno.nu_atos_cartoriais>0) {
                    HTML = '';
                    for(i=1;i<=retorno.nu_atos_cartoriais;i++) {
                        HTML += '<tr>';
                            HTML += '<td><b>' + i + '</b></td>';
                            HTML += '<td>Ato cartorial</td>';
                            HTML += '<td>' + retorno.valor_emolumento.toLocaleString('pt-br', {style: 'currency', currency: 'BRL'}) + '</td>';
                        HTML += '</tr>';
                    }

                    $('div.resultado').slideDown('fast');
                    $('div.resultado table>tbody').html(HTML);
                    $('div.resultado table>tfoot th.total').html(retorno.valor_total.toLocaleString('pt-br', {style: 'currency', currency: 'BRL'}));
                }

                ajax_success();
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });
});

function ocultar_todos(form) {
    form.find('div.cartorio, div.variaveis, div.resultado, div.botoes').slideUp('fast');
    $('div.resultado').slideUp('fast');
}
