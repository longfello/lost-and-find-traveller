<?php
/* @var $this PoputchikOrderController */
/* @var $model PoputchikOrder */

$this->breadcrumbs=array(
	'Poputchik Orders'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List PoputchikOrder', 'url'=>array('index')),
	array('label'=>'Create PoputchikOrder', 'url'=>array('create')),
	array('label'=>'Update PoputchikOrder', 'url'=>array('update', 'id'=>$model->id_order)),
	array('label'=>'Delete PoputchikOrder', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_order),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PoputchikOrder', 'url'=>array('admin')),
);
/*$cu = Yii::app()->user;
print $cu->id;*/
?>

<h1>View PoputchikOrder #<?php echo $model->id_order; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_order',
		'type_order',
		'target',
		'type_route',
		'id_route',
		'id_path',
		'transit',
		'direction',
		'id_settlement',
		'id_sa',
		'from_place',
		'to_place',
		'sum',
		'type_sum',
		'type_time',
		'date_to',
		'time_from_1',
		'time_from_2',
		'time_to_1',
		'time_to_2',
		'reverse',
		'date_reverse',
		'time_r_from_1',
		'time_r_from_2',
		'time_r_to_1',
		'time_r_to_2',
		'name',
		'phone',
		'type_auto',
		'id_brand',
		'id_model',
		'free_places_count',
		'comment',
		'date_order',
		'date_available',
		'id_user',
		'operator',
	),
)); ?>
