<div class="row periodicity-chooser-wrapper">
  <ul class="periodicity-chooser clearfix">
    <li class="periodicity-once">
      <input type="radio" value="once" checked="checked" name="periodicity" id="i-periodicity-once">
      <label for="i-periodicity-once"><?=Yii::t('poputchik', 'Разовая поездка');?></label>
    </li>
    <li class="periodicity-weekly">
      <input type="radio" value="weekly" name="periodicity" id="i-periodicity-weekly">
      <label for="i-periodicity-weekly"><span><?=Yii::t('poputchik', 'Регулярная поездка');?></span></label>
    </li>
  </ul>
  <div class="periodicity-params periodicity-params-weekly">
    <ul class="periodicity-params-weekly-days">
      <li>
        <input type="checkbox" value="Monday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Monday">
        <label for="i-periodicity-weekly-days-Monday"><?=Yii::t('poputchik', 'Пн');?></label>
      </li>
      <li>
        <input type="checkbox" value="Tuesday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Tuesday">
        <label for="i-periodicity-weekly-days-Tuesday"><?=Yii::t('poputchik', 'Вт');?></label>
      </li>
      <li>
        <input type="checkbox" value="Wednesday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Wednesday">
        <label for="i-periodicity-weekly-days-Wednesday"><?=Yii::t('poputchik', 'Ср');?></label>
      </li>
      <li>
        <input type="checkbox" value="Thursday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Thursday">
        <label for="i-periodicity-weekly-days-Thursday"><?=Yii::t('poputchik', 'Чт');?></label>
      </li>
      <li>
        <input type="checkbox" value="Friday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Friday">
        <label for="i-periodicity-weekly-days-Friday"><?=Yii::t('poputchik', 'Пт');?></label>
      </li>
      <li>
        <input type="checkbox" value="Saturday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Saturday">
        <label for="i-periodicity-weekly-days-Saturday"><?=Yii::t('poputchik', 'Сб');?></label>
      </li>
      <li>
        <input type="checkbox" value="Sunday" name="periodicity-weekly-days[]" id="i-periodicity-weekly-days-Sunday">
        <label for="i-periodicity-weekly-days-Sunday"><?=Yii::t('poputchik', 'Вс');?></label>
      </li>
    </ul>
  </div>
</div>

<?php

Yii::app()->clientScript->registerScript('periodicity-chooser', "
  $('.periodicity-chooser-wrapper .periodicity-params').hide();

  $('.periodicity-chooser-wrapper .periodicity-chooser input').on('change', function(e){
    $('.periodicity-chooser-wrapper .periodicity-params').hide();
    $('.periodicity-chooser-wrapper .periodicity-params-'+$(this).val()).show();
  });
", CClientScript::POS_READY);