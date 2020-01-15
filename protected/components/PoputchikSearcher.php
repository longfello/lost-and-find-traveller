<?php

  class PoputchikSearcher {
    public $form;
    public $engines = array(
      'basic'    => true,
      'transfer' => false,
    );

    public function __construct(PoputchikForm $form){
      $this->form = $form;
      foreach($this->engines as $key => $engine) {
        if ($engine) {
          $engineClass = "PoputchikSearcher_".$key;
          if (class_exists($engineClass)) {
            $this->engines[$key] = new $engineClass($this->form);
          }
        } else unset($this->engines[$key]);
      }
    }

    public function form(){
      return Yii::app()->controller->renderFile(Yii::getPathOfAlias('application.components.views.search').'/result_wrapper.php', array(
        'searcher' => $this
      ), TRUE);
    }

    public function search(){
      $results = array();
      foreach($this->engines as $engine) {
        $results[$engine->name] = $engine->search();
      }
      return $results;
    }

  }