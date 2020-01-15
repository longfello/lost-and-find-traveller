<?php
/* @var $this CountriesController */
/* @var $model Countries */

$this->breadcrumbs=array(
	'Страны'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список стран', 'url'=>array('index')),
	array('label'=>'Добавить страну', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#countries-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление странами</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'countries-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_country',
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
