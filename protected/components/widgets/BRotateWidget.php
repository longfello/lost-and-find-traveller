<?php

  class BRotateWidget extends CWidget {
    public $bnrTag;
    public $position = Banners::POSITION_TOP;

    public function run() {

      $this->position = $this->position;
      $result = Banners::model()->bannersGet(array('bnrTag' => $this->bnrTag, 'position' => $this->position));
      if ($result) {
        echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.brotate.brotate_') . $result['bnrTyp'] . '.php', array('banner' => $result['banner']), TRUE);
      } else {
        echo("No default banner for {$this->position} position");
      }
    }
  }
