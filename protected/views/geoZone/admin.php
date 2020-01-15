<?php
/* @var $this GeoZoneController */
/* @var $model GeoZone */

$this->breadcrumbs=array(
	'Области'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить область', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#geo-zone-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список областей</h1>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'geo-zone-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'code',
		'name_ru',
		'name_en',
		array(
			'class'=>'CButtonColumn',
      'template'=>'<nobr>{update}{delete}</nobr>'
		),
	),
)); ?>
