<?php

  class PeriodicityChooserWidget extends CWidget {
    public $layout = 'index';

    public function run() {
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.PeriodicityChooserWidget').'/'.$this->layout.'.php', array(), TRUE);
    }
  }
