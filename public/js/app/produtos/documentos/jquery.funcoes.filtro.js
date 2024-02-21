$(document).ready(function() {
    $('form[name=form-documentos-filtro]').on('change','select[name=id_pessoa_origem]',function(ev) {
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
                        form.find('select[name=id_usuario_cad]').append('<option value="'+usuarios.id_usuario+'">'+usuarios.no_usuario+' '+(usuarios.in_documento_ativo=='N'?'(Desabilitado)':'')+'</option>');
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
