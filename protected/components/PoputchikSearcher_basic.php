<?php

  class PoputchikSearcher_basic extends PoputchikSearcher_prototype {
    public $name = 'basic';
    public $title = 'Найдено 0 смешанных маршрутов';

    public function search(){
      if ($this->form->typeRoute == PpRoute::TYPE_ROUTE_ANOTHER) {
        $routes = PpRoute::model()->interCity($this->form->cityFrom, $this->form->cityTo);
      } else {
        $routes = PpRoute::model()->inCity($this->form->cityFrom);
      }
      $criteria = new CDbCriteria($routes->getDbCriteria());

      $this->appendFormCriteria($criteria);

      $this->count = $routes->count($criteria);

      $this->pages=new CPagination($this->count);
      $this->pages->pageSize=$this->onPage;
      $this->pages->applyLimit($criteria);

      $criteria->select = 't.id';
      $routes->getDbCriteria()->mergeWith($criteria);

      $routes->disableBehavior('spatial');
      $res = $routes->findAll();
      $routes->enableBehavior('spatial');

      $this->results = array();
      foreach($res as $el){
        $this->results[] = PpRoute::model()->findByPk($el->id);
      }

      $this->title = Yii::t('poputchik', 'search_title_title', array('{count}' => $this->count));

      return $this;
    }
  }