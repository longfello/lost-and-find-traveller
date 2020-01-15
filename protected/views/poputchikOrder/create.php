<?php
/* @var $this PoputchikOrderController */
/* @var $model PoputchikOrder */

$this->breadcrumbs=array(
	'Попутчики'=>array('index'),
	'Подать заявку',
);

?>

<?php $this->renderPartial('_form', array_merge(array('model'=>$model,	'user_data'=>$user_data), $this->getLists($model))); ?>