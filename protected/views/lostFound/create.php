<?php
/* @var $this LostFoundController */
/* @var $model LostFound */

$this->breadcrumbs=array(
	'Lost Founds'=>array('index'),
	'Create',
);


?>


<?php $this->renderPartial('_form', array('model'=>$model, 	'categories'=>$categories,	'user_data'=>$user_data,)); ?>