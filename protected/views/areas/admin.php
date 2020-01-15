<?php
/* @var $this AreasController */
/* @var $model Areas */

$this->breadcrumbs=array(
	'Районы'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Добавить район', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#areas-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление районами</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'areas-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_area',
		'name',
		array('name'=>'region_search', 'value'=>'$data->idRegion->name'),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
