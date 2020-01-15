<?php
/* @var $this PpRouteController */
/* @var $model PpRoute */

$this->breadcrumbs=array(
	'Pp Routes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PpRoute', 'url'=>array('index')),
	array('label'=>'Manage PpRoute', 'url'=>array('admin')),
);
?>

<h1>Create PpRoute</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>