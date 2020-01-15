<?php

  abstract class PoputchikSearcher_prototype {
    public $name;
    public $form;
    public $results = array();
    public $count = 0;
    public $pages;
    public $onPage = 10;
    public $inCityMinimalDistance = 0.0045; // 500 метров / 111111

    public function __construct(PoputchikForm $form){
      $this->form = $form;
    }

    public function search(){
      return $this;
    }

    public function appendFormCriteria(CDbCriteria &$criteria){
      $criteria->addCondition("enabled=:enabled");
      $criteria->addCondition("type=:type");
      if ($this->form->typeRoute == PpRoute::TYPE_ROUTE_SAME) {
        $criteria->addCondition("from_id=to_id");

        if ($this->form->from) {
          $this->inCityMinimalDistance = $this->form->getCityFrom()->population > 500000 ? 0.009: 0.0045; /// население > 500к ? 1000 метров : 500 метров
          // var_dump($this->form->from); die();
          $criteria->addCondition("st_distance(point(:from_lng, :from_lat), path_full) < :from_distance");
          $criteria->params[':from_lat'] = $this->form->from['lat'];
          $criteria->params[':from_lng'] = $this->form->from['lng'];
          $criteria->params[':from_distance'] = $this->inCityMinimalDistance;
        }

        if ($this->form->to) {
          $this->inCityMinimalDistance = $this->form->getCityTo()->population > 500000 ? 0.009: 0.0045;
          // var_dump($this->form->from); die();
          $criteria->addCondition("st_distance(point(:to_lng, :to_lat), path_full) < :to_distance");
          $criteria->params[':to_lat'] = $this->form->to['lat'];
          $criteria->params[':to_lng'] = $this->form->to['lng'];
          $criteria->params[':to_distance'] = $this->inCityMinimalDistance;
        }

        if ($this->form->from && $this->form->to) {
          $criteria->addCondition("st_distance(`from`, point(:from_lng2, :from_lat2)) < st_distance(`from`, point(:to_lng2, :to_lat2))");
          $criteria->params[':from_lat2'] = $this->form->from['lat'];
          $criteria->params[':from_lng2'] = $this->form->from['lng'];
          $criteria->params[':to_lat2']   = $this->form->to['lat'];
          $criteria->params[':to_lng2']   = $this->form->to['lng'];
        }

      } else {
        $criteria->addCondition("from_id <> to_id");
      }
      if ($this->form->date) {
        $criteria->addCondition("(departure = :departure) OR (FIND_IN_SET(:day_of_week,departure_periodicity)>0)");
        $criteria->params[':departure']   = date('Y-m-d H:i:s', $this->form->date);
        $criteria->params[':day_of_week'] = date('l', $this->form->date);
      }
      if ($this->form->seats) {
        $criteria->addCondition("free_seats >= :seats");
        $criteria->params[':seats']     = $this->form->seats;
      }

      if ($this->form->class) {
        $criteria->join .= " LEFT JOIN auto_models am ON am.id_model = car_id";
        $criteria->addCondition("am.class >= :class");
        $criteria->params[':class']     = $this->form->class;
      }

      // $criteria->addCondition("departure >= NOW() OR departure_periodicity IS NOT NULL");
      $criteria->addCondition("available_until >= NOW()");

      $criteria->params[':enabled']   = 1;
      $criteria->params[':type']      = $this->form->type;
      $criteria->order = "departure";
    }
  }