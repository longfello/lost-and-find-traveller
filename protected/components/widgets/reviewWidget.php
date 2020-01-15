<?php

  class reviewWidget extends CWidget {
    public function run() {
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.reviewWidget').'/index.php', array(), TRUE);
    }
  }
