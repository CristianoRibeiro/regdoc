$(document).ready(function() {
    $('form[name=form-registro-filtro]').on('change', 'select[name=id_estado_cartorio]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var id_estado = $(this).val();
        var obj_cidade = form.find('select[name=id_cidade_cartorio]');

        carregar_cidades(obj_cidade, id_estado);
    });

    $('form[name=form-registro-filtro]').on('change', 'select[name=id_cidade_cartorio]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var id_cidade = $(this).val();
        if (id_cidade>0) {
            var data_args = {
                'id_tipo_pessoa': [2],
                'id_tipo_serventia': [1],
                'id_cidade': id_cidade
            };
            $.ajax({
                type: "POST",
                url: URL_BASE+'app/pessoa/lista',
                data: data_args,
                beforeSend: function () {
                    ajax_beforesend();
                },
                success: function(pessoas) {
                    if (pessoas.length>0) {
                        HTML = '';
                        $.each(pessoas, function (key, pessoa) {
                            HTML += '<option value="'+pessoa.id_pessoa+'">'+pessoa.no_pessoa+'</option>';
                        });
                        form.find('select[name=id_pessoa_cartorio]').html(HTML).prop('disabled', false);
                    } else {
                        form.find('select[name=id_pessoa_cartorio]').html('').prop('disabled', true);
                    }
                    form.find('select[name=id_pessoa_cartorio]').selectpicker('refresh');

                    ajax_success()
                },
                error: function(ev, xhr, settings, error) {
                    $.unblockUI();
                    ajax_error(ev);
                }
            });
        } else {
            form.find('select[name=id_pessoa_cartorio]').html('').prop('disabled', true);
            form.find('select[name=id_pessoa_cartorio]').selectpicker('refresh');
        }
    });

    $('form[name=form-registro-filtro]').on('change','select[name=id_pessoa_origem]',function(ev) {
        ev.preventDefault();
        var obj_modal = $(this).closest('.modal');
        var form = $(this).closest('form');

        var data_args = {
            'id_pessoa_origem': $(this).val()
        };

        $.ajax({
            type: "POST",
            url: URL_BASE+'app/usuario/listar',
            data: data_args,
            context: this,
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(usuarios) {
                form.find('select[name=id_usuario_cad]').html('');
                if (usuarios.length>0) {
                    $.each(usuarios,function(key, usuarios) {
                        form.find('select[name=id_usuario_cad]').append('<option value="'+usuarios.id_usuario+'">'+usuarios.no_usuario+' '+(usuarios.in_registro_ativo=='N'?'(Desabilitado)':'')+'</option>');
                    });
                    form.find('select[name=id_usuario_cad]').prop('disabled', false);
                } else {
                    form.find('select[name=id_usuario_cad]').prop('disabled', true);
                }
                form.find('select[name=id_usuario_cad]').selectpicker('refresh');
                ajax_success();
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });
});
