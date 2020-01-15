<?php
/* @var $this RegionsController */
/* @var $model Regions */

$this->breadcrumbs=array(
	'Регионы'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список регионов', 'url'=>array('index')),
	array('label'=>'Добавить регион', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#regions-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление регионами</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'regions-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_region',
		'name',
		'id_country',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
