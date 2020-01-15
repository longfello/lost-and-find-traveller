<?php
/* @var $this SettlementsController */
/* @var $model Settlements */

$this->breadcrumbs=array(
	'Населённые пункты'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список населённых пунктов', 'url'=>array('index')),
	array('label'=>'Добавить населённый пункт', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#settlements-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление населёнными пунктами</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'settlements-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_settlement',
		'name',
		array('name'=>'region_search', 'value'=>'$data->idRegion->name'),
		array('name'=>'area_search', 'value'=>'$data->idArea->name'),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
