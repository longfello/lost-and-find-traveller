<?php
/* @var $this LostServiceController */
/* @var $model LostService */

$this->breadcrumbs=array(
	'Lost Services'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LostService', 'url'=>array('index')),
	array('label'=>'Manage LostService', 'url'=>array('admin')),
);
?>

<h1>Create LostService</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>