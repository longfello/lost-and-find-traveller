<div class="tph"><a href="#" id="write-review">Write a review</a></div>
<?php
/*
if ($isNewReviewAdded) {
  echo('<p>Your review will be displayed after moderation.</p>');
}
*/
?>
<?php if (!Yii::app()->user->isGuest): ?>
  <div class="form write-review-form" id='write-review-form' style="clear: both;">
    <h4>Write your comment please:</h4>
    <?php $form = $this->beginWidget('CActiveForm', array('id' => 'new-review-form', 'enableAjaxValidation' => false,)); ?>
    <?php echo $form->errorSummary($newModel); ?>
    <div class="row">
      <?php echo CHtml::activeTextArea($newModel, 'text'); ?>
      <?php echo $form->error($newModel, 'text'); ?>
    </div>
    <div class="row buttons">
      <p>Your overall rating of this property</p>
      <a class='rate rate-minus' href="#"></a>
      <div class="new-user-rate user-rate user-rate-0-0"></div>
      <a class='rate rate-plus' href="#"></a>
      <span>(click «+» or «-» to choose)</span>
      <?php echo CHtml::activeHiddenField($newModel, 'rate', array('id' => 'user-rate-value', 'value' => 0)); ?>
      <?php echo CHtml::submitButton('Send'); ?>
    </div>
    <?php $this->endWidget(); ?>
  </div><!-- form -->

<?php
  Yii::app()->clientScript->registerScript('commentWidgetScript', "
$(document).ready(function(){
  $('.rate').on('click', function(e){
    e.preventDefault();
    var val = $('#user-rate-value').val();
    if ($(this).hasClass('rate-minus')) {
      if (val > 0) val = 1*val - 0.5;
    } else {
      if (val < 5) val = 1*val + 0.5;
    }
    $('#user-rate-value').val(val);
    repaint_user_rate();
  });
});

function repaint_user_rate(){
  var val = $('#user-rate-value').val();
  val = val.replace('.', '-');
  if (val.length == 1) val = val + '-0';
  $('.new-user-rate').removeAttr('class').addClass('new-user-rate user-rate user-rate-'+val);
}
", CClientScript::POS_END);
  endif;
?>
