<?php

  class RLinkPager extends CLinkPager {
    public $firstPageLabel = '';
    public $prevPageLabel  = '';
    public $nextPageLabel  = '';
    public $lastPageLabel  = '';
    public $header         = '';

    public function __construct($owner=null){
      $this->firstPageLabel = Yii::t('common', 'Первая');;
      $this->prevPageLabel  = Yii::t('common', 'Предыдущая');
      $this->nextPageLabel  = Yii::t('common', 'Следующая');
      $this->lastPageLabel  = Yii::t('common', 'Последняя');
      parent::__construct($owner);
    }
  }