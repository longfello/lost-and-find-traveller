<?php
/**
 * Created by PhpStorm.
 * User: Miloslawsky
 * Date: 15.04.2015
 * Time: 17:44
 */

class EFormModel extends CWidget {
  public $layout = 'index';
  public function fetch($data = array()){
    return $this->renderFile(Yii::getPathOfAlias('application.components.forms.views').'/'.get_called_class().'/'.$this->layout.'.php', array($data), true);
  }
}