<?php
class SmsServiceApi {
	/**
	 * Адрес API
	 * @var unknown_type
	 */

	public function send ($api_params=null) {
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($api_params),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents('http://api.sms-mir.ru/message/send', false, $context);
		
		return json_decode($result);
	}
}
?>