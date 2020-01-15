<?php
/* @var $this PoputchikOrderController */
/* @var $model PoputchikOrder */

$this->breadcrumbs=array(
	'Poputchik Orders'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PoputchikOrder', 'url'=>array('index')),
	array('label'=>'Create PoputchikOrder', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#poputchik-order-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Попутчик - управление заявками</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'poputchik-order-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_order',
		'type_order',
		'target',
		'type_route',
		'id_route',
		'id_path',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
