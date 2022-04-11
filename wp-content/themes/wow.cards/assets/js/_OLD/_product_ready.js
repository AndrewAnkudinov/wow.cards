
// Показать/скрыть шаги HTML-страницы
function showStep(idStep) {
	console.log(idStep);
	jQuery('#content .step').each( function( index, element ) {
		var $element = jQuery(element);
		if (element.id == idStep) {
			$element.show();
		} else {
			$element.hide();
		}
	} );

}

var	media = function() {

	// Загрузить пользовательский аудио-файл
	document.querySelector('#inputUploadAudio').onchange = function (ev)
	{

		// Удалить предыдущий аудио-файл
		var urlAudioUser = mediaUser.player.src;
		if (!urlAudioUser)
			deleteAudioUser();

		// Анимировать кнопку загрузки
		var uploader = document.querySelector('#btnUploadAudio');
		var count = 0;
		var checkTimer = setInterval(function () {
			count++;
			if (count == 8) {
				uploader.innerHTML = '&nbsp;';
				count = 0;
			} else {
				uploader.innerHTML = 'Загрузка . . .';
			}
		}, 200);

		ev.target.files === undefined ? ev.target.value : ev.target.files;

		var xhr = new XMLHttpRequest();
		//xhr.responseType = 'json';
		xhr.onload = xhr.onerror = function (ev)
		{
			jQuery('#btnUploadAudio').text(btnUploadAudioText);
			clearTimeout(checkTimer);
			var resp = JSON.parse(ev.target.responseText);  // Парсить JSON-ответ процедуры загрузки файла
			console.log(resp);
			if (this.status == 200) {
				if (resp.error !== undefined) {
					console.log(resp.error);
					alert(resp.error); // Ответ севера: ' + resp.error
				} else if (
					resp.duration !== undefined
					&& resp.url !== undefined
				) {
					//runAfterUploadUserAudio(resp.url);
					console.log('runAfterUploadUserAudio');

					// Поменять начальную и конечную секунды выделенного отрезка пользовательского аудио
					mediaUser.segment.firstSecond = 0;
					mediaUser.segment.lastSecond = mediaUser.segment.firstSecond + mediaUser.segment.duration;
					//window.location.reload();

					jQuery(mediaUser.player)
						.attr('data-is-file-uploaded', 1)
						.attr('data-duration', resp.duration)
					;
					// Перегрузить аудио-файл в плеере с новым URL для очистки кэша
					mediaUser.player.src = resp.url;
					mediaUser.player.load();

					//initEditorMediaUser();
				}
			} else {
				console.log("error " + this.status);
			}
		};
		var formData = new FormData();
		formData.append("audio", ev.target.files[0]);
		formData.append("id_product", jQuery('#idProduct').val());

		xhr.open('POST', FF_root + '/app/ajax/ajax_upload_audio.php', true);
		xhr.send(formData);

	};

	// Удалить пользовательской аудио файл
	function deleteAudioUser()
	{

		var urlAudioUser = mediaUser.player.src;
		if (!urlAudioUser)
			return;

		// Очистить и скрыть аудио плеер с пользовательским файлом
		mediaUser.player.removeAttribute('src');
		mediaUser.player.load();
		jQuery(mediaUser.player)
			.attr('data-is-file-uploaded', 0)
			.attr('data-second', 0)
		//.hide()
		;
		jQuery('#figureAudioUser').hide();

		// Удалить аудио-файл
		var dataAjax = {
			'sid': ses_id,
			'name_deleted_file': getUrlWoParameters(urlAudioUser)
		};
		//console.log('dataAjax: ', dataAjax);
		jQuery.ajax({
			url: FF_root + '/app/ajax/ajax_delete_audio.php',
			cache: false,
			data : dataAjax,
			type: "POST"
			//dataType: "json"
		}).done(function(data) {
			console.log(data);
			var resp = JSON.parse(data);  // Парсить JSON-ответ процедуры загрузки файла
			if (resp.error !== undefined) {
				alert(resp.error);
			} else if (resp.success == 1) {

			}
		});

		// Стереть данные в базе данных о пользовательском аудио файле
		dataAjax = {
			id_product : jQuery('#idProduct').val(),
			urlAudioFile: '',
			secondStartAudio: 0
		};
		$.ajax({
			type: "POST",
			url: FF_root + '/app/ajax/ajax_save_audio_properties.php',
			data: dataAjax
		});

	}

};