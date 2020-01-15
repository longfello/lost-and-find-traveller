<?php

  class CountersWidget extends CWidget {
    public $layout;

    public function run() {
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.CounterWidget').'/'.$this->layout.'.php', array(), TRUE);
    }
  }
