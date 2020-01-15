<?php

  class CityAcWidget extends CWidget {
    public $name = null;
    public $lng  = null;
    public $lat  = null;
    public $id   = null;
    public $htmlOptions = array();
    public $class='';
    public $empty=false;
    public $locate=true;
    public $slug=false;
    public $area = array(
      'start' => array('lat' => -90, 'lng' => -90),
      'end'   => array('lat' =>  90, 'lng' =>  90),
    );

    private $located = false;
    private $lang;

    public function run() {
      $this->lang = Yii::app()->getLanguage();
      $this->locate();
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.CityAc').'/index.php', array(
        'area' => json_encode(array(
          'start' => array(
            'lat' => min($this->area['start']['lat'], $this->area['end']['lat']),
            'lng' => min($this->area['start']['lng'], $this->area['end']['lng']),
          ),
          'end' => array(
            'lat' => max($this->area['start']['lat'], $this->area['end']['lat']),
            'lng' => max($this->area['start']['lng'], $this->area['end']['lng']),
          ),
      ))), TRUE);
    }

    private function locate(){
      if (!$this->empty) {
        $this->locateById();
        if (!$this->located && $this->locate){
          $this->locateByGeo();
        }
        if (!$this->located && $this->locate){
          $this->locateByName();
        }
        if (!$this->located && $this->locate){
          $this->locateDefault();
        }
      }
    }

    private function locateById(){
      if (!is_null($this->id)) {
        $info = Geoname::model()->with_area()->findByPk($this->id);
        if ($info) {
          /* @var $info Geoname */
          $this->name = EGeocoderHelper::getFullName($this->id);
          $this->lng  = $info->longitude;
          $this->lat  = $info->latitude;
          $this->slug = $info->slug;
          $this->area['start']['lat'] = $info->start_lat;
          $this->area['start']['lng'] = $info->start_lng;
          $this->area['end']['lat']   = $info->end_lat;
          $this->area['end']['lng']   = $info->end_lng;

          $this->located = true;
        }
      }
    }
    private function locateByName(){
      if ($this->name) {
        $locations = EGeocoderHelper::getCityByNameMy($this->name);
        if ($locations) {
          $info = $locations[0];
          $this->id   = $info['id'];
          $this->name = EGeocoderHelper::getFullName($this->id);
          $this->lng                  = $info['longitude'];
          $this->lat                  = $info['latitude'];
          $this->slug                 = $info->slug;
          $this->area['start']['lat'] = $info['start_lat'];
          $this->area['start']['lng'] = $info['start_lng'];
          $this->area['end']['lat']   = $info['end_lat'];
          $this->area['end']['lng']   = $info['end_lng'];

          $this->located = true;
        }
      }
    }
    private function locateDefault(){
      $info = Yii::app()->location->city;
      if ($info) {
        $this->id   = $info->id;
        $this->name = EGeocoderHelper::getFullName($info->id);
        $this->lng                  = $info->longitude;
        $this->lat                  = $info->latitude;
        $this->slug                 = $info->slug;
        $this->area['start']['lat'] = $info->start_lat;
        $this->area['start']['lng'] = $info->start_lng;
        $this->area['end']['lat']   = $info->end_lat;
        $this->area['end']['lng']   = $info->end_lng;

        $this->located = true;
      }
    }
    private function locateByGeo(){
      if ($this->lng && $this->lat) {
        $locations = EGeocoderHelper::getCityByCoordMy($this->lat, $this->lng);
        if ($locations) {
          $info = $locations[0];
          $this->id   = $info['id'];
          $this->name = EGeocoderHelper::getFullName($this->id);
          $this->lng                  = $info['longitude'];
          $this->lat                  = $info['latitude'];
          $this->slug                 = $info->slug;
          $this->area['start']['lat'] = $info['start_lat'];
          $this->area['start']['lng'] = $info['start_lng'];
          $this->area['end']['lat']   = $info['end_lat'];
          $this->area['end']['lng']   = $info['end_lng'];

          $this->located = true;
        }
      }
    }
  }
