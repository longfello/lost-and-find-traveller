<?php

  class DateTimeBlockWidget extends CWidget {
    public $namePrefix = 'unnamed';
    public $date = '';
    public $required = true;
    public $defaultValue = '';
    public $lang;
    public $htmlOptions = array(
      'class' => 'wrapp-time'
    );


    public function run() {
      $this->lang = Yii::app()->getLanguage();
      $this->defaultValue = $this->defaultValue?$this->defaultValue:Yii::t('poputchik', "Когда");
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.DateTimeBlockWidget').'/index.php', array(), TRUE);
    }
  }
