<?php
/**
 * SMS API
 *
 * @author Aliscom
 */
// настройки API
define ('API_KEY', 'e83c3684-d8c7-fec4-4968-875acb9293c8');

class smsgate extends CApplicationComponent {
    

    public function send($phone, $text) {
		// параметры
		$api_params = array(
			"api_id" => API_KEY,
			'from' => 'INFOtoway',
			'to' => $phone,
			'text' => $text
		);
		
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($api_params),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents('http://sms.ru/sms/send', false, $context);
		
		$result = explode("\n", $result);
		//print_r($result);
		// результат
		if($result[0] == "100") return 0;
		else if($result[0] == "207") return 1;
		else return -1;
    }
}

?>