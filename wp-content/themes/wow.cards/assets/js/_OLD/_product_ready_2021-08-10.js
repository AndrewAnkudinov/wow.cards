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
			stepChangeAudio: 'stepChangeAudio'
			//stepEditorAudio: 'stepEditorAudio' // TODO: Возможно, редактор лучше сделать шагом, а не модальным окном
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
			chooseAudio.stopPlayback();
		}

	}

	var ses_id = getSessId('PHPSESSID');
	var
		$modalVideo = false,  // Модальное окно с видео
		//$modalVideo = $('#modalVideo')
		btnUploadAudio = document.querySelector('#btnUploadAudio'),
		idProduct = jQuery('#idProduct').val();


	// Задать переменные редактора выбора медиа-фрагмента
	var wavesurfer = {}, // Объект аудио-гистограммы
		editorMedia = {  // Редактор медиа-файла пользователя, в данном случае - аудио
			$box: jQuery('#editorMedia'),
			player: document.querySelector('#audioUser'),
			$bar: jQuery('#barMediaUser'),
			isPressedBar: false,
			segment: {
				duration: 30,  // Длительность медиа-отрезка для сторис в секундах
				firstSecond: 0, // Первая секунда выделенного отрека пользовательского аудио
				$range: jQuery('#rangeMediaSegment')
			}
		};

	//durationMediaUser = false;  // Длительность пользовательского аудио-файла
	//firstSecondAudioSegment = 0;  // Первая секунда выделенного отрезка пользовательского аудио
	//lastSecondAudioSegment = editorMedia.segment.firstSecond + editorMedia.segment.duration;  // Последняя секунда выделенного отрека пользовательского аудио

	//$rangeAudioSegment = jQuery('#rangeMediaSegment');
	//playerAudioUser = document.querySelector('#audioUser'); // Плеер пользовательского аудио //jQuery('#userAudio').get(0);
	//isPressedBarMediaUser = false;

	editorMedia.segment.lastSecond = editorMedia.segment.duration; // Последняя секунда выделенного отрека пользовательского аудио
	//console.log(editorMedia);
	// /Задать переменные редактора выбора медиа-фрагмента

	// МЕДИА УТИЛИТЫ
	var	utilitesMedia = {

		// Форматировать время медиа-файла
		formatTime: function(duration) {
			var secondsInHour = 60;
			var minuts = Math.trunc(duration / secondsInHour);
			var seconds = Math.trunc(duration % secondsInHour);
			//var formattedTime = paddingNumberZeros(seconds);
			return minuts + ':' + paddingNumberZeros(seconds);
		}

	};

	var	chooseAudio = function() {

		// Видео-плеер
		var playerVideo = false;
		if ($modalVideo) {
			//playerVideo = $modalVideo.find('video')[0];
		} else {
			playerVideo = $('#playerVideo')[0]; // Видео
		}
		console.log(playerVideo);

        // Вернуть активное аудио
        function getActiveAudio() {
            var $audio = $('#audioLib figure.active audio');
            console.log($audio);
            if ($audio.length == 0)
                return false;
            else
                return $audio[0];
        }

		var playerActiveAudio = getActiveAudio();

		//console.log(btnUploadAudio);
		var btnUploadAudioText = btnUploadAudio.textContent;  // TODO
		//console.log(btnUploadAudioText);

		// Переключить иконки плей/стоп
		function toggleIconPlay(selector, icon) {
			var $videoPlayBtn = $(selector).find('.play-button i');
			var audio = getActiveAudio();
			var $audioIconElement = $(playerActiveAudio).parent().find('i');
			if (icon == 'play') {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-stop fa-pause').addClass('fa-play');
			}
			else if (icon == 'stop') {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-play fa-pause').addClass('fa-stop');
			}
			else if (icon == 'pause') {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-play fa-stop').addClass('fa-pause');
			}
			else {
				$audioIconElement.add($videoPlayBtn).removeClass('fa-play fa-stop fa-pause');
			}
		}




		// Остановить воспроизведение
		function stopPlayback() {
			var audio = getActiveAudio();
			if (audio) {
				audio.pause();
				//audio.currentTime = 0;  // TODO: удалить
			}
			toggleIconPlay('.video_container', 'play');
			playerVideo.pause();
		}

        function pauseVideo() {
            var audio = getActiveAudio();
            if (audio) {
                audio.pause();
                //audio.currentTime = 0;  // TODO: удалить
            }
            toggleIconPlay('.video_container', 'pause');
            playerVideo.pause();
        }

        // Воспроизводить видео (опционально - синхронно с аудио)
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
				playerVideo.muted = true;
			}
			toggleIconPlay('.video_container', false);  // wow.cards без кнопки stop
			//toggleIconPlay('.video_container', 'stop');
			if ($modalVideo) {
				$modalVideo.modal('show'); // Запустить видео во всплывающем окне
			}
			playerVideo.currentTime = 0;
			playerVideo.play();
		}

        // Начать видео (опционально - синхронно с аудио)
		startVideo
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
			$('video').find('source').attr('src', $(playerVideo).find('source').attr('src') + '?md5=' + $(playerVideo).data('hash') + '#t=13');  // TODO: wow.cards: видео уже есть и его можно скачать. Зачем оплата?
			//$('video').find('source').attr('src', '/app/USERFILES/ready/33_st_preview.mp4?md5=' + $(playerVideo).data('hash'));
			$(playerVideo)[0].load();
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
		$(playerVideo)
			.on('ended',function() {
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
			})
			.on('play', function() {
                playVideo();
            })
			.on('pause', function() {
				pauseVideo();
			});


		// ЗАМЕНИТЬ АУДИО НА ПОЛЬЗОВАТЕЛЬСКОЕ

		// Нажать на кнопку выбора файла
		btnUploadAudio.addEventListener('click', function () {
			if (this.innerHTML == btnUploadAudioText) {
				document.querySelector('#inputUploadAudio').click();
			}
		}, false);


		// Удалить пользовательский аудио файл
		function deleteAudioUser()
		{

			var urlAudioUser = editorMedia.player.src;
			if (!urlAudioUser)
				return;

			// Очистить и скрыть аудио плеер с пользовательским файлом
			editorMedia.player.removeAttribute('src');
			editorMedia.player.load();
			jQuery(editorMedia.player)
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
				id_product : idProduct,
				urlAudioFile: '',
				secondStartAudio: 0
			};
			$.ajax({
				type: "POST",
				url: FF_root + '/app/ajax/ajax_save_audio_properties.php',
				data: dataAjax
			});

		}

		// Сохранить выбор аудио
		function saveAudioProperties()
		{
			var dataAjax = {
				id_product : idProduct
			};
			var urlAudioFile = jQuery('#audioLib figure.active audio').attr('src');
			if (urlAudioFile == undefined)
				urlAudioFile = '';
			dataAjax.url_audio = getUrlWoParameters(urlAudioFile);

			// Получить секунду начала пользовательского аудио
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
		}

		// Загрузить пользовательский аудио-файл
		document.querySelector('#inputUploadAudio').onchange = function (ev)
		{

			// Удалить предыдущий аудио-файл
			var urlAudioUser = editorMedia.player.src;
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
						editorMedia.segment.firstSecond = 0;
						editorMedia.segment.lastSecond = editorMedia.segment.firstSecond + editorMedia.segment.duration;
						//window.location.reload();

						jQuery(editorMedia.player)
							.attr('data-is-file-uploaded', 1)
							.attr('data-duration', resp.duration)
						;
						// Перегрузить аудио-файл в плеере с новым URL для очистки кэша
						editorMedia.player.src = resp.url;
						editorMedia.player.load();

						//initEditorMediaUser();
					}
				} else {
					console.log("error " + this.status);
				}
			};
			var formData = new FormData();
			formData.append("audio", ev.target.files[0]);
			formData.append("id_product", idProduct);

			xhr.open('POST', FF_root + '/app/ajax/ajax_upload_audio.php', true);
			xhr.send(formData);

		};

		// /ЗАМЕНИТЬ АУДИО НА ПОЛЬЗОВАТЕЛЬСКОЕ


		// Функции редактора выбора медиа-фрагмента
		var	selectorSegmentMedia = function()
		{

			var wavesurfer = {}; // Объект аудио-гистограммы

			/*
			// Получить полоску пользовательского аудио
			function getBarMediaUser() {
				return jQuery('#barMediaUser');
			}
			*/

			// Воспроизвести пользовательское аудио
			function playUserAudio() {
				editorMedia.player.play();
				toggleIconPlay('#editorMedia', 'pause');
			}

			// Поставить на паузу пользовательское аудио
			function pauseUserAudio() {
				editorMedia.player.pause();
				toggleIconPlay('#editorMedia', 'play');
			}


			/*
			// Функция: выполнить после загрузки пользовательского аудио-файла
			function runAfterUploadUserAudio(url)
			{

			}
			*/


			// Показать текущее время пользовательского аудио
			function showCurrentTimeMedia(time) {
				jQuery('#currentTimeUserAudio').text(utilitesMedia.formatTime(time));
			}

			// Настроить input type="range" под пользовательский аудио-файл
			function adjustInputRange(durationMediaUser) {

				var maxInputRange = Math.max(0, durationMediaUser - editorMedia.segment.duration);
				var percentWidthScrollRange = editorMedia.segment.duration/durationMediaUser * 100;
				var classScrollRange = 'scrollRange' + Math.round(percentWidthScrollRange);
				console.log(
					'editorMedia.segment.duration, maxInputRange, percentWidthScrollRange, classScrollRange:',
					editorMedia.segment.duration, maxInputRange, percentWidthScrollRange, classScrollRange
				);

				// Создать CSS-класс бегунка исходя из длительности аудио
				$("<style type='text/css'> #rangeMediaSegment." + classScrollRange + "::-webkit-slider-thumb"
					+ " { width: " + percentWidthScrollRange + "%;} </style>").appendTo("head");
				editorMedia.segment.$range
					.attr('max', maxInputRange)
					.attr('class', classScrollRange)
				;

				wavesurfer = WaveSurfer.create({
					container: '#waveform',
					waveColor: '#515253',
					progressColor: 'transparent'
				});
				wavesurfer.load(editorMedia.player.src);

				// Изменить поле текущего времени
				editorMedia.$bar.attr('max', durationMediaUser);

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
					secondsMediaTotal = Number(editorMedia.segment.$range.attr('max')) + editorMedia.segment.duration,
					secondsMediaCurrent = Number(editorMedia.segment.$range.val()),
					secondsSliderThumbCss = {
						'left' : secondsMediaCurrent/secondsMediaTotal * 100 + '%',
						'right' : (secondsMediaTotal - (secondsMediaCurrent + editorMedia.segment.duration))/secondsMediaTotal * 100 + '%'
					};
				console.log(
					'secondsMediaTotal, secondsMediaCurrent, secondsSliderThumbCss: ',
					secondsMediaTotal, secondsMediaCurrent, secondsSliderThumbCss
				);
				jQuery('#highlightAudioSegment').css(secondsSliderThumbCss);

				// Поменять начальную и конечную секунды выделенного отрезка пользовательского аудио
				editorMedia.segment.firstSecond = secondsMediaCurrent;
				editorMedia.segment.lastSecond = secondsMediaCurrent + editorMedia.segment.duration;
				console.log('editorMedia.segment.firstSecond: ', editorMedia.segment.firstSecond);
				console.log('editorMedia.segment.lastSecond: ', editorMedia.segment.lastSecond);
				//document.getElementById('printSecond').innerHTML = secondsMediaCurrent; // TODO: 2021-06-14 вызывало ошибку
			}

			// Иинициализировать редактор пользовательского медиа-файла
			function initEditorMediaUser() {

				// Разрушить старую гистограмму
				if (!jQuery.isEmptyObject(wavesurfer))
					wavesurfer.destroy();
				var durationMediaUser = jQuery(editorMedia.player).attr('data-duration');  // editorMedia.player.duration неверно определяется у формата .aac
				//var durationMediaUser = editorMedia.player.duration;
				console.log('durationMediaUser: ' + durationMediaUser);
				adjustInputRange(durationMediaUser);
				highlightMedia();
				showCurrentTimeMedia(0);

				// Показать полное время пользовательского медиа-файла
				var formattedDurationUserMedia = '__:__';
				if (durationMediaUser > 0) {
					console.log('utilitesMedia.formatTime(durationMediaUser): ', utilitesMedia.formatTime(durationMediaUser));
					formattedDurationUserMedia = utilitesMedia.formatTime(durationMediaUser);
				}
				jQuery('#totalTimeUserMedia').text(formattedDurationUserMedia);

				editorMedia.$box.modal('show');  // TODO: не рисутеся гистограмма, если окно скрыто
				editorMedia.$bar.attr({ 'max': durationMediaUser });
			}

			// Обработать смену медиа-отрезка
			editorMedia.segment.$range.on('change, input', function () {
				highlightMedia();
				console.log(jQuery(this).val());
			});

			// Перевести время пользовательского аудио-файла на начало выбранного отрезка
			function resetTimeSegmentMediaUser() {
				//editorMedia.$bar.val(editorMedia.segment.firstSecond);

				console.log('editorMedia.segment.firstSecond: ', editorMedia.segment.firstSecond);
				editorMedia.player.currentTime = editorMedia.segment.firstSecond;
			}

			// Обработать события пользовательского медиа-файла
			jQuery(editorMedia.player)

			// Обновлять текущее время пользовательского аудио при его воспроизведении
				.on('timeupdate', function () {

					var currentTime = Math.round( $(this).get(0).currentTime );

					// Начать воспроизведение с начала выделенного аудио-отрезка
					// если текущее время достигло его конца
					if (
						currentTime == editorMedia.segment.lastSecond
						&& !editorMedia.isPressedBar
					) {
						resetTimeSegmentMediaUser();
						return false;
					}

					console.log(currentTime);
					showCurrentTimeMedia(currentTime);
					if (!editorMedia.isPressedBar)
						editorMedia.$bar.val(currentTime);
				})

				// ... окончание воспроизведения
				.on('ended', function() {
					resetTimeSegmentMediaUser();
					this.play();
				} )

				// ... Инициализировать аудио-редактор после загрузки аудио-файла в браузер
				.on('loadedmetadata', function() {
					console.log('loadedmetadata');
					if (jQuery(editorMedia.player).attr('data-is-file-uploaded') == 1)
					//runAfterUploadUserAudio();
						initEditorMediaUser();
				} )
			;

			// Поменять текущее время пользовательского аудио после смены значения полоски
			editorMedia.$bar
				.on('change', function () {
					var playTime = Math.round( $(this).get(0).value );
					console.log(playTime);
					editorMedia.player.currentTime = playTime;
				})

				.on('mousedown', function () {
					editorMedia.isPressedBar = true;
				})

				.on('mouseup', function () {
					editorMedia.isPressedBar = false;
				})
			;

			// События медиа-редактора
			editorMedia.$box

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
					console.log(editorMedia.player.src);
					if (editorMedia.player.src != undefined && editorMedia.player.src != '') {
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
				editorMedia.player.volume = jQuery(this).val() / 100;
			})
				.end()

				// Подтвердить выбор секунды пользовательского аудио и сделать его активным
				.find('#btnTakeSegmentAudio').click(function (e) {
				//resetTimeSegmentMediaUser();
				//jQuery('#figureAudioUser').show().click();
				//console.log('editorMedia.segment.$range.val(): ', editorMedia.segment.$range.val());
				jQuery(editorMedia.player).attr('data-second', editorMedia.segment.$range.val()); // Передать выбранную секунду в data-аттрибут аудио при закрытии модального окна
			})
				.end()

				// Отменить (удалить) пользовательское аудио по клику на закрытие аудио-редактора без сохранения
				.find('.close').click(function (e) {
				deleteAudioUser();
			});

			//editorMedia.$box.modal('show');
			//initEditorMediaUser();
			//adjustInputRange(200);
			//highlightMedia();

		};

		selectorSegmentMedia();

		// Function: Иницализировать функции, запускаемые после полной загрузки страницы
		function initAfterDocumentLoad() {
			console.log('initAfterDocumentLoad');

			// ОБРАБОТАТЬ КЛИКИ ПО ВИДЕО/АУДИО
			// Играть/остановить главное видео
			/* 2021-08-09 Стандартьный ьплеер вместо дизанерской иконки Play
			$('.video_container').click(function ()
			{
				playerVideo.muted = false;  // TODO: включать звук только при первом клике!
				console.log(this);
				var isVideoPaused = playerVideo.paused;
				stopPlayback();
				if (isVideoPaused) {
					playVideo();
				}
			});
			*/

			// Сделать аудио активным и воспроизвести видео одновременно с ним
			$('#audioLib figure').click(function ()
			{
				showStep('stepStart');
				console.log(this);
				var audio = $(this).parent().find('audio')[0];  // TODO: 2021-08-03 parent не нужен вроде
				//var audioPaused = audio.paused;
				stopPlayback(); // Останавливать воспроизведение при запуске другого аудио
				$('#audioLib figure.active').removeClass('active');
				$(audio).closest('figure').addClass('active');
				//if (audioPaused) {
					playVideo();
				//}
				saveAudioProperties();
				/* 2020-10-07
				else {
					stopPlayback();
				}
				*/
			});

			/*
			// Автоплей видео после загрузки своего аудио
			if ($(playerVideo).attr('data-user-audio') == 1) {
				playVideo();
			}
			*/

			// Сохранить выбор музыки по клику по кнопке "Заменить"
			//$('.btn-change-audio').click(function ();
			/*2021-08-03
			// Сохранить выбор музыки по клику по кнопке "Купить"
			//$('#payLink').click(function ()
			{
				saveAudioProperties();

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
			});
			*/

		}

		// Return public data and functions
		return {

			// Data
			//test: test,

			// Functions
			initAfterDocumentLoad: initAfterDocumentLoad,
			stopPlayback: stopPlayback

		};

	}();


	// Выполнить после загрузки страницы
	jQuery(document).ready(function($)
	{

		// Цели Яндекс.Метрики
		jQuery('#payLink').click( function () {
			ym(64805569,'reachGoal','payLink');
		});

		// Выполнить после полной загрузки страницы
		jQuery(window).on('load', function()
		{

			chooseAudio.initAfterDocumentLoad(); // Обработать клики по видео/аудио
			//chooseAudio.initAfterDocumentLoad(); // Обработать клики по видео/аудио
			//runAfterUploadUserAudio();

			// Обработать клики по прочим элементам
			jQuery('#btnStepChangeAudio').click(function () {
				showStep('stepChangeAudio');
			});

		});

	});

})();