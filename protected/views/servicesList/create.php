<?php
/* @var $this ServicesListController */
/* @var $model ServicesList */

$this->breadcrumbs=array(
	'Services Lists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ServicesList', 'url'=>array('index')),
	array('label'=>'Manage ServicesList', 'url'=>array('admin')),
);
?>

<h1>Create ServicesList</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>