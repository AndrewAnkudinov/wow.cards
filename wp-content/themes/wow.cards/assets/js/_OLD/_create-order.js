"use strict";


// TODO: после заливки нового изображения сбиваются blob-изображения ранее обрезанных

// ЧТО_ТО ТИПА ФРЕЙМВОРКА ДЛЯ СОЗДАНИЯ КЛАССОВ В JAVASCRIPT (ООП)
// ДОКУМЕНТАЦИИ НЕ ОБНАРУЖЕНО :( НО, ПОХОЖЕ, ЧТО ЭТО СДЕЛАЛ https://babeljs.io/
// ВСЕГО СОЗДАНО 4 КЛАССА: View, UploadingFile, ProgressBar, Texts

function dispatch(name) {
	var detail = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];

	document.body.dispatchEvent(new CustomEvent(name, {
		detail: detail
	}));
}

var classCallCheck = function (instance, Constructor) {
	if (!(instance instanceof Constructor)) {
		throw new TypeError("Cannot call a class as a function");
	}
};

var createClass = function () {
	function defineProperties(target, props) {
		for (var i = 0; i < props.length; i++) {
			var descriptor = props[i];
			descriptor.enumerable = descriptor.enumerable || false;
			descriptor.configurable = true;
			if ("value" in descriptor) descriptor.writable = true;
			Object.defineProperty(target, descriptor.key, descriptor);
		}
	}

	return function (Constructor, protoProps, staticProps) {
		if (protoProps) defineProperties(Constructor.prototype, protoProps);
		if (staticProps) defineProperties(Constructor, staticProps);
		return Constructor;
	};
}();

// /ЧТО-ТО ТИПА ФРЕЙМВОРКА ДЛЯ СОЗДАНИЯ КЛАССОВ В JAVASCRIPT (ООП)


// СОЗДАТЬ ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
var vars = {
	FF_root             : '', // Корневая папка сайта
	idOrder             : '',
	configPage          : jQuery('#configPage').data(),
	/*
	$circularBar        : jQuery('#circularBar'),
	$circularBarPercent = jQuery('#circularBarPercent'),
	$progress	  = jQuery('.progress'),
	$progressBar  = jQuery('.progress-bar'),
	*/
	$emailOrder         : jQuery('#emailOrder'),
	btnSubmit           : document.querySelector('#btnSubmit')
};
console.log(vars);

// СОЗДАТЬ ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
/*
jQuery.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	jQuery.each(a, function() {
		if (o[this.name]) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};
*/
// scrollToAnchor
function scrollToAnchor(aid) {
	var aTag = jQuery("a[name='" + aid + "']");
	jQuery('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
	//return false;
}
/*
function scrollToId(id) {
	var aTag = jQuery("#" + id);
	jQuery('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
	//return false;
}
*/
function scrollToMedia(id) {

	var media = jQuery("#" + id);

	console.log('$(#fotoNVideoContainer>div).scrollTop():', $('#fotoNVideoContainer>div').scrollTop());
	var offsetShadow = 16;
	var offset = media.offset().top - $('#fotoNVideoContainer>div').offset().top - offsetShadow;  // Вычислить разность позиций контейнера для медиа и конкретной превьюшки
	console.log('media.offset().top, $(#fotoNVideoContainer>div).offset().top, offset: ', media.offset().top, $('#fotoNVideoContainer>div').offset().top, offset);
	//$('#fotoNVideoContainer').scrollTop = offset;
	$('#fotoNVideoContainer').animate({scrollTop: offset}, 'slow');

	/*
	var myElement = document.getElementById(id);
	console.log(myElement);
	var topPos = myElement.offsetTop;
	console.log(topPos);
	document.getElementById('fotoNVideoContainer>div').scrollTop = topPos;
	*/
	/*
	console.log(id);
	var posArray = $("#" + id).positionedOffset();
	$('#fotoNVideoContainer>div').scrollTop = posArray[1];
	*/
}

function isEmail(email) {
	if (email.length > 0
		&& (email.match(/.+?\@.+\..{2,}/g) || []).length !== 1) {
		return false;
	} else {
		//console.log('valid');
		return true;
	}
}

function is2words(string) {
	var tagCheckRE = new RegExp("(\\S+)(\\s+)(\\S+)");
	console.log(tagCheckRE + '.test(' + string + '): ' + tagCheckRE.test(string));
	return tagCheckRE.test(string);
}

// Auto Height Textarea
function autoHeight_(element) {
	console.log(element);
	console.log('element.scrollHeight: ', element.scrollHeight);
	return jQuery(element).css({
		'height'    : 'auto',
		'overflow-y': 'hidden'
	//}).height($(element).prop('scrollHeight'));
	}).height(element.scrollHeight);
}

// /ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ



/*
// Показать/скрыть HTML-элементы (в зависимости от того, есть или нет загруженные изображения)
function setDisplay(visible) {
	console.log(visible);

	// Кол-во изображений изменено
	if (visible === 'imageUploaded')
	{

		// Показывать поля для вода текстовых данных, если есть заруженный файл
		// Скрыть форму форму загрузки файлов, если загружено максимальное число
		var tmpImgUpload = jQuery("#fotoNVideoContainer li");
		console.log('tmpImgUpload: ' + tmpImgUpload);
		console.log('tmpImgUpload.length: ' + tmpImgUpload.length);
		console.log('maxImages: ' + vars.maxImages);

		// Если загружен хотя бы 1 медиа-файл
		if (tmpImgUpload.length > 0) {
			jQuery('#stepListMedia').show();
			jQuery('#stepStart').hide();
		} else {
			jQuery('#stepListMedia').hide();
			jQuery('#stepStart').show();
		}

		// Если загружено максимальное кол-во медиа-файлов
		if (tmpImgUpload.length >= vars.maxImages) {
			jQuery('#stepUploading').hide();
		} else {
			jQuery('#stepUploading').show();
		}
	}

	else if (visible === 'load') {		// В момент загрузки файла
		jQuery('#stepUploadingHeader, .btn-upload-media, #stepListMedia').hide();
	}
	else if (visible === 'crop') {		// После загрузки файла Открыть редактор/Скрыть главное тело документа
		//jQuery('#stepListMedia').removeClass('d-none');
		vars.$circularBar.hide();
		//jQuery('.btn-upload-media').toggleClass('btn-light');
		jQuery('#stepUploadingHeader, .btn-upload-media').show();
		jQuery('#stepUploading, #stepListMedia').hide();
	}

	else if (visible === 'cropEnd') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
		jQuery('#stepUploading, #stepListMedia').show();
		console.log('vars.$activeCropImage:');
		console.log(vars.$activeCropImage);
		scrollToId(vars.$activeCropImage.attr('id'));
		//$textOrder.focus();
		//scrollToAnchor('thumbnailAnchor'); 2020-05-12
	}
	/*
	 else if (visible === 'orderCreate') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
	 jQuery('#stepUploading, #stepListMedia').hide();
	 $stepCrop.hide();
	 $textOrder.focus();
	 scrollToAnchor('thumbnailAnchor');
	 }
	 *//*
	else if (visible === 'formSubmit') {	 // После отправки заказа скрыть все, кроме отправки заказа
		jQuery('#stepUploading, #stepListMedia').hide();
	}
	else {								// Конечное состояние документа после обрезки изображения
		jQuery('#stepUploading').hide();
		jQuery('#stepListMedia').show();
	}
}
*/

// Показать/скрыть HTML-элементы (в зависимости от того, если есть или нет загруженные изображения)
function setDisplay(visible) {
	console.log(visible);

	// Кол-во изображений изменено
	if (visible === 'imageUploaded')
	{

		// Показывать поля для вода текстовых данных, если есть заруженный файл
		// Скрыть форму форму загрузки файлов, если загружено максимальное число
		var tmpImgUpload = jQuery("#fotoNVideoContainer li");
		console.log(tmpImgUpload.length);
		// Если загружен хотя бы 1 медиа-файл
		if (tmpImgUpload.length > 0) {
			jQuery('#stepListMedia').show();
			jQuery('#stepStart').hide();
		} else {
			jQuery('#stepListMedia').hide();
			jQuery('#stepStart').show();
		}
		if (tmpImgUpload.length >= vars.configPage.minNumberFiles) {
			$(vars.btnSubmit).show();
		}
		else {
			$(vars.btnSubmit).hide();
		}

		// Если загружено максимальное кол-во медиа-файлов
		if (tmpImgUpload.length >= vars.maxNumberFiles) {
			jQuery('#stepUploading').hide();
		} else {
			jQuery('#stepUploading').show();
		}
	}

	if (visible === true) {		// Начальное состояние документа до загрузки файла
		jQuery('#stepUploading').show();
		jQuery('#stepListMedia').hide();
	}
	/*
	else if (visible === 'load') {		// В момент загрузки файла
		jQuery('.btn-upload-media, #stepListMedia').hide();
	}
	*/
	else if (visible === 'crop') {		// После загрузки файла /*Открыть редактор/Скрыть главное тело документа*/
		//jQuery('#stepListMedia').removeClass('d-none');
		//vars.$circularBar.hide();
		//jQuery('.btn-upload-media').toggleClass('btn-light');
		jQuery('.btn-upload-media').show();
		jQuery('#stepUploading, #stepListMedia, h1').hide();
	}
	else if (visible === 'cropHidden') {  // Скрытно открыть и закрыть редактор (сохранить данные обрезки)/Скрыть главное тело документа после загрузки файла
		//vars.$circularBar.hide();

		//jQuery('.btn-upload-media').toggleClass('btn-light');
		jQuery('.btn-upload-media').show();
		jQuery('#stepUploading, #stepListMedia').show();
		//$textOrder.focus();
		//scrollToAnchor('thumbnailAnchor'); 2020-05-12
	}
	else if (visible === 'cropEnd') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
		jQuery('#stepUploading, #stepListMedia, h1').show();
		jQuery('#stepCrop').hide();
		console.log('cropping.$croppedMedia:', cropping.$croppedMedia);
		//scrollToId(cropping.$croppedMedia.attr('id')); 2020-12-25 Вместо него теперь scrollToMedia
		//$textOrder.focus();
		//scrollToAnchor('thumbnailAnchor'); 2020-05-12
	}
	/*
	 else if (visible === 'orderCreate') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
	 jQuery('#stepUploading, #stepListMedia').hide();
	 $boxCrop.hide();
	 $textOrder.focus();
	 scrollToAnchor('thumbnailAnchor');
	 }
	 */
	else if (visible === 'formSubmit') {	 // После отправки заказа скрыть все, кроме отправки заказа
		jQuery('#stepUploading, #substepListMedia').hide();
	}
	else {								// Конечное состояние документа после обрезки изображения
		jQuery('#stepUploading').hide();
		jQuery('#stepListMedia').show();
	}
}

//Добавить/Исправить номера картинок
function addNumbers() {
	var elementsList = document.querySelectorAll('#fotoNVideoContainer li .box_thumb_media');
	var count = elementsList.length;
	Array.from(elementsList).forEach(function (item, i, arr) {
		/* 2020-09-24
		if (item.classList.contains('video'))
			item.querySelector('img').style.display = 'none';
			*/
		if (i + 1 == count) {
			if (item.innerHTML.indexOf('class="imageNumber"') > -1) {
				item.querySelector(".imageNumber").innerHTML = '<span class="material-icons">content_cut</span>' +
				'<span style="font-size: 24px; margin: 0 20px;">T+</span>' + (i+1);
			} else {
				item.innerHTML += '<div class="imageNumber">' + (i+1) + '</div>';
				if (item.innerHTML.indexOf('class="progress-holder"') == -1) {
					item.innerHTML += '<div class="progress-holder"><div class="fileuploader-progressbar"><div class="bar"></div></div></div>';
				}
			}
		} else {
			if (item.innerHTML.indexOf('class="progress-holder"') == -1) {
				item.innerHTML += '<div class="progress-holder"><div class="fileuploader-progressbar"><div class="bar"></div></div></div>';
			}
			if (item.innerHTML.indexOf('class="imageNumber"') > -1) {
				item.querySelector(".imageNumber").innerHTML = "";
			}
		}
		item.style.opacity = 1;
	});
}


/* DOCUMENT READY */

jQuery(document).ready(function ($) {


	// ===================================

	/* var ses_id;
	 function set_ses_key(ses) {
	 ses_id = ses;
	 }
	 console.log(ses());*/
	//console.log(document.cookie);
	var ses_id = getSessId('ses_var');
	var ses_ident = getSessId('ses_ident');
	if (ses_id === undefined) {

		ses_id = vars.configPage.ses;
		//console.log(ses_id);
	}
	if (ses_ident === undefined) {
		ses_ident = vars.configPage.uident;
		//console.log(ses_id);
	}
	//console.log(ses_id);
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


	// КЛАСС View - ПРОСМОТРОВЩИК ЗАГРУЖЕННЫХ ФАЙЛОВ

	var View = function () {
		function View(_ref) {
			var type = _ref.type;
			var name = _ref.name;
			var ind = _ref.ind;
			var partKey = _ref.partKey;
			classCallCheck(this, View);

			var slashInd = type.indexOf('/');
			this.type = slashInd !== -1 ? type.substring(0, slashInd) : type;
			this.name = name;
			this.ind = ind;
			this.partKey = partKey;

			this.render();
		}

		// Создать HTML-элементы загруженного изображения
		createClass(View, [{

			// Выполнить в процессе закачки файла (рендер превьюшки)
			key  : 'render',
			value: function render() {

				var li = document.createElement('li');
				console.log(this);
				li.className = 'col-6 col-md-4 col-xl-3 d-flex align-items-center justify-content-center flex-column loading';
				li.title = this.name;

				var imageCaptionInput = document.createElement('textarea');
				//var imageCaptionInput = document.createElement('input');
				//imageCaptionInput.type = 'text';
				jQuery(imageCaptionInput).attr('maxlength', 40);
				jQuery(imageCaptionInput).attr('rows', 1);
				imageCaptionInput.name = 'image_captions[]';
				imageCaptionInput.dataset.ind = this.ind;
				li.appendChild(imageCaptionInput);

				var boxThumbMedia = document.createElement('div');
				boxThumbMedia.className = 'box_thumb_media shadow rounded';
				li.appendChild(boxThumbMedia);

				var img = document.createElement('img');
				img.id = 'image' + this.ind;
				boxThumbMedia.appendChild(img);

				var delBtn = document.createElement('button');
				delBtn.type = 'button';
				delBtn.className = 'del_media';
				delBtn.dataset.ind = this.ind;
				li.appendChild(delBtn);

				li.addEventListener('dragover', addNumbers); // Не нужно на weezy.app, только 1 фото/видео, 2019-07-26
				this.els = {
					el    : li,
					delBtn: delBtn,
					imageCaptionInput: imageCaptionInput
				};

				return this;
			}
		}, {

			// Создать превьюшку медиа-файла пользователя после его загрузки
			key  : 'setPreview',
			value: function setPreview(wError, src, width, height) {
				console.log('setPreview');
				console.log(src);
				var el = this.els.el;
				el.classList.remove('loading');

				if (wError === true) {
					el.classList.add('wError');
					return;
				}

				//el.style.overflow = 'hidden';
				//el.style.backgroundImage = 'none';

				//el.style.backgroundImage = 'url(' + src + ')';
				var tempImg = el.querySelector('img');
				console.log(tempImg);
				tempImg.dataset.type = this.type;
				tempImg.onload = function () {  // Выполнить после загрузки файла

				};

				if (jQuery(tempImg).attr('data-width') == undefined) {  // Предотвратить повторное создание превью

					// Создать служебные аттрибуты залитого изображения
					var attrImg = {
						'data-width': width,
						'data-height': height,
						'data-file': src
					};
					if (this.type == 'image') {
						tempImg.src = src;
					}
					else if (this.type == 'video') {
						var blobStub = cropping.createBlobStub(width, height);
						tempImg.src = blobStub;
						attrImg['data-second'] = 0;

						// Создать видео-превьюшку
						var video = document.createElement('video');
						video.src = src;
						var boxThumbMedia = el.querySelector('.box_thumb_media');
						boxThumbMedia.insertBefore(video, tempImg);

					}
					attrImg['data-src'] = tempImg.src;  // Сохранить изображение до резки
					console.log(attrImg);
					jQuery(tempImg).attr(attrImg);
					el.querySelector('.progress-holder').style.display = 'none';

					// Не нужно на weezy.app, только 1 фото/видео, 2019-07-26
					if (el.querySelector('.imageNumber') != undefined) {
						el.querySelector('.imageNumber').style.display = 'flex';
					}

				}

			}
		}, {
			key  : 'append',
			value: function append(parent) {
				this.els.parent = parent;
				this.els.parent.appendChild(this.els.el);
			}
		}, {
			key  : 'destroy',
			value: function destroy() {
				this.els.parent.removeChild(this.els.el);
				for (var j = this.els.length - 1; j >= 0; j--) {
					this.els[j] = null;
				}
				this.els = null;
			}
		}]);
		return View;
	}();

	// CLASS VIEW - ПРОСМОТР ЗАГРУЖЕННЫХ ФАЙЛОВ


	// CLASS UPLOADING FILE (ЗАГРУЗКА ФАЙЛА)
	// this - объект с данными загружаемого файла

	var UploadingFile = function () {
		function UploadingFile(_ref) {
			var _ref$file = _ref.file;
			var file = _ref$file === undefined ? {} : _ref$file;
			var partKey = _ref.partKey;
			var ind = _ref.ind;
			var type = _ref.type;
			var name = _ref.name;
			var notAFile = _ref.notAFile;
			var src = _ref.src;
			var id = _ref.id;
			classCallCheck(this, UploadingFile);

			this.ind = ind;
			this.partKey = partKey;
			this.file = file;
			this.start = 0;
			this.xhr = null;
			this.count = 0;
			this.id = id !== undefined ? id : null;
			this.wError = false;
			this.notAFile = notAFile;

			this.size = file.size;
			this.type = type || file.type;
			this.name = name || file.name.replace(/ /g, '_');
			this.view = new View({
				type   : this.type,
				name   : this.name,
				ind    : this.ind,
				partKey: this.partKey
			});

			console.log('UploadingFile, this: ', this);
			if (notAFile === true) {
				this.src = src;
				this.view.setPreview(this.wError, this.src, this.width, this.height);
			}
		}

		createClass(UploadingFile, [
			{
				key  : 'getChunk',
				value: function getChunk() {
					return this.file.slice(this.start, this.next(), this.type);
				}
			}, {
				key  : 'next',
				value: function next() {
					var start = this.start;
					var fSize = this.size;
					var CHUNK_SIZE = 524288 * 4;  // Размер загружаемых фрагментов файла
					var newStart = start + CHUNK_SIZE > fSize ? fSize : start + CHUNK_SIZE;
					this.currChunkSize = newStart - this.start;
					this.start = newStart;
					return newStart;
				}
			}, {
				key  : 'startUpload',  // Начать загрузку очередного файла пользователя
				value: function startUpload() {
					console.log('startUpload: ', this);
					if (this.notAFile === true) { // Прервать загрузку файла
						this.onUploadEnd();
						return;
					}

					this.loadChunk();
				}
			}, {
				key  : 'loadChunk',
				value: function loadChunk() {

					console.log('loadChunk: ', this);
					if (this.start === this.size) { // Прервать загрузку файла
						this.delXhr();
						this.onUploadEnd();
						return;
					}

					this.collectFD();

					this.delXhr();

					var URL_CREATE = vars.FF_root + '/app/ajax/ajax-upload.php';
					this.xhr = new XMLHttpRequest();
					this.xhr.open("POST", URL_CREATE, true);
					this.xhr.onload = this.onLoad.bind(this);
					this.xhr.onerror = this.onError.bind(this);
					this.xhr.upload.onprogress = this.onProgress.bind(this);
					this.xhr.send(this.fd);

				}
			}, {
				key  : 'onLoad',
				value: function onLoad(ev) {
					this.clear();

					if (ev.target.status !== 200) {
						this.onError();
						return;
					}

					var chunkSize = this.currChunkSize;

					var resp = JSON.parse(ev.target.responseText);

					// if ( this.count === 0 ) {
					this.id = resp.file_id ? resp.file_id : this.id;
					// this.count++
					// }

					this.src = resp.src !== undefined ? resp.src : null;
					this.width = resp.width !== undefined ? resp.width : null;
					this.height = resp.height !== undefined ? resp.height : null;
					this.loadChunk();

					dispatch('chunk:loaded', JSON.stringify({
						chunkSize: chunkSize,
						partKey  : this.partKey
					}));
				}
			}, {
				//Прогресс выгрузки файла
				key  : 'onProgress',
				value: function onProgress(ev) {
					var done = this.start;
					var total = this.size;
					var present = Math.floor(done / total * 100);
					//console.log(present);
					if (this.type != 'audio/mp3') {
						this.view.els.el.querySelector('.bar').style.width = present + '%';
					}
				}
			}, {
				key  : 'onError',
				value: function onError() {
					this.clear();
					this.wError = true;
					this.onUploadEnd();
				}
			}, {
				key  : 'clear',
				value: function clear() {
					this.delXhr();
					this.currentChunk = null;
					this.fd = null;
				}
			}, {
				key  : 'collectFD',
				value: function collectFD() {
					this.fd = new FormData();
					this.fd.append('clip_id', vars.configPage.idDesign);
					this.fd.append('name', this.name);
					this.fd.append('size', this.size);
					this.fd.append('sid', ses_id);
					this.fd.append('uident', ses_ident);
					if (this.id !== null) {
						this.fd.append('file_id', this.id);
					}

					this.currentChunk = this.getChunk();
					this.fd.append('chunk', this.currentChunk);
				}
			}, {
				key  : 'delXhr',
				value: function delXhr() {
					if (this.xhr !== null) {
						this.xhr.onload = null;
						this.xhr.onerror = null;
					}
					this.xhr = null;
				}
			},

			{
				// Закончить загрузку файла (успешную или нет)
				key  : 'onUploadEnd',
				value: function onUploadEnd() {

					setDisplay('imageUploaded');

					console.log('onUploadEnd: ', this);
					var wError = this.wError;
					this.clear();
					this.file = null;

					// !!! DEL
					console.log(this);
					this.view.setPreview(
						wError,
						this.src,
						this.width,
						this.height
					);

					dispatch('file:loadend', JSON.stringify({
						wError : wError,
						partKey: this.partKey,
						ind    : this.ind
					}));
				}
			},

			{
				key  : 'delete',
				value: function _delete() {
					var _this = this;
					var URL_DELETE = vars.FF_root + '/app/ajax/ajax-delete-uploaded.php';
					fetch(URL_DELETE, {
						method : 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body   : 'file_id=' + this.id + '&sid=' + ses_id + '&uident=' + ses_ident
					}).then(function () {
						_this.destroy();

						dispatch('file:deleted');

					});
					cropping.clear();
				}
			}, {
				key  : 'destroy',
				value: function destroy() {
					this.view.destroy();
					this.view = null;
					var ind = this.coll.indexOf(this);
					this.coll[ind] = null;
					this.coll.splice(ind, 1);
					delete this.coll;
				}
			}
		]);
		return UploadingFile;
	}();

	// /CLASS UPLOADING FILE


	// CLASS PROGRESSBAR

	var SIZES = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	var ProgressBar = function () {
		function ProgressBar(el) {
			classCallCheck(this, ProgressBar);

			this.el = el;
			this.findEls();
			this.attrs = {
				speed    : 0,   // Скорость загрузки
				time     : 0,    // Время загрузки
				procent  : 0, // Процент загружен
				loaded   : 0,  // ??? Это св-во при второй и далее загрузке меньшего файла после большего.
				totalSize: 0  // Размер закачиваемого файла
			};
			this.timer = null;
			this.el.style.display = 'none';
			//vars.$circularBar.hide(); Не надо
		}

		createClass(ProgressBar, [{
			key  : 'findEls',
			value: function findEls() {
				var _this = this;

				var q = function q(sel) {
					return _this.el.querySelector(sel);
				};
				this.els = {
					speed    : q('.speed'),
					time     : q('.time'),
					procent  : q('.procent'),
					loaded   : q('.loaded'),
					totalSize: q('.totalSize'),
					barLine  : q('.bar .loaded')
				};
			}
		}, {
			key  : 'calcSpeed',
			value: function calcSpeed() {
				var perSecond = this.attrs.loaded / this.attrs.time;
				perSecond = this.humanizeSize(perSecond);
				perSecond += '/s';
				this.els.speed.textContent = perSecond;
			}
		}, {
			key  : 'calcProcents',
			value: function calcProcents() {

				// Weezy: Остановить искусственную анимацию прогресс-бара
				//console.log(circularBarInterval);
				//clearInterval(circularBarInterval);

				if (this.attrs.loaded === 0) {
					this.els.procent.textContent = 0;
					return;
				}
				var pr = this.attrs.loaded / this.attrs.totalSize * 100;
				pr = pr.toFixed(0);
				this.els.procent.textContent = pr;
				//vars.$circularBar.attr('data-value', pr);
				//$circularBarPercent.text(pr);
				//circularBarSet(); // Weezy: Запустить естественную анимацию прогресс-бара
				this.els.barLine.style.width = pr + '%';
			}
		}, {
			key  : 'startTimer',
			value: function startTimer() {

				/*
				 circularBarInterval = setInterval(function () {  // Запустить искусственную анимацию прогресс-бара
				 circularBarHandmade();
				 }, 500);
				 */

				var _this2 = this;

				//vars.$circularBar.show();
				//this.el.style.display = 'block';

				if (this.timer !== null) return;
				this.timer = setInterval(function () {
					var seconds = _this2.attrs.time;
					_this2.els.time.textContent = _this2.parseSeconds(seconds);
					_this2.attrs.time++;
					_this2.calcSpeed();
				}, 1000);
			}
		}, {
			key  : 'stopTimer',
			value: function stopTimer() {
				window.clearInterval(this.timer);
				this.timer = null;
			}
		}, {
			key  : 'humanizeSize',
			value: function humanizeSize(bytes) {

				bytes = parseInt(bytes);
				if (isNaN(bytes)) return '';
				if (bytes === 0) return '0 ' + SIZES[1];
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				if (i === 0) return bytes + ' ' + SIZES[i];
				var result = (bytes / Math.pow(1024, i)).toFixed(1);

				result += ' ' + SIZES[i];

				return result;
			}
		}, {
			key  : 'parseSeconds',
			value: function parseSeconds(seconds) {

				seconds = parseInt(seconds, 10);
				var mm = Math.floor(seconds / 60);
				var ss = seconds - mm * 60;

				var dates = {
					mm: mm,
					ss: ss
				};

				Object.keys(dates).forEach(function (key) {
					dates[key] = toStr(dates[key]);
				});

				return dates.mm + ':' + dates.ss;

				function toStr(num) {
					return num < 10 ? '0' + num : '' + num;
				}
			}
		}, {
			key  : 'reset',  // Сбросить значения прогресс-бара
			value: function reset() {
				setTimeout(function () {
					/*
					 console.log(this);
					 console.log(this.attrs);
					 console.log(this.attrs.loaded);
					 */
					this.stopTimer();
					this.el.style.display = 'none';
					this.attrs.loaded = 0;
					this.attrs.totalSize = 0;
					/*
					 console.log(this);
					 console.log(this.attrs);
					 console.log(this.attrs.loaded);
					 */
				}.bind(this), 100);  // Таймуат, чтобы успел обнулиться loaded - размер загрузки порции файла
			}
		}, {
			key: 'loaded',  // Загрузить порцию файла
			get: function get() {
				return this.attrs.loaded;
			},
			set: function set(size) {
				this.attrs.loaded = size;
				this.els.loaded.textContent = this.humanizeSize(size);
				this.calcProcents();
			}
		}, {
			key: 'totalSize',  // Получить полный вес загружаемого файла
			get: function get() {
				return this.attrs.totalSize;
			},
			set: function set(size) {
				this.attrs.totalSize = size;
				this.els.totalSize.textContent = this.humanizeSize(size);
				this.calcProcents();
			}
		}]);
		return ProgressBar;
	}();

	// /CLASS PROGRESSBAR


	// NAMESPACE "LOADER"

	var Loader = {
		init: function init() {
			console.log('Loader init');
			this.parts.fotoNvideo.maxLength = vars.configPage.maxNumberFiles;

			var media = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];

			this.addLis();
			this.makeSortable();
			this.setRestored(media);
		},

		get keys() {
			return Object.keys(this.parts);
		},

		parts: {
			fotoNvideo: {
				loading  : false,
				maxLength: vars.configPage.maxNumberFiles,
				startInd : 1,
				els      : {
					btn     : document.getElementsByClassName('btn-upload-media'),
					inp     : q('inputUploadMedia'),
					list    : document.querySelector('#fotoNVideoContainer ul'),
					counter : q('counterMedia'),
					messCounter : q('messCounterMedia')
				},
				items    : [],
				progress : new ProgressBar(q('fotoUploadProgress'))
			}
		},

		addLis: function addLis() {
			console.log('Loader addLis this:', this);
			var _this = this;

			// Обработать клики по кнопкам загрузки
			this.keys.forEach(function (key) {
				var part = _this.parts[key];
				for (var i=0; i < part.els.btn.length; i++) {
					part.els.btn[i].onclick = function(){
						part.els.inp.click();
					}
				};
				/*
				part.els.btn.onclick = function () {
					console.log('.btn-upload-media click');
					part.els.inp.click();
				};
				*/

				part.els.inp.onchange = function (ev) {
					_this.pushFilesForDownload(key, ev.target.files === undefined ? ev.target.value : ev.target.files);
					if (part.pushedCount !== 0) {
						_this.startDownload(key);
						Array.from(document.querySelectorAll('#fotoNVideoContainer li')).forEach(function (item, i, arr) {
							if (i >= _this.parts.fotoNvideo.maxLength) {
								item.style.opacity = 1;
							} else {
								item.style.opacity = 1;
							}
						});
					}
				};
			});

			// Прослушивать события
			document.body.addEventListener('file:loadend', this.onFileLoadEnd.bind(this));  // Загрузка файла завершена
			document.body.addEventListener('file:deleted', this.onFileDelete.bind(this));  // Удаление файла
			document.body.addEventListener('chunk:loaded', this.onChunkLoad.bind(this));  // Кусок загружен

			this.parts.fotoNvideo.els.list.onclick = this.onListClick.bind(this);

			// Загрузить файлы пользователя
			document.querySelector('.uploader').addEventListener("drop", function (e) {
				var key = 'fotoNvideo';
				var part = _this.parts[key];
				e.stopPropagation();
				e.preventDefault();
				_this.pushFilesForDownload(key, e.dataTransfer.files);
				if (part.pushedCount !== 0) {
					_this.startDownload(key);
					Array.from(document.querySelectorAll('#fotoNVideoContainer li')).forEach(function (item, i, arr) {
						if (i >= _this.parts.fotoNvideo.maxLength) {
							item.style.opacity = 1;
						} else {
							item.style.opacity = 1;
						}
					});
				}
				//document.querySelector('#inputUploadMedia').files = e.dataTransfer.files;
				document.querySelector('.uploader').style.opacity = 1;
				document.querySelector('.btn-upload-media').style.pointerEvents = "auto";
			});

		},

		// Начать загрузку файлов пользователя
		startDownload       : function startDownload(key) {  // key - fotoNvideo or mus
			jQuery('#stepListMedia').addClass('processing');
			var part = this.parts[key];
			console.log('startDownload, part: ', part);
			if (part.loading === true)
				return;

			if (part.items[0] !== undefined) {
				console.log('part.items[0]: ', part.items[0]);
				part.loading = true;
				part.items[0].startUpload();
				part.progress.startTimer();
				//setDisplay('load');
			}
		},

		// Нажать на загрузку файлов
		pushFilesForDownload: function pushFilesForDownload(key, files) {
			var part = this.parts[key];
			var pushedCount = 0;

			for (var j = 0; j < files.length; j++) {

				if (part.maxLength === part.items.length) /* Ограничить число загрузок */
					continue;

				if (part.items.length == 33) continue;
				pushedCount++;

				var f = files[j];
				if (f.size < 108664326) {
					var attrs = {
						file    : f,
						partKey : key,
						ind     : part.startInd,
						name    : f.name,
						notAFile: f.notAFile,
						type    : f.type,
						src     : f.src,
						id      : f.file_id
					};

					var newFile = new UploadingFile(attrs);
					part.items.push(newFile);
					newFile.view.append(part.els.list);
					newFile.coll = part.items;
				}
				part.startInd++;
			}

			var totalSize = 0;
			part.items.forEach(function (file) {
				totalSize += file.size;
			});
			part.progress.totalSize = totalSize;
			part.pushedCount = pushedCount;

			this.checkLength();
		},
		setRestored         : function setRestored(fotoNvideo) {
			var _this2 = this;

			[fotoNvideo].forEach(function (files, ind) {

				files.forEach(function (file) {
					file.name = file.name || '';
					file.notAFile = true;
					file.type = file.type || 'image';
				});

				_this2.pushFilesForDownload('fotoNvideo', files);
			});
		},

		// Конец загрузки одного из файлов пользователя
		onFileLoadEnd       : function onFileLoadEnd(ev) {
			var _JSON$parse = JSON.parse(ev.detail);

			var partKey = _JSON$parse.partKey;
			var ind = _JSON$parse.ind;


			var part = this.parts[partKey];

			var file = this.findByInd(ind, partKey);
			var indInArr = part.items.indexOf(file);

			var isLast = indInArr === part.items.length - 1;

			if (isLast === false) {
				console.log('onFileLoadEnd isLast, indInArr, part.items:', indInArr, part.items);
				part.items[indInArr + 1].startUpload();  // Загрузить следующий файл
			} else {
				this.stopLoading(part);  // Завершить загрузку файлов
			}
			if (partKey === 'fotoNvideo') {
				this.checkLength();
			}
			//console.log(part);
			console.log(part.progress);
			part.progress.reset(); // weezy
			console.log(part.progress);
			//setDisplay(false);
		},
		onFileDelete        : function () {
			this.checkLength();
			setDisplay('imageUploaded');
			//setDisplay(true); // weezy
		},
		checkLength         : function () {
			var part = this.parts.fotoNvideo;
			//Проверить количество пригодных ресурсов
			var length = 0;
			for (var i = 0; i < part.items.length; i++) {
				var curItem = part.items[i];
				if (curItem.type.indexOf('image') > -1 || curItem.type.indexOf('video') > -1) {
					if (curItem.src !== null && curItem.id !== null) {
						length++;
					} else {
						if (curItem.file == null) {
							this.findByInd(curItem.ind, 'fotoNvideo').delete();
						}
					}
				} else {
					if (curItem.file == null) {
						this.findByInd(curItem.ind, 'fotoNvideo').delete();
					}
				}
			}
			var needed = part.maxLength;
			var remainPhotos = needed - length;
			var needPhotos = vars.configPage.minNumberFiles - length

			// Есть загруженные фото
			if (remainPhotos < 1) { // Загружено максимальное кол-во фото
				document.querySelector('.btn-upload-media').classList.add('d-none');
				part.isFull = true;
			} else {
				part.isFull = false;
			}

			if (needPhotos > 0) {
				jQuery(part.els.messCounter).show();
				console.log(part.els.counter);
				part.els.counter.innerHTML = needPhotos;
			}
			else {
				jQuery(part.els.messCounter).hide();
			}

			addNumbers();
		},

		onChunkLoad         : function onChunkLoad(ev) {
			var _JSON$parse2 = JSON.parse(ev.detail);
			var chunkSize = _JSON$parse2.chunkSize;
			var partKey = _JSON$parse2.partKey;
			var part = this.parts[partKey];
			part.progress.loaded += chunkSize;
		},
		onListClick         : function onListClick(ev) {
			var trg = ev.target;
			if (!trg.classList.contains('del_media')) return;

			var ind = parseInt(trg.dataset.ind);
			if (isNaN(ind)) return;

			var file = this.findByInd(ind, 'fotoNvideo');
			if (file === null) return;

			file.delete();
		},
		findByInd           : function findByInd(ind, partKey) {
			var file = null;
			var items = this.parts[partKey].items;
			for (var j = items.length - 1; j >= 0; j--) {
				if (items[j].ind === ind) {
					file = items[j];
					break;
				}
			}
			return file;
		},

		// Закончить загрузку порции файлов пользователя TODO: Возможно, лучше сюда переместить обрезку фото
		stopLoading         : function stopLoading(part) {
			console.log('stopLoading');
			setDisplay('cropHidden');
			part.loading = false;
			part.progress.stopTimer();
			part.progress.el.style.display = 'none';
			this.resetInput(part.els.inp);

			// weezy.app: Запустить обрезку всех загруженных файлов пользователя, не содержащих данные об обрезке
			if (vars.configPage.isNeedCropping == 0) {
				return;
			}
			console.log(jQuery('#fotoNVideoContainer img'));
			jQuery('#fotoNVideoContainer img').each(function (index, tempImg) {

				//console.log( 'index : ' + index );
				//console.log( tempImg );
				//console.log(jQuery(tempImg).attr('data-crop'));
				if (!jQuery(tempImg).attr('data-crop')) {

					// Открыть редактор резки с появлением превьюшки загруженного изображения
					//jQuery('#modalCrop').modal('show');
					//setDisplay(false);
					var cropCreateTimeout = setInterval(function () {
						console.log(tempImg);
						console.log('cropping.$croppedMedia: ', cropping.$croppedMedia);
						if (!cropping.$croppedMedia) {
							console.log(cropper);
							console.log(cropping.imageCrop);
							console.log(jQuery(cropping.imageCrop));
							jQuery(cropping.imageCrop).cropper('destroy');
							console.log(cropper);
							cropping.createCrop(tempImg);
							clearInterval(cropCreateTimeout);
						}
					}, 100);
				}

			}).promise().done( function(){ jQuery('#stepListMedia').removeClass('processing'); } );
		},

		resetInput: function (inp) {
			var form = document.createElement('form');
			var par = inp.parentElement;
			par.insertBefore(form, inp);
			form.appendChild(inp);
			form.reset();
			par.insertBefore(inp, form);
			par.removeChild(form);
			form = null;
		},


		getData     : function () {
			var mediaList = this.parts.fotoNvideo.els.list;
			var media = this.parts.fotoNvideo;

			if (media.loading !== false)
				return null;

			var mediaData = media.items.map(function (file) {
				return {
					id  : file.id,
					ind : findInd(file.view.els.el),
					type: file.type
				};
			});
			return {
				media: mediaData
			};

			function findInd(el) {
				var list = mediaList.children;
				var length = list.length;
				for (var i = 0; i < length; i++) {
					if (list[i] === el) return i;
				}
			}
		},
		makeSortable: function makeSortable() {
			/* Сортировка не нужна на weezy.app, Ankudinov 2019-07-23
			 var el = this.parts.fotoNvideo.els.list;
			 new Sortable(el, {
			 draggable: 'li'
			 });
			 */
		}
	};

	// /LOADER


	function q(id) {
		return document.getElementById(id);
	}


	// CLASS TEXTS

	var Texts = function () {
		function Texts(wrp) {
			classCallCheck(this, Texts);

			this.wrp = wrp;
			this.findEls();
		}

		createClass(Texts, [{
			key  : 'findEls',
			value: function findEls() {
				/* Weezy 2020-04-20 Текстовыe поля теперь создаем на лету
				 var _this = this;

				 var els = window.A.toArr(this.wrp.querySelectorAll('li'));

				 els = els.map(function(el) {
				 var seconds = el.dataset.seconds;
				 var inp = el.querySelector('textarea');  // Weezy input[type=text]
				 //var inp = el.querySelector('input[type=text]');
				 inp.addEventListener('blur', _this.onInputBlur.bind(_this));

				 return {
				 // el,
				 seconds: seconds,
				 inp: inp,
				 val: function val(txt) {
				 if (txt !== undefined) {
				 this.inp.value = txt;
				 }
				 return this.inp.value;
				 },
				 validate: function validate() {
				 var val = this.val();
				 // var req = 1;
				 var req = this.inp.getAttribute('data-req');

				 // console.log(this);

				 // Проверить заполненность текстов перед отправкой
				 if (req == '1' && (val === '' || !is2words(val)))
				 {
				 var erName = this.inp.getAttribute('data-name');
				 var elemError = document.createElement('div');
				 elemError.classList.add('errortext');
				 if (val === '') {
				 elemError.innerHTML = 'add text for story';  // weezy
				 }
				 else {
				 elemError.innerHTML = 'add one word';
				 }
				 //elemError.innerHTML = 'Add your ' + erName;
				 var parentDiv = vars.btnSubmit.parentNode;
				 parentDiv.insertBefore(elemError, vars.btnSubmit);
				 this.addErrLis(elemError);
				 this.valid = false;
				 return false;
				 } else {
				 this.valid = true;
				 return true;
				 }
				 },
				 addErrLis: function addErrLis(elemError) {
				 this.inp.classList.add('hasError');
				 this.inp.onfocus = function(ev) {
				 var parentDiv = vars.btnSubmit.parentNode;
				 parentDiv.removeChild(elemError);
				 ev.target.classList.remove('hasError');
				 ev.target.onfocus = null;
				 };
				 }
				 };
				 });

				 this.els = els;
				 */
			}
		}, {
			key  : 'getData',
			value: function getData() {
				var validAll = true;
				var data = this.els.map(function (el) {
					var isValid = el.validate();
					if (isValid === false)
						validAll = false;

					return {
						seconds: el.seconds,
						text   : el.val()
					};
				});

				return {
					valid: validAll,
					texts: data
				};
			}
		}, {
			key  : 'setData',
			value: function setData() {
				var data = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];

				var els = this.els;
				data.forEach(function (txt) {
					for (var j = els.length - 1; j >= 0; j--) {
						if (txt.seconds === els[j].seconds) {
							els[j].val(txt.text);
							break;
						}
					}
				});
			}
		}, {
			key  : 'onInputBlur',
			value: function onInputBlur(ev) {
				///////////////// this.saveTxt();
				// let trg = ev.target
				// if ( trg.type === 'text' && trg.tagName.toLowerCase() === 'input' ) {
				//	 for ( let j = this.els.length - 1; j >= 0; j-- ) {
				//		 let txt = this.els[ j ]
				//		 if ( txt.inp === trg ) {
				//			 this.saveTxt( txt )
				//		 }
				//	 }
				// }
			}
		}, {
			key  : 'saveTxt',
			value: function saveTxt(txt) {
				var data = ['id_design=', vars.configPage.idDesign];
				data.push('texts=' + JSON.stringify(jQuery(form).serializeArray()));
				//data.push('texts=' + JSON.stringify(this.getData().texts));
				// data.push( `seconds=${txt.seconds}`, `text=${txt.val()}` )

				var URL_TXT_SAVE = vars.FF_root + '/app/ajax/ajax-save-text.php';
				fetch(URL_TXT_SAVE, {
					method : 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body   : data.join('&') + '&sid=' + ses_id + '&uident=' + ses_ident
				});
			}
		}]);
		return Texts;
	}();

	// /CLASS TEXTS


	// ОТПРАВИТЬ ЗАКАЗ

	function sendClipData(Loader, texts) {

		var data = collectData();
		if (data.isFull !== true)
			return;

		delete data.isFull;

		sendData(data);

		function collectData() {

			var result = {
				isFull: true
			};

			var email = getEmail();
			result.email = email.val;
			if (email.valid === false) {
				result.isFull = false;
			}

			/* 2020-04-22 Weezy, текстовые поля теперь другие
			var txt = texts.getData();
			//result.texts = JSON.stringify(txt.texts).replace(/&/g, '%26');
			result.texts = encodeURIComponent(JSON.stringify(txt.texts));
			if (txt.valid === false) {
				result.isFull = false;
			}
			*/
			var files = Loader.getData();
			if (files === null) {
				result.isFull = false;
			} else {
				result.media = JSON.stringify(files.media);
				result.items = JSON.stringify(Loader.parts.fotoNvideo.items, function (key, value) {
					if (key == 'coll' || key == 'view') {  // TODO: Разобраться, что за coll и view?
						return undefined;
					}
					return value;
				});
			}

			return result;
		}

		// Отправить собранные данные скрипту сохранения заказа
		function sendData(data) {
			//yaCounter25315490.reachGoal('zakaz-video');
			setTimeout(function () {
				jQuery('.btn-arrow-left, .btn-arrow-right').hide();
				vars.btnSubmit.setAttribute("disabled", "disabled");
				vars.btnSubmit.value = 'processing your order ...';
				vars.btnSubmit.classList.add('loading');
				vars.btnSubmit.classList.remove('btn-outline-light');
				vars.btnSubmit.classList.remove('btn-light');
				vars.btnSubmit.classList.remove('btn');
				vars.btnSubmit.textContent = 'processing your order ...';
				setDisplay('formSubmit'); // Wezzy
			}, 800);
			//return false;


			// Добавить данные из data-аттрибутов медиа-файлов в заказ
			var dataMediaFiles = [];
			jQuery('#fotoNVideoContainer li img').each(function () {
				var fileData = jQuery(this).data();

				// Не передавать на обработку некоторые data-аргументы медиа-файла
				delete fileData.src;
				delete fileData.height;
				delete fileData.width;
				delete fileData.cropCanvas;
				console.log(fileData);
				dataMediaFiles.push(fileData);
			});

			/*
			jQuery('#fotoNVideoContainer li img').each(function () {

				// Добавить ориентиацию фрейма в данные
				var $image = jQuery(this);
				var dataCropImage = $image.data('crop');
				dataCropImage.frame_orientation = $image.data('frame_orientation');
				dataCropImage.file = $image.data('file');

				// Добавить информацию о видео
				if ($image.data('type') == 'video') {
					dataCropImage.second = $image.data('second');
				}
				formDataCrop.push(dataCropImage);
			});
			*/

			var resolutionData = {
				"slideshow" : {
					'width' : vars.configPage.widthSlideshow,
					'height': vars.configPage.heightSlideshow
				},
				"frame_orientations": {
					"horizontal": {
						"width": vars.configPage.widthHorizontalFrame,
						"height": vars.configPage.heightHorizontalFrame
					},
					"vertical": {
						"width": vars.configPage.widthVerticalFrame,
						"height": vars.configPage.heightVerticalFrame
					}
				}
			};


			var ajaxBody = {
				sid        : ses_id,
				uident     : ses_ident,
				id_order   : vars.idOrder,
				less_photos: document.querySelector('#fotoNVideoContainer').dataset.less_photos,
				id_design  : vars.configPage.idDesign,
				//loader     : Loader,
				formData   : jQuery('form').serializeArray(),
				//formData   : jQuery('form').serializeObject(),
				//canvas_data: formDataCrop,
				media_files : dataMediaFiles,
				resolution : resolutionData,
				is_need_cropping: vars.configPage.isNeedCropping
			};

			Object.keys(data).forEach(function (key) {
				ajaxBody[key] = data[key];
			});

			console.log(ajaxBody);

			var URL_POST = vars.FF_root + '/app/ajax/ajax-create-order.php';
			fetch(URL_POST, {
				method : 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body   : jQuery.param(ajaxBody)
			}).then(function (resp) {
				console.log(resp);
				return resp.json();
			}).then(function (resp) {
				console.log(resp);
				//dataLayer.push({'event': 'zakaz'}); Deleted Andrew Ankudinov, вызывало ошибку посла переезда на slideshow-online.com, предположительно это обработка данных для Google Analytics посредством Google Tag Manager
				// yaCounter25315490.reachGoal('zakaz-video'); Deleted Andrew Ankudinov
				if (resp.id_order !== undefined) {

					jQuery('#configPage').attr('data-id-order', resp.id_order);
					jQuery('#modalMailEmail').html(vars.$emailOrder.val());
					jQuery('#modalMail').modal('show');

					//window.location = vars.FF_root + '/product/' + resp.id_order;
					//window.location = resp.order_url;
					vars.idOrder = resp.id_order;
				}
			});
		}

		function getEmail() {
			var inp = document.querySelector('#emailOrder');
			var val = inp.value;

			var res = {
				valid: true,
				val  : val
			};

			if (val === '' || !isEmail(val)) {
				res.valid = false;
				inp.classList.add('hasError');
				var elemError = document.createElement('div');
				elemError.classList.add('errortext');
				elemError.innerHTML = 'Add your e-mail';
				var parentDiv = vars.btnSubmit.parentNode;  // Weezy
				//var parentDiv = inp.parentNode;
				console.log(parentDiv);
				parentDiv.insertBefore(elemError, vars.btnSubmit);
				inp.onfocus = function () {
					inp.classList.remove('hasError');
					var parentDiv = vars.btnSubmit.parentNode;  // Weezy
					//var parentDiv = inp.parentNode;
					parentDiv.removeChild(elemError);
					inp.onfocus = null;
				};
			}

			return res;
		}
	}

	// ОТПРАВИТЬ ЗАКАЗ

	setPageStyles();

	var texts = void 0;

	function init() {

		texts = new Texts(document.getElementById('textsList'));

		addSendListener();

		// Создать объекты Loader, texts
		Loader.init();
		texts.setData();

		/*
		restoreOldSession().then(function (restored) {
			restored = restored || {};
			Loader.init(restored.media, restored.audio);
			texts.setData(restored.texts);
		});
		*/

	}
	init();

	function addSendListener() {
		document.querySelector('#btnSubmit').onclick = function () {
			//Очистить историю ошибок
			var parentDiv = vars.btnSubmit.parentNode;
			Array.from(parentDiv.querySelectorAll('.errortext')).forEach(function (item, i, arr) {
				parentDiv.removeChild(item);
			});
			sendClipData(Loader, texts);
			//return false;
		};
	}

	function setPageStyles() {
		var doc = document.documentElement;
		doc.style.overflow = 'visible';
		doc.style.overflowX = 'hidden';
		doc.style.height = 'auto';
		document.body.style.height = 'auto';
	}

	// ===================================
	

	//circularBarSet();
	//circularBarRotate(vars.$circularBar.find('.progress-right .progress-bar'), '45');


	// CHANGE TEXTAREA
	//console.log($('#textOrder')[0]);
	//autoHeight_($('#textOrder')[0]);
	//$('#textOrder').
	$('#boxTextareaCropping textarea')
		.on("keypress", function (e) {
			if ((e.keyCode == 10 || e.keyCode == 13)) {
				e.preventDefault();
				scrollToAnchor('btnSubmit');
				vars.$emailOrder.focus();
			}
		})
		.on('change input paste keyup propertychange', function () {
			if (this.getAttribute('maxLength') == this.value.length)
				jQuery('#messMaxLength').show();
			else
				jQuery('#messMaxLength').hide();
		});


	$('#fotoNVideoContainer')
		.on('change input paste keyup propertychange', 'textarea', function () {
			autoHeight_(this);
		});

	// /CHANGE TEXTAREA


	// Задержать клик закрытия модального окна для эффекта пузырей
	// Перейти на странинцу заказа после закрытия модального окна
	jQuery('#modalMailClose, #modalMailSubmit').click(function () {
		setTimeout(function () {
			jQuery('#modalMail').modal('hide');
		}, 800);
		return false;
	});
	jQuery('#modalMail').on('hidden.bs.modal', function (e) {
		var idOrder = $('#configPage').attr('data-id-order');
		window.location = vars.FF_root + '/product/' + idOrder;
	})

});

// /DOCUMENT READY


// Когда поля текста и почты заполнены - измени заливку кнопки
function highlightBtnSubmit() {
	var
		valueTextOrder = jQuery('#textOrder').val(),
		valueEmailOrder = vars.$emailOrder.val(),
		$btnSubmit = jQuery(vars.btnSubmit);
	if (
		is2words(valueTextOrder)
		&& valueEmailOrder != ''
		&& isEmail(valueEmailOrder)
	) {
		$btnSubmit.children().removeClass('d-none');
	} else {
		$btnSubmit.children().addClass('d-none');
	}
}
jQuery('#stepListMedia').on('input propertychange', '#textOrder, #emailOrder', highlightBtnSubmit);
//$textOrder.on('click', function() { scrollToAnchor('emailOrder'); });
vars.$emailOrder.on('click', function () {
	scrollToAnchor('btnSubmit');
});