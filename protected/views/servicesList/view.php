<?php
/* @var $this ServicesListController */
/* @var $model ServicesList */

$this->breadcrumbs=array(
	'Services Lists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ServicesList', 'url'=>array('index')),
	array('label'=>'Create ServicesList', 'url'=>array('create')),
	array('label'=>'Update ServicesList', 'url'=>array('update', 'id'=>$model->id_sl)),
	array('label'=>'Delete ServicesList', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_sl),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ServicesList', 'url'=>array('admin')),
);
?>

<h1>View ServicesList #<?php echo $model->id_sl; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_sl',
		'name',
	),
)); ?>
