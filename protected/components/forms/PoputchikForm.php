<?php

  class PoputchikForm extends EFormModel {
    const DEFAULT_TYPE_ROUTE = PpRoute::TYPE_ROUTE_ANOTHER;
    const DEFAULT_TYPE       = PpRoute::TYPE_DRIVER;

    public $typeRoute = self::DEFAULT_TYPE_ROUTE;
    public $type      = PpRoute::TYPE_DRIVER;
    public $from      = false;
    public $to        = false;
    /* @var Geoname|bool*/
    public $cityFrom  = false;
    /* @var Geoname|bool*/
    public $cityTo    = false;
    public $date      = false;
    public $class     = false;
    public $seats     = 1;
    public $page      = 1;

    public $isAdditionalFilled = false;
    public $isIndex = false;

    public $layout = 'index';

    public function __construct($owner=null){
      parent::__construct($owner);
      $this->_getParams();
      $this->_validate();

      $this->isAdditionalFilled = (
          $this->date ||
          $this->class ||
          $this->seats
      );

      $this->isIndex = !(
        $this->cityFrom ||
        $this->cityTo ||
        $this->isAdditionalFilled
      );


      // print_r($this->to); die();
    }

    public function getCityFrom(){
      return $this->cityFrom?$this->cityFrom:new Geoname();
    }

    public function getCityTo(){
      return $this->cityTo?$this->cityTo:new Geoname();
    }

    private function _getParams(){
      $this->typeRoute = Core::getGet('type_route', $this->typeRoute);
      $this->type      = Core::getGet('type',       $this->type);
      $this->from      = Core::getGet('from',       false);
      $this->to        = Core::getGet('to',         false);
      if ($slug = Core::getGet('cityFrom',   false)) $this->cityFrom = Geoname::model()->findByAttributes(array('slug' => mb_strtolower($slug)));
      if ($slug = Core::getGet('cityTo',   false))   $this->cityTo   = Geoname::model()->findByAttributes(array('slug' => mb_strtolower($slug)));
      $this->date      = Core::getGet('date', false);
      $this->class     = Core::getGet('class', false);
      $this->seats     = Core::getGet('seats', false, type_int);
      $this->page      = Core::getGet('page', false, type_int);
    }
    private function _validate(){
      if ($this->from) {
        $els = explode(',', $this->from);
        if (count($els) >=3){
          $lat = array_shift($els);
          $lng = array_shift($els);
          $this->from = array(
              'lat'  => floatval($lat),
              'lng'  => floatval($lng),
            'name' => htmlspecialchars(implode(',',$els)),
          );
        } else {
          $this->from = false;
        }
      }

      if ($this->to) {
        $els = explode(',', $this->to);
        if (count($els) >=3){
          $lat = array_shift($els);
          $lng = array_shift($els);
          $this->to = array(
              'lat'  => floatval($lat),
              'lng'  => floatval($lng),
            'name' => htmlspecialchars(implode(',',$els)),
          );
        } else {
          $this->to = false;
        }
      }

      if (!in_array($this->typeRoute, array(PpRoute::TYPE_ROUTE_SAME, PpRoute::TYPE_ROUTE_ANOTHER))) {
        $this->typeRoute = self::DEFAULT_TYPE_ROUTE;
      }
      if (!in_array($this->type, array(PpRoute::TYPE_DRIVER, PpRoute::TYPE_PASSENGER))) {
        $this->typeRoute = self::DEFAULT_TYPE;
      }

      $d = explode('.', $this->date);
      if (count($d) == 3) {
        $this->date = mktime(0,0,0,$d[1], $d[0], $d[2]);
      } else {
        $this->date = false;
      }

      if (!in_array($this->class, AutoModels::getClassesList())) {
        $this->class = false;
      }

      $this->seats = ($this->seats > 4 || $this->seats < 1)?false:$this->seats;
    }

  }