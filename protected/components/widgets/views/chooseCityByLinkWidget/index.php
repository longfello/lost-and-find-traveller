<?php
  /* @var $this chooseCityByLinkWidget */
  $el_no = uniqid();
  $el_id = 'chooseCityByLink-'.$el_no;
?>
<a href="#" id="<?=$el_id?>"><?= $this->name ?></a><?php
Yii::app()->clientScript->registerScript($el_id, "
  $('#{$el_id}').on('click', function(e){
    e.preventDefault();
    router.selectCityByName('{$this->selector}', $(this).text());
  });
", CClientScript::POS_READY);