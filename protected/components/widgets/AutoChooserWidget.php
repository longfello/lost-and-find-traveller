<?php

  class AutoChooserWidget extends CWidget {
    public $class='';
    public $types=array();
    public $type=array();
    public $brands=array();
    public $brand=array();
    public $models=array();

    public $lang;

    public function run() {
      $this->lang = Yii::app()->getLanguage();

      $this->types  = AutoTypes::model()->findAll(array('order' => 'id'));
      $this->type   = $this->types[0]->id;
      $this->brands = AutoBrands::model()->findAll(array('order' => 'brand'));
      $this->brand  = $this->brands[0]->id_brand;
      $this->models = AutoModels::model()->findAllByAttributes(array(
        'id_brand' => $this->brand,
        'id_type'  => $this->type
      ), array('order' => 'model'));

      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.AutoChooser').'/index.php', array(), TRUE);
    }

  }
