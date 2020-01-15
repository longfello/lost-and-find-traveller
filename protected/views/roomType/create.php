<?php
/* @var $this RoomTypeController */
/* @var $model RoomType */

$this->breadcrumbs=array(
	'Room Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RoomType', 'url'=>array('index')),
	array('label'=>'Manage RoomType', 'url'=>array('admin')),
);
?>

<h1>Create RoomType</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>