<?php
/* @var $this ReferensController */
/* @var $model Referens */

$this->breadcrumbs=array(
	'Referens'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Referens', 'url'=>array('index')),
	array('label'=>'Manage Referens', 'url'=>array('admin')),
);
?>

<h1>Create Referens</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>