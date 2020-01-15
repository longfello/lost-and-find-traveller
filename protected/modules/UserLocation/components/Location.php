<?php
/**
 * Created by PhpStorm.
 * User: Miloslawsky
 * Date: 04.12.2014
 * Time: 10:51
 */

class Location {
  const COOKIE = 1;
  const SESSION = 2;
  const DATABASE = 3;
  const GEOIP = 10;

  public $defaultCity = 524901;

  private $cookie  = 'uloc';
  private $session = 'UserLocation';

  /* @var Geoname */
  public $city;
  public $mode = self::SESSION;

  private $notFounded = false;

  public function __construct(){
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('application.modules.UserLocation.assets.js').'/modernizr.js'
        ),
        CClientScript::POS_END
    );

    $location = Yii::app()->session->get($this->session, false);
    if (!$location) {
      $this->mode = self::COOKIE;
      $location = isset(Yii::app()->request->cookies[$this->cookie]) ? Yii::app()->request->cookies[$this->cookie]->value : false;
      if (!$location) {
        if (Yii::app()->user->isGuest) {
          $this->city = $this->getCityIDByIp();
          $location = $this->city?$this->city->id:false;
          $this->mode = self::GEOIP;
        } else {
          $location = $location = Yii::app()->user->model()->city_id;
          $this->mode = self::DATABASE;
          if (!$location) {
            $this->city = $this->getCityIDByIp();
            $location = $this->city?$this->city->id:false;
            $this->mode = self::GEOIP;
          }
        }
        if (!$this->notFounded) Yii::app()->request->cookies[$this->cookie] = new CHttpCookie($this->cookie, $location);
      }
      if (!$this->notFounded) Yii::app()->session->add($this->session, $location);
    }

    if (!($this->city instanceof Geoname)) {
      $this->city = Geoname::model()->with_area()->findByPk($location);
    }
    if (!$this->city) {
      $this->city = Geoname::model()->with_area()->findByPk($this->defaultCity);
      $this->notFounded = true;
    }

    if ($this->notFounded) {
      Yii::app()->clientScript->registerScriptFile(
          Yii::app()->assetManager->publish(
              Yii::getPathOfAlias('application.modules.UserLocation.assets.js').'/locator.js'
          ),
          CClientScript::POS_END
      );
    }
  }

  public function init(){
    return true;
  }

  function set($id_or_NetCity){
    $id = ($id_or_NetCity instanceof Geoname)?$id_or_NetCity->id:$id_or_NetCity;
    $city = Geoname::model()->with_area()->findByPk($id);
    if ($city) {
      $this->city = $city;
      $this->save($id);
      if (!Yii::app()->user->isGuest) {
        Yii::app()->user->getModel()->city_id = $id;
        Yii::app()->user->getModel()->save();
      }
      return  true;
    }
    return false;
  }

  function clearCache(){
    Yii::app()->session->remove($this->session);
    unset(Yii::app()->request->cookies[$this->cookie]);
  }

  public function save($id){
    Yii::app()->session->add($this->session, $id);
    Yii::app()->request->cookies[$this->cookie] = new CHttpCookie($this->cookie, $id);
  }

  public function getCityIDByIp(){
    // IP-адрес, который нужно проверить
    $ip = Core::getIP();
//    $ip = (substr($ip, 0, 7) == '192.168')?"195.24.147.106":$ip;
    // Преобразуем IP в число
    $int = sprintf("%u", ip2long($ip));

    // Ищем город в глобальной базе
    $sql = "select * from (select * from net_city_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
    $result = Yii::app()->db->createCommand($sql)->queryRow();
    if ($result){
      $city    = Geoname::model()->with_area()->findByPk($result['geonameid']);
    }

    if (!$city) {
      $city    = Geoname::model()->with_area()->findByPk($result['geonameid']);
      if (!$city) {
        $city = Geoname::model()->with_area()->findByPk($this->defaultCity);
        $this->notFounded = true;
      }
    }

    return $city;
  }

}