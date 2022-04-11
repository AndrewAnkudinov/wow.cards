//*** MAIN ***

window.addEventListener('DOMContentLoaded', async function() {
    var urlLibCard = '/app/lib/designs/postcard';
	document.querySelector('.content').style.minHeight = 'calc(100vh - '
		+ (document.querySelector('header.site-header').offsetHeight
		+ document.querySelector('footer.site-footer').offsetHeight) + 'px)';
	//document.querySelector('.content').style.minHeight = 'calc(100vh - ' + (document.querySelector('.header').offsetHeight + document.querySelector('.footer').offsetHeight) + 'px)';
	//Определить размер конструктора
	let card = JSON.parse(document.querySelector('.constructor').dataset.card);
console.log(card);
	// TODO: откуда эти числа - 30 - 20 - 25, 30?
	let ratio = (document.querySelector('.content').offsetHeight - document.querySelector('.download').offsetHeight - 25 - 50) / card.height;
	//let ratio = (document.querySelector('.content').offsetHeight - document.querySelector('.download').offsetHeight - 30 - 20 - 25) / card.height;
	if (document.querySelector(' .content').offsetHeight > document.querySelector('.content').offsetWidth)
		ratio = (document.querySelector('.content').offsetWidth - 30) / card.height;
		//ratio = (document.querySelector('.content').offsetWidth - 25 - 50) / card.height;
	document.querySelector('.constructor').style.width = card.width * ratio + 'px';
	document.querySelector('.constructor').style.height = card.height * ratio + 'px';
	document.querySelector('.zoom-box').style.width = card.height * ratio + 'px';

	//Загрузить и отобразить открытку
	let template = await downloadImage(urlLibCard + '/images/' + card.template);
	let img = document.querySelector('.constructor .front');
	img.width = card.width * ratio;
	img.height = card.height * ratio;
	let ctx = img.getContext("2d");
	ctx.drawImage(template, 0, 0, img.width, img.height);
	//Создать картинку
	async function downloadImage(URL){
		return new Promise(async (resolve, reject) =>{
			let img = new Image();
			img.src = URL;
			img.onload = ()=> resolve(img);
		});
	}
	
	let decor;
	if (card.decor != undefined){
		//Загрузить и отобразить декорацию
		decor = await downloadImage(urlLibCard + '/images/' + card.decor);
		let img1 = document.querySelector('.constructor .decor');
		img1.classList.remove('hide');
		img1.width = card.width * ratio;
		img1.height = card.height * ratio;
		let ctx1 = img1.getContext("2d");
		ctx1.drawImage(decor, 0, 0, img1.width, img1.height);
	}

	//Добавить стиль при hover и шрифт
	let style = document.createElement('style');
	style.innerHTML = '.constructor:hover .text-area{border-color:' + card.txt.color + ';} ' +
					  (card.txtTwo != undefined ? '.constructor:hover .text-area.two{border-color:' + card.txtTwo.color + ';} ' : '') +
					  '@font-face{font-family:"' + card.txt.fontName + '"; src: url("' + urlLibCard + '/fonts/' + card.txt.fontFile + '");} ' +
					  (card.txtTwo != undefined ? '@font-face{font-family:"' + card.txtTwo.fontName + '"; src: url("' + urlLibCard + '/fonts/' + card.txtTwo.fontFile + '");} ' : '') +
					  'textarea::placeholder{color:' + card.txt.color + ';} ' +
					  (card.txtTwo != undefined ? 'textarea.two::placeholder{color:' + card.txtTwo.color + ';} ' : '') +
					  '.text-slot:focus-within .text-area{border-color:' + card.txt.color + ';}' +
					  (card.txtTwo != undefined ? '.text-slot.two:focus-within .text-area.two{border-color:' + card.txtTwo.color + ';}' : '');
	document.getElementsByTagName('head')[0].appendChild(style);

	function textAction(cardText, type){
		if (cardText.fontSize > 100) document.querySelector('.two.one-line').innerHTML = '0';
		//Позиция текстового блока
		let textSlot = document.querySelector('.constructor .text-slot' + type);
		if (type == '.two') textSlot.classList.remove('hide');
		textSlot.style.left = (cardText.X * ratio - 10) + 'px';
		textSlot.style.top = (cardText.Y * ratio - 6) + 'px';
		document.querySelector('.constructor .text-area' + type).style.width = (cardText.width * ratio) + 'px';
		document.querySelector('.constructor .text-area' + type).style.height = (cardText.height * ratio - 10) + 'px';
		if (cardText.rotate != 0) textSlot.style.transform = 'rotate(' + cardText.rotate + 'deg)';
		
		//Чтобы узнать высоту текста
		let OneLine = document.querySelector('.constructor .one-line' + type);
		OneLine.style.fontSize = (cardText.fontSize * ratio) + 'px';
		OneLine.style.fontFamily = cardText.fontName;
		OneLine.style.lineHeight = cardText.lineHeight / cardText.fontSize;
		if (cardText.upperCase == true) OneLine.style.textTransform = 'uppercase';
		
		//Для корректировки размера шрифта на мультилайн
		let multiLine = document.querySelector('.constructor .multi-line' + type);
		multiLine.value = (cardText.default == 'data' ? new Date().toLocaleDateString() : cardText.default);
		multiLine.style.fontSize = (cardText.fontSize * ratio) + 'px';
		multiLine.style.fontFamily = cardText.fontName;
		multiLine.style.width = (cardText.width * ratio) + 'px';
		multiLine.style.letterSpacing = (cardText.letterSpace / 1.1953125 * ratio) + 'px';
		multiLine.style.lineHeight = cardText.lineHeight / cardText.fontSize;
		if (cardText.upperCase == true) multiLine.style.textTransform = 'uppercase';
		multiLine.rows = cardText.lines;
		
		//Установить параметры текста
		let textContent = document.querySelector('.constructor .text-content' + type);
		textContent.placeholder = multiLine.value;
		textContent.style.color = cardText.color;
		textContent.style.fontSize = (cardText.fontSize * ratio) + 'px';
		textContent.style.fontFamily = cardText.fontName;
		textContent.style.width = (cardText.width * ratio) + 'px';
		textContent.style.height = (cardText.height * ratio) + 'px';
		textContent.style.lineHeight = cardText.lineHeight / cardText.fontSize;
		textContent.style.top = (cardText.marginTop * ratio) + 'px';
		textContent.style.paddingTop = (cardText.paddingTop * ratio) + 'px';
		textContent.style.letterSpacing = (cardText.letterSpace / 1.1953125 * ratio) + 'px';
		if (cardText.upperCase == true) textContent.style.textTransform = 'uppercase';
		
		//Изменение текста
		let startFontSize = cardText.fontSize * ratio;
		let endFontSize = startFontSize * 0.2;
		let curFontSize = startFontSize;
		let lastText = '';
		let lastFont = 0;
		document.querySelector('.text-content' + type).addEventListener('input', function(e){
			if (this.value != '' && this.value.substr(-4) == '    ' && this.value.length > lastText.length) this.value = lastText;
			function useSpace(txt){
				let pos = undefined;
				for (let i=0; i<txt.length; i++){
					if (txt.charAt(i) == ' ' && txt.charAt(i-1) != ' ') pos = i;
					if (txt.charAt(i) != ' ') pos = undefined;
				}
				if (pos == undefined) return txt;
				return txt.substr(0, pos+1) + 'w'.repeat(txt.length - pos);
			}
			multiLine.value = this.value != '' ? useSpace(this.value) : this.placeholder;

			//Увеличить шрифт
			while ((multiLine.scrollHeight < OneLine.scrollHeight * cardText.lines || cardText.lines == 1) && curFontSize < startFontSize){
				curFontSize += 2;
				multiLine.style.fontSize = curFontSize + 'px';
				OneLine.style.fontSize = curFontSize + 'px';
			}
			//Уменшить шрифт
			let lines = getLines(type);
			while ((multiLine.scrollHeight > OneLine.scrollHeight * cardText.lines) ||
			(lines[0].indexOf(' ') == -1 && lines[0].indexOf('-') == -1 && lines[0].indexOf('+') == -1 && lines[0].indexOf("\n") == -1)){
				if (curFontSize < endFontSize) break;
				if (lines.length == 1) break;
				curFontSize -= 2;
				//console.log('dec: ' + curFontSize);
				OneLine.style.fontSize = curFontSize + 'px';
				multiLine.style.fontSize = curFontSize + 'px';
				lines = getLines(type);
			}
			//Если не поместилось - отменить ввод
			if (multiLine.scrollHeight > OneLine.scrollHeight * cardText.lines){
				this.value = lastText;
				multiLine.value = lastText;
				curFontSize = lastFont;
				OneLine.style.fontSize = curFontSize + 'px';
				multiLine.style.fontSize = curFontSize + 'px';
			}
			//Центровка текста
			multiLine.rows = 1;
			if (multiLine.scrollHeight > OneLine.scrollHeight) multiLine.rows = cardText.lines;
			this.style.marginTop = ((cardText.height * ratio - multiLine.scrollHeight) / 2) + 3 + 'px';

			this.style.fontSize = curFontSize + 'px';
			lastText = this.value;
			lastFont = curFontSize;
		}, false);
	}
	textAction(card.txt, '');
	if (card.txtTwo != undefined) textAction(card.txtTwo, '.two');
	//document.querySelector('.constructor .text-content').focus();
	
	//*** ФОТО ***
	let user = {
		'X'     :0,											//смещение по X
		'Y'     :0,											//смещение по Y
		'W'     :0,											//макс смещение по ширине
		'H'     :0,											//макс смещение по высоте
		'canvas':{},
		'zoomW' :0,											//
		'zoomH' :0,											//
		'zoom'  :0											//масштаб
	}
	let userPhoto = undefined;	//Bitmap фото пользователя
	if (card.photo != undefined){
		let photoSlot = document.querySelector('.photo-slot');
		let photoEl = document.querySelector('.user-image');
		if (card.photo.rotate != 0) photoSlot.style.transform = 'rotate(' + card.photo.rotate + 'deg)';
		photoSlot.style.top = (card.photo.Y * ratio) + 'px';
		photoSlot.style.left = (card.photo.X * ratio + 1) + 'px';
		photoSlot.style.width = photoEl.style.width = (card.photo.width * ratio) + 'px';
		photoSlot.style.height = photoEl.style.height = (card.photo.height * ratio) + 'px';
		
		let filSelector = document.querySelector('.file-selector');
		filSelector.style.top = ((card.photo.Y + card.photo.height / 4) * ratio) + 'px';
		filSelector.style.left = ((card.photo.X + card.photo.width / 4) * ratio) + 'px';
		filSelector.style.width = (card.photo.width / 2 * ratio) + 'px';
		filSelector.style.height = (card.photo.height / 2 * ratio) + 'px';
		
		let removeEl = document.querySelector('.delete');
		removeEl.style.top = (card.photo.Y * ratio + 5) + 'px';
		removeEl.style.left = (card.photo.X * ratio + 5) + 'px';

		//Скрыть/показать нужные элементы
		function toggleHideShow(){
			document.querySelector('.user-image').classList.toggle('hide');
			document.querySelector('.file-selector').classList.toggle('hide');
			document.querySelector('.delete').classList.toggle('hide');
			document.querySelector('.photo-icon').classList.toggle('hide');
			document.querySelector('.zoom-box').classList.toggle('hide');
		}
		document.querySelector('.file-selector').addEventListener('click', () => document.querySelector('.file-selector input').click());

		//Выбор файла
		document.querySelector('.file-selector input').addEventListener('change', async function(e){
			if (e.target.files.length == 0) return;
			//Загрузить и отобразить фото пользователя
			userPhoto = await downloadImage(window.URL.createObjectURL(e.target.files[0]));
			let img = document.querySelector('.user-image');
			img.width = card.photo.width * ratio;
			img.height = card.photo.height * ratio;
			let ctx = img.getContext("2d");
			let rat = Math.max(img.width / userPhoto.width, img.height / userPhoto.height);
			let sWidth = img.width / rat;
			let sHeight = img.height / rat;
			user.X = ((userPhoto.width - sWidth) / 2) * rat;
			user.Y = ((userPhoto.height - sHeight) / 2) * rat;
			user.W = userPhoto.width * rat - img.width;
			user.H = userPhoto.height * rat - img.height;
			user.zoomW = userPhoto.width * rat;
			user.zoomH = userPhoto.height * rat;
			user.canvas.ctx = ctx;
			user.canvas.sWidth = sWidth;
			user.canvas.sHeight = sHeight;
			user.canvas.imgWidth = img.width;
			user.canvas.imgHeight = img.height;
			user.canvas.rat = rat;
			user.zoom = 0;
			updateImage(user.X, user.Y);
			
			toggleHideShow();
		});
		//Удалить картинку
		document.querySelector('.delete').addEventListener('click', () =>{
			userPhoto = undefined;
			document.querySelector('.file-selector input').value = '';
			toggleHideShow();
		})
	}
	//Zoom-out
	document.querySelector('#zoom_out').addEventListener('click', function(){
		if (user.zoom <= 0) return;
		user.zoom = user.zoom - 10;
		if (user.zoom < 0) user.zoom = 0;
		updateImage(user.X, user.Y);
	});
	//Zoom-in
	document.querySelector('#zoom_in').addEventListener('click', function(){
		if (user.zoom >= 100) return;
		user.zoom = user.zoom + 10;
		if (user.zoom > 100) user.zoom = 100;
		updateImage(user.X, user.Y);
	});
	//Перемещение бегунка
	let sliderOffsetX  = 0;
	let sliderPointerX = 0;
	let prevZoom = 0
	let sliderDown = false;
	document.querySelector('.slider-corner').addEventListener('mousedown', function(e){
		sliderDown = true;
		sliderOffsetX = e.clientX;
		prevZoom = user.zoom;
	}, true);
	document.addEventListener('mouseup', () => sliderDown = false, true);
	document.addEventListener('mousemove', function(event){
		if (sliderDown == true){
			//переместить картинку
			sliderPointerX = event.clientX - sliderOffsetX;
			user.zoom = prevZoom + parseInt((sliderPointerX / document.querySelector('.slider').offsetWidth) * 100);
			if (user.zoom > 100) user.zoom = 100;
			if (user.zoom < 0) user.zoom = 0;
			updateImage(user.X, user.Y);
		}
	}, true);
	
	//Обновить картинку
	function updateImage(X, Y){
		let zoomed = 1 + user.zoom / 100;
		if (X / user.canvas.rat + user.canvas.sWidth / zoomed > userPhoto.width) X = (userPhoto.width - user.canvas.sWidth / zoomed) * user.canvas.rat;
		if (Y / user.canvas.rat + user.canvas.sHeight / zoomed > userPhoto.height) Y = (userPhoto.height - user.canvas.sHeight / zoomed) * user.canvas.rat;

		document.querySelector('.slider-corner').style.left = user.zoom + '%';
		document.querySelector('.slider-tooltip').innerHTML = user.zoom + '%';

		user.canvas.ctx.clearRect(0, 0, user.canvas.imgWidth, user.canvas.imgHeight);
		user.canvas.ctx.drawImage(userPhoto, X / user.canvas.rat, Y / user.canvas.rat, user.canvas.sWidth / zoomed, user.canvas.sHeight / zoomed, 0, 0, user.canvas.imgWidth, user.canvas.imgHeight);
	}
	//Перемещение картинки
	let offset  = {'x':0, 'y':0};
	let pointer = {'x':0, 'y':0};
	let localX = 0, localY = 0;
	let isDown = false;
	document.querySelector('.user-image').addEventListener(('ontouchstart' in window ? 'touchstart' : 'mousedown'), function(e){
		isDown = true;
		offset = {'x':(e.touches != undefined ? e.touches[0].clientX : e.clientX), 'y':(e.touches != undefined ? e.touches[0].clientY : e.clientY)};
	}, true);
	document.addEventListener(('ontouchstart' in window ? 'touchend' : 'mouseup'), () =>{
		if (isDown == true){
			user.X = localX;
			user.Y = localY;
		}
		isDown = false;
	}, true);
	document.addEventListener(('ontouchstart' in window ? 'touchmove' : 'mousemove'), function(event){
		if (isDown == true){
			//переместить картинку
			pointer = {'x':(event.touches != undefined ? event.touches[0].clientX : event.clientX) - offset.x,
			           'y':(event.touches != undefined ? event.touches[0].clientY : event.clientY) - offset.y};
		   let zoomed = user.zoom / 100;
			localX = user.X - pointer.x / (1 + zoomed);
			localY = user.Y - pointer.y / (1 + zoomed);
			if (localX < 0) localX = 0;
			if (localY < 0) localY = 0;
			if (localX > user.W + user.zoomW * zoomed) localX = user.W + user.zoomW * zoomed;
			if (localY > user.H + user.zoomH * zoomed) localY = user.H + user.zoomH * zoomed;
			updateImage(localX, localY);
		}
	}, true);
	//************

	//Все ресурсы загружены
	document.fonts.ready.then(() => {
		document.querySelector('.constructor .text-content').style.marginTop =
				((card.txt.height * ratio - document.querySelector('.constructor .multi-line').scrollHeight) / 2) + 3 + 'px';
		if (card.txtTwo != undefined) document.querySelector('.constructor .text-content.two').style.marginTop =
				((card.txtTwo.height * ratio - document.querySelector('.constructor .multi-line.two').scrollHeight) / 2) + 3 + 'px';
		document.querySelector('.idle').classList.add('hide');
		//Заменить текст по умолчанию
		let defaultText = card.txt.default;
		let defaultText2 = undefined;
		if (card.txtTwo != undefined && card.txtTwo.default != undefined) {
			defaultText2 = card.txtTwo.default;
		}
		var event = new Event('input', {bubbles: true, cancelable: true});
		document.querySelector('.text-content').value = defaultText;
		document.querySelector('.text-content').dispatchEvent(event);
		if (card.txtTwo != undefined && defaultText2 != undefined) {
			document.querySelector('.text-content.two').value = defaultText2;
			document.querySelector('.text-content.two').dispatchEvent(event);
		}
		/*
		var event = new Event('input', {bubbles: true, cancelable: true});
		document.querySelector('.text-content').value = card.txt.default;
		document.querySelector('.text-content').dispatchEvent(event);
		if (card.txtTwo != undefined && card.txtTwo.default != undefined){
			document.querySelector('.text-content.two').value = card.txtTwo.default;
			document.querySelector('.text-content.two').dispatchEvent(event);
		}
		*/
	});

	//Сформировать и скачать картинку
	document.querySelector('button.download').addEventListener('click', async() =>{
		this.disabled = true;
		//Нарисовать картинку
		let canvas = document.querySelector('#out');
		canvas.width = card.width;
		canvas.height = card.height;
		let ctx = canvas.getContext("2d");

		//Нарисовать фото пользователя
		if (card.photo != undefined && userPhoto != undefined){
			if (card.photo.rotate != 0){
				ctx.save();
				ctx.rotate(card.photo.rotate * Math.PI / 180);
			}
			let zoomed = 1 + user.zoom / 100;
			let X = user.X, Y = user.Y;
			if (X / user.canvas.rat + user.canvas.sWidth / zoomed > userPhoto.width) X = (userPhoto.width - user.canvas.sWidth / zoomed) * user.canvas.rat;
			if (Y / user.canvas.rat + user.canvas.sHeight / zoomed > userPhoto.height) Y = (userPhoto.height - user.canvas.sHeight / zoomed) * user.canvas.rat;

			ctx.drawImage(userPhoto, X / user.canvas.rat, Y / user.canvas.rat, user.canvas.sWidth / zoomed, user.canvas.sHeight / zoomed, card.photo.X + (card.photo.marginX != undefined ? card.photo.marginX : 0), card.photo.Y + (card.photo.marginY != undefined ? card.photo.marginY : 0), card.photo.width, card.photo.height);
			if (card.photo.rotate != 0) ctx.restore();
		}
		ctx.drawImage(template, 0, 0, canvas.width, canvas.height);	//Картинка
		
		//тексты
		canvas.style.letterSpacing = card.txt.drawletterSpace + 'px';
		ctx = canvas.getContext("2d");
		drawText(ctx, card.txt, ratio, ((card.txt.height * ratio - document.querySelector('.constructor .multi-line').scrollHeight) / 2) + 3, '');
		if (card.txtTwo != undefined){
			canvas.style.letterSpacing = card.txtTwo.drawletterSpace + 'px';
			ctx = canvas.getContext("2d");
			drawText(ctx, card.txtTwo, ratio,
						((card.txtTwo.height * ratio - document.querySelector('.constructor .multi-line.two').scrollHeight) / 2) + 3, '.two');
		}
		
		//Нарисовать декор
		if (card.decor != undefined){
			let ctx = canvas.getContext("2d");
			ctx.drawImage(decor, 0, 0, canvas.width, canvas.height);
		}

		//Нарисовать лого
		if (card.logo != undefined){
			let logo = await downloadImage(urlLibCard + '/logo/' + card.logo);
			let ctx = canvas.getContext("2d");
			ctx.drawImage(logo, 0, 0, canvas.width, canvas.height);
		}

		//Сохранить картинку
		function dataURItoBlob(dataURI){
		  let mime = dataURI.split(',')[0].split(':')[1].split(';')[0];
		  let binary = atob(dataURI.split(',')[1]);
		  let array = [];
		  for (let i=0; i<binary.length; i++) array.push(binary.charCodeAt(i));
		  return new Blob([new Uint8Array(array)], {type: mime});
		}
		
		if (location.href.indexOf('localhost') != -1){
			canvas.classList.remove('hide');
		}else{
			let save_link = document.createElement('a');
			save_link.href = URL.createObjectURL(dataURItoBlob(canvas.toDataURL('image/jpeg')));
			save_link.download = card.name + '.jpg';
			let event = document.createEvent('MouseEvents');
			event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
			save_link.dispatchEvent(event);
		}
		this.disabled = false;

		window.location.href = document.querySelector('.constructor').dataset.urlRedirect;
		//window.location.replace(document.querySelector('.constructor').dataset.urlRedirect);
		//$('#stepCreatePostcard').hide();
		//$('#stepSuggestFormatsCurrentCategory').show();
	});
});

//Нарисовать текст
function drawText(ctx, cardText, ratio, margin, type){
	//Получить строки
	let fontSize = parseFloat(document.querySelector('.constructor .multi-line' + type).style.fontSize);
	let multiLine = document.querySelector('.constructor .multi-line' + type).cloneNode(true);
	document.querySelector('.constructor .multi-line' + type).parentNode.appendChild(multiLine);
	if (multiLine.wrap){
		multiLine.setAttribute("wrap", "off");
	}else{
		multiLine.setAttribute("wrap", "off");
		let newArea = multiLine.cloneNode(true);
		newArea.value = multiLine.value;
		multiLine.parentNode.replaceChild(newArea, multiLine);
		multiLine = newArea;
	}
	let strRawValue = multiLine.value;
	multiLine.value = "";
	let nEmptyWidth = multiLine.scrollWidth;
	let nLastWrappingIndex = -1;
	for (let i=0; i<strRawValue.length; i++){
		let curChar = strRawValue.charAt(i);
		if (curChar == ' ' || curChar == '-' || curChar == '+') nLastWrappingIndex = i;
		multiLine.value += curChar;
		if (multiLine.scrollWidth > nEmptyWidth){
			let buffer = "";
			if (nLastWrappingIndex >= 0){
				for (let j = nLastWrappingIndex + 1; j < i; j++) buffer += strRawValue.charAt(j);
				nLastWrappingIndex = -1;
			}
			buffer += curChar;
			multiLine.value = multiLine.value.substr(0, multiLine.value.length - buffer.length);
			multiLine.value += "\n" + buffer;
		}
	}
	multiLine.parentNode.removeChild(multiLine);
	let lines = multiLine.value.split("\n");

	//Нарисовать текст
	ctx.fillStyle = cardText.color;
 	ctx.textBaseline = "top";
	ctx.textAlign = "center";
	if (cardText.rotate != 0){
		ctx.save();
		ctx.rotate(cardText.rotate * Math.PI / 180);
	}
	for (let i=0; i<lines.length; i++){
		let line = cardText.upperCase == true ? lines[i].replace(/w(?=w*$)/g, " ").toLocaleUpperCase() : lines[i].replace(/w(?=w*$)/g, " ");
		if (i == lines.length - 1 && document.querySelector('.text-content' + type).value.substr(-1) == 'w'){
			line = line.substr(0, line.length - 1) + 'w';
		}
		ctx.font = (fontSize / ratio) + 'px ' + cardText.fontName;
		let X = cardText.X + (cardText.width / 2) + cardText.leftConstant;
		let Y = cardText.Y + margin / ratio + cardText.topConstant;
		Y += (fontSize / ratio) * (cardText.lineHeight / cardText.fontSize) * i;
		ctx.fillText(line, X, Y);
	}
	if (cardText.rotate != 0) ctx.restore();
}

function getLines(type){
	let mlt = document.querySelector('.constructor .multi-line' + type).cloneNode(true);
	document.querySelector('.constructor .multi-line' + type).parentNode.appendChild(mlt);
	if (mlt.wrap){
		mlt.setAttribute("wrap", "off");
	}else{
		mlt.setAttribute("wrap", "off");
		let newArea = mlt.cloneNode(true);
		newArea.value = mlt.value;
		mlt.parentNode.replaceChild(newArea, mlt);
		mlt = newArea;
	}
	let strRawValue = mlt.value;
	mlt.value = "";
	let nEmptyWidth = mlt.scrollWidth;
	let nLastWrappingIndex = -1;
	for (let i=0; i<strRawValue.length; i++){
		let curChar = strRawValue.charAt(i);
		if (curChar == ' ' || curChar == '-' || curChar == '+') nLastWrappingIndex = i;
		mlt.value += curChar;
		if (mlt.scrollWidth > nEmptyWidth){
			let buffer = "";
			if (nLastWrappingIndex >= 0){
				for (let j = nLastWrappingIndex + 1; j < i; j++) buffer += strRawValue.charAt(j);
				nLastWrappingIndex = -1;
			}
			buffer += curChar;
			mlt.value = mlt.value.substr(0, mlt.value.length - buffer.length);
			mlt.value += "|" + buffer;
		}
	}
	mlt.parentNode.removeChild(mlt);
	return mlt.value.split("|");
}