
$(document).ready(function()
{	 
	/*
	 * Controle do dropdown do menu principal:
	 *		- Quando o usuário clicar em um menu que não possuir a classe "nav-unique"
	 *		  o evento padrão será interrompido, caso o menu clicado não estiver ativo
	 *		  os outros menus perderão o status de ativo e o menu clicado se tornará ativo.
	 */
	$('nav#navbar-menu').on('click','ul.navbar-nav>li.nav-item>a.nav-link',function(ev)
	{
		var li = $(this).parent('li');
		if (!li.hasClass('nav-unique'))
		{
			ev.preventDefault();

			if (!li.hasClass('active'))
			{
				$('nav#navbar-menu ul.navbar-nav>li.nav-item.active').removeClass('active');
				li.addClass('active');
			}
		}
	});

	/* Funções do filtro padrão:
	 * 		- Quando o botão do filtro é clicado, o objeto com a classe ".card-filter"
	 *		  que estiver no mesmo container do botão será exibido;
	 *		- Caso o usuário clique na opção cancelar, o formulário será reiniciado,
	 *		  porém, as bibliotecas de data e select devem ser reiniciadas manualmente;
	 *		- O botão do filtro fica oculto quando o formulário do filtro está ativo.
	 */
	$('div.card').on('click','div.buttons>button.abrir-filtro',function(ev)
	{
		var card = $(this).closest('.card');
		var btn_abrir_filtro = $(this);

		if (card.find('div.card-filter').length>0) {
			card.find('div.card-filter').slideDown('fast',function(ev)
			{
				$(this).addClass('active');
			});
			btn_abrir_filtro.hide();

			card.on('reset','div.card-filter form',function(ev)
			{
				card.find('div.card-filter').slideUp('fast',function(ev)
				{
					$(this).removeClass('active');
				});
				btn_abrir_filtro.show();

				card.find('div.card-filter .periodo input').each(function(ev)
				{
					$(this).datepicker('clearDates');
				});

				card.find('div.card-filter .selectpicker').each(function(ev)
				{
		        	$(this).val('').selectpicker('refresh');
		        });
			});
		}
	});
	$('div.card-filter').on('click','button.limpar-filtro',function(ev)
	{
		window.location.href=$(this).closest('form').attr('action');
	});

	/* Funções para troca de pessoa (entidade):
	 * 		- Trocar a pessoa ativa na sessão, isso possibilita a troca de entidade entre pessoas.
	 */
	$('div.pessoa-ativa div.dropdown-menu>a').click(function(ev) {
    	ev.preventDefault();

    	var data_args =
		{
			'key':$(this).data('key')
		};

    	$.ajax({
			type: "POST",
			url: URL_BASE+'app/usuario/troca-pessoa',
			data: data_args,
			beforeSend: function () {
				 ajax_beforesend();
			},
			success: function(retorno) {
				swal('Sucesso!','Troca efetuada com sucesso.','success').then(function() {
					location.reload();
				});
			},
			error: function (request, status, error) {
				ajax_error(ev);
			}
		});
    });
    // Menu fixado no topo
    var shrinkHeader = 100;
    $(window).scroll(function() {
        var scroll = getCurrentScroll();
        if (scroll >= shrinkHeader){
            $('.header').addClass('shrink');
            $('.sub-header').addClass('shrink');
        }
        else {
            $('.header').removeClass('shrink');
            $('.sub-header').removeClass('shrink');
        }
    });

    function getCurrentScroll() {
        return window.pageYOffset || document.documentElement.scrollTop;
    }

    // button return to top home page
    $(window).scroll(function () {
        if ($(this).scrollTop() > shrinkHeader) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

});

function carregar_cidades(obj_cidade, id_estado = 0, id_cidade = 0, uf_estado = '', no_cidade = '', readonly = false) {
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
			if (obj_cidade.hasClass('selectpicker')) {
				obj_cidade.html('');
			} else {
				obj_cidade.html('<option value="">Selecione</option>');
			}
            if (cidades.length>0) {
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
                obj_cidade.prop('disabled',true);
            }
			if (obj_cidade.hasClass('selectpicker')) {
				if (readonly) {
					obj_cidade.addClass('readonly');
				} else {
					obj_cidade.removeClass('readonly');
					obj_cidade.closest('.bootstrap-select').removeClass('readonly');
				}
				obj_cidade.selectpicker('refresh');
			} else {
				obj_cidade.attr('readonly', readonly);
			}
            ajax_success();
        },
        error: function(ev, xhr, settings, error) {
            ajax_error(ev);
        }
    });
}
