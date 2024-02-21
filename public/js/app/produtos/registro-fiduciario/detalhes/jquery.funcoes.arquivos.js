$(document).ready(function() {
    $('div#registro-fiduciario-arquivos').on('show.bs.modal', function (ev) {
        $(this).find('.modal-title').text($(ev.relatedTarget).data('title') ? $(ev.relatedTarget).data('title') : 'Arquivos');

        carregar_arquivos($(this), $(ev.relatedTarget).data('idregistro'), $(ev.relatedTarget).data('idtipoarquivo'), $(ev.relatedTarget).data('idparte'));
    });

    $('div#registro-fiduciario-arquivos').on('click', 'button.salvar-arquivos', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-arquivos]');
        var obj_modal = $(this).closest('.modal');

        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_tipo_arquivo_grupo_produto = form.find('input[name=id_tipo_arquivo_grupo_produto]').val();
        var id_registro_fiduciario_parte = form.find('input[name=id_registro_fiduciario_parte]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/arquivos',
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'sucesso':
                        var alerta = swal({title: 'Sucesso!', html: retorno.message, type: 'success'});
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.message, "warning");
                        break;
                    default:
                        var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                        break;
                }
                alerta.then(function () {
                    location.reload();
                    /*if (retorno.recarrega == 'true') {
                        carregar_arquivos(obj_modal, id_registro_fiduciario, id_tipo_arquivo_grupo_produto, id_registro_fiduciario_parte);
                    }*/
                    ajax_success();
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-arquivos').on('click', 'table.arquivos td.acoes button.remover', function(ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-arquivos]');
        var obj_modal = $(this).closest('.modal');

        var id_arquivo_grupo_produto = $(this).data('idarquivo');

        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();
        var id_tipo_arquivo_grupo_produto = form.find('input[name=id_tipo_arquivo_grupo_produto]').val();
        var id_registro_fiduciario_parte = form.find('input[name=id_registro_fiduciario_parte]').val();

        swal({
            title: 'Tem certeza?',
            html: 'A remoção do arquivo não poderá ser desfeita!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            confirmButtonClass: 'btn btn-success btn-lg ml-3',
            cancelButtonClass: 'btn btn-danger btn-lg ml-3',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                data_args = {
                    'id_arquivo_grupo_produto': id_arquivo_grupo_produto,
                    'id_registro_fiduciario': id_registro_fiduciario,
                    'id_tipo_arquivo_grupo_produto': id_tipo_arquivo_grupo_produto,
                    'id_registro_fiduciario_parte': id_registro_fiduciario_parte
                }

                $.ajax({
                    type: "DELETE",
                    url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/arquivos',
                    data: data_args,
                    beforeSend: function() {
                        ajax_beforesend();
                    },
                    success: function(retorno) {
                        switch (retorno.status) {
                            case 'sucesso':
                                var alerta = swal({title: 'Sucesso!', html: retorno.message, type: 'success'});
                                break;
                            case 'alerta':
                                var alerta = swal("Ops!", retorno.message, "warning");
                                break;
                            default:
                                var alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
                                break;
                        }
                        alerta.then(function() {
                            if (retorno.recarrega == 'true') {
                                carregar_arquivos(obj_modal, id_registro_fiduciario, id_tipo_arquivo_grupo_produto, id_registro_fiduciario_parte);
                            }
                            ajax_success();
                        });
                    },
                    error: function(ev, xhr, settings, error) {
                        ajax_error(ev);
                    }
                });
            }
        });
    });
});
function carregar_arquivos(obj_modal, id_registro_fiduciario, id_tipo_arquivo_grupo_produto, id_registro_fiduciario_parte) {
    data_args = {
        'id_tipo_arquivo_grupo_produto': id_tipo_arquivo_grupo_produto,
        'id_registro_fiduciario_parte': id_registro_fiduciario_parte
    }

    $.ajax({
        type: "GET",
        url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/arquivos',
        data: data_args,
        context: this,
        beforeSend: function() {
            ajax_beforesend();
        },
        success: function(retorno) {
            obj_modal.find('.modal-body form').html(retorno);
            ajax_success();

            // Verificar se salvar será permitido
            if (obj_modal.find('.modal-body').find('div#arquivos').length>0) {
                obj_modal.find('.modal-footer').find('.salvar-arquivos').show();
            } else {
                obj_modal.find('.modal-footer').find('.salvar-arquivos').hide();
            }
        },
        error: function(ev, xhr, settings, error) {
            ajax_error(ev, obj_modal, true);
        }
    });
}
