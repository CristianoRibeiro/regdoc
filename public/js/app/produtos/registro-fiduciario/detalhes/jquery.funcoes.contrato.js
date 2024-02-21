$(document).ready(function() {
    $('div#registro-fiduciario-contrato').on('show.bs.modal', function (ev) {
        var id_registro_fiduciario = $(ev.relatedTarget).data('idregistro');

        $.ajax({
            type: "GET",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/contrato/editar',
            context: this,
            beforeSend: function() {
                ajax_beforesend()
            },
            success: function (retorno) {
                $(this).find('.modal-body form').html(retorno)
                ajax_success()
            },
            error: function (ev, xhr, settings, error) {
                ajax_error(ev, $(this), true);
            }
        });
    });

    $('div#registro-fiduciario-contrato').on('click', 'button.atualizar-contrato', function (ev) {
        ev.preventDefault();
        var form = $('form[name=form-registro-fiduciario-contrato]');
        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

        $.ajax({
            type: "PUT",
            url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/contrato',
            data: form.serialize(),
            beforeSend: function() {
                ajax_beforesend();
            },
            success: function(retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.message, "error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!", retorno.message, "success");
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
                        location.reload();
                    }
                    ajax_success();
                });
            },
            error: function(ev, xhr, settings, error) {
                ajax_error(ev);
            }
        });
    });

    $('div#registro-fiduciario-contrato').on('change', 'select[name=id_estado_contrato]', function(ev) {
        ev.preventDefault();
        var form = $(this).closest('form');
        var obj_cidade = form.find('select[name=id_cidade_contrato]');

        var id_estado = $(this).val();
        carregar_cidades(obj_cidade, id_estado);
    });

    $('div#registro-fiduciario-contrato').on('change', 'select[name=modelo_contrato]', function(ev) {
        var modelo_contrato = $(this).val();
        var obj_modal = $(this).closest('.modal');

        switch (modelo_contrato) {
            case 'SFH':
                obj_modal.find('.modelo-contrato.sfi, .modelo-contrato.pmcmv').slideUp('fast', function() {
                    obj_modal.find('.modelo-contrato.sfh').slideDown('fast');
                });
                break;
            case 'SFI':
                obj_modal.find('.modelo-contrato.sfh, .modelo-contrato.pmcmv').slideUp('fast', function() {
                    obj_modal.find('.modelo-contrato.sfi').slideDown('fast');
                });
                break;
            case 'PMCMV':
                obj_modal.find('.modelo-contrato.sfh, .modelo-contrato.sfi').slideUp('fast', function() {
                    obj_modal.find('.modelo-contrato.pmcmv').slideDown('fast');
                });
                break;
            default:
                obj_modal.find('.modelo-contrato.sfh, .modelo-contrato.sfi, .modelo-contrato.pmcmv').slideUp('fast');
                break;
        }
    });
});
