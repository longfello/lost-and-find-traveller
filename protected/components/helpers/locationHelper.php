<?php

  class locationHelper {
    /* @var Settlements */
    public $city;
    /* @var Regions */
    public $region;
    /* @var Countries */
    public $country;

    public function __construct($locationID){
      $this->city = Settlements::model()->findByPk($locationID);
      $this->city = $this->city?$this->city:new Settlements();

      $this->region = Regions::model()->findByPk($this->city->id_region);
      $this->region = $this->region?$this->region:new Regions();

      $this->country = Countries::model()->findByPk($this->region->id_country);
      $this->country = $this->country?$this->country:new Countries();
    }

  }