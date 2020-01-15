<?php
/* @var $this LostServiceController */
/* @var $model LostService */

$this->breadcrumbs=array(
	'Lost Services'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List LostService', 'url'=>array('index')),
	array('label'=>'Create LostService', 'url'=>array('create')),
	array('label'=>'Update LostService', 'url'=>array('update', 'id'=>$model->id_ls)),
	array('label'=>'Delete LostService', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_ls),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LostService', 'url'=>array('admin')),
);
?>

<h1>View LostService #<?php echo $model->id_ls; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_ls',
		'name',
		'contact_phone',
		'id_thing',
	),
)); ?>
