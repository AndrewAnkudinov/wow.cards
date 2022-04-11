<?php

# ФОРМА УСКОРЕНИЯ/ОПЛАТЫ КЛИПА


ini_set('display_errors', 1);
error_reporting(E_ERROR);

# Подключить файл конфигурации сайта
# Подключить файл конфигурации магазинов оплаты
# Подключить ключевые файлы WP
require_once( __DIR__ . '/../app-config.php');
require_once( PATH_APP . '/payment/config_payment.php');
require_once( PATH_APP . '/payment/functions_payment.php');
require_once( PATH_ROOT . '/wp-config.php' );


# Создать массив с обязательными свойствами платежа
$payment = [];
foreach ([/*'price', */'id_product', 'type_payment', 'quantity_frames', 'hash'] as $item) {
	if (!isset($_GET[$item]))
		die ('Недостаточно данных оплаты');
	$payment[$item] = $_GET[$item];
}

# Проверить хэш данных оплаты
if ($payment['hash'] != payment\get_hash_payment_product($payment))
	die ('Неверный хэш данных оплаты');

# Цена продукта
$payment['price'] = payment\calc_price_product($payment);
$payment['name_product'] = 'Заказ №' . $payment['id_product'] . ' ' . $payment['type_payment']; // название продукта
$payment['return_url'] = home_url() . '/product/' . $payment['id_product'] . '?' . $payment['type_payment']; // URL страницы, которую надо загрузить, после оплты  // payment=
$payment['custom_data'] = [
	'id_product' => $payment['id_product'],
	'type_payment' => $payment['type_payment']
]; //

/*
# Email юзера
$userMail = $_REQUEST['userMail'];
if (!filter_var($userMail, FILTER_VALIDATE_EMAIL)) {
	die('E-mail адрес ' . $userMail . ' указан неверно.');
}
$orderNumber = $payment['id_product'] . $payment['type_payment'];
if ($userMail == 'my_works@mail.ru' || $payment['id_product'] == 675852)
	$payment['price'] = 1;
*/
$amount = sprintf( "%01.2f", $payment['price'] );


// СОЗДАТЬ ПЛАТЕЖ YOOKASSA

require __DIR__ . '/yandex/lib/autoload.php'; // Подключить класс для работы с YooKassa
use YooKassa\Client; // Импортировать класс

$client = new Client(); // Создать экземпляр объекта
$client->setAuth($config_payment['yandex']['shopId'], $config_payment['yandex']['ShopPassword']); // Устанавливить данные магазина (можно вбить для тестового магазина).
$idempotenceKey = uniqid('', true); // Создать идентификатор платежа

$payment_yookassa = $client->createPayment(
	array(
		'amount' => array(
			'value' => $payment['price'],
			'currency' => 'RUB',
		),
		'confirmation' => array(
			'type' => 'redirect',
			'return_url' => $payment['return_url'],
		),
		'capture' => true,
		'description' => $payment['name_product'],
		'metadata' => $payment['custom_data']
	),
	uniqid('', true)
);
//var_dump($payment_yookassa->confirmation);
//var_dump($payment);


// после выполнения запроса яндекс-касса не должна возвращать статус canceled. Отсутствие этого статуса означает
// что яндекс-касса вернула confirmation_url - то есть URL на который необходимо перенаправить клиента для оплаты,
// то есть ввода информации о банковской карте.
// Получить переменную $confirmation_url_yookassa которая или равна false или содержит URL для перенаправления для совершения оплаты клиентом
$confirmation_url_yookassa = false;
if (isset($payment_yookassa->status)
	and ($payment_yookassa->status != "canceled")
	and isset($payment_yookassa->confirmation->confirmation_url)) {
	$confirmation_url_yookassa = $payment_yookassa->confirmation->confirmation_url;
}

# /СОЗДАТЬ ПЛАТЕЖ YOOKASSA


# HTML

?>
<?php get_header(); ?>
	<?php

	if (is_user_logged_in()) {
		echo '<!--';
		var_dump($_REQUEST);
		echo '-->';
	}
	
	?>

	<div class="col-12 text-center">
		
		<div class="step" id="choosePaymentSystem">
			
			<div class="h2">Сумма к оплате:<br>
					<span class="text-danger"><?php echo $amount ?> руб.</span>
			</div>
			<br>
			<div class="row">
				<div class="col h4">Выберите способ оплаты:</div>
			</div>
			<div class="row">
				<ul class="list-group m-auto">
					<?php if ($confirmation_url_yookassa) { ?>
					<li class="list-group-item">
						<a href="<?php echo $confirmation_url_yookassa ?>" class="btn btn-primary">ЮKassa</a>
					</li>
					<?php } ?>
					<li class="list-group-item">
						<div id="checkoutCloudPayments" class="btn btn-primary" onclick="payCloudPayments()">CloudPayments</div>
					</li>
				</ul>
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

	<!-- FOOTER -->
<?php get_footer(); ?>