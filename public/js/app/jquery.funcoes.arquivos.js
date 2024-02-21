var index_arquivos = 0;

$(document).ready(function() {
	$('div#novo-arquivo').on('show.bs.modal', function (ev) {
    	var h_index_arquivos = ($('[name=hidden_index_arquivos]').val() > 0) ? $('[name=hidden_index_arquivos]').val() : index_arquivos;
		var id_tipo_arquivo_grupo_produto = $(ev.relatedTarget).data('idtipoarquivo');
		var container = $(ev.relatedTarget).data('container');
		var limite = $(ev.relatedTarget).data('limite');
		var token = $(ev.relatedTarget).data('token');
		var id_flex = $(ev.relatedTarget).data('idflex')?$(ev.relatedTarget).data('idflex'):0;
		var texto = $(ev.relatedTarget).data('texto')?$(ev.relatedTarget).data('texto'):'';
		var pasta = $(ev.relatedTarget).data('pasta')?$(ev.relatedTarget).data('pasta'):'outros';
		var extensoes = $(ev.relatedTarget).data('extensoes');
		var in_ass_digital = $(ev.relatedTarget).data('inassdigital')?$(ev.relatedTarget).data('inassdigital'):'';

		if (total_arquivo(container,'')<parseInt(limite) || limite=='0') {
			var data_args = {
				'id_tipo_arquivo_grupo_produto':id_tipo_arquivo_grupo_produto,
				'token':token,
				'id_flex':id_flex,
				'texto':texto,
				'container':container,
				'pasta':pasta,
				'index_arquivos':h_index_arquivos,
				'extensoes':extensoes,
				'in_ass_digital':in_ass_digital
			};
			$.ajax({
				type: "POST",
				url: URL_BASE+'app/arquivos/novo',
				data: data_args,
				context: this,
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(retorno) {
					$(this).find('.modal-body form').html(retorno);
					ajax_success();
				},
				error: function(ev, xhr, settings, error) {
			    	ajax_error(ev);
			    }
			});
		} else {
			swal("ATENÇÃO", "Você alcançou o limite de "+limite+" arquivo(s) para esta seção.", "warning");
			return false;
		}
	});

	$('form[name=form-novo-arquivo]').on('change','label.arquivo-upload input[type=file]',function(ev) {
		if (!$(this).val() == '') {
			var form = $(this).closest('form');

			var id_tipo_arquivo_grupo_produto = form.find('input[name=id_tipo_arquivo_grupo_produto]').val();
			var container = form.find('input[name=container]').val();
			var extensoes = form.find('input[name=extensoes]').val();
			var in_converter_pdf = form.find('input[name=in_converter_pdf]').val();

			var no_arquivo = $(this).val().split('\\');
			var no_arquivo = remove_caracteres(no_arquivo[no_arquivo.length-1]);
			var ext_arquivo = no_arquivo.split('.');
			var ext_arquivo = ext_arquivo[ext_arquivo.length-1];
			if (extensoes!='') {
				var ext_permitidas = extensoes.split('|');
			} else {
				var ext_permitidas = extensoes_permitidas(id_tipo_arquivo_grupo_produto);
			}
			if (ext_permitidas.indexOf(ext_arquivo.toLowerCase())<0) {
				swal("ATENÇÃO", "O arquivo selecionado é inválido, tipos de arquivo permitidos: <br /><br />."+ext_permitidas.join(', .'), "warning");
				$(this).replaceWith($(this).val('').clone(true));
				return false;
			} else if (total_arquivo(container,remove_caracteres(no_arquivo).toLowerCase())>0) {
				swal("ATENÇÃO", "O arquivo selecionado já foi inserido.", "warning");
				$(this).replaceWith($(this).val('').clone(true));
				return false;
			} else {
				form.find('div.msg-arquivo').slideUp();
				form.find('div.erros').slideUp();
				form.find('label.arquivo-upload h4').html(remove_caracteres(no_arquivo).toLowerCase());
				form.find('label.arquivo-upload h4').prop('title',remove_caracteres(no_arquivo).toLowerCase());
				switch (ext_arquivo) {
					case 'pdf':
						var classes_icone = 'fas fa-file-pdf';
						break;
					case 'png':
					case 'jpg':
					case 'bmp':
					case 'tif':
						var classes_icone = 'fas fa-file-image';
						if (in_converter_pdf=='S') {
							form.find('div.msg-arquivo').slideDown();
							form.find('div.msg-arquivo div.mensagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
						}
						break;
					case 'doc':
					case 'docx':
					case 'rtf':
						var classes_icone = 'fas fa-file-word';
						if (in_converter_pdf=='S') {
							form.find('div.msg-arquivo').slideDown();
							form.find('div.msg-arquivo div.mensagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
						}
						break;
					case 'xls':
					case 'xlsx':
						var classes_icone = 'fas fa-file-excel';
						if (in_converter_pdf=='S') {
							form.find('div.msg-arquivo').slideDown();
							form.find('div.msg-arquivo div.mensagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
						}
						break;
					case 'txt':
						var classes_icone = 'fas fa-file-alt';
						if (in_converter_pdf=='S') {
							form.find('div.msg-arquivo').slideDown();
							form.find('div.msg-arquivo div.mensagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
						}
						break;
					case 'ppt':
					case 'pptx':
					case 'pps':
					case 'ppsx':
						var classes_icone = 'fas fa-file-powerpoint';
						if (in_converter_pdf=='S') {
							form.find('div.msg-arquivo').slideDown();
							form.find('div.msg-arquivo div.mensagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
						}
						break;
					default:
						var classes_icone = 'material-icons';
						break;
				}
				form.find('label.arquivo-upload i').attr('class', classes_icone);
			}
		}
	});

	$('div#novo-arquivo').on('click','button.enviar-arquivo', function(ev) {
		var form = $('form[name=form-novo-arquivo]');
		var erros = new Array();
		var obj_modal = $(this).closest('.modal');

		if (form.find('input#arquivo-upload').val()=='') {
			erros.push('O arquivo é obrigatório.');
		}
		if (erros.length>0) {
			form_error(erros);
		} else {
			form.find('div.erros').slideUp();
			var data = new FormData(form.get(0));
			$.ajax({
				url: URL_BASE+'app/arquivos/inserir',
				type: 'POST',
				data: data,
				beforeSend: function() {
					ajax_beforesend();
					form.find('label.arquivo-upload').hide();
					form.find('div.progresso-upload').show();
				},
				success: function(retorno) {
					retorno_arquivo(retorno, obj_modal);
					var limite = $(retorno.arquivo.container + ' button.novo-arquivo').data('limite');

                    var container = $(retorno.arquivo.container + ' button.novo-arquivo').data('container');

                    if ((total_arquivo(container,'')>=parseInt(limite) && parseInt(limite) != 0 )) {
                        $(retorno.arquivo.container + ' button.novo-arquivo').hide();
					}
                    ajax_success();
				},
				error: function(ev, xhr, settings, error) {
					form.find('label.arquivo-upload').show();
					form.find('div.progresso-upload').hide();
			    	ajax_error(ev);
			    },
				cache: false,
				contentType: false,
				processData: false,
				xhr: function() {
					var myXhr = $.ajaxSettings.xhr();
					if (myXhr.upload) {
						myXhr.upload.addEventListener('progress', function (ev) {
							if (ev.lengthComputable) {
								var porcentagem = ev.loaded / ev.total;
								porcentagem = parseInt(porcentagem * 100);

								form.find('div.progresso-upload div.progress-bar').css('width',porcentagem+'%');

								if (porcentagem >= 100) {
									form.find('div.progresso-upload').hide();
								} else if (porcentagem > 10) {
									form.find('div.progresso-upload div.progress-bar').html(porcentagem+'%');
								}
							}
						}, false);
					}
					return myXhr;
				}
			});
		}
	});

	$('body').on('click','div.arquivo>button.remover',function(ev) {
		ev.preventDefault();
		var index_arquivo = $(this).data('indexarquivo');
		var token = $(this).data('token');

		var data_args = {
			'token':token,
			'index_arquivo':index_arquivo
		};
		$.ajax({
			type: "POST",
			url: URL_BASE+'app/arquivos/remover',
			data: data_args,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				swal("Sucesso!",retorno.msg,"success").then(function() {
                    $(retorno.arquivo.container + ' button.novo-arquivo').show();
	
					remove_arquivo(retorno,index_arquivo);

					ajax_success();
				});
			},
			error: function(ev, xhr, settings, error)  {
		    	ajax_error(ev);
		    },
		});
	});

	$(`body`).on(`click`, `td.multiplo-assinar > input[type=checkbox]` , (evt) => {

		const everyCheckbox = [...document.querySelectorAll(`td.multiplo-assinar > input[type=checkbox]`)];
		const button = document.querySelector(`#assinar-documentos-selecionados`);

		if(everyCheckbox.some((checkbox) => checkbox.checked)) {

			if(!button) {

				const modalFooter = document.querySelector(`#registro-fiduciario-arquivos div.modal-footer`);
				
				const button = document.createElement(`button`);

				button.classList.add(`btn`, `btn-primary`);
				button.textContent = `Assinar Selecionados`;
				button.type = `button`;
				button.id = `assinar-documentos-selecionados`;
				button.onclick = () => {

					const idRegistroFiduciario = document.querySelector(`input[name=id_registro_fiduciario]`).value;

					const data = {
						idsArquivos: everyCheckbox.filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.dataset.idArquivo)
					};

					$.ajax({
						type: `POST`,
						url: URL_BASE + `app/produtos/registros/${idRegistroFiduciario}/iniciar-assinaturas/outros-arquivos`,
						data,
						context: this,
						beforeSend: () => ajax_beforesend(),
						success: (data) => {
							
							ajax_success();
							
							const anchor = document.createElement(`a`);
							anchor.href = data.url;
							anchor.target = `_blank`;
							anchor.rel = `noreferrer`; // segurança

							anchor.click();

							everyCheckbox.filter(checkbox => checkbox.checked).forEach(checkbox => {

								const parent = checkbox.parentElement;
					
								const svg = document.createElementNS(`http://www.w3.org/2000/svg`, `svg`);
								svg.setAttributeNS(null, `viewBox`, `0 0 24 24`);
								svg.setAttributeNS(null, `height`, `24`);
								svg.setAttributeNS(null, `width`, `24`);
					
								const path1 = document.createElementNS(`http://www.w3.org/2000/svg`, `path`);
								path1.setAttributeNS(null, `d`, `M0 0h24v24H0z`);
								path1.setAttributeNS(null, `fill`, `none`);
					
								const path2 = document.createElementNS(`http://www.w3.org/2000/svg`, `path`);
								path2.setAttributeNS(null, `d`, `M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z`);
					
								svg.appendChild(path1);
								svg.appendChild(path2);
					
								parent.appendChild(svg);
					
								checkbox.remove();
					
							});

							button.remove();

						},
						error: (ev) => ajax_error(ev)
					});

				};

				modalFooter.insertBefore(button, modalFooter.firstChild);

				$(evt.target).closest(`.modal`).on(`hide.bs.modal`, () => {

					button.remove();
		
				});

			}

		} else {

			button.remove();

		}

	});

	$('div#visualizar-arquivo').on('show.bs.modal', function (ev) {
		var id_arquivo_grupo_produto = $(ev.relatedTarget).data('idarquivo');
		var subtitulo = $(ev.relatedTarget).data('subtitulo');
		var titulo = $(ev.relatedTarget).data('titulo');
		var no_extensao = $(ev.relatedTarget).data('noextensao');

		var data_args = {
			'id_arquivo_grupo_produto':id_arquivo_grupo_produto
		};

		$.ajax({
			type: "POST",
			url: URL_BASE+'app/arquivos/visualizar',
			data: data_args,
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				if (no_extensao=='pdf') {
					$(this).addClass('total-height');
				} else {
					$(this).removeClass('total-height');
				}

				ajax_success(titulo, $(this));

				$(this).find('.modal-body').html(retorno.view);

				if (retorno.url_download!='') {
					$(this).find('.modal-footer a#arquivo-download').show();
					$(this).find('.modal-footer a#arquivo-download').attr('href',retorno.url_download);
				} else {
					$(this).find('.modal-footer a#arquivo-download').hide();
					$(this).find('.modal-footer a#arquivo-download').attr('href','javascript:void(0);');
				}

				if (retorno.url_download_p7s!='') {
					$(this).find('.modal-footer a#arquivo-download-p7s').show();
					$(this).find('.modal-footer a#arquivo-download-p7s').attr('href',retorno.url_download_p7s);
				} else {
					$(this).find('.modal-footer a#arquivo-download-p7s').hide();
					$(this).find('.modal-footer a#arquivo-download-p7s').attr('href','javascript:void(0);');
				}
			},
			error: function(ev, xhr, settings, error) {
				ajax_error(ev, $(this), true);
			}
		});
	});

	$('div#visualizar-assinaturas').on('show.bs.modal', function (ev) {
		var id_arquivo_grupo_produto = $(ev.relatedTarget).data('idarquivo');
		var subtitulo = $(ev.relatedTarget).data('subtitulo');
		var titulo = $(ev.relatedTarget).data('titulo');

		var data_args = {
			'id_arquivo_grupo_produto':id_arquivo_grupo_produto,
		};

	    $.ajax({
			type: "POST",
			url: URL_BASE+'app/arquivos/assinaturas',
			data: data_args,
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body').html(retorno);
				ajax_success(titulo, $(this));
			},
			error: function(ev, xhr, settings, error) {
		    	ajax_error(ev, $(this), true);
		    }
		});
	});

    $('div#novo-arquivo-multiplo').on('show.bs.modal', function (ev) {
    	var h_index_arquivos = ($('[name=hidden_index_arquivos]').val() > 0) ? $('[name=hidden_index_arquivos]').val() : index_arquivos;
		var id_tipo_arquivo_grupo_produto = $(ev.relatedTarget).data('idtipoarquivo');
		var id_registro_fiduciario = $(ev.relatedTarget).data('idregistrofiduciario');
		var container = $(ev.relatedTarget).data('container');
		var token = $(ev.relatedTarget).data('token');
		var id_flex = $(ev.relatedTarget).data('idflex')?$(ev.relatedTarget).data('idflex'):0;
		var texto = $(ev.relatedTarget).data('texto')?$(ev.relatedTarget).data('texto'):'';
		var pasta = $(ev.relatedTarget).data('pasta')?$(ev.relatedTarget).data('pasta'):'outros';
		var extensoes = $(ev.relatedTarget).data('extensoes');
		var in_ass_digital = $(ev.relatedTarget).data('inassdigital')?$(ev.relatedTarget).data('inassdigital'):'';

		
		var data_args = {
			'id_tipo_arquivo_grupo_produto':id_tipo_arquivo_grupo_produto,
			'id_registro_fiduciario': id_registro_fiduciario,
			'token':token,
			'id_flex':id_flex,
			'texto':texto,
			'container':container,
			'pasta':pasta,
			'index_arquivos':h_index_arquivos,
			'extensoes':extensoes,
			'in_ass_digital':in_ass_digital
		};
		$.ajax({
			type: "POST",
			url: URL_BASE+'app/arquivos/novo',
			data: data_args,
			context: this,
			beforeSend: function() {
				ajax_beforesend();
			},
			success: function(retorno) {
				$(this).find('.modal-body form').html(retorno);
				ajax_success();
			},
				error: function(ev, xhr, settings, error) {
			    ajax_error(ev);
			}
		});
		
	});

	$('div#novo-arquivo-multiplo').on('click','button.enviar-arquivo-multiplos', function(ev) {
		ev.preventDefault();
		var form = $('form[name=form-novo-arquivo-multiplo]');
		var obj_modal = $(this).closest('.modal');

        var id_registro_fiduciario = form.find('input[name=id_registro_fiduciario]').val();

		var container = form.find('input[name=container]').val();
		if(container == "div#arquivos-outros-documentos"){
		
			$.ajax({
				url: URL_BASE+'app/arquivos/inserir_multiplos',
				type: 'POST',
				data: form.serialize(),
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(retorno) {

                    $('div#arquivos-outros-documentos').html("");
                    var botao_arquivo = "";
					$.each(retorno.arquivos, function(key, arquivo) {

						botao_arquivo += '<div class="arquivo btn-group" id="'+arquivo.hash_index+'" data-inassdigital="'+arquivo.in_ass_digital+'">';
						botao_arquivo += '<button type="button" class="nome btn btn-primary text-truncate">'+arquivo.no_descricao_arquivo+'</button>';
						botao_arquivo += '<input type="hidden" name="no_arquivo[]" value="'+arquivo.no_descricao_arquivo+'" />';
						if (arquivo.in_ass_digital=='S' || arquivo.in_ass_digital=='O') {
							botao_arquivo += '<button type="button" class="assinatura btn" data-indexarquivo="'+arquivo.hash_index+'" data-token="'+arquivo.token+'"></button>';
						}
						botao_arquivo += '<button type="button" class="remover btn btn-danger" data-indexarquivo="'+arquivo.hash_index+'" data-token="'+arquivo.token+'"><i class="fas fa-times"></i></button>';
	                    botao_arquivo += '</div> ';


					});

					$(container).prepend(botao_arquivo);

					obj_modal.modal('hide');
					
					ajax_success();
				},
				error: function(ev, xhr, settings, error) {
			    	ajax_error(ev);
			    },			
			});
			
		}else{

			$.ajax({
				type: 'POST',
				url: URL_BASE + 'app/produtos/registros/' + id_registro_fiduciario + '/arquivos',
				data: form.serialize(),
				beforeSend: function() {
					ajax_beforesend();
				},
				success: function(retorno) {
					switch (retorno.status) {
							case 'erro':
								alerta = swal("Erro!", retorno.message, "error");
								break;
							case 'sucesso':
								alerta = swal("Sucesso!", retorno.message, "success");
								break;
							case 'alerta':
								alerta = swal("Ops!", retorno.message, "warning");
								break;
							default:
								alerta = swal("Ops!", 'O servidor não retornou um status.', "warning");
								break;
					}
					alerta.then(function() {
						if (retorno.recarrega === 'true') {
							location.reload();
						}
						ajax_success();
					})
				},
				error: function(ev, xhr, settings, error) {
					ajax_error(ev, $(this), true);
				}
						
			});

		}
		
	});
 

});
function extensoes_permitidas(id_tipo_arquivo_grupo_produto) {
    return ['pdf','png','jpg','jpeg','bmp','tif','doc','docx','xls','xlsx','txt','ppt','pptx','pps','ppsx','rtf'];
}

function retorno_arquivo(retorno,modal) {
	var h_index_arquivos = ($('[name=hidden_index_arquivos]').val() > 0) ? $('[name=hidden_index_arquivos]').val() : index_arquivos;
	botao_arquivo = '<div class="arquivo btn-group" id="'+h_index_arquivos+'" data-inassdigital="'+retorno.arquivo.in_ass_digital+'">';
		botao_arquivo += '<button type="button" class="nome btn btn-primary text-truncate">'+retorno.arquivo.no_descricao_arquivo+'</button>';
		botao_arquivo += '<input type="hidden" name="no_arquivo[]" value="'+retorno.arquivo.no_descricao_arquivo+'" />';
		if (retorno.arquivo.in_ass_digital=='S' || retorno.arquivo.in_ass_digital=='O') {
			botao_arquivo += '<button type="button" class="assinatura btn" data-indexarquivo="'+h_index_arquivos+'" data-token="'+retorno.token+'"></button>';
		}
		botao_arquivo += '<button type="button" class="remover btn btn-danger" data-indexarquivo="'+h_index_arquivos+'" data-token="'+retorno.token+'"><i class="fas fa-times"></i></button>';
	botao_arquivo += '</div> ';
	$(retorno.arquivo.container).prepend(botao_arquivo);

	index_arquivos++;

	modal.modal('hide');

	atualiza_assinar_todos(retorno.token);
}

function total_arquivo(container,no_arquivo) {
	if (no_arquivo!='') {
		var find = 'input[value="'+no_arquivo+'"]';
	} else {
		var find = 'div.arquivo';
	}
	return $(container).find(find).length;
}

function remove_arquivo(retorno,index_arquivo) {
	$(retorno.arquivo.container).find('div#'+index_arquivo).remove();
	atualiza_assinar_todos(retorno.token);
}


$(".fechar-modal-arquivos").on("click", function(){
	location.reload();
}) 