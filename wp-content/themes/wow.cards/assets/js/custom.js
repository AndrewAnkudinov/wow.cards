// CUSTOM.JS - избранные js-функци, общие для текущей WP-темы

// Создать Namespace меdia в виде js-объекта, для содержания переменных и методов интерефейса видео- и аудио-плееров
var media = {

	textIconOnSound: 'volume_up',
	textIconOffSound: 'volume_off',

	// Получить фигуру, родительскую для выбранного HTML-элемента
	// Получить id фигуры, родительской для выбранного HTML-элемента
	getParentFigure: function(element) {
		return jQuery(element).closest('figure');
	},
	/*
	getIdParentFigure: function(element) {
		return this.getParentFigure().attr('id');
	},
	*/

	// Получить дочернее видео jQuery-объекта (HTML-фигуры)
	getChildVideo: function($figure) {
		console.log($figure);
		return $figure.find('video')[0];
	},

	// Переключить иконки плей/стоп jQuery-объекта (HTML-фигуры)
	toggleIconSound: function($figure, icon) {
		var $iconSound = $figure.find('.btn_sound i');
		if (icon == 'on') {
			$iconSound.text(this.textIconOnSound);
		}
		else if (icon == 'off') {
			$iconSound.text(this.textIconOffSound);
		}
	},
	/*
	toggleIconSound: function($figure, icon) {
		var $btnSound = $figure.find('.btn_sound');
		if (icon == 'on') {
			$btnSound.removeClass('off_sound').addClass('on_sound');
		}
		else if (icon == 'off') {
			$btnSound.removeClass('on_sound').addClass('off_sound');
		}
	},
	*/

	// Включить звук видео jQuery-объекта (HTML-фигуры)
	// Выключить звук видео jQuery-объекта (HTML-фигуры)
	onSoundVideo: function($figure) {
		console.log('onSoundVideo');
		$(this.getChildVideo($figure)).prop('muted', false);
		this.toggleIconSound($figure, 'off');
	},
	offSoundVideo: function($figure) {
		console.log('offSoundVideo');
		$(this.getChildVideo($figure)).prop('muted', true);
		this.toggleIconSound($figure, 'on');
	},

};

function stopVideo(video){
	video.pause();
	video.currentTime = 0;
}

$(document).ready(function() {

	// videojs
	var playersVideo = document.querySelectorAll('.video-js');
	var playersVideojs = Array();
	for (var i = 0, len = playersVideo.length; i < len; i++) {
		playersVideojs[i] = videojs(playersVideo[i].id);
		playersVideojs[i].userActive(false);
	}
	console.log(playersVideojs);

	// События карусели
	var typesDesign  = ['Postcard', 'Slideshow', 'Story']; // Форматы дизайнов, у которых могут быть слайдеры
	typesDesign.forEach(function(typeDesign) {
		var $carousel = jQuery('#carouselChoose' + typeDesign);
		var linkDesign = document.getElementById('linkDesign');
		console.log('typeDesign:', typeDesign);
		console.log('$carousel:', $carousel);
		if ($carousel.length == 0)
			return;
		$slides = $carousel.find('.carousel-item');
		$videos = $slides.find('video');
		console.log('$carousel:', $carousel);
		console.log('$slides:', $slides);
		$carousel

			// Подставить URL из карусели в кноку "Создать заказ"
			.on('slide.bs.carousel', function (ev) {
				var url = jQuery(ev.relatedTarget).attr('data-url');
				var link = document.getElementById('link' + typeDesign);
				console.log('link:', typeDesign, link);
				console.log('ev:', ev);
				console.log('ev.relatedTarget:', ev.relatedTarget);
				console.log('data-url:', jQuery(ev.relatedTarget).attr('data-url'));
				if (link)
					link.href = url;
				if (linkDesign)
					linkDesign.href = url;
				//document.getElementById('link' + typeDesign).href = jQuery(ev.relatedTarget).attr('data-url');


			})

			// Остановить все видео этой карусели
			.on('slid.bs.carousel', function (ev) {
				console.log('$slides.not(.active).find(video):', $slides.not('.active').find('video'));
				$slides
					.not('.active')
					.find('video')
					.trigger('pause')
				$slides
					.filter('.active')
					.find('video')
					.trigger('play')
				;
			})

			.carousel();

			//.trigger('slid');

		// Синхронизировать уровень звука всех видео карусели
		$videos.on('volumechange', function () {

			var $video = $(this);
			console.log($video);

			// Запускать синхронизацию только при правке звуку вручную, то есть у активного слайда
			var $parentCarouselItem = $video.closest( '.carousel-item' );
			if (!$parentCarouselItem.hasClass('active'))
				return true;

			var
				volume = this.volume,
				muted = this.getAttribute('muted');
			console.log(volume);
			console.log(muted);
			/*
			if (muted == null)
				$videos.removeAttr('mute');
				*/
			$videos.each(function(i, video) {
				if (this == video)  // Пропустить самого себя
				var
					volume2 = video.volume,
					muted2 = video.getAttribute('muted');
				if (volume2 == volume && muted2 == muted)
					return true;
				var figure = media.getParentFigure(video);
				if (muted == 'muted')
					media.offSoundVideo(figure);
				else
					media.onSoundVideo(figure);
				//console.log($video);
				video.volume = volume;
				console.log(video);
			});

			// check video.prop('muted');
		});


	});
	//jQuery('#carouselChooseDesign').carousel(); // Activate Carousel


	// Анимация клика на некоторые кнопки
	$('.bubbly-button').click(function(){ // #btnSlide, #loadFotoBtn, //, #btnThemeShow, #btnTheme
		var $elem = $(this);
		var href = $elem.attr('href');
		console.log($(this));
		// do animation
		/*
		 $(this).toggleClass(' btn-light', 1000);/*.fadeOut( 1000, function(){
		 // go to link when animation completes
		 window.location=href;
		 });
		 */
		//$(this).toggleClass('btn-light', function(){
		$(this).toggleClass('animate');
		setTimeout(function () {
			console.log($elem);
			console.log($elem.attr('id'));
			$elem.toggleClass('animate');
			//$(this).click();
			if ($elem.attr('id') == 'btnSlide')
				window.location = href;
		}, 800)

		/*
		// over ride browser following link when clicked
		if (
			$elem.attr('id') != 'downloadLink'
			&& $elem.attr('id') != 'modalMailSubmit'
			&& $elem.attr('id') != 'modalMailClose'
		)
			return false;
			*/
	})
/*
	$(function(){
		var y = 0;
		setInterval(function(){
			y-=.5;
			$('body').css('background-position', '0 ' + y + 'px');
		}, 40);
	})
*/


/*
	confetti.maxCount = 30;     //set max confetti count
	//confetti.speed = 2;          //set the particle animation speed
	confetti.frameInterval = 50; //the confetti animation frame interval in milliseconds
	confetti.alpha = 1.0;        //the alpha opacity of the confetti (between 0 and 1, where 1 is opaque and 0 is invisible)
	//confetti.gradient = false;   //whether to use gradients for the confetti particles
	confetti.start();
*/

	// Change the second argument to your options:
	// https://github.com/sampotts/plyr/#options
	/*
	const players = Plyr.setup('.plyr', {captions: {active: true}, autoplay: true, muted: true });

	$(players).each(function() {
		console.log(this);
		$(this).on('ready', function(e) {
			console.log(this);
			this.muted = true;
			this.play();
		});
	});
	*/


	/*
	players.on('ready', () => {
		players.muted = true;
		players.play();
	})
	*/

/*
	const player = new Plyr('.plyr', {captions: {active: true}, autoplay: true, muted: true });

	// Expose player so it can be used from the console
	window.player = player;
	player.on('ready', () => {
		player.muted = true;
		player.play();
	})
*/



});