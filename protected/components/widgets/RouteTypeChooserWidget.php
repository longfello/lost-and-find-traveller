<?php

  class RouteTypeChooserWidget extends CWidget {
    public $layout = 'index';

    public function run() {
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.RouteTypeChooserWidget').'/'.$this->layout.'.php', array(), TRUE);
    }
  }
