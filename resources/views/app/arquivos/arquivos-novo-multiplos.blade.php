<input type="hidden" name="token" value="{{$request->token}}" />
<input type="hidden" name="id_registro_fiduciario" value="{{$request->id_registro_fiduciario}}" />
<input type="hidden" id="id_tipo_arquivo_grupo_produto" name="id_tipo_arquivo_grupo_produto" value="{{$request->id_tipo_arquivo_grupo_produto}}" />
<input type="hidden" name="id_flex" value="{{$request->id_flex}}" />
<input type="hidden" name="texto" value="{{$request->texto}}" />
<input type="hidden" name="pasta" value="{{$request->pasta}}" />
<input type="hidden" name="container" value="{{$request->container}}" />
<input type="hidden" name="index_arquivos" value="{{$request->index_arquivos}}" />
<input type="hidden" name="extensoes" value="{{$request->extensoes}}" />
<input type="hidden" id="in_ass_digital" name="in_ass_digital" value="{{$request->in_ass_digital}}" />
<input type="hidden" name="in_converter_pdf" value="{{$in_converter_pdf}}" />
<div id="id_dropzone" class="my-dropzone dropzone dropzone-files d-flex justify-content-center flex-wrap">
        <input type="hidden" name="hash_files" value="{{$request->token}}" />
        <input type="hidden" name="teste" value="0" />
        <div class="dz-message needsclick">
            <button type="button" class="dz-button">
                <i class="fas fa-file-upload"></i> <br />
                Arraste o documento aqui ou clique para enviar.
            </button><br>
            <span class="note needsclick"></span>
        </div>
</div>


<script>
 Dropzone.autoDiscover = false;
  var template =
        '<div class="dz-preview dz-file-preview">' +
		'<div class="dz-image"><img data-dz-thumbnail></div>' +
		'<div class="dz-details">' +
        '<div class="dz-size" data-dz-size></div>' +
		'<div class="dz-filename"><span data-dz-name></span></div>' +
		'</div>' +
		'<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>' +
		'<div class="dz-success-mark"><i class="fas fa-check"></i></div>' +
		'<div class="dz-error-mark"><i class="fas fa-times"></i></div>' +
		'<div class="dz-error-message"><span data-dz-errormessage></span></div>' +
	    '</div>';


  // Dropzone has been added as a global variable.
  var dropzone = new Dropzone("div.my-dropzone", {
    url: URL_BASE+"app/temp-files/store",
    method: 'post',
    addRemoveLinks: true,
		dictCancelUpload: 'Cancelar',
		dictCancelUploadConfirmation: 'Tem certeza que deseja cancelar o upload?',
		dictRemoveFile: 'Remover',
		previewTemplate: template,
		parallelUploads: 1,
        autoProcessQueue: true,

        headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		params: function params(files, xhr, chunk) {
			return {
				'hash_files': $('#id_dropzone').find('input[name=hash_files]').val(),
                'id_tipo_arquivo_grupo_produto': $('#id_tipo_arquivo_grupo_produto').val(),
                'in_ass_digital': $('#in_ass_digital').val(),
			};
		}
  });

  dropzone.on("success", function(file, xhr) {
    file.hash_index = xhr.hash_index;
  });

	dropzone.on('error', function(file, xhr) {

    message = 'Erro desconhecido, atualize a página e tente novamente. Caso o erro persista, contate o administrador.';

		if (xhr.errors) {
			message = '';
			$.each(xhr.errors, function(key, error) {
				message += error + '<br />';
			});
		}

		var errorDisplay = document.querySelectorAll('[data-dz-errormessage]');
		errorDisplay[errorDisplay.length - 1].innerHTML = message;
  });

  dropzone.on("removedfile", function(file) {
    $.ajax({
      method: 'post',
      url: URL_BASE + 'app/temp-files/destroy',
      data: {
        'hash_files': $('#id_dropzone').find('input[name=hash_files]').val(),
        'temp_file': file.hash_index
      },
      error: function(ev, xhr, settings, error) {
          ajax_error(ev);
      }
    });
  });

</script>

