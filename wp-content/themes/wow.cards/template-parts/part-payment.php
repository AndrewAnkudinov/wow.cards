<?php

/*
Template Name: Страница оплаты
*/
//var_dump($post_meta);
# Подключить файл конфигурации магазинов оплаты
require_once( PATH_APP . '/payment/config_payment.php');
require_once( PATH_APP . '/lib/designs/Designs.php');

# Создать массив со свойствами платежа
$payment = [
	'id_product' => ID_PRODUCT,
	'type_payment' => 'buy',
	'price' => Designs::get_part_price_product($post_meta['price'][0], 2)
];
//var_dump($payment);

# Цена продукта
/*
require_once( PATH_APP . '/payment/functions_payment.php');
$price = payment\calc_price_product($payment['quantity_frames']);
$payment['price'] = payment\get_part_price_product($price, 2);
*/
$payment['name_product'] = 'Заказ №' . $payment['id_product'] . ' ' . $payment['type_payment']; // название продукта
$payment['return_url'] = home_url() . '/product/' . $payment['id_product'] . '?' . $payment['type_payment']; // URL страницы, которую надо загрузить, после оплты  // payment=
$payment['custom_data'] = [
	'id_product' => $payment['id_product'],
	'type_payment' => $payment['type_payment']
]; //

# HTML

?>
	<div class="col-12" style="background: url(<?php echo get_stylesheet_directory_uri() ?>/assets/img/bg_stain.svg) center center no-repeat;">

		<div class="step" id="main">
			<div class="row">
				<div class="col-3 text-center container-content d-flex flex-column justify-content-center align-items-end">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/pony_1.png" alt="">
				</div>
				<div class="col-6 text-center">
			
					<div class="container-content d-flex flex-column justify-content-center align-items-center">
						<div class="container-h1">
							<h1>Скачать видео
								<br>в <span class="text-danger">full-hd</span> качестве и без водяных знаков
								<br>можно после оплаты в <span class="text-danger"><u><?php echo $payment['price'] ?> ₽</u></span>.</h1>
						</div>
						<div style="margin: calc(7vh) 0 calc(15vh);">
							<?php
							/*
							<a href="<?php echo payment\build_url_page_payment($payment) ?>" class="btn btn-danger shadow mx-auto bubbly-button">
								Оплатить
							</a>
							*/
							?>
	
							<a href="#"
							   class="btn btn-danger shadow mx-auto bubbly-button"
							   onclick="payCloudPayments()">
								Оплатить
							</a>
							<span class="arrow_clockwise shake shake-constant"></span>
						</div>
			
					</div>
			
				</div>
				<!--
				<div class="col-3 text-center container-content d-flex flex-column justify-content-end align-items-start">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/pony_2.png" alt="">
				</div>
				-->
			</div>
		</div>
		
		<!-- Форма оплаты cloudpayments -->
		<div class="step" id="cloudPayments" style="display: none;">

			<script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>
			<script>
				this.payCloudPayments = function () {
					var widget = new cp.CloudPayments({language: "<?php echo str_replace('_', '-', get_locale()) ?>"});
					widget.pay('auth', // или 'charge'
						{ //options
							publicId: '<?php echo $config_payment['cloudpayments']['publicId'] ?>', //id из личного кабинета
							description: '<?php echo $payment['name_product'] ?>', //назначение
							amount: <?php echo $payment['price'] ?>, //сумма
							currency: 'RUB', //валюта
							//accountId: 'user@example.com', //идентификатор плательщика (необязательно)
							invoiceId: '<?php echo $payment['id_product'] ?>', //номер заказа  (необязательно)
							skin: "mini", //дизайн виджета (необязательно)
							data: <?php echo json_encode($payment['custom_data']) ?>
						},
						{
							onSuccess: function (options) { // success
								//действие при успешной оплате
								document.location.href = '<?php echo $payment['return_url'] ?>'
							},
							onFail: function (reason, options) { // fail
								//действие при неуспешной оплате
							},
							onComplete: function (paymentResult, options) { //Вызывается как только виджет получает от api.cloudpayments ответ с результатом транзакции.
								//например вызов вашей аналитики Facebook Pixel
							}
						}
					)
				};
				//$('#checkoutCloudPayments').click(payCloudPayments);
			</script>

		</div>

	</div>

	<script>

		// Показать/скрыть шаги страницы
		function showStep(visible) {
			jQuery('#content .step').each( function( index, element ) {
				var $element = jQuery(element);
				if (element.id == visible) {
					$element.show();
				} else {
					$element.hide();
				}
			} );
		}

	</script>