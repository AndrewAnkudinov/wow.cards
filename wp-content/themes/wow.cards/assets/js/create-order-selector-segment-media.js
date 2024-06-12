'use strict';

(function() {


	// Определить корневую папку сайта
	var FF_root = '';


	// СЛУЖЕБНЫЕ ФУНКЦИИ

	// Дополнить число меньше, чем 10, нулем спереди
	function paddingNumberZeros(number) {
		if (number < 10)
			number = '0' + number;
		return number;
	}

	// /СЛУЖЕБНЫЕ ФУНКЦИИ

	var
		$modalVideo = false;  // Модальное окно с видео
		//$modalVideo = $('#modalVideo');

	// Задать переменные редактора выбора медиа-фрагмента
	var editorMedia = {  // Редактор медиа-файла пользователя, в данном случае - аудио
			$box: jQuery('#editorMedia'),
			player: document.querySelector('#mediaUser'),
			$bar: jQuery('#barMediaUser'),
			isPressedBar: false,
			segment: {
				duration: 30,  // Длительность медиа-отрезка для сторис в секундах
				firstSecond: 0, // Первая секунда выделенного отрека пользовательского аудио
				$range: jQuery('#rangeMediaSegment')
			}
		};

	//durationMediaUser = false;  // Длительность пользовательского аудио-файла
	//firstSecondMediaSegment = 0;  // Первая секунда выделенного отрезка пользовательского аудио
	//lastSecondMediaSegment = editorMedia.segment.firstSecond + editorMedia.segment.duration;  // Последняя секунда выделенного отрека пользовательского аудио

	//$rangeMediaSegment = jQuery('#rangeMediaSegment');
	//playerMediaUser = document.querySelector('#mediaUser'); // Плеер пользовательского аудио //jQuery('#userMedia').get(0);
	//isPressedBarMediaUser = false;

	editorMedia.segment.lastSecond = editorMedia.segment.duration; // Последняя секунда выделенного отрека пользовательского аудио
	//console.log(editorMedia);
	// /Задать переменные редактора выбора медиа-фрагмента


	// | МЕДИА УТИЛИТЫ

	var	utilitesMedia = {

		// Форматировать время медиа-файла
		formatTime: function(duration) {
			var secondsInHour = 60;
			var minuts = Math.trunc(duration / secondsInHour);
			var seconds = Math.trunc(duration % secondsInHour);
			//var formattedTime = paddingNumberZeros(seconds);
			return minuts + ':' + paddingNumberZeros(seconds);
		},

		// Переключить иконки плей/стоп
		toggleIconPlay: function toggleIconPlay(selector, icon) {
			var $videoPlayBtn = $(selector).find('.play-button i');
			$videoPlayBtn.removeClass('fa-play fa-stop fa-pause').addClass('fa-' + icon);
		}

	};


	// | КЛАСС РЕДАКТОРА ВЫБОРА МЕДИА-ФРАГМЕНТА

	var	selectorSegmentMedia = function()
	{

		/*
		 // Получить полоску пользовательского аудио
		 function getBarMediaUser() {
		 return jQuery('#barMediaUser');
		 }
		 */

		// Воспроизвести пользовательское аудио
		function playMediaUser() {
			editorMedia.player.play();
			utilitesMedia.toggleIconPlay('#editorMedia', 'pause');
		}

		// Поставить на паузу пользовательское аудио
		function pauseMediaUser() {
			editorMedia.player.pause();
			utilitesMedia.toggleIconPlay('#editorMedia', 'play');
		}

		// Показать текущее время пользовательского аудио
		function showCurrentTimeMedia(time) {
			jQuery('#currentTimeMediaUser').text(utilitesMedia.formatTime(time));
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

			// Изменить поле текущего времени
			editorMedia.$bar.attr('max', durationMediaUser);
		}

		// Поменять медиа сегмент
		function highlightMedia() {

			var
                secondsMediaTotal = Number(editorMedia.segment.$range.attr('max')) + editorMedia.segment.duration,
                secondsMediaCurrent = Number(editorMedia.segment.$range.val());
				
			// Поменять начальную и конечную секунды выделенного отрезка пользовательского аудио
			editorMedia.segment.firstSecond = secondsMediaCurrent;
			editorMedia.segment.lastSecond = secondsMediaCurrent + editorMedia.segment.duration;
			console.log('editorMedia.segment.firstSecond: ', editorMedia.segment.firstSecond);
			console.log('editorMedia.segment.lastSecond: ', editorMedia.segment.lastSecond);
			resetTimeSegmentMediaUser();
			//document.getElementById('printSecond').innerHTML = secondsMediaCurrent; // TODO: 2021-06-14 вызывало ошибку
		}

		// Иинициализировать редактор пользовательского медиа-файла
		function initEditorMediaUser() {

			var durationMediaUser = jQuery(editorMedia.player).attr('data-duration');  // editorMedia.player.duration неверно определяется у формата .aac
			//var durationMediaUser = editorMedia.player.duration;
			console.log('durationMediaUser: ' + durationMediaUser);
			adjustInputRange(durationMediaUser);
			highlightMedia();
			showCurrentTimeMedia(0);

			// Показать полное время пользовательского медиа-файла
			var formattedDurationMediaUser = '__:__';
			if (durationMediaUser > 0) {
				console.log('utilitesMedia.formatTime(durationMediaUser): ', utilitesMedia.formatTime(durationMediaUser));
				formattedDurationMediaUser = utilitesMedia.formatTime(durationMediaUser);
			}
			jQuery('#totalTimeMediaUser').text(formattedDurationMediaUser);

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

		// Покрасить трек поля <input type="range" ... />
		function paintTrackInputRange(el) {
			var value = (el.value-el.min)/(el.max-el.min)*100
			el.style.background = 'linear-gradient(to right, #4F0074 0%, #4F0074 ' + value + '%, rgba(79, 0, 116, 0.51) ' + value + '%, rgba(79, 0, 116, 0.51) 100%)'

		}

		// События медиа-редактора
		editorMedia.$box

		// Создать интерфейс редактора при открытии модального окна
			.on('show.bs.modal', function (e) {
				initEditorMediaUser();
			})

			// Ставить аудио на паузу при закрытии модального окна
			.on('hide.bs.modal', function (e) {
				pauseMediaUser();
			})

			// Плей/пауза после клика по кнопке Play/Pause
			.find('.play-button')
			.on('click'/*, '.fa-play'*/, function () {
				console.log(this);
				var $icon = $(this).find('i');
				console.log($icon);
				if ($icon.hasClass('fa-play')) {
					playMediaUser();
				} else {
					pauseMediaUser(); // .fa-pause
				}
			})
			.end()

			// Поменять уровень громкости пользовательского меда-файла
			.find('#volumeMediaUser')
			.each(function() {
				paintTrackInputRange(this);
			})
			.on('change, input', function () {

				// Покрасить трек уровня звука
				paintTrackInputRange(this);
				//var value = (this.value-this.min)/(this.max-this.min)*100
				//this.style.background = 'linear-gradient(to right, #4F0074 0%, #4F0074 ' + value + '%, rgba(79, 0, 116, 0.51) ' + value + '%, rgba(79, 0, 116, 0.51) 100%)'

				console.log(jQuery(this).val());
				editorMedia.player.volume = jQuery(this).val() / 100;
			})
			/*
					// Покрасить трек уровня звука
					document.getElementById("volumeMediaUser").oninput = function() {
						var value = (this.value-this.min)/(this.max-this.min)*100
						this.style.background = 'linear-gradient(to right, #4F0074 0%, #4F0074 ' + value + '%, rgba(79, 0, 116, 0.51) ' + value + '%, rgba(79, 0, 116, 0.51) 100%)'
					};*/
			.end()

			// Подтвердить выбор секунды пользовательского аудио и сделать его активным
			.find('#btnTakeSegmentMedia').click(function (e) {
				//resetTimeSegmentMediaUser();
				//jQuery('#figureMediaUser').show().click();
				//console.log('editorMedia.segment.$range.val(): ', editorMedia.segment.$range.val());
				jQuery(editorMedia.player).attr('data-second', editorMedia.segment.$range.val()); // Передать выбранную секунду в data-аттрибут аудио при закрытии модального окна
			})
			.end()

			// Отменить (удалить) пользовательское аудио по клику на закрытие аудио-редактора без сохранения
			.find('.close').click(function (e) {
			});

		//editorMedia.$box.modal('show');
		//initEditorMediaUser();
		//adjustInputRange(200);
		//highlightMedia();

	};

	selectorSegmentMedia();

	// | /КЛАСС РЕДАКТОРА ВЫБОРА МЕДИА-ФРАГМЕНТА


	// Выполнить после загрузки страницы
	jQuery(document).ready(function($)
	{

		// Выполнить после полной загрузки страницы
		jQuery(window).on('load', function()
		{

			// Обработать клики по прочим элементам
			jQuery('#btnStepChangeMedia').click(function () {
				showStep('stepChangeMedia');
			});

		});

	});

})();