<?php

class DefaultController extends Controller
{
	public function actionIndex(){
		$location = Core::getPost('location', array());
    $lng = isset($location['longitude'])?$location['longitude']:false;
    $lat = isset($location['latitude'])?$location['latitude']:false;

    if ($lng && $lat) {
      $city = EGeocoderHelper::getCityByCoordMy($lat, $lng);
      if ($city && isset($city[0])) {
        Yii::app()->location->set($city[0]['id']);
        echo(json_encode(array('cmd' => 'reload')));
      } else {
        echo(json_encode(array('cmd' => 'ask')));
      }
    } else {
      echo(json_encode(array('cmd' => 'ask')));
    }

	}
}