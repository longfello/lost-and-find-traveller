<?php
/* @var $this GeneralController */

$this->breadcrumbs=array(
	Yii::t('general', 'Активация объявления'),
);
?>
<h1><?=Yii::t('general', 'Активация объявления')?></h1>

<p><?=Yii::t('general', 'Для активации поданного объявления укажите код, отправленный по SMS на номер +{{phone}}<br /><a id="change-phone" href="#" class="ajax">Отправить на другой номер?</a>', array('{{phone}}' => $phone))?></p>
<div id="general-activate-form" class="form">
	<?php if($error): ?>
	<div class="errorSummary">
	<?=$error?>
	</div>
	<?php endif; ?>
	<?php echo CHtml::beginForm(); ?>
		<div class="row">
			<?php echo CHtml::label(Yii::t('general', 'Код активации'),'activecode'); ?><?php echo CHtml::textField('activecode'); ?><?php echo CHtml::submitButton(Yii::t('common', 'Отправить')); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div><!-- form -->

<div id="other-phone-wrapper" style="display: none;">
	<div id="other-phone-form" class="form">
		<?php echo CHtml::beginForm(); ?>
			<div class="row">
				+<?php echo CHtml::textField('phone'); ?>
			</div>
		<?php echo CHtml::endForm(); ?>
	</div><!-- form -->
</div>
<a id="resend-code" href="" style="display: none;"><?=Yii::t('general', 'Не пришёл код активации?')?></a>

<?php
Yii::app()->clientScript->registerScript('aos', "
  setTimeout(function(){
        $('#resend-code').show();
    }, 15000);
  jQuery('#change-phone').click(function () {
	$('#other-phone-wrapper').dialog({
			modal: true,
			width: 500,
			title: '".Yii::t('general', 'Укажите номер сотового телефона')."',
			buttons: {
					'Сохранить': function() {
						$('#other-phone-form form').submit();
					},
					'Отмена': function() {
						$(this).dialog('close');
					}
				}
		});
		return false;
  })".($error_phone ? '.click()' : '').";",
  CClientScript::POS_READY
);
?>