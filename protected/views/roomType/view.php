<?php
/* @var $this RoomTypeController */
/* @var $model RoomType */

$this->breadcrumbs=array(
	'Room Types'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List RoomType', 'url'=>array('index')),
	array('label'=>'Create RoomType', 'url'=>array('create')),
	array('label'=>'Update RoomType', 'url'=>array('update', 'id'=>$model->id_rt)),
	array('label'=>'Delete RoomType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_rt),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RoomType', 'url'=>array('admin')),
);
?>

<h1>View RoomType #<?php echo $model->id_rt; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_rt',
		'title',
		'places',
	),
)); ?>
