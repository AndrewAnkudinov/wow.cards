<?php

# AJAX: СОХРАНИТЬ В СЕССИЮ ТЕКСТЫ ЗАКАЗА

if ( $_POST['id_design'] && $_POST['texts'] )
{
	//$_POST['sid'] = '4f4e24d6656db3daaa4ef123da49c56f';
	$sid = $_POST['sid'];
	session_id( $sid ); // Применяем сессию с конкретным ID полученным из POST
	if ( ! isset( $_SESSION ) ) {
		session_start();
	}

	$_SESSION['BX_UPLOADED_CLIPS'][ $_POST['id_design'] ]['text'] = json_decode( $_POST['image_captions'], true );
	echo json_encode( array( 'stasus' => 'success' ) );
}
