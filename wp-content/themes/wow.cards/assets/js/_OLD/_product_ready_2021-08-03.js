'use strict';

(function() {


	// Определить корневую папку сайта
	var FF_root = '';


	// СЛУЖЕБНЫЕ ФУНКЦИИ

	// Получить cookie
	function getSessId(key) {
		var params = document.cookie.split('; ').map(function (params) {
			return params.split('=')
		});
		for (var j = params.length - 1; j >= 0; j--) {
			if (params[j][0] === key) {
				return params[j][1]
			}
		}
	}

	// Получить URL без GET-параметров
	function getUrlWoParameters(url) {
		return url.split('?')[0];
	}

	// Дополнить число меньше, чем 10, нулем спереди
	function paddingNumberZeros(number) {
		if (number < 10)
			number = '0' + number;
		return number;
	}

	// /СЛУЖЕБНЫЕ ФУНКЦИИ


	// Показать/скрыть шаги страницы
	function showStep(visible) {

		var eventsToSteps = {
			stepStart: 'stepStart', //
			stepChangeAudio: 'stepChangeAudio',
			stepEditorAudio: 'stepEditorAudio'
		};

		jQuery('#content .step').each( function( index, element ) {
			var $element = jQuery(element);
			if (element.id == eventsToSteps[visible]) {
				$element.show();
			} else {
				$element.hide();
			}
		} );

		// Останавливать воспроизведение при закрытии содержащего его контейнера
		if (visible != 'stepStart') {  // ... шага
			stopPlayback();
		}

	}

	var ses_id = getSessId('PHPSESSID');


	var
		$boxEditorMedia = jQuery('#mediaEditor'),
		//durationMediaUser = false;  // Длительность пользовательского аудио-файла
		//firstSecondAudioSegment = 0;  // Первая секунда выделенного отрезка пользовательского аудио
		//lastSecondAudioSegment = mediaUser.segment.firstSecond + mediaUser.segment.duration;  // Последняя секунда выделенного отрека пользовательского аудио
		wavesurfer = {}, // Объект аудио-гистограммы
		btnUploadAudio = document.querySelector('#btnUploadAudio'),
		mediaUser = {  // Редактор медиа-файла пользователя, в данном случае - аудио
			player: document.querySelector('#audioUser'),
			$bar: jQuery('#barMediaUser'),
			isPressedBar: false,
			segment: {
				duration: 30,  // Длительность медиа-отрезка для сторис в секундах
				firstSecond: 0, // Первая секунда выделенного отрека пользовательского аудио
				$range: jQuery('#rangeMediaSegment')
			}
		},
		$modalVideo = false  // Модальное окно с видео
		//$modalVideo = $('#modalVideo')

		//$rangeAudioSegment = jQuery('#rangeMediaSegment');
		//playerAudioUser = document.querySelector('#audioUser'); // Плеер пользовательского аудио //jQuery('#userAudio').get(0);
		//isPressedBarMediaUser = false;
		;

	// МЕДИА УТИЛИТЫ
	var	utilitesMedia = function() {

	};
	// selectorSegmentMedia
	var	productReady = function() {

		// Видео-элемент
		var mainVideo = false;
		if ($modalVideo) {
			//mainVideo = $modalVideo.find('video')[0];
		} else {
			mainVideo = $('#mainVideo')[0]; // Видео
		}

		//console.log(btnUploadAudio);
		var btnUploadAudioText = btnUploadAudio.textContent;  // TODO
		//console.log(btnUploadAudioText);
		mediaUser.segment.lastSecond = mediaUser.segment.duration; // Последняя секунда выделенного отрека пользовательского аудио
		//console.log(mediaUser);

		// Переключить иконки плей/стоп
		function toggleIconPlay(selector, icon) {
			var $videoPlayBtn = $(selector).find('.play-button i');
			var audio = getActiveAudio();
			var $audioIconElement = $(audio).parent().find('i');
			if (icon == 'play') {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-stop fa-pause').addClass('fa-play');
			}
			else if (icon == 'stop') {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-play fa-pause').addClass('fa-stop');
			}
			else if (icon == 'pause') {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-play fa-stop').addClass('fa-pause');
			}
		}

		// Вернуть активное аудио
		function getActiveAudio() {
			var $audio = $('#audioLib figure.active audio');
			console.log($audio);
			if ($audio.length == 0)
				return false;
			else
				return $audio[0];
		}


		// Остановить воспроизведение
		function stopPlayback() {
			var audio = getActiveAudio();
			if (audio) {
				audio.pause();
				//audio.currentTime = 0;  // TODO: удалить
			}
			toggleIconPlay('.video_container', 'play');
			mainVideo.pause();
		}

		// Остановить воспроизведение
		function closeBoxAudio() {
			stopPlayback();
		}

		// Воспроизвести видео (опционально - синхронно с аудио)
		function playVideo() { // TODO
			var audio = getActiveAudio();
			//console.log(audio);
			if (audio) {
				var startTimeAudio = 0;
				var audioDataSecond = jQuery(audio).attr('data-second');
				console.log('audioDataSecond: ', audioDataSecond);
				if (audioDataSecond != undefined) {
					startTimeAudio = audioDataSecond;
				}
				audio.currentTime = startTimeAudio;
				audio.play();
				mainVideo.muted = true;
			}
			toggleIconPlay('.video_container', 'stop');
			if ($modalVideo) {
				$modalVideo.modal('show'); // Запустить видео во всплывающем окне
			}
			mainVideo.currentTime = 0;
			mainVideo.play();
		}

		// Интерефейс видео- и аудио- плеера

		// Показать библиотеку аудио для мобильных экранов
		$('#showAudioLib').click( function (e) {
			$(this).removeClass('d-block d-sm-none').addClass('d-none');
			$('#audioLib').removeClass('d-none d-sm-block');
		});


		// ОСОБЕННОСТИ МОДАЛЬНОГО ОКНА С ВИДЕО

		if ($modalVideo)
		{

			// Создать ссылку на главное видео
			$('video').find('source').attr('src', $(mainVideo).find('source').attr('src') + '?md5=' + $(mainVideo).data('hash') + '#t=13');  // TODO: wow.cards: видео уже есть и его можно скачать. Зачем оплата?
			//$('video').find('source').attr('src', '/app/USERFILES/ready/33_st_preview.mp4?md5=' + $(mainVideo).data('hash'));
			$(mainVideo)[0].load();
		}


		// Удалить опцию "сохранить" правой кнопкой мыши из видео html5
		//$('video').bind('contextmenu', function() { return false; });

		// /Интерефейс видео- и аудио- плеера

		/* Вкладки категорий аудио
		// Останавливать воспроизведение при переключении на другую категорию аудио
		$('#audioLibTab a[data-toggle="tab"]').on('hide.bs.tab', function (e) {
			media.stopPlayback();
		});
		*/

		// Останавливать воспроизведение при закрытии содержащего его контейнера
		if ($modalVideo) {
			$modalVideo.on('hide.bs.modal', function (e) {  // ... модального окна
				stopPlayback();
			});
		} else {

		}


		// Запускать воспроизведение заново после окончания видео
		$(mainVideo).on('ended',function() {
			/*
			var audio = getActiveAudio();
			if (audio) {
				var startTimeAudio = 0;
				var audioDataSecond = jQuery(audio).attr('data-second');
				console.log('audioDataSecond: ', audioDataSecond);
				if (audioDataSecond != undefined) {
					startTimeAudio = audioDataSecond;
				}
				audio.currentTime = startTimeAudio;
			}
			*/
			playVideo();
		});


		// ЗАМЕНИТЬ АУДИО НА ПОЛЬЗОВАТЕЛЬСКОЕ

		// Нажать на кнопку выбора файла
		btnUploadAudio.addEventListener('click', function () {
			if (this.innerHTML == btnUploadAudioText) {
				document.querySelector('#inputUploadAudio').click();
			}
		}, false);


		/*
		// Получить полоску пользовательского аудио
		function getBarMediaUser() {
			return jQuery('#barMediaUser');
		}
		*/

		// Воспроизвести пользовательское аудио
		function playUserAudio() {
			mediaUser.player.play();
			toggleIconPlay('#mediaEditor', 'pause');
		}

		// Поставить на паузу пользовательское аудио
		function pauseUserAudio() {
			mediaUser.player.pause();
			toggleIconPlay('#mediaEditor', 'play');
		}

		// Форматировать время пользовательского аудио
		function formatTime(time) {
			var secondsInHour = 60;
			var minuts = Math.trunc(time / secondsInHour);
			var seconds = Math.trunc(time % secondsInHour);
			//var formattedTime = paddingNumberZeros(seconds);
			return minuts + ':' + paddingNumberZeros(seconds);
		}

		// Показать текущее время пользовательского аудио
		function showCurrentTimeMedia(time) {
			jQuery('#currentTimeUserAudio').text(formatTime(time));
		}

		// Настроить input type="range" под пользовательский аудио-файл
		function adjustInputRange(durationMediaUser) {

			var maxInputRange = Math.max(0, durationMediaUser - mediaUser.segment.duration);
			var percentWidthScrollRange = mediaUser.segment.duration/durationMediaUser * 100;
			var classScrollRange = 'scrollRange' + Math.round(percentWidthScrollRange);
			console.log(
				'mediaUser.segment.duration, maxInputRange, percentWidthScrollRange, classScrollRange:',
				mediaUser.segment.duration, maxInputRange, percentWidthScrollRange, classScrollRange
			);

			// Создать CSS-класс бегунка исходя из длительности аудио
			$("<style type='text/css'> #rangeMediaSegment." + classScrollRange + "::-webkit-slider-thumb"
				+ " { width: " + percentWidthScrollRange + "%;} </style>").appendTo("head");
			mediaUser.segment.$range
				.attr('max', maxInputRange)
				.attr('class', classScrollRange)
			;

			wavesurfer = WaveSurfer.create({
				container: '#waveform',
				waveColor: '#515253',
				progressColor: 'transparent'
			});
			wavesurfer.load(mediaUser.player.src);

			// Изменить поле текущего времени
			mediaUser.$bar.attr('max', durationMediaUser);

			/*
			var lengthDocumentStyleSheets = document.styleSheets[1].rules.length;
			console.log(lengthDocumentStyleSheets);
			for (var j = 0; j < lengthDocumentStyleSheets; j++) {
				var rule = document.styleSheets[1].rules[j];
				console.log(rule);
				if (rule.cssText.match("webkit-slider-thumb")) {
					console.log(rule);
					rule.style.width = '100px';
				}
			}
			*/
		}

		// Поменять медиа сегмент
		function highlightMedia() {

			// Выделить цветом отрезок аудио
			var
				secondsMediaTotal = Number(mediaUser.segment.$range.attr('max')) + mediaUser.segment.duration,
				secondsMediaCurrent = Number(mediaUser.segment.$range.val()),
				secondsSliderThumbCss = {
					'left' : secondsMediaCurrent/secondsMediaTotal * 100 + '%',
					'right' : (secondsMediaTotal - (secondsMediaCurrent + mediaUser.segment.duration))/secondsMediaTotal * 100 + '%'
				};
			console.log(
				'secondsMediaTotal, secondsMediaCurrent, secondsSliderThumbCss: ',
				secondsMediaTotal, secondsMediaCurrent, secondsSliderThumbCss
			);
			jQuery('#highlightAudioSegment').css(secondsSliderThumbCss);

			// Поменять начальную и конечную секунды выделенного отрезка пользовательского аудио
			mediaUser.segment.firstSecond = secondsMediaCurrent;
			mediaUser.segment.lastSecond = secondsMediaCurrent + mediaUser.segment.duration;
			console.log('mediaUser.segment.firstSecond: ', mediaUser.segment.firstSecond);
			console.log('mediaUser.segment.lastSecond: ', mediaUser.segment.lastSecond);
			//document.getElementById('printSecond').innerHTML = secondsMediaCurrent; // TODO: 2021-06-14 вызывало ошибку
		}

		// Иинициализировать редактор пользовательского медиа-файла
		function initEditorMediaUser() {

			// Разрушить старую гистограмму
			if (!jQuery.isEmptyObject(wavesurfer))
				wavesurfer.destroy();
			var durationMediaUser = jQuery(mediaUser.player).attr('data-duration');  // mediaUser.player.duration неверно определяется у формата .aac
			//var durationMediaUser = mediaUser.player.duration;
			console.log('durationMediaUser: ' + durationMediaUser);
			adjustInputRange(durationMediaUser);
			highlightMedia();
			showCurrentTimeMedia(0);

			// Показать полное время пользовательского медиа-файла
			var formattedDurationUserMedia = '__:__';
			if (durationMediaUser > 0) {
				console.log('formatTime(durationMediaUser): ', formatTime(durationMediaUser));
				formattedDurationUserMedia = formatTime(durationMediaUser);
			}
			jQuery('#totalTimeUserMedia').text(formattedDurationUserMedia);

			$boxEditorMedia.modal('show');  // TODO: не рисутеся гистограмма, если окно скрыто
			mediaUser.$bar.attr({ 'max': durationMediaUser });
		}

		/*
		// Функция: выполнить после загрузки пользовательского аудио-файла
		function runAfterUploadUserAudio(url)
		{

		}
		*/

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

		// Обработать смену медиа-отрезка
		mediaUser.segment.$range.on('change, input', function () {
			highlightMedia();
			console.log(jQuery(this).val());
		});

		// Перевести время пользовательского аудио-файла на начало выбранного отрезка
		function resetTimeSegmentMediaUser() {
			//mediaUser.$bar.val(mediaUser.segment.firstSecond);

			console.log('mediaUser.segment.firstSecond: ', mediaUser.segment.firstSecond);
			mediaUser.player.currentTime = mediaUser.segment.firstSecond;
		}

		// Обработать события пользовательского медиа-файла
		jQuery(mediaUser.player)

			// Обновлять текущее время пользовательского аудио при его воспроизведении
			.on('timeupdate', function () {

				var currentTime = Math.round( $(this).get(0).currentTime );

				// Начать воспроизведение с начала выделенного аудио-отрезка
				// если текущее время достигло его конца
				if (
					currentTime == mediaUser.segment.lastSecond
					&& !mediaUser.isPressedBar
				) {
					resetTimeSegmentMediaUser();
					return false;
				}

				console.log(currentTime);
				showCurrentTimeMedia(currentTime);
				if (!mediaUser.isPressedBar)
					mediaUser.$bar.val(currentTime);
			})

			// ... окончание воспроизведения
			.on('ended', function() {
				resetTimeSegmentMediaUser();
				this.play();
			} )

			// ... Инициализировать аудио-редактор после загрузки аудио-файла в браузер
			.on('loadedmetadata', function() {
				console.log('loadedmetadata');
				if (jQuery(mediaUser.player).attr('data-is-file-uploaded') == 1)
					//runAfterUploadUserAudio();
					initEditorMediaUser();
			} )
		;

		// Поменять текущее время пользовательского аудио после смены значения полоски
		mediaUser.$bar
			.on('change', function () {
				var playTime = Math.round( $(this).get(0).value );
				console.log(playTime);
				mediaUser.player.currentTime = playTime;
			})

			.on('mousedown', function () {
				mediaUser.isPressedBar = true;
			})

			.on('mouseup', function () {
				mediaUser.isPressedBar = false;
			})
		;

		// События медиа-редактора
		$boxEditorMedia

			// Создать интерфейс редактора при открытии модального окна
			.on('show.bs.modal', function (e) {
				stopPlayback();
				initEditorMediaUser();
			})

			// Ставить аудио на паузу при закрытии модального окна
			.on('hide.bs.modal', function (e) {
				pauseUserAudio();
			})

			// Плей видео после закрытия модального окна, если есть пользовательское аудио
			.on('hidden.bs.modal', function (e) {
				console.log(mediaUser.player.src);
				if (mediaUser.player.src != undefined && mediaUser.player.src != '') {
					jQuery('#figureAudioUser').show().click();
				}
			})

			// Плей/пауза после клика по кнопке Play/Pause
			.find('.play-button')
			.on('click', '.fa-play', function () {
				playUserAudio();
			})
			.on('click', '.fa-pause', function () {
				pauseUserAudio();
			})
			.end()

			// Поменять уровень громкости пользовательского аудио
			.find('#volumeUserAudio').on('change, input', function () {
				console.log(jQuery(this).val());
				mediaUser.player.volume = jQuery(this).val() / 100;
			})
			.end()

			// Подтвердить выбор секунды пользовательского аудио и сделать его активным
			.find('#btnTakeSegmentAudio').click(function (e) {
				//resetTimeSegmentMediaUser();
				//jQuery('#figureAudioUser').show().click();
				//console.log('mediaUser.segment.$range.val(): ', mediaUser.segment.$range.val());
				jQuery(mediaUser.player).attr('data-second', mediaUser.segment.$range.val()); // Передать выбранную секунду в data-аттрибут аудио при закрытии модального окна
			})
			.end()

			// Отменить (удалить) пользовательское аудио по клику на закрытие аудио-редактора без сохранения
			.find('.close').click(function (e) {
				deleteAudioUser();
			});

		// /ЗАМЕНИТЬ АУДИО НА ПОЛЬЗОВАТЕЛЬСКОЕ


		// Function: Иницализировать функции, запускаемые после полной загрузки страницы
		function initAfterDocumentLoad() {
			console.log('initAfterDocumentLoad');

			// ОБРАБОТАТЬ КЛИКИ ПО ВИДЕО/АУДИО
			// Плей/стоп главного видео с родным звуком
			$('.video_container').click(function ()
			{
				console.log(this);
				var isVideoPaused = mainVideo.paused;
				stopPlayback();
				if (isVideoPaused) {
					playVideo();
				}
			});

			// Сделать аудио активным и воспроизвести видео одновременно с ним
			$('#audioLib figure').click(function ()
			{
				showStep('stepStart');
				console.log(this);
				var audio = $(this).parent().find('audio')[0];
				var audioPaused = audio.paused;
				stopPlayback(); // Останавливать воспроизведение при запуске другого аудио
				$('#audioLib figure.active').removeClass('active');
				$(audio).closest('figure').addClass('active');
				if (audioPaused) {
					playVideo();
				}
				/* 2020-10-07
				else {
					stopPlayback();
				}
				*/
			});

			/*
			// Автоплей видео после загрузки своего аудио
			if ($(mainVideo).attr('data-user-audio') == 1) {
				playVideo();
			}
			*/

			//$boxEditorMedia.modal('show');
			//initEditorMediaUser();
			//adjustInputRange(200);
			//highlightMedia();

		}

		// Return public data and functions
		return {

			// Data
			//test: test,

			// Functions
			initAfterDocumentLoad: initAfterDocumentLoad

		};

	}();

	var selectorSegmentMedia = {

	};


	// Сохранить выбор музыки по клику по кнопке "Купить"
	$('#payLink').click(function ()
	{

		var productId = jQuery('#idProduct').val();
		var dataAjax = {
			id_product : productId
		};
		var urlAudioFile = jQuery('#audioLib figure.active audio').attr('src');
		if (urlAudioFile == undefined)
			urlAudioFile = '';
		dataAjax.url_audio = getUrlWoParameters(urlAudioFile);

		// Получить секунду неачала пользовательского аудио
		dataAjax.second_start_audio = 0;
		var secondStartAudio = jQuery('#userAudio').attr('data-second');
		if (secondStartAudio != undefined)
			dataAjax.second_start_audio = secondStartAudio;

		console.log(dataAjax);
		console.log(urlAudioFile);

		$.ajax({
			type: "POST",
			url: FF_root + '/app/ajax/ajax_save_audio_properties.php',
			data: dataAjax,
			//dataType: 'json',
			success: function (textStatus, status) {
				console.log(textStatus);
				console.log(status);
			},
			error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
			}
			//dataType: dataType
		});

		// Проверить статус оплаты заказа
		var checkTimer = setInterval(function () {
			var xhr = new XMLHttpRequest();
			xhr.open('GET', FF_root + '/app/ajax/ajax_get_product_payment_status.php?product_id=' + productId);
			xhr.onload = function (ev) {
				console.log(ev.target.responseText);
				if (ev.target.responseText == '1') {
					window.location.reload();
				}
			};
			xhr.send();
		}, 1000 * 10);
		//return false;

		/*
		$.ajax({
			type: 'post',
			url: '/app/ajax/save_audio_name.php',
			context: $form, // context will be "this" in your handlers
			success: function() { // your success handler
			},
			error: function() { // your error handler
			},
			complete: function() {
				// make sure that you are no longer handling the submit event; clear handler
				this.off('submit');
				// actually submit the form
				this.submit();
			}
		});
		*/
	});

	// Выполнить после загрузки страницы
	jQuery(document).ready(function($)
	{

		//console.log(productReady());
		//productReady();

		// Цели Яндекс.Метрики
		jQuery('#payLink').click( function () {
			ym(64805569,'reachGoal','payLink');
		});

		// Выполнить после полной загрузки страницы
		jQuery(window).on('load', function()
		{

			productReady.initAfterDocumentLoad(); // Обработать клики по видео/аудио
			//productReady.initAfterDocumentLoad(); // Обработать клики по видео/аудио
			//runAfterUploadUserAudio();

			// Обработать клики по прочим элементам
			jQuery('#btnStepChangeAudio').click(function () {
				showStep('stepChangeAudio');
			});

		});

	});

})();