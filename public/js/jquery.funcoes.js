// Valor inicial do zindex dos modais
var zindex = 1040;

$(document).ready(function() {
	// Configurações de padrões da biblioteca blockUI
	$.blockUI.defaults.css.background = '';
	$.blockUI.defaults.css.border = 'none';
	$.blockUI.defaults.css.width = '100%';
	$.blockUI.defaults.css.left = '0';
	$.blockUI.defaults.baseZ = 2000;
	$.blockUI.defaults.message = $('div#loading>div');

	/* Configuração global do ajax
	 * 		- É necessário configurar o header do ajax para que passe o TOKEN necessário
	 *		  em todas as transações do tipo POST com AJAX para o Laravel.
	 */
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Inicia as máscaras padrões do sistema
    iniciar_mascaras();

	// Iniciar a biblioteca datepicker.
    iniciar_datepicker();

    // Iniciar a biblioteca autoNumeric.
    iniciar_autonumeric();

	// Iniciar tooltip
	$('.btn-tooltip').tooltip();

	// Iniciar a biblioteca DataTable
	init_datatable();

	// Iniciar a biblioteca Multiselect
	iniciar_multiselect();

	/*
     * Interceptar as requisições quando a requisição obtém sucesso:
     * 		- Iniciar as máscaras para conteúdos que forem carregados por AJAX;
     * 		- Iniciar a biblioteca datepicker para conteúdos que forem carregados por AJAX;
     *		- Iniciar a biblioteca autoNumeric.
	 *		- Iniciar a biblioteca selectpicker
	 *		- Iniciar tooltip
	 *		- Iniciar a biblioteca multiselect
     */
    $(document).ajaxSuccess(function(ev) {
    	iniciar_mascaras();
    	iniciar_datepicker();
    	iniciar_autonumeric();
    	$('.selectpicker').selectpicker('render');
		$('.btn-tooltip').tooltip();
		$('[data-toggle="tooltip"]').tooltip();
		iniciar_multiselect();
    });

	/*
     * Gerar log de todo conteúdo resultante de um AJAX:
     * 		- Caso o sistema esteja com o DEBUG ativado no arquivo ".env",
     * 		  todo resultado de requisições AJAX serão exibidas no console log
     * 		  quando completadas.
     */
    if (APP_DEBUG) {
	    $(document).ajaxComplete(function(event, xhr) {
	    	console.log(xhr.responseText);
		});
	}

	/* Controle dos modais no evento "show":
	 *		- Quando um modal é iniciado, é setado um z-index para ele e a variável
	 *		  global "zindex" é incrementada para que o próximo modal funcione perfeitamente;
	 *		- Junto com o modal, é criado o modal-backdrop para o fundo.
	 */
	$(document).on('show.bs.modal', '.modal', function(ev) {
	    $(this).css('z-index',zindex+1);

		setTimeout(function() {
	        $('.modal-backdrop').not('.modal-stack').css('z-index', zindex).addClass('modal-stack');
	        zindex += 10;
	    }, 0);
	});
	/* Controle dos modais no evento "hidden":
	 *		- Quando um modal é fechado, o modal-backdrop referente à ele é removido. Caso
	 *		  tenham mais de 1 modal aberta neste momento (contando com a que está fechando),
	 *		  a classe 'modal-open' é adicionada novamente para não "bugar" a barra de rolagem;
	 *		- Além disso, é corrigido o "padding-right" que o bootstrap remove  automaticamente
	 *		  quando um modal é aberto/fechado;
	 *		- Quando um modal é fechado é setado a classe "carregando" é removida e o conteúdo é
	 *		  apagado, para que no próximo carregamento a modal esteja limpa.
	 */
	$(document).on('hidden.bs.modal', '.modal', function(ev) {
		$(document.body).css('padding-right','0');
		if ($('.modal:visible').length) {
			$(document.body).addClass('modal-open');
			$(document).height()>$(window).height() && $(document.body).css('padding-right','17px');
		} else {
			$(document.body).css('padding-right','0');
		}
		zindex -= 10;

		if ($(this).find('.modal-body form').length>0) {
			$(this).find('.modal-body form').html('');
		} else {
			$(this).find('.modal-body').html('');
		}

		$.unblockUI()
	});

	// Configurações de padrões da biblioteca Sweet Alert 2
	swal.setDefaults({confirmButtonClass : 'btn'});

	/* Habilita o Tooltip do Bootstrap 4
	 *		- Para utilizar, basta adicionar os seguintes atributos no elemento:
	 *			+ data-toggle="tooltip" = Ativa tooltip no elemento (obrigatório)
	 *			+ data-placement="top" = Define a posição de onde o tooltip vai ficar (opcional)
	 *			+ title="Texto do tooltip" = Define o texto do tooltip (obrigatório)
	 *		- Outras opções estão disponíveis em:
	 *			+ http://getbootstrap.com/docs/4.0/components/tooltips/#options
	*/
	$('[data-toggle="tooltip"]').tooltip();

	// Define se o sistema está sendo acessado pelo celular
	isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

	$(document).on('click', 'a.copiar-link', function (e) {
        e.preventDefault();
        var temp = $("<div>");
        $("body").append(temp);
        temp.attr("contenteditable", true)
             .html($(this).attr('href')).select()
             .on("focus", function() { document.execCommand('selectAll',false,null); })
             .focus();
        document.execCommand("copy");
        temp.remove();
        swal("Sucesso!","Link copiado para a área de transferência com sucesso.", "success");
    });
});
function iniciar_mascaras() {
	$('.cpf').mask('000.000.000-00');
	$('.cnpj').mask('00.000.000/0000-00');
	$('.protocolo').mask('0000.0000.0000.0000');
	$('.celular').mask('(00) 00000-0000');
	$('.cep').mask('00000-000');
	$('.ddd').mask('00');
	$(".telefone").mask("0000-00009");
	$(".telefoneDDD").mask("(00) 0000-00009");
	$(".hora").mask("00:00");
	$(".codigo-seguranca").mask("00000000");

	var TelMaskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	TelMaskOptions = {
		onKeyPress: function(val, e, field, options) {
			field.mask(TelMaskBehavior.apply({}, arguments), options);
		}
	};
	$('.telefone_celular').mask(TelMaskBehavior, TelMaskOptions);

	// Mascara de CPF e CNPJ
	var CpfCnpjMaskBehavior = function (val) {
		return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
	},
	cpfCnpjpOptions = {
		onKeyPress: function(val, e, field, options) {
	       field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
	    }
	};
    $('.cpf_cnpj').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
}
function iniciar_datepicker() {
	$('.data').datepicker({
		language: 'pt-BR',
		format: 'dd/mm/yyyy',
		autoclose: true,
		zIndexOffset: 15
	});

	$('.data').datepicker().on('show.bs.modal', function (e) {
		e.stopPropagation();
	});

	$('.periodo').datepicker({
		language: 'pt-BR',
		format: 'dd/mm/yyyy',
		endDate: 'today',
		autoclose: true,
		zIndexOffset: 15
	});

	$('.periodo').datepicker().on('show.bs.modal', function (e) {
		e.stopPropagation();
	});

	$('.data_ate_hoje').datepicker({
		language: 'pt-BR',
		format: 'dd/mm/yyyy',
		endDate: 'today',
		autoclose: true,
		zIndexOffset: 15
	});

	$('.data_ate_hoje').datepicker().on('show.bs.modal', function (e) {
		e.stopPropagation();
	});
}
function iniciar_autonumeric() {
	$('.real').autoNumeric('init', {vMin:'0', aSep: '.', aDec: ',', aSign: 'R$ '});
	$('.porcent').autoNumeric('init', {vMin:'-100', mDec:'2', aDec:',', aSep:'.', vMax:'100', aSign: ' %', pSign: 's'});
	$('.porcent-4casas').autoNumeric('init', {vMin:'-100', mDec:'4', aDec:',', aSep:'.', vMax:'100', aSign: ' %', pSign: 's'});
	$('.porcent-pos').autoNumeric('init', {vMin:'0', mDec:'2', aDec:',', aSep:'.', vMax:'100', aSign: ' %', pSign: 's'});
	$('.porcent-neg').autoNumeric('init', {vMin:'-100', mDec:'2', aDec:',', aSep:'.', vMax:'0', aSign: ' %', pSign: 's'});
	$('.numero100').autoNumeric('init', {vMin:'0', mDec:'0', aDec:',', aSep:'.', vMax:'100'});
	$('.numero').autoNumeric('init', {vMin:'0', mDec:'0', aDec:',', aSep:'.'});
	$('.numero-s-ponto').autoNumeric('init', {vMin:'0', mDec:'0', aDec:',', aSep:''});
}
function remove_caracteres(text) {
	text = text.replace(/[áàâãªä]/g, 'a')
			   .replace(/[ÁÀÂÃÄ]/g, 'A')
			   .replace(/[ÍÌÎÏ]/g, 'I')
			   .replace(/[íìîï]/g, 'i')
			   .replace(/[éèêë]/g, 'e')
			   .replace(/[ÉÈÊË]/g, 'E')
			   .replace(/[óòôõºö]/g, 'o')
			   .replace(/[ÓÒÔÕÖ]/g, 'O')
			   .replace(/[úùûü]/g, 'u')
			   .replace(/[ÚÙÛÜ]/g, 'U')
			   .replace(/[ç]/g, 'c')
			   .replace(/[Ç]/g, 'C')
			   .replace(/ñ/g, 'n')
			   .replace(/Ñ/g, 'N')
			   .replace(/–/g, '-')
			   .replace(/[’‘‹›‚]/g, '')
			   .replace(/[“”«»„]/g, '')
			   .replace(/ /g, '_');

	return text;
}

function ajax_beforesend() {
	$.blockUI();
}

function ajax_success(titulo, modal) {
	if (titulo) {
		modal.find('.modal-title>span').html(titulo);
	}
	$.unblockUI();
}
function ajax_error(ev, modal, hide_modal) {
	tipo = 'error';
	titulo = 'Erro!';
	reload = false;
	switch (ev.status) {
		case 419:
			msg = 'Houve um problema na resposta do servidor, clique em OK para atualizar a página.';
			reload = true;
			break;
		case 422:
			tipo = 'warning';
			titulo = 'ATENÇÃO';
			msg = 'Alguns campos não foram devidamente preenchidos: <br /><br />';
			if (typeof ev.responseJSON.Erros !== 'undefined') {
				errors_array = ev.responseJSON.Erros;
			} else if (typeof ev.responseJSON.errors !== 'undefined') {
				errors_array = ev.responseJSON.errors;
			}
			$.each(errors_array,function(key,erro) {
				msg += erro+'<br />';
			});
			break;
		case 400:
			tipo = 'warning';
			titulo = 'ATENÇÃO';
			msg = ev.responseJSON.message;
			break;
		default:
			msg = 'Por favor, tente novamente mais tarde. Erro '+ev.status;
			if (APP_DEBUG) {
				if (typeof ev.responseJSON !== 'undefined') {
					if (ev.responseJSON.message.length<=0) {
						msg += ' ('+ev.statusText+')';
					} else {
						msg += '<br /><br />'+ev.responseJSON.message;
					}
				} else if (typeof ev.statusText !== 'undefined') {
					msg += ' ('+ev.statusText+')';
				} else {
					msg += ' (Erro desconhecido)';
				}
			}
			break;
	}

	$.unblockUI();

	swal({
		title: titulo,
		html: msg,
		type: tipo,
	}).then(function () {
		if (modal) {
			if (hide_modal) {
				modal.modal('hide');
			}
		}
		if (reload) {
			location.reload();
		}
	});
}
function form_error(erros) {
	swal('Ops!', 'Alguns campos não foram devidamente preenchidos: <br /><br />'+erros.join('<br />'), 'warning');
}
function init_datatable() {
	$('.datatable').DataTable({
        "order": [],
		"language": {
			"url": URL_BASE + "js/libs/i18n/datatables.pt-BR.json"
		}
	});
}
function iniciar_multiselect() {
	$('.multiselect').each(function() {
		var multiselect_from = $(this).find('select.multiselect-from').attr('id');

		$('#' + multiselect_from).multiselect();
	});
}
Number.prototype.zeropad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {
		s = "0" + s;
	}
    return s;
}
