<?php
/* @var $this RoomTypeController */
/* @var $model RoomType */

$this->breadcrumbs=array(
	'Room Types'=>array('index'),
	$model->title=>array('view','id'=>$model->id_rt),
	'Update',
);

$this->menu=array(
	array('label'=>'List RoomType', 'url'=>array('index')),
	array('label'=>'Create RoomType', 'url'=>array('create')),
	array('label'=>'View RoomType', 'url'=>array('view', 'id'=>$model->id_rt)),
	array('label'=>'Manage RoomType', 'url'=>array('admin')),
);
?>

<h1>Update RoomType <?php echo $model->id_rt; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>