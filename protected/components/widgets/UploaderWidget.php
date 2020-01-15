<?php

  class UploaderWidget extends CWidget {
    public $namePrefix = 'unnamed';
    public $layout     = 'index';

    public function run() {
      $cs = Yii::app()->clientScript;
      $am = Yii::app()->assetManager;
      $jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
      $cs->registerScriptFile($jsUrl . '/plupload/plupload.full.min.js', CClientScript::POS_END);
      $cs->registerScriptFile($jsUrl . '/plupload/i18n/'.Yii::app()->language.'.js', CClientScript::POS_END);
      $cs->registerScriptFile($jsUrl . '/widget/uploader.js', CClientScript::POS_END);

      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.UploaderWidget').'/'.$this->layout.'.php', array(
        'jsUrl' => $jsUrl
      ), TRUE);
    }
  }
