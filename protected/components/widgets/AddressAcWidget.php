<?php

  class AddressAcWidget extends CWidget {
    public $class='';
    public $parent=false;
    public $htmlOptions = array();
    public $layout = 'index';
    public $defaults = array(
      'lat'  => false,
      'lng'  => false,
      'name' => false
    );

    private $lang;

    public function run() {
      if ($this->defaults){
        if (isset($this->defaults['lng'])  && $this->defaults['lng'])  $this->htmlOptions['data-lng'] = $this->defaults['lng'];
        if (isset($this->defaults['lat'])  && $this->defaults['lat'])  $this->htmlOptions['data-lat'] = $this->defaults['lat'];
        if (isset($this->defaults['name']) && $this->defaults['name']) $this->htmlOptions['value']    = $this->defaults['name'];
      }
      $this->lang = Yii::app()->getLanguage();
      echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.AddressAc').'/'.$this->layout.'.php', array(), TRUE);
    }
  }
