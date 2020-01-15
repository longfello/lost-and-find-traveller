<?php

  class headerFillerWidget extends CWidget {
    public function run() {
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.headerFillerWidget').'/index.php', array(), TRUE);
    }
  }
