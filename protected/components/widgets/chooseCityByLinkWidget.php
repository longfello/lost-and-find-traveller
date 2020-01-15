<?php

  class chooseCityByLinkWidget extends CWidget {
    public $selector = '';
    public $name     = array();

    public $lang;

    public function run() {
      $this->lang = Yii::app()->getLanguage();

      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.chooseCityByLinkWidget').'/index.php', array(), TRUE);
    }

  }
