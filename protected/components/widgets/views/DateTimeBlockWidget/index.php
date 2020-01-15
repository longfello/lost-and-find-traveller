<?php
/* @var $this DateTimeBlockWidget */
  $el_no = uniqid();
  $el_id = 'DateTimeBlock-'.$el_no;
  if (isset($this->htmlOptions['id'])) unset($this->htmlOptions['id']);

?>

<div id="<?=$el_id?>"  <?php
foreach($this->htmlOptions as $key => $value) {
  echo($key.'="'.$value.'" ');
}
?> data-required="<?=$this->required?"required":"none"?>">
  <?php Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
      'name' => $this->namePrefix.'date',
      'language' => $this->lang,
      'value' => $this->date,
      'htmlOptions' => array(
          'class' => 'datepicker',
          'size' => '10', // textField size
          'maxlength' => '10', // textField maxlength
          'defaultValue' => $this->defaultValue,
      ),
      'options' => array(
          'onSelect'=> 'js: function(date, ins) { $(this).focus(); focusNext(); }'
      ),
  ));
  ?>
  <input size="2" maxlength="2" defaultvalue="00" id="" type="text" value="" name="<?=$this->namePrefix?>-hour" class="<?=$this->namePrefix?>-time-hour time-hour default validate-el " autocomplete="off" data-max="23">
  <span class="colon">:</span>
  <input size="2" maxlength="2" defaultvalue="00" id="" type="text" value="" name="<?=$this->namePrefix?>-min" class="<?=$this->namePrefix?>-time-min time-min default validate-el " autocomplete="off" data-max="59">
</div>

<script type="text/javascript">
  $(window).ready(function(){
    "use strict";
    var wrapper = $('#<?=$el_id?>');
    $('.time-hour, .time-min', wrapper).on('keyup', function(){
      if($(this).val().length > 1) focusNext();
    });
    $('.time-hour, .time-min', wrapper).on('blur', function(){
      var val = parseInt($(this).val()) || 0;
      var max = parseInt($(this).data('max'));
      val = (val > max) ? max       : val;
      val = (val < 0)   ? 0         : val;
      val = (val < 10)  ? "0" + val : val;
      $(this).val(val);
    });
  });
</script>